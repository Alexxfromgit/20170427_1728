<?php
class ModelToolCSVPricePro extends Model {
	private $CSV_SEPARATOR = ';';
	private $CSV_ENCLOSURE = '"';

	private $field_caption = NULL;
	private $setting = array();
	private $attributes = array();
	private $CoreType = NULL;
	private $PathNameByCategory = array();
	private $CustomFields = array();
	private $Categories = array();
	private $CategoriesString = '';
	private $FindCategory = 0;
	
	// File
	private $f_tell = 0;
	
	//-------------------------------------------------------------------------
	// Constructor
	//-------------------------------------------------------------------------
	public function __construct($registry) {
		parent::__construct($registry);
		$this->load->model('setting/setting');
		$this->CoreType = $this->config->get('csv_core_type');
	}
	
	//-------------------------------------------------------------------------
	// Import
	//-------------------------------------------------------------------------
	public function import($data, $setting) {
		
		// Set Setting
		//-------------------------------------------------------------------------
		$this->setting = $setting;

		// Get Field Caption
		//-------------------------------------------------------------------------
		if ( ($this->field_caption = $this->getFieldCaption($data['file_name'])) === NULL) {
			return NULL;
		}
		
		// init $getProductID function
		//-------------------------------------------------------------------------
		$getProductID = 'getProductID';
		
		if($this->setting['key_field'] == '_ID_' AND isset($this->field_caption['_ID_']) ) {
			$getProductID = 'getProductID_Id';
		} elseif($this->setting['key_field'] == '_SKU_' AND isset($this->field_caption['_SKU_'])) {
			$getProductID = 'getProductID_SKU';
		} elseif($this->setting['key_field'] == '_EAN_' AND isset($this->field_caption['_EAN_'])) {
			$getProductID = 'getProductID_EAN';
		} elseif($this->setting['key_field'] == '_JAN_' AND isset($this->field_caption['_JAN_'])) {
			$getProductID = 'getProductID_JAN';
		} elseif($this->setting['key_field'] == '_ISBN_' AND isset($this->field_caption['_ISBN_'])) {
			$getProductID = 'getProductID_ISBN';
		} elseif($this->setting['key_field'] == '_MPN_' AND isset($this->field_caption['_MPN_'])) {
			$getProductID = 'getProductID_MPN';
		} elseif ($this->setting['key_field'] == '_MODEL_' AND isset($this->field_caption['_MODEL_'])) {
			$getProductID = 'getProductID_Model';
		} elseif ($this->setting['key_field'] == '_NAME_' AND isset($this->field_caption['_NAME_'])) {
			$getProductID = 'getProductID_Name';
		}
		
		// Check Iterator
		//-------------------------------------------------------------------------
		if($this->setting['iter_limit'] > 0){
			// Set ftell
			if($data['ftell'] != 0) {
				$this->f_tell = $data['ftell'] - 20*1024; 
			}
		}
		
		// check categories for iterator
		//-------------------------------------------------------------------------
		if( isset($this->session->data['csvprice_pro_categories']) ) {
			$this->Categories = unserialize($this->session->data['csvprice_pro_categories']); 
			$this->CategoriesString = implode($this->setting['delimiter_category'], $this->Categories);
			unset($this->session->data['csvprice_pro_categories']);
		}
		
		// Disable All Products
		//-------------------------------------------------------------------------
		if($this->setting['product_disable'] == 1  && $data['ftell'] == 0) {
			$this->db->query('UPDATE `' . DB_PREFIX . 'product` SET status = 0');
		}
		
		// IMPORT CSV FORMAT 
		//-------------------------------------------------------------------------
		$item_count = 0;
		$update_count = 0;
		$insert_count = 0;
		$error_count = 0;
		$result = NULL;
		
		if (($handle = fopen($data['file_name'], 'r')) !== FALSE) {
			
			fseek($handle, $this->f_tell); // set position before CSV Caption
			
			while(($items = fgetcsv($handle, 10*1024, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE)) !== FALSE) {
				$item_count++;
				if ( (count($items) + $this->FindCategory) == count($this->field_caption)) {

					// Check Find Category
					if($this->setting['find_category'] == 1) {

						 if ( $this->findCategory($items) === true ) continue;
						 
						 if(isset($this->field_caption['_CATEGORY_'])) {
						 	$items[$this->field_caption['_CATEGORY_']] = $this->CategoriesString;
						 } else {
						 	$error_count ++;
							continue;
						 }
					}

					if ( ($product_id = $this->$getProductID($items)) !== FALSE ) {
							
						// check import mode
						if( $this->setting['mode'] == 1 || $this->setting['mode'] == 2 ) {
							if( $this->updateProduct($product_id, $items) ) {
								$update_count++;
							} else {
								$error_count ++;
							}
						}
						
					} else {
						
						// check import mode
						if( $this->setting['mode'] == 1 || $this->setting['mode'] == 3) {
							if( $this->addProduct($items) ) {
								$insert_count++;
							} else {
								$error_count ++;
							}
							
						} else {
							$error_count ++;
						}
						
					}
				} else {
					$error_count ++;
				}
				// Check Iterator
				//-------------------------------------------------------------------------
				if ( $item_count == $this->setting['iter_limit'] AND $this->setting['iter_limit'] > 0 ) {
					$result['ftell'] = ftell($handle);
					$this->session->data['csvprice_pro_categories'] = serialize($this->Categories);
					break;
				}
			}
			fclose($handle);
			$result['total'] = $data['total'] + $item_count;
			$result['update'] = $data['update'] + $update_count;
			$result['insert'] = $data['insert'] + $insert_count;
			$result['error'] = $data['error'] + $error_count;
		}
		
		$this->cache->delete('product');
		
		if (isset($this->field_caption['_CATEGORY_'])) {
			$this->cache->delete('category');
			
			if( in_array(VERSION, array('1.5.5', '1.5.5.1')) ){
				// Function to repair any erroneous categories that are not in the category path table.
				$this->load->model('catalog/category');
				$this->model_catalog_category->repairCategories();
			}
		}
		return $result;
	}
	
	//-------------------------------------------------------------------------
	// Field Name
	//-------------------------------------------------------------------------
	private function getFieldCaption($file_name) { 
		$result = NULL;

		if (($handle = fopen($file_name, 'r')) !== FALSE) {
			if (($field_caption = fgetcsv($handle, 1000, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE)) !== FALSE) {
				for($i = 0; $i < count($field_caption); $i++) {
					$field_caption[$i] = trim($field_caption[$i], " \t\n");
				}
				$result = array_flip($field_caption); // перевёртыш
				//$result = $field_caption;
				$this->f_tell = ftell($handle); 
			} 
			fclose($handle);
		}
		
		return $result;
	}
	
