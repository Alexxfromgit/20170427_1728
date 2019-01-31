<?php
class ControllerModuleOCFilter extends Controller {
	private $error = array();

	public function index() {
    $this->data = array_merge($this->data, $this->load->language('module/ocfilter'));

		$this->document->setTitle($this->language->get('heading_title_module'));
    $this->document->addStyle('view/stylesheet/ocfilter/ocfilter.css');
    $this->document->addScript('view/javascript/ocfilter/ocfilter.js');

		$this->load->model('setting/setting');

    $this->data['heading_title'] = $this->language->get('heading_title_module');

		# OCFilter main settings
		#		 		key                    default

    $main_settings = array(
      'status' 							=> 0,
      'position' 						=> 'column_left',
      'sort_order' 					=> 0,
      'show_price' 					=> 0,
      'show_selected' 			=> 0,
      'show_diagram' 				=> 0,
      'show_button' 				=> 0,
      'show_counter' 				=> 0,
			'price_type' 					=> 'links-slide',
			'price_links_count'   => 4,
      'manual_price'   			=> 0,
      'consider_discount' 	=> 0,
      'consider_special' 		=> 0,
      'show_options_limit' 	=> '',
      'show_values_limit'		=> '',
			'hide_empty_values' 	=> 0,
      'manufacturer' 				=> 0,
      'manufacturer_type' 	=> 'checkbox',
      'stock_status' 				=> 0,
      'stock_status_type' 	=> 'radio',
			'stock_status_method' => 'quantity',
			'stock_out_value'     => 0,
      'pco_show_type' 			=> 'list',
      'pco_show_limit' 			=> 6,
      'use_animation'       => 0
    );

		# OCFilter modules settings
		#		 		key                    default

    $modules_settings = array(
      'status' 							=> 0,
      'position' 						=> 'content_top',
      'sort_order' 					=> 0,
      'show_price' 					=> 0,
      'show_diagram' 				=> 0,
      'show_options_limit' 	=> '',
      'show_values_limit'		=> '',
      'manufacturer' 				=> 0,
      'stock_status' 				=> 0,
			'options_id'          => array()
    );

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
			$data = array();

			$data['ocfilter_module'] = array();

      $data['ocfilter_module'][] = array_merge($main_settings, array_shift($this->request->post['ocfilter_module']));

	    foreach ($this->request->post['ocfilter_module'] as $module) {
        $data['ocfilter_module'][] = array_merge($modules_settings, $module);
			}

			$this->model_setting_setting->editSetting('ocfilter', $data);

			$this->session->data['success'] = $this->language->get('text_success');

      if (!isset($this->request->get['apply'])) {
			  $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
      } else {
        $this->redirect($this->url->link('module/ocfilter', 'token=' . $this->session->data['token'], 'SSL'));
      }
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

    if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['breadcrumbs'] = array();

 		$this->data['breadcrumbs'][] = array(
     	'text'      => $this->language->get('text_home'),
		  'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
    	'separator' => false
 		);

 		$this->data['breadcrumbs'][] = array(
     	'text'      => $this->language->get('text_module'),
		  'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
    	'separator' => ' :: '
 		);

 		$this->data['breadcrumbs'][] = array(
     	'text'      => $this->language->get('heading_title_module'),
		  'href'      => $this->url->link('module/ocfilter', 'token=' . $this->session->data['token'], 'SSL'),
    	'separator' => ' :: '
 		);

		$this->data['save'] = $this->url->link('module/ocfilter', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['apply'] = $this->url->link('module/ocfilter', 'token=' . $this->session->data['token'] . '&apply', 'SSL');
		$this->data['install'] = $this->url->link('module/ocfilter/install', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['reinstall'] = $this->url->link('module/ocfilter&update', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

    $this->data['token'] = $this->session->data['token'];

    $this->load->model('catalog/ocfilter');
    $this->data['categories'] = $this->model_catalog_ocfilter->getCategories(0);

    $this->data['installed'] = $this->model_catalog_ocfilter->existsTables() && !isset($this->request->get['update']);

    if (!$this->data['installed']) {
      $this->data['validate_install'] = $this->validateInstall();

			$this->data['files'] = array();

  		foreach ($this->model_catalog_ocfilter->getCodeSteps() as $step) {
				$basename = basename($step['file']);

				if (file_exists($step['file'])) {
  				if (!is_writable($step['file'])) {
            $text = sprintf($this->language->get('text_file_not_writable'), $basename);
  				} else {
            $text = sprintf($this->language->get('text_file_writable'), $basename);
  				}
  			} else {
  				$text = sprintf($this->language->get('text_file_not_exist'), $basename);
  			}

				$this->data['files'][] = array(
				 'package' => $step['package'],
				 'path' 	 => realpath(str_replace($basename, '', $step['file'])),
				 'text'    => $text
				);
			}
    }

    $this->load->model('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();

		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

    $this->data['positions'] = array(
      'content_top',
      'content_bottom',
      'column_left',
      'column_right'
    );

    $this->data['types'] = array(
      'checkbox',
      'radio',
      'select'
    );

    $this->data['price_types'] = array(
      'links' => $this->language->get('text_links'),
      'slide' => $this->language->get('text_slide'),
      'links-slide' => $this->language->get('text_links_slide')
    );

    if (isset($this->request->post['ocfilter_module'])) {
			$modules = $this->request->post['ocfilter_module'];
		} elseif ($this->config->has('ocfilter_module')) {
			$modules = $this->config->get('ocfilter_module');
		} else {
			$modules = array();
		}

		if ($modules) {
			$this->data = array_merge($this->data, array_merge($main_settings, array_shift($modules)));
		} else {
			$this->data = array_merge($this->data, $main_settings);
		}

		foreach ($modules as $key => $module) {
      $modules[$key]['options'] = array();

			if (isset($module['category_id']) && $this->data['installed']) {
				$modules[$key]['options'] = $this->model_catalog_ocfilter->getOptionsByCategoryId($module['category_id']);
			}

			foreach ($modules_settings as $_key => $default) {
				if (!isset($module[$_key])) {
          $module[$_key] = $default;
				}
			}
		}

		$this->data['modules'] = $modules;

		$this->template = 'module/ocfilter.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

  public function copyAttributes() {
    $json = array();

    $this->load->language('module/ocfilter');

    if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['option_store']) && isset($this->request->post['type'])) {
      $this->load->model('catalog/ocfilter');

      $this->model_catalog_ocfilter->copyAttributesToOCFilter($this->request->post);

      $json['message'] = $this->language->get('text_ready');
    } else {
      $json['message'] = $this->language->get('error_fields');
    }

    $this->response->setOutput(json_encode($json));
  }

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/ocfilter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

    $query = $this->db->query("SELECT layout_id FROM " . DB_PREFIX . "layout_route WHERE route = 'product/category' AND store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1");

    if ($query->num_rows) {
      $this->request->post['ocfilter_module'][0]['layout_id'] = $query->row['layout_id'];
    } else {
      $this->error['warning'] = sprintf($this->language->get('error_layout'), $this->url->link('design/layout', 'token=' . $this->session->data['token'], 'SSL'));
    }

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateInstall() {
		$this->config->load('ocfilter');

		foreach ($this->model_catalog_ocfilter->getCodeSteps() as $step) {
			if (!file_exists($step['file']) || (!is_writable($step['file']) && !chmod($step['file'], 0777))) {
    		return false;
			}
		}

		return true;
	}

	public function install() {
    if (!isset($this->request->post['package']) && !isset($this->request->get['update'])) {
      $this->redirect($this->url->link('module/ocfilter', 'token=' . $this->session->data['token'], 'SSL'));
    }

    $this->load->language('module/ocfilter');
    $this->load->model('catalog/ocfilter');

		if ($this->validateInstall()) {
      $this->model_catalog_ocfilter->installCode($this->request->post['package']);
      $this->model_catalog_ocfilter->createTables();

      $this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'catalog/ocfilter');
			$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'catalog/ocfilter');

      $this->session->data['success'] = sprintf($this->language->get('text_success_create'), $this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token'], 'SSL'));
		}

    $this->redirect($this->url->link('module/ocfilter', 'token=' . $this->session->data['token'], 'SSL'));
  }
}
?>