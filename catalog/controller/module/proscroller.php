<?php  
class ControllerModuleproscroller extends Controller {
	
	protected $path = array();
	
	protected function index($setting) {
		static $module = 0;
		$this->language->load('module/proscroller');
		$this->load->model('catalog/category');
		
		$this->document->addScript('catalog/view/javascript/jquery/jquery.jcarousel.min.js');
		$this->document->addStyle('catalog/view/theme/default/stylesheet/proscroller.css');
		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/carousel.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/carousel.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/carousel.css');
		}
		
		if ($setting['title']) {
		$this->data['heading_title'] = $setting['title'][$this->config->get('config_language_id')];
		} else {
		$category = $this->model_catalog_category->getCategory($setting['category_id']);
			if (isset($category['name'])) {
			$this->data['heading_title'] = $category['name'];
				} else {
				$this->data['heading_title'] = $this->language->get('heading_title');
				}
		}
		
		if (($setting['position'] == 'column_left') || ($setting['position'] == 'column_right')){
		$this->data['position'] = 'column';
		}

    	$this->data['button_cart'] = $this->language->get('button_cart');
    	$this->data['visible'] = $setting['visible'];
    	$this->data['scroll'] = $setting['scroll'];
    	$this->data['sort'] = $setting['sort'];
		$this->data['type'] = "wrap: 'last'";
		if ($setting['autoscroll'] > 0) {
		$this->data['autoscroll'] = $setting['autoscroll'];}
			else {$this->data['autoscroll'] = '0';}
		if ($setting['animationspeed'] > 0) {
		$this->data['animationspeed'] = $setting['animationspeed'];}
			else {$this->data['animationspeed'] = '1000';}
		$this->data['hoverpause'] = $setting['hoverpause'];
		$this->data['disableauto'] = $setting['disableauto'];
			
		$this->data['show_title'] = $setting['show_title'];
		$this->data['show_price'] = $setting['show_price'];
		$this->data['show_rate'] = $setting['show_rate'];
		$this->data['show_cart'] = $setting['show_cart'];
			
		$this->load->model('module/proscroller');
		
		$this->load->model('tool/image');
		
		if (isset($this->request->get['path'])) {
			$this->path = explode('_', $this->request->get['path']);
			
			$this->category_id = end($this->path);
		}
		$url = '';

        $this->data['products'] = array();
			
		if ($setting['category_id'] == 'featured') {
			$this->data['products'] = $this->getfeaturedproducts($setting);
		} else {
			$this->data['products'] = $this->getcategoryproducts($setting);
		}
						
		$this->data['module'] = $module++; 
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/proscroller.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/proscroller.tpl';
		} else {
			$this->template = 'default/template/module/proscroller.tpl';
		}
		
		$this->render();
  	}
	
	public function getcategoryproducts($setting){
	
	$data = array(
				'filter_category_id'  => $setting['category_id'], 
				'filter_sub_category' => true, 
				'sort'                => $setting['sort'],
				'order'               => 'DESC',
				'start'               => '0',
				'limit'               => $setting['count']
			);
	$products = $this->model_module_proscroller->getProducts($data);
					
					foreach ($products as $product) {
						if ($product['image']) {
							$image = $product['image'];
						} else {
							$image = 'no_image.jpg';
						}
						
						if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
							$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
						} else {
							$price = false;
						}
								
						if ((float)$product['special']) {
							$special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
						} else {
							$special = false;
						}
				
						$options = $this->model_catalog_product->getProductOptions($product['product_id']);
						
						if ($this->config->get('config_review_status')) {
							$rating = (int)$product['rating'];
						} else {
							$rating = false;
						}
						
						$this->data['products'][] = array(
							'id'      => $product['product_id'],
							'name'    => $product['name'],
							'model'   => $product['model'],
							'qty'     => $product['quantity'],
							'rating'  => $rating,
							'reviews' => sprintf($this->language->get('text_reviews'), (int)$product['reviews']),
							'thumb'   => $this->model_tool_image->resize($image, $setting['image_width'], $setting['image_height']),
							'price'   => $price,
							'special' => $special,
							'href'    => $this->url->link('product/product', 'product_id=' . $product['product_id']),
						);
					}
		return $this->data['products'];
	}
	
	public function getfeaturedproducts($setting){
	$products = explode(',', $setting['featured']);		

		if (empty($setting['count'])) {
			$setting['count'] = 5;
		}
		
		$products = array_slice($products, 0, (int)$setting['count']);
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $setting['image_width'], $setting['image_height']);
				} else {
					$image = false;
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
						
				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
				
				if ($this->config->get('config_review_status')) {
					$rating = $product_info['rating'];
				} else {
					$rating = false;
				}
					
				$this->data['products'][] = array(
					'id' => $product_info['product_id'],
					'thumb'   	 => $image,
					'name'    	 => $product_info['name'],
					'price'   	 => $price,
					'special' 	 => $special,
					'rating'     => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
				);
			}
		}
		return $this->data['products'];
	}
	
	
	
}
?>