	//-------------------------------------------------------------------------
	// Update Product
	//-------------------------------------------------------------------------
	private function updateProduct(&$product_id, &$data) {
		// init Macros
		//-------------------------------------------------------------------------
		$this->initMacros();
		
		$sql = '';

		if(isset($this->field_caption['_MODEL_'])) $sql .= ' model = REPLACE(\'' . $this->db->escape($data[$this->field_caption['_MODEL_']]) . '\', \'"\', \'&quot;\'),';
		if(isset($this->field_caption['_SKU_'])) {
			$sql .= ' sku = \'' . $this->db->escape($data[$this->field_caption['_SKU_']]) . '\',';
		}
		if(in_array(VERSION, array('1.5.4', '1.5.4.1', '1.5.5', '1.5.5.1'))){
			if(isset($this->field_caption['_EAN_'])) $sql .= ' ean = \'' . $this->db->escape($data[$this->field_caption['_EAN_']]) . '\',';
			if(isset($this->field_caption['_JAN_'])) $sql .= ' jan = \'' . $this->db->escape($data[$this->field_caption['_JAN_']]) . '\',';
			if(isset($this->field_caption['_ISBN_'])) $sql .= ' isbn = \'' . $this->db->escape($data[$this->field_caption['_ISBN_']]) . '\',';
			if(isset($this->field_caption['_MPN_'])) $sql .= ' mpn = \'' . $this->db->escape($data[$this->field_caption['_MPN_']]) . '\',';
		}
		if(isset($this->field_caption['_UPC_'])) $sql .= ' upc = \'' . $this->db->escape($data[$this->field_caption['_UPC_']]) . '\',';
		if(isset($this->field_caption['_LOCATION_'])) $sql .= ' location = \'' . $this->db->escape($data[$this->field_caption['_LOCATION_']]) . '\',';
		if(isset($this->field_caption['_QUANTITY_'])) {
			$sql .= ' quantity = \'' . (int)$data[$this->field_caption['_QUANTITY_']] . '\',';
		}
		if(isset($this->field_caption['_STOCK_STATUS_ID_'])) {
			$sql .= ' stock_status_id = \'' . (int)$data[$this->field_caption['_STOCK_STATUS_ID_']] . '\',';
		} else {
			if(isset($this->field_caption['_QUANTITY_']) && (int)$data[$this->field_caption['_QUANTITY_']] == 0) {
				 $sql .= ' stock_status_id = \'' . (int)$this->config->get('config_stock_status_id') . '\',' ;
			}
		}
		
		if(isset($this->field_caption['_SHIPPING_'])) $sql .= ' shipping = \'' . $this->db->escape($data[$this->field_caption['_SHIPPING_']]) . '\',';
		
		if ( isset($this->field_caption['_PRICE_']) ) {
			
			$price = preg_replace("/\\s+/iu", "", $data[$this->field_caption['_PRICE_']]);
			$price = preg_replace("/,/iu", ".", $price);
			$price = (float)$price;
			
			if ( $this->setting['calc_mode'] == 1 ) {
				if($this->setting['calc_value'][0] != '' && $this->setting['calc_value'][0] > 0 ) {
					$price = $price * (float)$this->setting['calc_value'][0];
				}
				if($this->setting['calc_value'][1] != '' && $this->setting['calc_value'][1] > 0 ) {
					$price = $price * (float)$this->setting['calc_value'][1];
	            }
			} elseif ( $this->setting['calc_mode'] == 2 ) {
				if($this->setting['calc_value'][0] != '' && $this->setting['calc_value'][0] > 0 ) {
					$price = $price + (float)$this->setting['calc_value'][0];
				}
				if($this->setting['calc_value'][1] != '' && $this->setting['calc_value'][1] > 0 ) {
					$price = $price + (float)$this->setting['calc_value'][1];
	            }
			}
			$sql .= ' price = \'' . number_format((float)$price, 4, '.', ''). '\',';
		}
		
		// Update Product Discount
		if( isset($this->field_caption['_DISCOUNT_']) ) {
			$this->updateDiscount($product_id, $data[$this->field_caption['_DISCOUNT_']]);
		}
		// Update Product Special
		if( isset($this->field_caption['_SPECIAL_'])) {
			$this->updateSpecial($product_id, $data[$this->field_caption['_SPECIAL_']]);
		}
		
		if(isset($this->field_caption['_SORT_ORDER_'])) $sql .= ' sort_order = \'' . (int)$data[$this->field_caption['_SORT_ORDER_']] . '\',';
		if(isset($this->field_caption['_POINTS_'])) $sql .= ' points = \'' . (int)$data[$this->field_caption['_POINTS_']] . '\',';
		if(isset($this->field_caption['_WEIGHT_'])) {
			$weight = preg_replace( "/,/iu", '.', $data[$this->field_caption['_WEIGHT_']] );
			$weight = preg_replace("/\\s+/iu", "", $weight);
			$sql .= ' weight = \'' . (float)$weight . '\',';
		}
		if(isset($this->field_caption['_LENGTH_'])) $sql .= ' length = \'' . (float)$data[$this->field_caption['_LENGTH_']] . '\',';
		if(isset($this->field_caption['_WIDTH_'])) $sql .= ' width = \'' . (float)$data[$this->field_caption['_WIDTH_']] . '\',';
		if(isset($this->field_caption['_HEIGHT_'])) $sql .= ' height = \'' . (float)$data[$this->field_caption['_HEIGHT_']] . '\',';
		if(isset($this->field_caption['_IMAGE_'])) $sql .= ' image = \'' .  $this->db->escape(html_entity_decode($data[$this->field_caption['_IMAGE_']], ENT_QUOTES, 'UTF-8')) . '\',';
		
		if( $this->setting['product_disable'] == 1 ) {
			$sql .= ' status = 1,';
		} else {
			if ( isset($this->field_caption['_STATUS_']) ) {
				$sql .= ' status = \'' . (int)$data[$this->field_caption['_STATUS_']] . '\',';
			}
		}
		
		// Custom Fields product
		//-------------------------------------------------------------------------
		if( isset($this->CustomFields[DB_PREFIX . 'product']) && count($this->CustomFields[DB_PREFIX . 'product']) > 0 ) {
			foreach ($this->CustomFields[DB_PREFIX . 'product'] as $field) {
				if(isset($this->field_caption[$field['csv_name']])) $sql .= ' ' . $field['field_name'] . ' = \'' . $this->db->escape($data[$this->field_caption[$field['csv_name']]]) . '\',';
			}
		}
		
		// _MANUFACTURER_
		//-------------------------------------------------------------------------
		if(!$this->setting['skip_manufacturer']) {
			if( isset($this->field_caption['_MANUFACTURER_']) ) {
				$manufacturer_id = $this->getManufacturer($data[$this->field_caption['_MANUFACTURER_']]); 
				$sql .= ' manufacturer_id = \'' . (int)$manufacturer_id . '\',';
			} else {
				$sql .= ' manufacturer_id = \'' . (int)$this->setting['product_manufacturer'] . '\',';
			}
		}
		$sql .= ' date_modified = NOW(),';
		
		if(!empty($sql)) {
			$sql = 'UPDATE `' . DB_PREFIX . 'product` SET ' . mb_substr($sql,0,-1) . ' WHERE product_id = \'' . (int)$product_id . '\'';
			$this->db->query($sql);
		}
		
		// Update Product description by language_id
		//-------------------------------------------------------------------------
		$sql = '';
		if(isset($this->field_caption['_NAME_'])) {
			$sql .= ' name = REPLACE(\'' . $this->db->escape($data[$this->field_caption['_NAME_']]) . '\', \'"\', \'&quot;\'),';
		}
		if($this->CoreType == 'ocstore') {
			if(isset($this->field_caption['_HTML_TITLE_'])) $sql .= ' seo_title = \'' . $this->db->escape($data[$this->field_caption['_HTML_TITLE_']]) . '\',';
			if(isset($this->field_caption['_HTML_H1_'])) $sql .= ' seo_h1 = \'' . $this->db->escape($data[$this->field_caption['_HTML_H1_']]) . '\',';
		}
		if(isset($this->field_caption['_META_KEYWORDS_'])) $sql .= ' meta_keyword = \'' . $this->db->escape($data[$this->field_caption['_META_KEYWORDS_']]) . '\',';
		if(isset($this->field_caption['_META_DESCRIPTION_'])) $sql .= ' meta_description = \'' . $this->db->escape($data[$this->field_caption['_META_DESCRIPTION_']]) . '\',';
		if(isset($this->field_caption['_DESCRIPTION_'])) {
			$sql .= ' description = \'' . $this->db->escape(htmlspecialchars($data[$this->field_caption['_DESCRIPTION_']])) . '\',';
		}
		if(in_array(VERSION, array('1.5.4', '1.5.4.1', '1.5.5', '1.5.5.1'))){
			if(isset($this->field_caption['_PRODUCT_TAG_'])) $sql .= ' tag = \'' . $this->db->escape(htmlspecialchars($data[$this->field_caption['_PRODUCT_TAG_']])) . '\',';
		}
		
		// Custom Fields product
		//-------------------------------------------------------------------------
		if( isset($this->CustomFields[DB_PREFIX . 'product_description']) && count($this->CustomFields[DB_PREFIX . 'product_description']) > 0 ) {
			foreach ($this->CustomFields[DB_PREFIX . 'product_description'] as $field) {
				if(isset($this->field_caption[$field['csv_name']])) $sql .= ' ' . $field['field_name'] . ' = \'' . $this->db->escape( htmlspecialchars($data[$this->field_caption[$field['csv_name']]]) ) . '\',';
			}
		}
		
		if(!empty($sql)) {
			$result = $this->db->query('SELECT 1 FROM `' . DB_PREFIX . 'product_description` WHERE product_id = \'' . (int)$product_id . '\' AND language_id = \'' . (int)$this->setting['language_id'] . '\'');
			
			if($result->num_rows > 0 ) {
				$sql = 'UPDATE `' . DB_PREFIX . 'product_description` SET ' . mb_substr($sql,0,-1)  . ' WHERE product_id = \'' . (int)$product_id . '\' AND language_id = \'' . (int)$this->setting['language_id'] . '\'';
			} else {
				$sql = 'INSERT INTO `' . DB_PREFIX . 'product_description` SET ' . $sql  . ' product_id = \'' . (int)$product_id . '\', language_id = \'' . (int)$this->setting['language_id'] . '\'';
			}
			$this->db->query($sql);
		}
		
		// Update product Tag for old VERSION
		//-------------------------------------------------------------------------
		if(in_array(VERSION, array('1.5.1.3', '1.5.1.3.1', '1.5.2', '1.5.2.1', '1.5.3', '1.5.3.1'))){
			if(isset($this->field_caption['_PRODUCT_TAG_'])) {
				$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_tag` WHERE product_id = \'' . (int)$product_id . '\' AND language_id = \'' . (int)$this->setting['language_id'] . '\'');
				if( !empty($data[$this->field_caption['_PRODUCT_TAG_']]) ) {
					$tags = explode(',', $data[$this->field_caption['_PRODUCT_TAG_']]);
					foreach ($tags as $tag) {
						$sql = 'INSERT INTO `' . DB_PREFIX . 'product_tag` SET product_id = \'' . (int)$product_id . '\', language_id = \'' . (int)$this->setting['language_id'] . '\', tag = \'' . $this->db->escape(trim($tag)) . '\'';
						$this->db->query($sql);
					}
				}
			}
		}
		
		// Update Product SEO Keyword
		//-------------------------------------------------------------------------
		$sql = '';
		if(isset($this->field_caption['_SEO_KEYWORD_'])) {
			$this->db->query('DELETE FROM `' . DB_PREFIX . 'url_alias` WHERE query = \'product_id=' . (int)$product_id . '\'');
			if( !empty($data[$this->field_caption['_SEO_KEYWORD_']]) ) {
				$sql = 'INSERT INTO `' . DB_PREFIX . 'url_alias` SET query = \'product_id=' . (int)$product_id . '\', keyword = \'' . $this->db->escape($data[$this->field_caption['_SEO_KEYWORD_']]) . '\'';
				$this->db->query($sql);
			}
		}
		
		// Update Product Images
		//-------------------------------------------------------------------------
		if(isset($this->field_caption['_IMAGES_'])) {
			$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_image` WHERE product_id = \'' . (int)$product_id . '\'');
			if( !empty($data[$this->field_caption['_IMAGES_']]) ) {
				$images = explode(',', $data[$this->field_caption['_IMAGES_']]);
				foreach ($images as $image) {
					if(!empty($image)) {
						$sql = 'INSERT INTO `' . DB_PREFIX . 'product_image` SET product_id = \'' . (int)$product_id . '\', image = \'' . $this->db->escape(html_entity_decode($image, ENT_QUOTES, 'UTF-8')) . '\'';
						$this->db->query($sql);
					}
				}
			}
		}

		// Update Product Category
		//-------------------------------------------------------------------------
		$main_category_id = 0;
		
		if( !$this->setting['skip_category'] || isset($this->field_caption['_CATEGORY_']) ) {
			
			if( isset($this->field_caption['_CATEGORY_ID_']) && !empty($data[$this->field_caption['_CATEGORY_ID_']]) ) {
				$categories = explode(',', $data[$this->field_caption['_CATEGORY_ID_']]);
			} else {
				$categories = $this->setting['product_category'];
			}
			
			if( isset($this->field_caption['_CATEGORY_']) && !empty($data[$this->field_caption['_CATEGORY_']]) ) {
				$categories = $this->addCategory($data[$this->field_caption['_CATEGORY_']]);
			}
			
			if (!empty($categories)) {
				if($this->CoreType == 'ocstore' && $this->setting['skip_main_category'] == 1) {

					$result = $this->db->query('SELECT category_id FROM `' . DB_PREFIX . 'product_to_category` WHERE product_id = \'' . (int)$product_id . '\' AND main_category = 1');
					
					if($result->num_rows > 0) {
						$main_category_id = $result->row['category_id'];
					}
					
				} 
				$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_to_category` WHERE product_id = \'' . (int)$product_id . '\'');

				foreach ($categories as $category_id) {
					$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_to_category` SET product_id = \'' . (int)$product_id . '\', category_id = \'' . (int)$category_id . '\'');
				}
				
				if($main_category_id == 0) {
					$main_category_id = $category_id;
				}
			}
		}
		
		// Update Product Main Category
		//-------------------------------------------------------------------------
		if($this->CoreType == 'ocstore') {
			if(empty($categories) && $this->setting['skip_main_category'] == 0) {
				$main_category_id = $this->setting['main_category_id'];
			}
			if( $main_category_id > 0 ) {
				$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_to_category` WHERE (product_id = \'' . (int)$product_id . '\' AND category_id = \'' . (int)$main_category_id . '\') OR (product_id = \'' . (int)$product_id . '\' AND main_category = 1)');
				$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_to_category` SET product_id = \'' . (int)$product_id . '\', category_id = \'' . (int)$main_category_id . '\', main_category = 1');
			}
		}
		
		if( isset($this->field_caption['_MAIN_CATEGORY_']) && !empty($data[$this->field_caption['_MAIN_CATEGORY_']]) ) {
			$this->updateMainCategory($product_id, $data[$this->field_caption['_MAIN_CATEGORY_']]);
		}

		// Add Product options
		//-------------------------------------------------------------------------
		if( isset($this->field_caption['_OPTIONS_']) ) {

			$this->addProductOptions($product_id, $data[$this->field_caption['_OPTIONS_']]);
		}

		// Add Product Attributes
		//-------------------------------------------------------------------------
		if( isset($this->field_caption['_ATTRIBUTES_']) ) {
				
			$attributes = array();
			
			$attributes = explode("\n", $data[$this->field_caption['_ATTRIBUTES_']]);
			$attributes = array_unique($attributes, SORT_STRING); // added in v2.2.2a
			
			if(!empty($attributes)) {
				
				$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_attribute` WHERE product_id = \'' . (int)$product_id . '\'');
				
				$tmp_product_attributes = array(); // added in v2.2.2a
				foreach ($attributes as $attribute_date) {
					$attribute = explode('|', $attribute_date);
					if(count($attribute) == 3) {
						$attribute[0] = trim($attribute[0]);
						$attribute[1] = trim($attribute[1]);
						$attribute[2] = trim($attribute[2]);
						// check
						if(!isset($this->attributes[mb_strtolower($attribute[0].$attribute[1])])) {
							$attribute_id = $this->addProductAttribute($attribute[0], $attribute[1]);
						} else {
							$attribute_id = $this->attributes[mb_strtolower($attribute[0].$attribute[1])];
						}
						
						// Add
						if (!in_array($product_id.'-'.$attribute_id.'-'.$this->setting['language_id'], $tmp_product_attributes) ) {
							$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_attribute` 
								SET	product_id = \'' . (int)$product_id . '\',
								attribute_id = \'' . (int)$attribute_id . '\',
								language_id = \'' . (int)$this->setting['language_id'] . '\',
								text = \'' .$this->db->escape($attribute[2]) . '\'
							');
							$tmp_product_attributes[] = $product_id.'-'.$attribute_id.'-'.$this->setting['language_id'];
						}
					}
				}
			}
		}
		
		return TRUE;
	}
	
	//-------------------------------------------------------------------------
	// Add Product
	//-------------------------------------------------------------------------
	private function addProduct(&$data) {
		// init Macros
		//-------------------------------------------------------------------------
		$this->initMacros();
		
		// Check Product Name
		//-------------------------------------------------------------------------
		if( !isset($this->field_caption['_NAME_']) || trim($this->field_caption['_NAME_']) == '' ) {
			return FALSE;
		} 
		
		//-------------------------------------------------------------------------
		$sql = '';
		
		if(isset($this->field_caption['_MODEL_'])) $sql .= ' model = REPLACE(\'' . $this->db->escape($data[$this->field_caption['_MODEL_']]) . '\', \'"\', \'&quot;\'),';
		if(isset($this->field_caption['_SKU_'])) {
			$sql .= ' sku = \'' . $this->db->escape($data[$this->field_caption['_SKU_']]) . '\',';
		}
		if(in_array(VERSION, array('1.5.4', '1.5.4.1', '1.5.5', '1.5.5.1'))){
			if(isset($this->field_caption['_EAN_'])) $sql .= ' ean = \'' . $this->db->escape($data[$this->field_caption['_EAN_']]) . '\',';
			if(isset($this->field_caption['_JAN_'])) $sql .= ' jan = \'' . $this->db->escape($data[$this->field_caption['_JAN_']]) . '\',';
			if(isset($this->field_caption['_ISBN_'])) $sql .= ' isbn = \'' . $this->db->escape($data[$this->field_caption['_ISBN_']]) . '\',';
			if(isset($this->field_caption['_MPN_'])) $sql .= ' mpn = \'' . $this->db->escape($data[$this->field_caption['_MPN_']]) . '\',';
		}
		if(isset($this->field_caption['_UPC_'])) $sql .= ' upc = \'' . $this->db->escape($data[$this->field_caption['_UPC_']]) . '\',';
		if(isset($this->field_caption['_LOCATION_'])) $sql .= ' location = \'' . $this->db->escape($data[$this->field_caption['_LOCATION_']]) . '\',';
		if(isset($this->field_caption['_QUANTITY_'])) {
			$sql .= ' quantity = \'' . (int)$data[$this->field_caption['_QUANTITY_']] . '\',';
		} 
		if(isset($this->field_caption['_STOCK_STATUS_ID_'])) $sql .= ' stock_status_id = \'' . (int)$data[$this->field_caption['_STOCK_STATUS_ID_']] . '\',';
		if(isset($this->field_caption['_SHIPPING_'])) $sql .= ' shipping = \'' . $this->db->escape($data[$this->field_caption['_SHIPPING_']]) . '\',';
		
		if ( isset($this->field_caption['_PRICE_']) ) {
			
			$price = preg_replace("/\\s+/iu", "", $data[$this->field_caption['_PRICE_']]);
			$price = preg_replace("/,/iu", ".", $price);
			$price = (float)$price;
			
			if ( $this->setting['calc_mode'] == 1 ) {
				if($this->setting['calc_value'][0] != '' && $this->setting['calc_value'][0] > 0 ) {
					$price = $price * (float)$this->setting['calc_value'][0];
				}
				if($this->setting['calc_value'][1] != '' && $this->setting['calc_value'][1] > 0 ) {
					$price = $price * (float)$this->setting['calc_value'][1];
	            }
			} elseif ( $this->setting['calc_mode'] == 2 ) {
				if($this->setting['calc_value'][0] != '' && $this->setting['calc_value'][0] > 0 ) {
					$price = $price + (float)$this->setting['calc_value'][0];
				}
				if($this->setting['calc_value'][1] != '' && $this->setting['calc_value'][1] > 0 ) {
					$price = $price + (float)$this->setting['calc_value'][1];
	            }
			}
			$sql .= ' price = \'' . number_format($price, 4, '.', ''). '\',';
		}
		
		if(isset($this->field_caption['_SORT_ORDER_'])) $sql .= ' sort_order = \'' . (int)$data[$this->field_caption['_SORT_ORDER_']] . '\',';
		if(isset($this->field_caption['_POINTS_'])) $sql .= ' points = \'' . (int)$data[$this->field_caption['_POINTS_']] . '\',';
		if(isset($this->field_caption['_WEIGHT_'])) {
			$weight = preg_replace( "/,/iu", '.', $data[$this->field_caption['_WEIGHT_']] );
			$weight = preg_replace("/\\s+/iu", "", $weight);
			$sql .= ' weight = \'' . (float)$weight . '\',';
		}
		if(isset($this->field_caption['_LENGTH_'])) $sql .= ' length = \'' . (float)$data[$this->field_caption['_LENGTH_']] . '\',';
		if(isset($this->field_caption['_WIDTH_'])) $sql .= ' width = \'' . (float)$data[$this->field_caption['_WIDTH_']] . '\',';
		if(isset($this->field_caption['_HEIGHT_'])) $sql .= ' height = \'' . (float)$data[$this->field_caption['_HEIGHT_']] . '\',';
		if(isset($this->field_caption['_IMAGE_'])) $sql .= ' image = \'' .  $this->db->escape(html_entity_decode($data[$this->field_caption['_IMAGE_']], ENT_QUOTES, 'UTF-8')) . '\',';
		
		if ( isset($this->field_caption['_STATUS_']) ) {
			$sql .= ' status = \'' . (int)$data[$this->field_caption['_STATUS_']] . '\',';
		} else {
			$sql .= ' status = \'' . (int)$this->setting['status'] . '\',';
		}
		
		
		$sql .= ' tax_class_id = \'' . (int)$this->setting['tax_class_id'] . '\',';
		$sql .= ' minimum = \'' . (int)$this->setting['minimum'] . '\',';
		$sql .= ' subtract = \'' . (int)$this->setting['subtract'] . '\',';
		if(!isset($this->field_caption['_STOCK_STATUS_ID_'])) {
			$sql .= ' stock_status_id = \'' . (int)$this->setting['stock_status_id'] . '\',';
		}
		if( !isset($this->field_caption['_SHIPPING_']) ) { $sql .= ' shipping = \'' . (int)$this->setting['shipping'] . '\',';}
		$sql .= ' weight_class_id = \'' . (int)$this->setting['weight_class_id'] . '\',';
		$sql .= ' length_class_id = \'' . (int)$this->setting['length_class_id'] . '\',';
		$sql .= ' date_added = NOW(),';
		$sql .= ' date_available = DATE_FORMAT(NOW(),\'%Y-%m-%d\'),';
		
		// Custom Fields product
		//-------------------------------------------------------------------------
		if( isset($this->CustomFields[DB_PREFIX . 'product']) && count($this->CustomFields[DB_PREFIX . 'product']) > 0 ) {
			foreach ($this->CustomFields[DB_PREFIX . 'product'] as $field) {
				if(isset($this->field_caption[$field['csv_name']])) $sql .= ' ' . $field['field_name'] . ' = \'' . $this->db->escape($data[$this->field_caption[$field['csv_name']]]) . '\',';
			}
		}
		
		// _MANUFACTURER_
		//-------------------------------------------------------------------------
		if( isset($this->field_caption['_MANUFACTURER_']) ) {
			$manufacturer_id = $this->getManufacturer($data[$this->field_caption['_MANUFACTURER_']]); 
			$sql .= ' manufacturer_id = \'' . (int)$manufacturer_id . '\'';
		} else {
			$sql .= ' manufacturer_id = \'' . (int)$this->setting['product_manufacturer'] . '\''; // Last Field
		}
		
		$sql = 'INSERT INTO `' . DB_PREFIX . 'product` SET ' . $sql;
		$this->db->query($sql);
		
		$product_id = $this->db->getLastId();
		
		if (isset($this->setting['product_store'])) {
			foreach ($this->setting['product_store'] as $store_id) {
				$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_to_store` SET product_id = \'' . (int)$product_id . '\', store_id = \'' . (int)$store_id . '\'');
			}
		}
		
		if(!$product_id) return FALSE;
		
		// Add Product Discount
		//-------------------------------------------------------------------------
		if( isset($this->field_caption['_DISCOUNT_']) ) {
			$this->updateDiscount($product_id, $data[$this->field_caption['_DISCOUNT_']]);
		}
		
		// Add Product Special
		//-------------------------------------------------------------------------
		if( isset($this->field_caption['_SPECIAL_']) ) {
			$this->updateSpecial($product_id, $data[$this->field_caption['_SPECIAL_']]);
		}
		
		// Add Product description by language_id
		//-------------------------------------------------------------------------
		$sql = '';

		if(isset($this->field_caption['_NAME_'])) {
			$sql .= ' name = REPLACE(\'' . $this->db->escape($data[$this->field_caption['_NAME_']]) . '\', \'"\', \'&quot;\'),';
		}
		if(isset($this->field_caption['_HTML_TITLE_'])) $sql .= ' seo_title = \'' . $this->db->escape($data[$this->field_caption['_HTML_TITLE_']]) . '\',';
		if(isset($this->field_caption['_HTML_H1_'])) $sql .= ' seo_h1 = \'' . $this->db->escape($data[$this->field_caption['_HTML_H1_']]) . '\',';
		if(isset($this->field_caption['_META_KEYWORDS_'])) $sql .= ' meta_keyword = \'' . $this->db->escape($data[$this->field_caption['_META_KEYWORDS_']]) . '\',';
		if(isset($this->field_caption['_META_DESCRIPTION_'])) $sql .= ' meta_description = \'' . $this->db->escape($data[$this->field_caption['_META_DESCRIPTION_']]) . '\',';
		if(isset($this->field_caption['_DESCRIPTION_'])) {
			$sql .= ' description = \'' . $this->db->escape(htmlspecialchars($data[$this->field_caption['_DESCRIPTION_']])) . '\',';
		}
		if(in_array(VERSION, array('1.5.4', '1.5.4.1', '1.5.5', '1.5.5.1'))){
			if(isset($this->field_caption['_PRODUCT_TAG_'])) $sql .= ' tag = \'' . $this->db->escape(htmlspecialchars($data[$this->field_caption['_PRODUCT_TAG_']])) . '\',';
		}
		
		// Custom Fields product
		//-------------------------------------------------------------------------
		if( isset($this->CustomFields[DB_PREFIX . 'product_description']) && count($this->CustomFields[DB_PREFIX . 'product_description']) > 0 ) {
			foreach ($this->CustomFields[DB_PREFIX . 'product_description'] as $field) {
				if(isset($this->field_caption[$field['csv_name']])) $sql .= ' ' . $field['field_name'] . ' = \'' . $this->db->escape( htmlspecialchars($data[$this->field_caption[$field['csv_name']]]) ) . '\',';
			}
		}
		
		if(!empty($sql)) {
			$sql = 'INSERT INTO `' . DB_PREFIX . 'product_description` SET ' . $sql . ' product_id = \'' . (int)$product_id . '\', language_id = \'' . (int)$this->setting['language_id'] . '\'';
			$this->db->query($sql);
		}
		
		// Add product Tag for old VERSION
		//-------------------------------------------------------------------------
		if(in_array(VERSION, array('1.5.1.3', '1.5.1.3.1', '1.5.2', '1.5.2.1', '1.5.3', '1.5.3.1'))){
			if(isset($this->field_caption['_PRODUCT_TAG_'])) {
				$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_tag` WHERE product_id = \'' . (int)$product_id . '\' AND language_id = \'' . (int)$this->setting['language_id'] . '\'');
				if( !empty($data[$this->field_caption['_PRODUCT_TAG_']]) ) {
					$tags = explode(',', $data[$this->field_caption['_PRODUCT_TAG_']]);
					foreach ($tags as $tag) {
						$sql = 'INSERT INTO `' . DB_PREFIX . 'product_tag` SET product_id = \'' . (int)$product_id . '\', language_id = \'' . (int)$this->setting['language_id'] . '\', tag = \'' . $this->db->escape(trim($tag)) . '\'';
						$this->db->query($sql);
					}
				}
			}
		}
		
		// Add Product SEO Keyword
		//-------------------------------------------------------------------------
		$sql = '';
		if(isset($this->field_caption['_SEO_KEYWORD_'])) {
			$this->db->query('DELETE FROM `' . DB_PREFIX . 'url_alias` WHERE query = \'product_id=' . (int)$product_id . '\'');
			if( !empty($data[$this->field_caption['_SEO_KEYWORD_']]) ) {
				$sql = 'INSERT INTO `' . DB_PREFIX . 'url_alias` SET query = \'product_id=' . (int)$product_id . '\', keyword = \'' . $this->db->escape($data[$this->field_caption['_SEO_KEYWORD_']]) . '\'';
				$this->db->query($sql);
			}
		}
		
		// Add Product Images
		//-------------------------------------------------------------------------
		if(isset($this->field_caption['_IMAGES_'])) {
			$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_image` WHERE product_id = \'' . (int)$product_id . '\'');
			if( !empty($data[$this->field_caption['_IMAGES_']]) ) {
				$images = explode(',', $data[$this->field_caption['_IMAGES_']]);
				foreach ($images as $image) {
					if(!empty($image)) {
						$sql = 'INSERT INTO `' . DB_PREFIX . 'product_image` SET product_id = \'' . (int)$product_id . '\', image = \'' . $this->db->escape(html_entity_decode($image, ENT_QUOTES, 'UTF-8')) . '\'';
						$this->db->query($sql);
					}
				}
			}
		}

		// Add Product Category
		//-------------------------------------------------------------------------
		$main_category_id = 0;

		if( isset($this->field_caption['_CATEGORY_ID_']) && !empty($data[$this->field_caption['_CATEGORY_ID_']]) ) {
			$categories = explode(',', $data[$this->field_caption['_CATEGORY_ID_']]);
		} else {
			$categories = $this->setting['product_category'];
		}
		
		if( isset($this->field_caption['_CATEGORY_']) && !empty($data[$this->field_caption['_CATEGORY_']]) ) {
			$categories = $this->addCategory($data[$this->field_caption['_CATEGORY_']]);
		}
		
		if (!empty($categories)) {
			if($this->CoreType == 'ocstore' && $this->setting['skip_main_category'] == 1) {
					
				$result = $this->db->query('SELECT category_id FROM `' . DB_PREFIX . 'product_to_category` WHERE product_id = \'' . (int)$product_id . '\' AND main_category = 1');
				
				if($result->num_rows > 0) {
					$main_category_id = $result->row['category_id'];
				}
				
			} 
			$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_to_category` WHERE product_id = \'' . (int)$product_id . '\'');

			foreach ($categories as $category_id) {
				$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_to_category` SET product_id = \'' . (int)$product_id . '\', category_id = \'' . (int)$category_id . '\'');
			}
			
			if($main_category_id == 0) {
				$main_category_id = $category_id;
			}
		}
		
		// Add Product Main Category
		//-------------------------------------------------------------------------
		if($this->CoreType == 'ocstore') {
			if(empty($categories) && $this->setting['skip_main_category'] == 0) {
				$main_category_id = $this->setting['main_category_id'];
			}
			if( $main_category_id > 0 ) {
				$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_to_category` WHERE (product_id = \'' . (int)$product_id . '\' AND category_id = \'' . (int)$main_category_id . '\') OR (product_id = \'' . (int)$product_id . '\' AND main_category = 1)');
				$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_to_category` SET product_id = \'' . (int)$product_id . '\', category_id = \'' . (int)$main_category_id . '\', main_category = 1');
			}
		}
		
		if( isset($this->field_caption['_MAIN_CATEGORY_']) && !empty($data[$this->field_caption['_MAIN_CATEGORY_']]) ) {
			$this->updateMainCategory($product_id, $data[$this->field_caption['_MAIN_CATEGORY_']]);
		}
		
		// Add Product options
		//-------------------------------------------------------------------------
		if( isset($this->field_caption['_OPTIONS_']) ) {

			$this->addProductOptions($product_id, $data[$this->field_caption['_OPTIONS_']]);
		}
		
		// Add Product Attributes
		//-------------------------------------------------------------------------
		if( isset($this->field_caption['_ATTRIBUTES_'])) {
				
			$attributes = array();
			
			$attributes = explode("\n", $data[$this->field_caption['_ATTRIBUTES_']]);
			
			if(!empty($attributes)) {
				$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_attribute` WHERE product_id = \'' . (int)$product_id . '\'');
				
				$tmp_product_attributes = array(); // added in v2.2.2a
				foreach ($attributes as $attribute_date) {
					$attribute = explode('|', $attribute_date);
					if(count($attribute) == 3) {
						$attribute[0] = trim($attribute[0]);
						$attribute[1] = trim($attribute[1]);
						$attribute[2] = trim($attribute[2]);
						// check
						if(!isset($this->attributes[mb_strtolower($attribute[0].$attribute[1])])) {
							$attribute_id = $this->addProductAttribute($attribute[0], $attribute[1]);
						} else {
							$attribute_id = $this->attributes[mb_strtolower($attribute[0].$attribute[1])];
						}
						
						// Add
						if (!in_array($product_id.'-'.$attribute_id.'-'.$this->setting['language_id'], $tmp_product_attributes) ) {
							$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_attribute` 
								SET	product_id = \'' . (int)$product_id . '\',
								attribute_id = \'' . (int)$attribute_id . '\',
								language_id = \'' . (int)$this->setting['language_id'] . '\',
								text = \'' .$this->db->escape($attribute[2]) . '\'
							');
							$tmp_product_attributes[] = $product_id.'-'.$attribute_id.'-'.$this->setting['language_id'];
						}
					}
				}
			}
		}
		return TRUE;
	}

	//-------------------------------------------------------------------------
	// Export Data to CSV
	//-------------------------------------------------------------------------
	public function export(&$data) {
		$this->setting = $data;
		
		// init Macros
		//-------------------------------------------------------------------------
		$this->initMacros();

		$output = '';

		$ods_title = array();
		
		// CSV Category Title
		if($this->setting['export_category'] == 1) {
			$ods_title[] = '_CATEGORY_ID_';
		} elseif ($this->setting['export_category'] == 2) {
			$ods_title[] = '_CATEGORY_';
		}
		
		$_fields = array();
		$_left = array();
		$_where = array();
		
		if(isset($data['fields_set']['_ID_'])) { $_fields[] = 'p.product_id'; $ods_title[] = '_ID_'; }
		
		if(isset($data['fields_set']['_MAIN_CATEGORY_'])) {
			$_fields[] =  " ( SELECT cd.name FROM `" . DB_PREFIX . "product_to_category` p2c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON (p2c.category_id = cd.category_id) WHERE p2c.main_category = 1 AND p2c.product_id = p.product_id LIMIT 1) AS main_category ";
			$ods_title[] = '_MAIN_CATEGORY_';
		}
		
		if(isset($data['fields_set']['_NAME_'])) {
			$_fields[] = 'REPLACE (pd.name, \'&quot;\', \'"\') AS name';
			if( ! isset($_left['pd']) ) $_left['pd'] = " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id) ";
			if( ! isset($_where['pd']) ) $_where['pd'] = " pd.language_id = '" . (int)$data['language_id'] . "' ";
			$ods_title[] = '_NAME_';
 
		}
		if(isset($data['fields_set']['_MODEL_'])) { $_fields[] = 'REPLACE (p.model, \'&quot;\', \'"\') AS model'; $ods_title[] = '_MODEL_'; }
		if(isset($data['fields_set']['_SKU_'])) { $_fields[] = 'p.sku'; $ods_title[] = '_SKU_'; }
		if(in_array(VERSION, array('1.5.4', '1.5.4.1', '1.5.5', '1.5.5.1'))){
			if(isset($data['fields_set']['_EAN_'])) { $_fields[] = 'p.ean'; $ods_title[] = '_EAN_'; }
			if(isset($data['fields_set']['_JAN_'])) { $_fields[] = 'p.jan'; $ods_title[] = '_JAN_'; }
			if(isset($data['fields_set']['_ISBN_'])) { $_fields[] = 'p.isbn'; $ods_title[] = '_ISBN_'; }
			if(isset($data['fields_set']['_MPN_'])) { $_fields[] = 'p.mpn'; $ods_title[] = '_MPN_'; }
		}
		if(isset($data['fields_set']['_UPC_'])) { $_fields[] = 'p.upc'; $ods_title[] = '_UPC_'; }
		if(isset($data['fields_set']['_MANUFACTURER_'])) {
			$_left[] = ' LEFT JOIN `' . DB_PREFIX . 'manufacturer` m ON (p.manufacturer_id = m.manufacturer_id) ';
			$_fields[] = 'm.name AS manufacturer';
			$ods_title[] = '_MANUFACTURER_';
		}
		
		if(isset($data['fields_set']['_SHIPPING_'])) { $_fields[] = 'p.shipping'; $ods_title[] = '_SHIPPING_'; }
		if(isset($data['fields_set']['_LOCATION_'])) { $_fields[] = 'p.location'; $ods_title[] = '_LOCATION_'; }
		if(isset($data['fields_set']['_PRICE_'])) { $_fields[] = 'TRUNCATE(p.price, 2) AS price'; $ods_title[] = '_PRICE_'; }
		if(isset($data['fields_set']['_POINTS_'])) { $_fields[] = 'p.points'; $ods_title[] = '_POINTS_'; }
		if(isset($data['fields_set']['_QUANTITY_'])) { $_fields[] = 'p.quantity'; $ods_title[] = '_QUANTITY_'; }
		if(isset($data['fields_set']['_STOCK_STATUS_ID_'])) { $_fields[] = 'p.stock_status_id'; $ods_title[] = '_STOCK_STATUS_ID_'; }
		if(isset($data['fields_set']['_LENGTH_'])) { $_fields[] = 'p.length'; $ods_title[] = '_LENGTH_'; }
		if(isset($data['fields_set']['_WIDTH_'])) { $_fields[] = 'p.width'; $ods_title[] = '_WIDTH_'; }
		if(isset($data['fields_set']['_HEIGHT_'])) { $_fields[] = 'p.height'; $ods_title[] = '_HEIGHT_'; }
		if(isset($data['fields_set']['_WEIGHT_'])) { $_fields[] = 'p.weight'; $ods_title[] = '_WEIGHT_'; }
		
		// SEO Keyword
		if(isset($data['fields_set']['_SEO_KEYWORD_'])) {
			$_fields[] = "(SELECT su.keyword FROM `" . DB_PREFIX . "url_alias` su WHERE su.query = CONCAT('product_id=', p.product_id) LIMIT 1) AS keyword";
			$ods_title[] = '_SEO_KEYWORD_';
		}
		
		if(isset($data['fields_set']['_HTML_TITLE_'])) {
			$_fields[] = 'pd.seo_title';
			if( ! isset($_left['pd']) ) $_left['pd'] = " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id) ";
			if( ! isset($_where['pd']) ) $_where['pd'] = " pd.language_id = '" . (int)$data['language_id'] . "' ";
			$ods_title[] = '_HTML_TITLE_';
		}
		
		if(isset($data['fields_set']['_HTML_H1_'])) {
			$_fields[] = 'pd.seo_h1';
			if( ! isset($_left['pd']) ) $_left['pd'] = " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id) ";
			if( ! isset($_where['pd']) ) $_where['pd'] = " pd.language_id = '" . (int)$data['language_id'] . "' ";
			$ods_title[] = '_HTML_H1_';
		}
		
		if(isset($data['fields_set']['_META_KEYWORDS_'])) {
			$_fields[] = 'pd.meta_keyword';
			if( ! isset($_left['pd']) ) $_left['pd'] = " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id) ";
			if( ! isset($_where['pd']) ) $_where['pd'] = " pd.language_id = '" . (int)$data['language_id'] . "' ";
			$ods_title[] = '_META_KEYWORDS_';
		}
		
		if(isset($data['fields_set']['_META_DESCRIPTION_'])) {
			$_fields[] = 'pd.meta_description';
			if( ! isset($_left['pd']) ) $_left['pd'] = " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id) ";
			if( ! isset($_where['pd']) ) $_where['pd'] = " pd.language_id = '" . (int)$data['language_id'] . "' ";
			$ods_title[] = '_META_DESCRIPTION_';
		}
		
		if(isset($data['fields_set']['_DESCRIPTION_'])) {
			$_fields[] = 'pd.description';
			if( ! isset($_left['pd']) ) $_left['pd'] = " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id) ";
			if( ! isset($_where['pd']) ) $_where['pd'] = " pd.language_id = '" . (int)$data['language_id'] . "' ";
			$ods_title[] = '_DESCRIPTION_';
		}
		
		// Product Tags
		if(isset($data['fields_set']['_PRODUCT_TAG_'])) {
			// For ocStore & OpenCart
			if(in_array(VERSION, array('1.5.4', '1.5.4.1', '1.5.5', '1.5.1'))) {
				$_fields[] = 'pd.tag';
				if( ! isset($_left['pd']) ) $_left['pd'] = " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id) ";
				if( ! isset($_where['pd']) ) $_where['pd'] = " pd.language_id = '" . (int)$data['language_id'] . "' ";
				$ods_title[] = '_PRODUCT_TAG_';
			}
		}

		// Product Image
		if(isset($data['fields_set']['_IMAGE_'])) { $_fields[] = 'p.image'; $ods_title[] = '_IMAGE_'; }
		
		// Product Sort Order
		if(isset($data['fields_set']['_SORT_ORDER_'])) { $_fields[] = 'p.sort_order'; $ods_title[] = '_SORT_ORDER_'; }

		// Product Status
		if(isset($data['fields_set']['_STATUS_'])) { $_fields[] = 'p.status'; $ods_title[] = '_STATUS_'; }
		
		// Custom Fields product
		if( isset($this->CustomFields[DB_PREFIX . 'product']) && count($this->CustomFields[DB_PREFIX . 'product']) > 0 ) {
			foreach ($this->CustomFields[DB_PREFIX . 'product'] as $field) {
				if(isset($data['fields_set'][$field['csv_name']])) {
					$_fields[] = 'p.' . $field['field_name']; 
					$ods_title[] = $field['csv_name'];
				}
			}
		}
		
		// Custom Fields product_description
		if( isset($this->CustomFields[DB_PREFIX . 'product_description']) && count($this->CustomFields[DB_PREFIX . 'product_description']) > 0 ) {
			foreach ($this->CustomFields[DB_PREFIX . 'product_description'] as $field) {
				if(isset($data['fields_set'][$field['csv_name']])) {
					$_fields[] = 'pd.' . $field['field_name'];
					if( ! isset($_left['pd']) ) $_left['pd'] = " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id) ";
					if( ! isset($_where['pd']) ) $_where['pd'] = " pd.language_id = '" . (int)$data['language_id'] . "' ";
					$ods_title[] = $field['csv_name'];
				}
			}
		}
		
		// WHERE quantity 
		if((int)$data['product_qty'] > 0 ) {
			$_where['qty'] = ' p.quantity <= '.(int)$data['product_qty'] . ' ';
		}
		
		// WHERE manufacturer 
		if($data['manufacturer']) {
			$manufacturer  =  implode(',', $data['manufacturer']);
			$_where[] = ' (p.manufacturer_id IN ('.$manufacturer.')) ';
		}

		// WHERE category 
		if($data['category']) {
			$category  =  implode(',', $data['category']);
			$_where[] = ' (p2c.category_id IN ('.$category .')) ';
			$_left[] = ' LEFT JOIN `' . DB_PREFIX . 'product_to_category` p2c ON (p.product_id = p2c.product_id) ';
		}
		
		// LIMIT 
		$_limit =  " LIMIT " . (int)$data['limit_start'] . ", " . (int)$data['limit_end'];
		
		if( count($_where) > 0 ) {
			$WHERE = ' WHERE ' . implode('AND', $_where) . ' ';
		} else {
			$WHERE = '';
		}

		$sql = 'SELECT DISTINCT '  . (implode(',', $_fields)) . ' FROM ' . DB_PREFIX . 'product p '. (implode(' ', $_left)) . $WHERE . ' ORDER BY p.product_id ' . $_limit;

		$query = $this->db->query($sql);
		
		if(count($query->rows) < 1) {
			$output = array(
				'error' => 'error_export_empty_rows'
			); 
			return $output;
		}
		
		if ($data['file_format'] == 'csv') {
			//EXPORT CSV FORMAT
			
			$charset = ini_get('default_charset');
			ini_set('default_charset', 'UTF-8');
			$tmp = $this->get_tmp_dir();
			$uid = uniqid();
			$tmp_dir = $tmp . '/' . $uid . '/';
			$file = $tmp . '/' . $uid.'.csv';
			
			if (($handle = fopen($file, 'w')) !== FALSE) {
		
				// CSV Caption Title
				if(isset($data['fields_set']['_DISCOUNT_'])) {
					$ods_title[] = '_DISCOUNT_';
				}
				if(isset($data['fields_set']['_SPECIAL_'])) {
					$ods_title[] = '_SPECIAL_';
				}
				if(isset($data['fields_set']['_OPTIONS_'])) {
					$ods_title[] = '_OPTIONS_';
				}
				if(isset($data['fields_set']['_ATTRIBUTES_'])) {
					$ods_title[] = '_ATTRIBUTES_';
				}
				if(isset($data['fields_set']['_IMAGES_'])) {
					$ods_title[] = '_IMAGES_';
				}
				if(isset($data['fields_set']['_PRODUCT_TAG_'])) {
					if (in_array(VERSION, array('1.5.1.3', '1.5.1.3.1', '1.5.2', '1.5.2.1', '1.5.3.1'))) {
						$ods_title[] = '_PRODUCT_TAG_';
					}
				}
				fputcsv($handle, $ods_title, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE);
				
				foreach ($query->rows as $fields) {

					// Get Category for Product
					if($this->setting['export_category'] == 1 || $this->setting['export_category'] == 2) {
						$cat = $this->getCategory($fields['product_id']);
						$fields = array_merge(array('category' => (string)$cat), $fields);
					}
					// Get discount for Product
					if(isset($data['fields_set']['_DISCOUNT_'])) {
						$fields['discount'] = $this->getProductDiscounts($fields['product_id']);
					}

					// Get Special for Product
					if(isset($data['fields_set']['_SPECIAL_'])) {
						$fields['special'] = $this->getProductSpecial($fields['product_id']);
					}
					
					// Get options for Product
					if(isset($data['fields_set']['_OPTIONS_'])) {
						$fields['options'] = $this->getProductOptions($fields['product_id'], (int)$data['language_id']);
					}
					// Get attribute for Product
					if(isset($data['fields_set']['_ATTRIBUTES_'])) {
						$fields['attribute'] = $this->getProductAttribute($fields['product_id'], (int)$data['language_id']);
					}
					// Get Images for Product
					if(isset($data['fields_set']['_IMAGES_'])) {
						$fields['images'] = $this->getProductImages($fields['product_id']);
					}
					
					if(isset($fields['description']) AND $fields['description'] != '' ) {
						$fields['description'] = htmlspecialchars_decode($fields['description']);
					}
					
					// Get Product Tags
					if(isset($data['fields_set']['_PRODUCT_TAG_'])) {
							
						// OpenCart & ocStore
						if (in_array(VERSION, array('1.5.1.3', '1.5.1.3.1', '1.5.2', '1.5.2.1', '1.5.3.1'))) {
							$fields['tag'] = $this->getProductTag($fields['product_id'], (int)$data['language_id']);
						}
					}
    				
    				fputcsv($handle, $fields, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE);
				}
				fclose($handle);
			} else {
				return '';
			}
				
			if (($output = file_get_contents($file)) !== FALSE ) {
				unlink($file);
				return $output;
			} else {
				return '';
			}
			
			 
		}
	}

	private function updateDiscount(&$product_id, $discount) {
		$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_discount` WHERE product_id = \'' . (int)$product_id . '\'');
		
		 if (empty($discount)) {
		 	return;
		 } 

		$product_discount = explode("\n", $discount);
		
		$discount_data = array();
		
		foreach ($product_discount as $str) {
			$discount_data[] = explode(',', trim($str));
		}
		unset($product_discount);
		
		if (!empty($discount_data)) {
			foreach ($discount_data as $product_discount) {
				if(count($product_discount) < 4) {
					continue;
				}
				$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_discount`
					SET product_id = \'' . (int)$product_id . '\', 
					customer_group_id = \'' . (int)$product_discount[0] . '\', 
					quantity = \'' . (int)$product_discount[1] . '\', 
					priority = \'' . (int)$product_discount[2] . '\', 
					price = \'' . (float)$product_discount[3] . '\', 
					date_start = \'' . ((isset($product_discount[4])) ? $this->db->escape($product_discount[4]) : '') . '\', 
					date_end = \'' . ((isset($product_discount[5])) ? $this->db->escape($product_discount[5]) : '') . '\''
				);
			}
		}
	}
	
	//-------------------------------------------------------------------------
	// Update Product Special
	//-------------------------------------------------------------------------
	private function updateSpecial(&$product_id, $special) {
		$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_special` WHERE product_id = \'' . (int)$product_id . '\'');
		
		if (empty($special)) {
		 	return;
		 }

		$product_special = explode("\n", $special);
		
		$special_data = array();
		
		foreach ($product_special as $str) {
			$special_data[] = explode(',', trim($str));
		}
		unset($product_special);
		
		if (!empty($special_data)) {
			foreach ($special_data as $product_special) {
				if(count($product_special) < 3) {
					continue;
				}
				$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_special`
					SET product_id = \'' . (int)$product_id . '\', 
					customer_group_id = \'' . (int)$product_special[0] . '\', 
					priority = \'' . (int)$product_special[1] . '\', 
					price = \'' . (float)$product_special[2] . '\', 
					date_start = \'' . ((isset($product_special[3])) ? $this->db->escape($product_special[3]) : '') . '\', 
					date_end = \'' . ((isset($product_special[4])) ? $this->db->escape($product_special[4]) : '') . '\''
				);
			}
		}
	}
	
	private function findCategory(&$data) {
		$index = -1;
		$sum = 0;
		
		for ($i=0; $i < count($data) ; $i++) { 
			if( !empty($data[$i]) ) {
				$sum++;
				$index = $i;
			}
		}

		if($sum == 1) {
			$prefix_count = substr_count($data[$index], $this->setting['sub_category_prefix']);
			if($prefix_count == 0) {
				$this->Categories = array();
				$this->Categories[] = $data[$index];
				$this->CategoriesString = $data[$index];
			} else {
				$this->Categories[$prefix_count] = str_replace('!', '', $data[$index]);
				$this->Categories = array_slice($this->Categories, 0, $prefix_count + 1);
				$this->CategoriesString = implode($this->setting['delimiter_category'], $this->Categories);
			}
			
			if( !isset($this->field_caption['_CATEGORY_']) ) {
				$this->field_caption['_CATEGORY_'] = count($this->field_caption);
				$this->FindCategory = 1;
			}
			
			return true;

		} else {

			return false;
		}

	}
	
	//-------------------------------------------------------------------------
	// Update Product Main Category By Category Name
	//-------------------------------------------------------------------------
	private function updateMainCategory(&$product_id, &$category_name) {
		$result = $this->db->query('SELECT category_id FROM `' . DB_PREFIX . 'category_description` WHERE language_id = \''. (int)$this->setting['language_id']. '\' AND name = REPLACE(\'' . $this->db->escape($category_name) . '\', \'"\', \'&quot;\') LIMIT 1');
		
		if(isset($result->num_rows) AND $result->num_rows > 0 ) {
			if($result->row['category_id'] > 0) {
				$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_to_category` WHERE product_id = \'' . (int)$product_id . '\' AND category_id  = \'' . (int)$result->row['category_id'] . '\'');
				$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_to_category` SET product_id = \'' . (int)$product_id . '\',  category_id  = \'' . (int)$result->row['category_id'] . '\', main_category = 1');
			}
		}
	}
	
	private function addCategory($data) {
		if(empty($data)) {
			return 0;
		}
		$categories_id = array();
		
		$categories_data = explode("\n", $data);
		
		foreach ($categories_data as $data) {
				
			$categories = explode($this->setting['delimiter_category'], $data);
			$parent_id = 0;
	
			foreach ($categories as $category) {
				$category = trim($category, " \n\t");
				
				$result = $this->db->query('SELECT cd.category_id FROM `' . DB_PREFIX . 'category_description` cd LEFT JOIN `' . DB_PREFIX . 'category` c ON (c.category_id = cd.category_id) WHERE LOWER(cd.name) = LOWER(\'' . $this->db->escape($category) . '\') AND c.parent_id = \''.$parent_id.'\' LIMIT 1');

				if(isset($result->num_rows) AND $result->num_rows > 0 ) {
					$category_id = $result->row['category_id'];
				} else {
					if($parent_id == 0) {
						$column = 1;
						$top = 1;
					} else {
						$column = 0;
						$top = 0;
					}
					$this->db->query('INSERT INTO `' . DB_PREFIX . 'category` SET parent_id = ' . $parent_id . ', `top` = ' . (int)$top . ', `column` = ' . $column . ', sort_order = 1, status = 1, date_modified =  NOW(), date_added = NOW()');
					
					$category_id = $this->db->getLastId();
					
					$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$this->setting['language_id'] . "', name = '" . $this->db->escape($category) . "'");
					
					// Category to Store
					foreach ($this->setting['product_store'] as $store_id) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
					}
				}

				$parent_id = $category_id;
				
				if( $this->setting['fill_category'] ) {
					$categories_id[] = $category_id;
				}
			}
			if( !$this->setting['fill_category'] ) {
				$categories_id[] = $category_id;
			}
		}
		return array_unique($categories_id);
	}
	
	// Get Categories
	//-------------------------------------------------------------------------
	private function getCategory(&$product_id) {
		
		// export only main category for ocStore
		if( $this->CoreType == 'ocstore' && $this->setting['export_main_category'] == 1 && $this->setting['export_category'] == 1) {
			$result = $this->db->query('SELECT p2c.category_id FROM ' . DB_PREFIX . 'product_to_category p2c WHERE p2c.main_category = 1 AND p2c.product_id = \'' . (int)$product_id . '\' LIMIT 1');
			if($result->num_rows > 0) {
				return $result->row['category_id'];
			} else {
				return 0;
			}
		}
		
		$max_level = 10;
		$categories = array();
			
		// check Main Category for ocStore 
		$main_category_id = 0;
		if($this->CoreType == 'ocstore') {
			if($this->setting['export_main_category'] == 1){
				$order = ' ORDER BY p2c.main_category DESC LIMIT 1';
			} else {
				$order = ' ORDER BY p2c.main_category ';
			}
		} else {
			$order = '';
		}
		
		$sql = "SELECT CONCAT_WS(','";
		for ($i = $max_level-1; $i >= 0; --$i) {
			$sql .= ", t$i.category_id";
		}
		$sql .= ") AS path FROM " . DB_PREFIX . "product_to_category p2c LEFT JOIN " . DB_PREFIX . "category t0 ON (t0.category_id = p2c.category_id)";
		for ($i = 1; $i < $max_level; ++$i) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "category t$i ON (t$i.category_id = t" . ($i-1) . ".parent_id)";
		}
		$sql .= ' WHERE p2c.product_id = \'' . (int)$product_id . '\' ' . $order;

		$result = $this->db->query($sql);
		
		if($result->num_rows > 0) {
			foreach($result->rows as $product_path){
				if(empty($product_path['path'])) continue;
				
				if($this->setting['export_category'] == 1) {
					$categories[] = $product_path['path'];
				} elseif(!isset($this->PathNameByCategory[$product_path['path']])) {
					$path = $this->getPathNameByCategory($product_path['path']);
					if($path) {
						$categories[] = $this->PathNameByCategory[$product_path['path']] = $path;
					}
				} else {
					$categories[] = $this->PathNameByCategory[$product_path['path']];
				}
			}
		}
		
		if(empty($categories)) {
			return '';
		} else {
			$data =  implode(($this->setting['export_category'] == 1 ? ',' : "\n"), $categories);
			if( $this->setting['export_category'] == 1 ) {
				$categories = array();
				$categories = explode(',', $data);
				$main_category_id = array_pop($categories);
				$categories = array_unique($categories);
				$categories[] = $main_category_id;
				$data =  implode(',', $categories);
			}
			return $data;
		}

	}
	
	private function getPathNameByCategory($path){
		$category_path = array();

		//$result = $this->db->query("SELECT name FROM " . DB_PREFIX . "category_description WHERE category_id IN (" .$path. ") AND language_id = '" . (int)$this->setting['language_id']. "'");
		$result = $this->db->query("SELECT name FROM " . DB_PREFIX . "category_description WHERE category_id IN (" .$path. ") AND language_id = '" . (int)$this->setting['language_id']. "' ORDER BY FIELD (category_id, " .$path. ")");
		if($result->num_rows > 0) {
			foreach($result->rows as $category_info){
				$category_path[] = $category_info['name'];
			}
		}
		if(empty($category_path)) {
			return false;
		} else {
			return implode($this->setting['delimiter_category'], $category_path);
		}
	}
	
	// Add Manufacturer
	//-------------------------------------------------------------------------
	private function getManufacturer(&$name) {
		$name = trim($name, " \t\n");
		
		if(empty($name)) return 0;
		
		$result = $this->db->query('SELECT manufacturer_id FROM `' . DB_PREFIX . 'manufacturer` WHERE LOWER(name) = LOWER(\'' . $this->db->escape($name) . '\') LIMIT 1');
		
		if(isset($result->num_rows) AND $result->num_rows > 0 ) {
			return $result->row['manufacturer_id'];
		} else {
			$this->db->query('INSERT INTO `' . DB_PREFIX . 'manufacturer` SET name = \'' . $this->db->escape($name) . '\', sort_order = 0');
		
			$manufacturer_id = $this->db->getLastId();
	
			if (isset($this->setting['product_store'])) {
				foreach ($this->setting['product_store'] as $store_id) {
					$this->db->query('INSERT INTO `' . DB_PREFIX . 'manufacturer_to_store` SET manufacturer_id = \'' . (int)$manufacturer_id . '\', store_id = \'' . (int)$store_id . '\'');
				}
			}
			if ($this->CoreType == 'ocstore') {
				$this->db->query('INSERT INTO `' . DB_PREFIX . 'manufacturer_description` SET manufacturer_id = \'' . (int)$manufacturer_id . '\', language_id = \'' . (int)$this->setting['language_id'] . '\'');
			}
			return $manufacturer_id;
		}
	}
	
	// Add Options
	//-------------------------------------------------------------------------
	private function addProductOptions($product_id, $options) {
		// Delete old product option
		$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_option` WHERE  product_id = \'' . (int)$product_id . '\'');
		$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_option_value` WHERE  product_id = \'' . (int)$product_id . '\'');
		
		if(empty($options)){
			return;
		}
		$data = explode("\n", $options);
		
		if(!empty($data)) {
			
			foreach($data as $option_string){
				$option = explode('|', $option_string);
				
				if(empty($option)) {
					continue;
				}
				
				if(count($option) < 4 ) {
					
					// BEGIN Simple Adding Options
					$option = explode(',', $option_string);
					if( empty($option) ) {
						continue;
					} 
					foreach($option as $value) {
						$query = $this->db->query('SELECT option_id, option_value_id FROM `' . DB_PREFIX . 'option_value_description` WHERE LOWER(name) = LOWER(\'' .$this->db->escape(trim($value)) . '\') AND language_id = \'' . (int)$this->setting['language_id'] . '\' LIMIT 1');
				
						if($query->num_rows > 0 AND isset($query->row['option_value_id'])) {
							$option_id = $query->row['option_id'];
							$option_value_id = $query->row['option_value_id'];
						} else {
							continue; // END
						}
						
						$query = $this->db->query('SELECT product_option_id FROM `' . DB_PREFIX . 'product_option` WHERE product_id = \'' . (int)$product_id . '\' AND option_id = \'' . $option_id . '\' LIMIT 1');
				
						if(isset($query->row['product_option_id'])) {
							$product_option_id = $query->row['product_option_id'];
						} else {
							// Add new product option
							$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_option` SET product_id = \'' . (int)$product_id . '\', option_id = \'' . $option_id . '\', required = 0');
							$product_option_id = $this->db->getLastId();
						}

						$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_option_value` SET
							product_option_id = \'' . (int)$product_option_id . '\',
							 product_id = \'' . (int)$product_id . '\',
							  option_id = \'' . (int)$option_id . '\',
							option_value_id = \'' . (int)$option_value_id . '\',
							       quantity = 1,
							       subtract = '.(int)$this->setting['subtract'].',
							          price = 0,
							   price_prefix = \'+\',
							         points = 0,
							  points_prefix = \'+\',
							         weight = 0,
							  weight_prefix = \'+\'
						');
						unset($option_id);
						unset($product_option_id);
						unset($option_value_id);
					} 
					// END Simple Adding Options
					continue;
				}
				
				if( !(count($option) == 4 || count($option) == 5 || count($option) == 9 || count($option) == 10) ) {
					return;
				}
				
				$option[0] = trim($option[0]);
				$option[1] = trim($option[1]);
				$option[2] = trim($option[2]);
				$price = trim($option[3]);
				$price_prefix = '+';
				$points = 0;
				$points_prefix = '+';
				$weight = 0;
				$weight_prefix = '+';
				$option_required = 0;
				
				if(isset($option[4]) && count($option) == 5) {
					$option_required = trim($option[4]);
				} elseif (count($option) == 10 || count($option) == 9) {
						
					if(isset($option[9])) {
						$option_required = trim($option[9]);
					}
					
					// + Цена: + Баллы: + Вес:
					$price_prefix = trim($option[3]);
					$price = trim($option[4]);
					$points_prefix = trim($option[5]);
					$points = trim($option[6]);
					$weight_prefix = trim($option[7]);
					$weight = trim($option[8]);
				}
				
				$query = $this->db->query('SELECT option_id FROM `' . DB_PREFIX . 'option_description` WHERE LOWER(name) = LOWER(\'' .$this->db->escape($option[0]) . '\') AND language_id = \'' . (int)$this->setting['language_id'] . '\' LIMIT 1');
				
				if(isset($query->row['option_id'])) {
					$option_id = $query->row['option_id'];
				} else {
					// Add new Option
					$this->db->query('INSERT INTO `' . DB_PREFIX . 'option` SET type = \'select\', sort_order = 0');
					$option_id = $this->db->getLastId();
					$this->db->query('INSERT INTO `' . DB_PREFIX . 'option_description` SET language_id = \'' . (int)$this->setting['language_id'] . '\', option_id = \'' . $option_id . '\', name = \'' .$this->db->escape($option[0]) . '\'');
				}
				
				$query = $this->db->query('SELECT option_value_id FROM `' . DB_PREFIX . 'option_value_description` WHERE LOWER(name) = LOWER(\'' .$this->db->escape($option[1]) . '\') AND option_id = \'' . $option_id . '\' AND language_id = \'' . (int)$this->setting['language_id'] . '\' LIMIT 1');
				
				if(isset($query->row['option_value_id'])) {
					$option_value_id = $query->row['option_value_id'];
				} else {
					// Add new Option
					$this->db->query('INSERT INTO `' . DB_PREFIX . 'option_value` SET option_id = \'' . $option_id . '\', sort_order = 0');
					$option_value_id = $this->db->getLastId();
					$this->db->query('INSERT INTO `' . DB_PREFIX . 'option_value_description` SET language_id = \'' . (int)$this->setting['language_id'] . '\', option_value_id = \'' . $option_value_id . '\', option_id = \'' . $option_id . '\', name = \'' .$this->db->escape($option[1]) . '\'');
				}
				
				$query = $this->db->query('SELECT product_option_id FROM `' . DB_PREFIX . 'product_option` WHERE product_id = \'' . (int)$product_id . '\' AND option_id = \'' . $option_id . '\' LIMIT 1');
				
				if(isset($query->row['product_option_id'])) {
					$product_option_id = $query->row['product_option_id'];
				} else {
					// Add new product option
					$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_option` SET product_id = \'' . (int)$product_id . '\', option_id = \'' . $option_id . '\', required = \'' . $option_required . '\'');
					$product_option_id = $this->db->getLastId();
				}

				$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_option_value` SET
					product_option_id = \'' . (int)$product_option_id . '\',
					 product_id = \'' . (int)$product_id . '\',
					  option_id = \'' . (int)$option_id . '\',
					option_value_id = \'' . (int)$option_value_id . '\',
					       quantity = \''.$option[2]. '\',
					       subtract = '.(int)$this->setting['subtract'].',
					          price = \''.$price. '\',
					   price_prefix = \'' . $price_prefix . '\',
					         points = ' . $points . ',
					  points_prefix = \'' . $points_prefix . '\',
					         weight = ' . $weight . ',
					  weight_prefix = \'' . $weight_prefix . '\'
				');
			}
		}
	}
	// Add Attribute
	//-------------------------------------------------------------------------
	private function addProductAttribute($group_name, $attribute_name) {
		$query = $this->db->query('SELECT attribute_group_id FROM `' . DB_PREFIX . 'attribute_group_description` WHERE LOWER(name) = LOWER(\'' .$this->db->escape($group_name) . '\') AND language_id = \'' . (int)$this->setting['language_id'] . '\' LIMIT 1');

		if(isset($query->row['attribute_group_id'])) {
			$attribute_group_id = $query->row['attribute_group_id'];
		} else {
			$this->db->query('INSERT INTO `' . DB_PREFIX . 'attribute_group` SET sort_order = 1');
			$attribute_group_id = $this->db->getLastId();
			$this->db->query('INSERT INTO `' . DB_PREFIX . 'attribute_group_description` 
				SET attribute_group_id = '.(int)$attribute_group_id.',
				language_id = \'' . (int)$this->setting['language_id'] . '\',
				name = \'' .$this->db->escape($group_name) . '\'
			');
		}
		
		$query = $this->db->query('SELECT ad.attribute_id FROM `' . DB_PREFIX . 'attribute_description` ad 
			LEFT JOIN `' . DB_PREFIX . 'attribute` a ON (ad.attribute_id = a.attribute_id)
			WHERE LOWER(ad.name) = LOWER(\'' .$this->db->escape($attribute_name) . '\') AND ad.language_id = \'' . (int)$this->setting['language_id'] . '\' AND a.attribute_group_id = \'' . (int)$attribute_group_id . '\' LIMIT 1
			');
		
		if(isset($query->row['attribute_id'])) {
			$attribute_id = $query->row['attribute_id'];
		} else {
			$this->db->query('INSERT INTO `' . DB_PREFIX . 'attribute` SET sort_order = 1, attribute_group_id = '. (int)$attribute_group_id);
			$attribute_id = $this->db->getLastId();
			$this->db->query('INSERT INTO `' . DB_PREFIX . 'attribute_description`
				SET attribute_id = '.(int)$attribute_id.',
				language_id = \'' . (int)$this->setting['language_id'] . '\',
				name = \'' .$this->db->escape($attribute_name) . '\'
			');
		}

		return $attribute_id;
	}
	
	// get Product Images
	//-------------------------------------------------------------------------
	private function getProductImages($product_id) {
		$images = array();
		$query = $this->db->query('SELECT pi.image FROM `'. DB_PREFIX . 'product_image` pi WHERE pi.product_id = ' . (int)$product_id);
		
		foreach ($query->rows as $result) {
			$images[] = $result['image'];
		}
		
		return implode(",", $images);
	}
	
	// get Discounts
	//-------------------------------------------------------------------------
	private function getProductDiscounts($product_id) {
		
		$discount = array();
		$query = $this->db->query('SELECT CONCAT( customer_group_id, \',\', quantity, \',\', priority, \',\', TRUNCATE(price, 2), \',\', date_start, \',\', date_end) AS p_discount  FROM `' . DB_PREFIX . 'product_discount` WHERE product_id = ' . (int)$product_id);
		
		foreach ($query->rows as $result) {
			$discount[] = $result['p_discount'];
		}
		return implode("\n", $discount);
	}
	
	//-------------------------------------------------------------------------
	// get Special
	//-------------------------------------------------------------------------
	private function getProductSpecial($product_id) {
		
		$special = array();
		$query = $this->db->query('SELECT CONCAT( ps.customer_group_id, \',\', ps.priority, \',\', TRUNCATE(ps.price, 2), \',\', ps.date_start, \',\', ps.date_end) AS p_special FROM `' . DB_PREFIX . 'product_special` ps WHERE ps.product_id = ' . (int)$product_id);
		/*$query = $this->db->query(
		'SELECT CONCAT( cgd.name, \',\', ps.priority, \',\', TRUNCATE(ps.price, 2), \',\', ps.date_start, \',\', ps.date_end) AS p_special 
		FROM `' . DB_PREFIX . 'product_special` ps
		WHERE cgd.language_id = \'' . (int)$this->config->get('config_language_id') . '\' AND ps.product_id = ' . (int)$product_id);*/
		
		foreach ($query->rows as $result) {
			$special[] = $result['p_special'];
		}
		return implode("\n", $special);
	}
	
	// get Options
	//-------------------------------------------------------------------------
	private function getProductOptions($product_id, $language_id = false) {
		
		$options = array();
		//$sql = "SELECT CONCAT(od.name, '|', ovd.name, '|', pov.quantity, '|', pov.price, '|', po.required) AS p_options
		$sql = "SELECT CONCAT(od.name, '|', ovd.name, '|', pov.quantity, '|', pov.price_prefix, '|', pov.price, '|', pov.points_prefix, '|', pov.points, '|', pov.weight_prefix, '|', pov.weight, '|', po.required) AS p_options
			FROM `" . DB_PREFIX . "product_option_value` pov
			LEFT JOIN `" . DB_PREFIX . "product_option` po ON (pov.product_option_id = po.product_option_id)
			LEFT JOIN `" . DB_PREFIX . "option_value_description` ovd ON (pov.option_value_id = ovd.option_value_id AND ovd.language_id = '" . (int)$language_id . "')
			LEFT JOIN `" . DB_PREFIX . "option_description` od ON (pov.option_id = od.option_id AND od.language_id = '" . (int)$language_id . "')
		  	WHERE pov.product_id = " . (int)$product_id;
		$query = $this->db->query($sql);
		
		foreach ($query->rows as $result) {
			$options[] = $result['p_options'];
		}
		
		return implode("\n", $options);
	}
	
	// Add Attribute
	//-------------------------------------------------------------------------
	private function getProductAttribute($product_id, $language_id = false) {
		$attribute = array();
		$sql = "SELECT CONCAT( attgd.name, '|', attd.name, '|', patt.text) AS p_attribute FROM `" . DB_PREFIX . "product_attribute` patt
		  	LEFT JOIN `" . DB_PREFIX . "attribute_description` attd ON (attd.attribute_id = patt.attribute_id)
		  	LEFT JOIN `" . DB_PREFIX . "attribute` att ON (attd.attribute_id = att.attribute_id) 
		  	LEFT JOIN `" . DB_PREFIX . "attribute_group_description` attgd ON (attgd.attribute_group_id = att.attribute_group_id)
		  	WHERE patt.product_id = " . (int)$product_id . " AND attgd.language_id = '" . (int)$language_id . "' AND attd.language_id = '" . $language_id . "' AND patt.language_id = '" . $language_id . "'";
		$query = $this->db->query($sql);
		
		foreach ($query->rows as $result) {
			$attribute[] = $result['p_attribute'];
		}
		
		return implode("\n", $attribute);
	}
	
	// Add Attribute
	//-------------------------------------------------------------------------
	private function getProductTag($product_id, $language_id = false) {
		$tags = array();
		$query = $this->db->query('SELECT tag FROM `' . DB_PREFIX . 'product_tag` WHERE product_id = \'' . (int)$product_id . '\' AND language_id = \'' . (int)$language_id . '\'');
		
		foreach ($query->rows as $result) {
			$tags[] = $result['tag'];
		}
		
		return implode(',', $tags);
	}
	
	// Add Attribute
	//-------------------------------------------------------------------------
	private function initMacros() {
		$csv_macros = $this->config->get('csv_macros');
		if(isset($csv_macros['custom_fields']) && count($csv_macros['custom_fields']) > 0) {
			foreach ($csv_macros['custom_fields'] as $field) {
				$this->CustomFields[$field['tbl_name']][] = array(
					'field_name' => $field['field_name'],
					'csv_name' => $field['csv_name']
				);
			}
		}
	}
	
	public function get_tmp_dir() {
		return DIR_CSVPRICE_PRO;
	}
	
	//-------------------------------------------------------------------------
	// Get Product ID 
	//-------------------------------------------------------------------------
	private function getProductID(&$data){
		return FALSE;
	}
	
	//-------------------------------------------------------------------------
	// Serach Product By Name
	//-------------------------------------------------------------------------
	private function getProductID_Name(&$data){
		if( empty($data[$this->field_caption['_NAME_']]) ) {
			return FALSE;
		}
		
		$result = $this->db->query('SELECT product_id FROM `' . DB_PREFIX . 'product_description` WHERE language_id = \''. (int)$this->setting['language_id']. '\' AND name = REPLACE(\'' . $this->db->escape($data[$this->field_caption['_NAME_']]) . '\', \'"\', \'&quot;\') LIMIT 1');
		
		if(isset($result->num_rows) AND $result->num_rows > 0 ) {
			return $result->row['product_id']; 
		} else {
			return FALSE;
		}
	}
	
	//-------------------------------------------------------------------------
	// Serach Product By Id
	//-------------------------------------------------------------------------
	private function getProductID_Id(&$data){
		if( empty($data[$this->field_caption['_ID_']]) ) {
			return FALSE;
		}
		
		$result = $this->db->query('SELECT product_id FROM `' . DB_PREFIX . 'product` WHERE product_id = \''. (int)$data[$this->field_caption['_ID_']]. '\' LIMIT 1');
		
		if(isset($result->num_rows) AND $result->num_rows > 0 ) {
			return $result->row['product_id']; 
		} else {
			return FALSE;
		}
	}

	//-------------------------------------------------------------------------
	// Serach Product By Model
	//-------------------------------------------------------------------------
	private function getProductID_Model(&$data){
		if( empty($data[$this->field_caption['_MODEL_']]) ) {
			return FALSE;
		}
		
		$result = $this->db->query('SELECT product_id FROM `' . DB_PREFIX . 'product` WHERE model = REPLACE(\'' . $this->db->escape($data[$this->field_caption['_MODEL_']]) . '\', \'"\', \'&quot;\') LIMIT 1');
		
		if(isset($result->num_rows) AND $result->num_rows > 0 ) {
			return $result->row['product_id']; 
		} else {
			return FALSE;
		}
	}
	
	//-------------------------------------------------------------------------
	// Serach Product By SKU
	//-------------------------------------------------------------------------
	private function getProductID_SKU(&$data){
		if( empty($data[$this->field_caption['_SKU_']]) ) {
			return FALSE;
		}
		
		$result = $this->db->query('SELECT product_id FROM `' . DB_PREFIX . 'product` WHERE sku = \''. $this->db->escape($data[$this->field_caption['_SKU_']]). '\' LIMIT 1');
		
		if(isset($result->num_rows) AND $result->num_rows > 0 ) {
			return $result->row['product_id']; 
		} else {
			return FALSE;
		}
	}
	
	//-------------------------------------------------------------------------
	// Serach Product By EAN
	//-------------------------------------------------------------------------
	private function getProductID_EAN(&$data){
		if( empty($data[$this->field_caption['_EAN_']]) ) {
			return FALSE;
		}
		
		$result = $this->db->query('SELECT product_id FROM `' . DB_PREFIX . 'product` WHERE ean = \''. $this->db->escape($data[$this->field_caption['_EAN_']]). '\' LIMIT 1');
		
		if(isset($result->num_rows) AND $result->num_rows > 0 ) {
			return $result->row['product_id']; 
		} else {
			return FALSE;
		}
	}
	
	//-------------------------------------------------------------------------
	// Serach Product By JAN
	//-------------------------------------------------------------------------
	private function getProductID_JAN(&$data){
		if( empty($data[$this->field_caption['_JAN_']]) ) {
			return FALSE;
		}
		
		$result = $this->db->query('SELECT product_id FROM `' . DB_PREFIX . 'product` WHERE jan = \''. $this->db->escape($data[$this->field_caption['_JAN_']]). '\' LIMIT 1');
		
		if(isset($result->num_rows) AND $result->num_rows > 0 ) {
			return $result->row['product_id']; 
		} else {
			return FALSE;
		}
	}
	
	//-------------------------------------------------------------------------
	// Serach Product By ISBN
	//-------------------------------------------------------------------------
	private function getProductID_ISBN(&$data){
		if( empty($data[$this->field_caption['_ISBN_']]) ) {
			return FALSE;
		}
		
		$result = $this->db->query('SELECT product_id FROM `' . DB_PREFIX . 'product` WHERE isbn = \''. $this->db->escape($data[$this->field_caption['_ISBN_']]). '\' LIMIT 1');
		
		if(isset($result->num_rows) AND $result->num_rows > 0 ) {
			return $result->row['product_id']; 
		} else {
			return FALSE;
		}
	}
	
	//-------------------------------------------------------------------------
	// Serach Product By MPN
	//-------------------------------------------------------------------------
	private function getProductID_MPN(&$data){
		if( empty($data[$this->field_caption['_MPN_']]) ) {
			return FALSE;
		}
		
		$result = $this->db->query('SELECT product_id FROM `' . DB_PREFIX . 'product` WHERE mpn = \''. $this->db->escape($data[$this->field_caption['_MPN_']]). '\' LIMIT 1');
		
		if(isset($result->num_rows) AND $result->num_rows > 0 ) {
			return $result->row['product_id']; 
		} else {
			return FALSE;
		}
	}
}
?>