<?php

$_ = $cfg = array();

/**
* Admin files
**/

$code_steps = array();

$code_steps[] = array(
	'file'    => DIR_APPLICATION . 'controller/catalog/product.php',
	'package' => 'base',
	'actions' => array(
		array(
			'{SEARCH}' => 'function getForm() {',

			'{REPLACE}' => '\\1
			# OCFilter start
			$this->document->addStyle(\'view/stylesheet/ocfilter/ocfilter.css\');
			$this->document->addScript(\'view/javascript/ocfilter/ocfilter.js\');
			# OCFilter end'
		),

		array(
			'{SEARCH}' => '$this->data[\'tab_general\'] = $this->language->get(\'tab_general\');',

			'{REPLACE}' => '# OCFilter start
			$this->data[\'tab_ocfilter\'] = $this->language->get(\'tab_ocfilter\');
			$this->data[\'entry_values\'] = $this->language->get(\'entry_values\');
			$this->data[\'ocfilter_select_category\'] = $this->language->get(\'ocfilter_select_category\');
			# OCFilter end
			\\1'
		)
	)
);

$code_steps[] = array(
	'file'    => DIR_APPLICATION . 'controller/common/header.php',
	'package' => 'base',
	'actions' => array(
		array(
			'{SEARCH}' => '$this->language->get(\'text_option\');',

			'{REPLACE}' => '\\1
			# OCFilter start
			$this->data[\'text_ocfilter\'] = $this->language->get(\'text_ocfilter\');
			# OCFilter end'
		),

		array(
			'{SEARCH}' => '$this->url->link(\'catalog/option\', \'token=\' . $this->session->data[\'token\'], \'SSL\');',

			'{REPLACE}' => '\\1
				# OCFilter start
				$this->data[\'ocfilter\'] = $this->url->link(\'catalog/ocfilter\', \'token=\' . $this->session->data[\'token\'], \'SSL\');
				# OCFilter end'
		)
	)
);

$code_steps[] = array(
	'file'    => DIR_APPLICATION . 'language/{directory}/catalog/product.php',
	'package' => 'base',
	'actions' => array(
		array(
			'{SEARCH}' => '\'Действие\';',

			'{REPLACE}' => '\\1
	# OCFilter start
	$_[\'entry_values\']          		= \'Добавьте значения для этой опции.\';
	$_[\'tab_ocfilter\']          		= \'Опции фильтра\';
	$_[\'ocfilter_select_category\'] 	= \'Для начала, выберите категории для этого товара.\';
	# OCFilter end'
		)
	)
);

$code_steps[] = array(
	'file'    => DIR_APPLICATION . 'language/{directory}/common/header.php',
	'package' => 'base',
	'actions' => array(
		array(
			'{SEARCH}' => '\'Опции\';',

			'{REPLACE}' => '\\1
	# OCFilter start
	$_[\'text_ocfilter\']                      = \'Фильтр товаров OCFilter\';
	# OCFilter end'
		)
	)
);

$code_steps[] = array(
	'file'    => DIR_APPLICATION . 'model/catalog/product.php',
	'package' => 'base',
	'actions' => array(
		array(
	    '{PREG_QUOTE}' => false,

	    '{SEARCH}' => 'if \(isset\(\$data\[\'product_image\'\]\)\).+?\}.+?\}',

			'{REPLACE}' => '\\1

			# OCFilter start
      $this->db->query("DELETE FROM " . DB_PREFIX . "ocfilter_option_value_to_product WHERE product_id = \'" . (int)$product_id . "\'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "ocfilter_option_value_to_product_description WHERE product_id = \'" . (int)$product_id . "\'");

			if (isset($data[\'ocfilter_product_option\'])) {
				foreach ($data[\'ocfilter_product_option\'] as $option_id => $values) {
					foreach ($values[\'values\'] as $value_id => $value) {
						if (isset($value[\'selected\'])) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_value_to_product SET product_id = \'" . (int)$product_id . "\', option_id = \'" . (int)$option_id . "\', value_id = \'" . (int)$value_id . "\', slide_value_min = \'" . (isset($value[\'slide_value_min\']) ? (float)$value[\'slide_value_min\'] : 0) . "\', slide_value_max = \'" . (isset($value[\'slide_value_max\']) ? (float)$value[\'slide_value_max\'] : 0) . "\'");

							foreach ($value[\'description\'] as $language_id => $description) {
								if (trim($description[\'description\'])) {
									$this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_value_to_product_description SET product_id = \'" . (int)$product_id . "\', option_id = \'" . (int)$option_id . "\', value_id = \'" . (int)$value_id . "\', language_id = \'" . (int)$language_id . "\', description = \'" . $this->db->escape($description[\'description\']) . "\'");
								}
							}
						}
					}
				}
			}
			# OCFilter end'
		),

		array(
			'{SEARCH}' => '$data = array_merge($data, array(\'product_discount\' => $this->getProductDiscounts($product_id)));',

			'{REPLACE}' => '\\1
				# OCFilter start
				$this->load->model(\'catalog/ocfilter\');

				$data = array_merge($data, array(\'ocfilter_option_value_to_product\' => $this->model_catalog_ocfilter->getProductOCFilterValues($product_id)));
				# OCFilter end'
		),

		array(
			'{SEARCH}' => '$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = \'" . (int)$product_id . "\'");',

			'{REPLACE}' => '\\1
			# OCFilter start
			$this->db->query("DELETE FROM " . DB_PREFIX . "ocfilter_option_value_to_product WHERE product_id = \'" . (int)$product_id . "\'");
			# OCFilter end'
		)
	)
);

$code_steps[] = array(
	'file'    => DIR_APPLICATION . 'view/template/catalog/product_form.tpl',
	'package' => 'base',
	'actions' => array(
		array(
			'{SEARCH}' => '<?php echo $footer; ?>',

			'{REPLACE}' => '<!-- OCFilter start -->
	<script type="text/javascript"><!--
	ocfilter.php = {
		text_select: \'<?php echo $text_select; ?>\',
		ocfilter_select_category: \'<?php echo $ocfilter_select_category; ?>\',
		entry_values: \'<?php echo $entry_values; ?>\',
		tab_ocfilter: \'<?php echo $tab_ocfilter; ?>\'
	};

	ocfilter.php.languages = [];

	<?php foreach ($languages as $language) { ?>
	ocfilter.php.languages.push({
		\'language_id\': <?php echo $language[\'language_id\']; ?>,
		\'name\': \'<?php echo $language[\'name\']; ?>\',
	  \'image\': \'<?php echo $language[\'image\']; ?>\'
	});
	<?php } ?>

	//--></script>
  <!-- OCFilter end -->
	\\1'
		)
	)
);

$code_steps[] = array(
	'file'    => DIR_APPLICATION . 'view/template/common/header.tpl',
	'package' => 'base',
	'actions' => array(
		array(
			'{SEARCH}' => '<li><a href="<?php echo $product; ?>"><?php echo $text_product; ?></a></li>',

			'{REPLACE}' => '\\1
	          <!-- OCFilter start -->
	          <li><a href="<?php echo $ocfilter; ?>"><?php echo $text_ocfilter; ?></a></li>
	          <!-- OCFilter end -->'
		)
	)
);

/**
* Catalog files
**/

$code_steps[] = array(
	'file'    => DIR_CATALOG . 'model/catalog/product.php',
	'package' => 'base',
	'actions' => array(
		array(
			'{SEARCH}' => 'if (!empty($data[\'filter_manufacturer_id\']))',

			'{REPLACE}' => '# OCFilter start
				if (!empty($data[\'filter_ocfilter\'])) {
					if (!$this->registry->has(\'ocfilter_sql\') || null === $this->registry->get(\'ocfilter_sql\')) {
						$this->load->model(\'catalog/ocfilter\');

						$this->model_catalog_ocfilter->getOCFilterData($data);
					}

					if (!$this->registry->get(\'ocfilter_sql\')) {
						return (__FUNCTION__ == \'getTotalProducts\' ? 0 : array());
					}

					$sql .= $this->registry->get(\'ocfilter_sql\');

					if ($this->registry->has(\'ocfilter_sql\')) {
						$this->registry->set(\'ocfilter_sql\', null);
					}
				}
				# OCFilter end

				\\1'
		)
	)
);

$code_steps[] = array(
	'file'    => DIR_CATALOG . 'controller/product/category.php',
	'package' => 'base',
	'actions' => array(
		array(
			'{SEARCH}' => '$this->load->model(\'catalog/product\');',

			'{REPLACE}' => '\\1

			# OCFilter start
			$this->load->model(\'catalog/ocfilter\');

			if (isset($this->request->get[$this->ocfilter[\'index\']])) {
				$filter_ocfilter = $this->request->get[$this->ocfilter[\'index\']];
			} else {
				$filter_ocfilter = null;
			}
			# OCFilter end'
		),

		array(
	    '{PREG_QUOTE}' => false,

			'{SEARCH}' => '\'filter_category_id\'\s+?\=\>\s+?\$category_id,',

			'{REPLACE}' => '\\1
					# OCFilter start
					\'filter_ocfilter\'    => $filter_ocfilter,
					# OCFilter end'
		),

		array(
			'{SEARCH}' => '$results = $this->model_catalog_product->getProducts($data);',

			'{REPLACE}' => '\\1

				# OCFilter start
				$ocfilter_products_options = $this->model_catalog_ocfilter->getOCFilterProductsOptions($results);
				# OCFilter end'
		),

		array(
	    '{PREG_QUOTE}' => false,

			'{SEARCH}' => '\=\>\s+?\$result\[\'product_id\'\],',

			'{REPLACE}' => '\\1
						# OCFilter start
						\'ocfilter_products_options\' => $ocfilter_products_options[$result[\'product_id\']],
						# OCFilter end'
		),

		array(
			'{SEARCH}' => '$url = \'\';',

			'{REPLACE}' => '\\1

				# OCFilter start
				if (isset($this->request->get[$this->ocfilter[\'index\']])) {
					$url .= \'&\' . $this->ocfilter[\'index\'] . \'=\' . $this->request->get[$this->ocfilter[\'index\']];
				}
				# OCFilter end'
		)
	)
);

$code_steps[] = array(
	'file'    => DIR_CATALOG . 'controller/product/compare.php',
	'package' => 'compare',
	'actions' => array(
		array(
			'{SEARCH}' => '$this->load->model(\'catalog/product\');',

			'{REPLACE}' => '\\1
			# OCFilter start
			$this->load->model(\'catalog/ocfilter\');
			# OCFilter end'
		),

    array(
			'{SEARCH}' => '$this->data[\'attribute_groups\'] = array();',

			'{REPLACE}' => '\\1
			# OCFilter start
			$products_id = $this->session->data[\'compare\'];

			$product_options = array();

			if ($products_id) {
			  $filter_options = $this->model_catalog_ocfilter->getOptionsByProductsId($products_id);

			  foreach ($filter_options as $product_id => $options) {
			    foreach($options as $option) {
			      if (isset($option[\'values\']) && $option[\'values\']) {
			        $product_options[$product_id][$option[\'option_id\']] = $option[\'values\'];

			        $this->data[\'attribute_groups\'][$option[\'option_id\']] = array(
			          \'name\' => \'\',
			          \'attribute\' => array($option[\'option_id\'] => array(\'name\' => $option[\'name\']))
			        );
			      }
			    }
			  }
			}
			# OCFilter end'
		),

    array(
			'{SEARCH}' => '$attribute_data = array();',

			'{REPLACE}' => '\\1

			# OCFilter start
			if (isset($product_options[$product_id])) {
			  $attribute_data = $product_options[$product_id];
			}
			# OCFilter end'
		),

    array(
			'{PREG_QUOTE}' => false,

			'{SEARCH}' => '\$attribute_groups \=.+?\}.+?\}',

			'{REPLACE}' => ''
		),

    array(
			'{PREG_QUOTE}' => false,

			'{SEARCH}' => 'foreach \(\$attribute_groups as \$attribute_group\).+?\}.+?\}',

			'{REPLACE}' => ''
		)
	)
);

$code_steps[] = array(
	'file'    => DIR_CATALOG . 'controller/product/manufacturer.php',
	'package' => 'product_list_options',
	'actions' => array(
		array(
			'{SEARCH}' => '$this->load->model(\'catalog/product\');',

			'{REPLACE}' => '\\1

			# OCFilter start
			$this->load->model(\'catalog/ocfilter\');
			# OCFilter end'
		),

		array(
			'{SEARCH}' => '$results = $this->model_catalog_product->getProducts($data);',

			'{REPLACE}' => '\\1

				# OCFilter start
				$ocfilter_products_options = $this->model_catalog_ocfilter->getOCFilterProductsOptions($results);
				# OCFilter end'
		),

		array(
	    '{PREG_QUOTE}' => false,

			'{SEARCH}' => '\=\>\s+?\$result\[\'product_id\'\],',

			'{REPLACE}' => '\\1
						# OCFilter start
						\'ocfilter_products_options\' => $ocfilter_products_options[$result[\'product_id\']],
						# OCFilter end'
		)
	)
);

$code_steps[] = array(
	'file'    => DIR_CATALOG . 'controller/product/search.php',
	'package' => 'product_list_options',
	'actions' => array(
		array(
			'{SEARCH}' => '$this->load->model(\'catalog/product\');',

			'{REPLACE}' => '\\1

			# OCFilter start
			$this->load->model(\'catalog/ocfilter\');
			# OCFilter end'
		),

		array(
			'{SEARCH}' => '$results = $this->model_catalog_product->getProducts($data);',

			'{REPLACE}' => '\\1

				# OCFilter start
				$ocfilter_products_options = $this->model_catalog_ocfilter->getOCFilterProductsOptions($results);
				# OCFilter end'
		),

		array(
	    '{PREG_QUOTE}' => false,

			'{SEARCH}' => '\=\>\s+?\$result\[\'product_id\'\],',

			'{REPLACE}' => '\\1
						# OCFilter start
						\'ocfilter_products_options\' => $ocfilter_products_options[$result[\'product_id\']],
						# OCFilter end'
		)
	)
);

$code_steps[] = array(
	'file'    => DIR_CATALOG . 'controller/product/special.php',
	'package' => 'product_list_options',
	'actions' => array(
		array(
			'{SEARCH}' => '$this->load->model(\'catalog/product\');',

			'{REPLACE}' => '\\1

			# OCFilter start
			$this->load->model(\'catalog/ocfilter\');
			# OCFilter end'
		),

		array(
			'{SEARCH}' => '$results = $this->model_catalog_product->getProductSpecials($data);',

			'{REPLACE}' => '\\1

				# OCFilter start
				$ocfilter_products_options = $this->model_catalog_ocfilter->getOCFilterProductsOptions($results);
				# OCFilter end'
		),

		array(
	    '{PREG_QUOTE}' => false,

			'{SEARCH}' => '\=\>\s+?\$result\[\'product_id\'\],',

			'{REPLACE}' => '\\1
						# OCFilter start
						\'ocfilter_products_options\' => $ocfilter_products_options[$result[\'product_id\']],
						# OCFilter end'
		)
	)
);

$code_steps[] = array(
	'file'    => DIR_CATALOG . 'controller/product/product.php',
	'package' => 'product_info_options',
	'actions' => array(
		array(
			'{SEARCH}' => '$this->load->model(\'catalog/product\');',

			'{REPLACE}' => '\\1

			# OCFilter start
			$this->load->model(\'catalog/ocfilter\');
			# OCFilter end'
		),

		array(
			'{SEARCH}' => '$this->data[\'attribute_groups\'] = $this->model_catalog_product->getProductAttributes($this->request->get[\'product_id\']);',

			'{REPLACE}' => '# \\1
			# OCFilter start
			$this->data[\'attribute_groups\'] = array();

			$product_options = array();

			$ocfilter_options = $this->model_catalog_ocfilter->getOptionsByProductsId(array($this->request->get[\'product_id\']), true);

			if ($ocfilter_options) {
				foreach ($ocfilter_options as $product_id => $options) {
					foreach ($options as $option) {
            if (isset($option[\'values\']) && $option[\'values\']) {
							$this->data[\'attribute_groups\'][] = array(
								\'name\' => $option[\'name\'],
								\'attribute\' => array(array(
                  \'name\' => \'\',
                  \'text\' => $option[\'values\']
                ))
							);
						}
					}
				}
			}
			# OCFilter end'
		)
	)
);

$code_steps[] = array(
	'file'    => DIR_CATALOG . 'view/theme/' . $this->data['config_template'] . '/template/product/category.tpl',
	'package' => 'product_list_options',
	'actions' => array(
		array(
	    '{PREG_QUOTE}' => false,

			'{SEARCH}' => '\<\?[=a-z\s]+?\$product\[\'\w*?descr\w*?\'\].+?\?\>',

			'{REPLACE}' => '\\1
				<!-- OCFilter start -->
				<?php if ($product[\'ocfilter_products_options\']) { ?>
			  <?php if (is_array($product[\'ocfilter_products_options\'])) { ?>
			  <ul class="product-ocfilter-options">
			    <?php foreach ($product[\'ocfilter_products_options\'] as $ocfilter_option) { ?>
			    <li><span class="product-ocfilter-option"><?php echo $ocfilter_option[\'name\']; ?>:</span> <span class="product-ocfilter-value"><?php echo $ocfilter_option[\'values\']; ?></span></li>
			    <?php } ?>
			  </ul>
			  <?php } else { ?>
			  <?php echo $product[\'ocfilter_products_options\']; ?>
			  <?php } ?>
			  <?php } ?>
				<!-- OCFilter end -->
			'
		)
	)
);

$code_steps[] = array(
	'file'    => DIR_CATALOG . 'view/theme/' . $this->data['config_template'] . '/template/product/manufacturer_info.tpl',
	'package' => 'product_list_options',
	'actions' => array(
		array(
	    '{PREG_QUOTE}' => false,

			'{SEARCH}' => '\<\?[=a-z\s]+?\$product\[\'\w*?descr\w*?\'\].+?\?\>',

			'{REPLACE}' => '\\1
				<!-- OCFilter start -->
				<?php if ($product[\'ocfilter_products_options\']) { ?>
			  <?php if (is_array($product[\'ocfilter_products_options\'])) { ?>
			  <ul class="product-ocfilter-options">
			    <?php foreach ($product[\'ocfilter_products_options\'] as $ocfilter_option) { ?>
			    <li><span class="product-ocfilter-option"><?php echo $ocfilter_option[\'name\']; ?>:</span> <span class="product-ocfilter-value"><?php echo $ocfilter_option[\'values\']; ?></span></li>
			    <?php } ?>
			  </ul>
			  <?php } else { ?>
			  <?php echo $product[\'ocfilter_products_options\']; ?>
			  <?php } ?>
			  <?php } ?>
				<!-- OCFilter end -->
			'
		)
	)
);

$code_steps[] = array(
	'file'    => DIR_CATALOG . 'view/theme/' . $this->data['config_template'] . '/template/product/search.tpl',
	'package' => 'product_list_options',
	'actions' => array(
		array(
	    '{PREG_QUOTE}' => false,

			'{SEARCH}' => '\<\?[=a-z\s]+?\$product\[\'\w*?descr\w*?\'\].+?\?\>',

			'{REPLACE}' => '\\1
				<!-- OCFilter start -->
				<?php if ($product[\'ocfilter_products_options\']) { ?>
			  <?php if (is_array($product[\'ocfilter_products_options\'])) { ?>
			  <ul class="product-ocfilter-options">
			    <?php foreach ($product[\'ocfilter_products_options\'] as $ocfilter_option) { ?>
			    <li><span class="product-ocfilter-option"><?php echo $ocfilter_option[\'name\']; ?>:</span> <span class="product-ocfilter-value"><?php echo $ocfilter_option[\'values\']; ?></span></li>
			    <?php } ?>
			  </ul>
			  <?php } else { ?>
			  <?php echo $product[\'ocfilter_products_options\']; ?>
			  <?php } ?>
			  <?php } ?>
				<!-- OCFilter end -->
			'
		)
	)
);

$code_steps[] = array(
	'file'    => DIR_CATALOG . 'view/theme/' . $this->data['config_template'] . '/template/product/special.tpl',
	'package' => 'product_list_options',
	'actions' => array(
		array(
	    '{PREG_QUOTE}' => false,

			'{SEARCH}' => '\<\?[=a-z\s]+?\$product\[\'\w*?descr\w*?\'\].+?\?\>',

			'{REPLACE}' => '\\1
				<!-- OCFilter start -->
				<?php if ($product[\'ocfilter_products_options\']) { ?>
			  <?php if (is_array($product[\'ocfilter_products_options\'])) { ?>
			  <ul class="product-ocfilter-options">
			    <?php foreach ($product[\'ocfilter_products_options\'] as $ocfilter_option) { ?>
			    <li><span class="product-ocfilter-option"><?php echo $ocfilter_option[\'name\']; ?>:</span> <span class="product-ocfilter-value"><?php echo $ocfilter_option[\'values\']; ?></span></li>
			    <?php } ?>
			  </ul>
			  <?php } else { ?>
			  <?php echo $product[\'ocfilter_products_options\']; ?>
			  <?php } ?>
			  <?php } ?>
				<!-- OCFilter end -->
			'
		)
	)
);

/*

$code_steps[] = array(
	'file'    => ,
	'package' => 'base',
	'actions' => array(
		array(
			'{SEARCH}' => '',

			'{REPLACE}' => '

			\\1'
		)
	)
);

*/

$table_steps = array();

$table_steps['ocfilter_option'] = array(
	"`option_id` 		INT(11) 		NOT NULL AUTO_INCREMENT",
	"`type` 				VARCHAR(16) NOT NULL DEFAULT 'checkbox'",
  "`keyword` 			VARCHAR(64) NOT NULL DEFAULT ''",
  "`selectbox` 		TINYINT(1) 	NOT NULL DEFAULT '0'",
  "`grouping` 		TINYINT(2) 	NOT NULL DEFAULT '0'",
  "`color` 				TINYINT(1) 	NOT NULL DEFAULT '0'",
  "`image` 				TINYINT(1) 	NOT NULL DEFAULT '0'",
  "`status` 			TINYINT(1) 	NOT NULL DEFAULT '1'",
  "`sort_order` 	INT(11) 		NOT NULL DEFAULT '0'",
  "PRIMARY KEY (`option_id`)"
);

$table_steps['ocfilter_option_description'] = array(
	"`option_id` 		INT(11) 			NOT NULL",
  "`language_id` 	TINYINT(2) 		NOT NULL",
  "`name` 				VARCHAR(255) 	NOT NULL DEFAULT ''",
  "`postfix` 			VARCHAR(32) 	NOT NULL DEFAULT ''",
  "`description` 	VARCHAR(255) 	NOT NULL DEFAULT ''",
  "PRIMARY KEY (`option_id`, `language_id`)"
);

$table_steps['ocfilter_option_to_category'] = array(
	"`option_id` 		INT(11) NOT NULL",
  "`category_id` 	INT(11) NOT NULL",
  "PRIMARY KEY (`category_id`, `option_id`)"
);

$table_steps['ocfilter_option_to_store'] = array(
	"`option_id` 		INT(11) NOT NULL",
  "`store_id` 		INT(11) NOT NULL",
  "PRIMARY KEY (`store_id`, `option_id`)"
);

$table_steps['ocfilter_option_value'] = array(
	"`value_id` 		INT(11) 			NOT NULL AUTO_INCREMENT",
  "`option_id` 		INT(11) 			NOT NULL DEFAULT '0'",
  "`keyword` 			VARCHAR(64) 	NOT NULL DEFAULT ''",
	"`color` 				VARCHAR(6) 		NOT NULL DEFAULT ''",
	"`image` 				VARCHAR(255) 	NOT NULL DEFAULT ''",
  "`sort_order` 	INT(11) 			NOT NULL DEFAULT '0'",
  "PRIMARY KEY (`value_id`, `option_id`)"
);

$table_steps['ocfilter_option_value_description'] = array(
	"`value_id` 		INT(11) 			NOT NULL",
  "`option_id` 		INT(11) 			NOT NULL",
  "`language_id` 	TINYINT(2) 		NOT NULL",
  "`name` 				VARCHAR(255) 	NOT NULL DEFAULT ''",
  "PRIMARY KEY (`value_id`, `language_id`, `option_id`)"
);

$table_steps['ocfilter_option_value_to_product'] = array(
	"`product_id` 	INT(11) NOT NULL",
  "`value_id` 		INT(11) NOT NULL",
  "`option_id` 		INT(11) NOT NULL",
	"`slide_value_min` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'",
	"`slide_value_max` DECIMAL(15,4) NOT NULL DEFAULT '0.0000'",
  "PRIMARY KEY (`product_id`, `value_id`, `option_id`)",
	"INDEX `slide_value_min_slide_value_max` (`slide_value_min`, `slide_value_max`)"
);

$table_steps['ocfilter_option_value_to_product_description'] = array(
	"`product_id` 	INT(11) NOT NULL",
  "`value_id` 		INT(11) NOT NULL",
  "`option_id` 		INT(11) NOT NULL",
  "`language_id` 	TINYINT(2) NOT NULL",
  "`description` 	VARCHAR(255) NOT NULL DEFAULT ''",
  "PRIMARY KEY (`product_id`, `value_id`, `option_id`, `language_id`)"
);

$_['ocfilter_install_code_steps'] = $code_steps;
$_['ocfilter_install_table_steps'] = $table_steps;

$cfg = $_;

?>