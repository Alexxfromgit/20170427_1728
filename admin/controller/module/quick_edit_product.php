<?php
class ControllerModuleQuickEditProduct extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/quick_edit_product');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('quick_edit_product', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_product'] = $this->language->get('text_product');
		$this->data['text_category'] = $this->language->get('text_category');
		$this->data['text_category_info'] = $this->language->get('text_category_info');
		$this->data['text_product_info'] = $this->language->get('text_product_info');
		
		$this->data['entry_quick_edit_category'] = $this->language->get('entry_quick_edit_category');
		$this->data['entry_category_parent'] = $this->language->get('entry_category_parent');
		$this->data['entry_category_filter'] = $this->language->get('entry_category_filter');
		$this->data['entry_category_image'] = $this->language->get('entry_category_image');
		$this->data['entry_category_stores'] = $this->language->get('entry_category_stores');
		$this->data['entry_category_design'] = $this->language->get('entry_category_design');	
		$this->data['entry_quick_edit_product'] = $this->language->get('entry_quick_edit_product');
		$this->data['entry_all_buttons'] = $this->language->get('entry_all_buttons');
		$this->data['entry_general_data'] = $this->language->get('entry_general_data');
		$this->data['entry_product_categories'] = $this->language->get('entry_product_categories');
		$this->data['entry_product_filter'] = $this->language->get('entry_product_filter');
		$this->data['entry_product_related'] = $this->language->get('entry_product_related');
		$this->data['entry_product_code'] = $this->language->get('entry_product_code');
		$this->data['entry_product_tax_class'] = $this->language->get('entry_product_tax_class');
		$this->data['entry_product_minimum'] = $this->language->get('entry_product_minimum');
		$this->data['entry_product_subtract'] = $this->language->get('entry_product_subtract');
		$this->data['entry_product_dimension'] = $this->language->get('entry_product_dimension');
		$this->data['entry_product_weight'] = $this->language->get('entry_product_weight');
		$this->data['entry_product_date_sort'] = $this->language->get('entry_product_date_sort');
		$this->data['entry_product_attribute'] = $this->language->get('entry_product_attribute');
		$this->data['entry_product_options'] = $this->language->get('entry_product_options');
		$this->data['entry_product_special'] = $this->language->get('entry_product_special');
		$this->data['entry_product_discount'] = $this->language->get('entry_product_discount');
		$this->data['entry_product_images'] = $this->language->get('entry_product_images');
		$this->data['entry_product_stores'] = $this->language->get('entry_product_stores');
		$this->data['entry_product_downloads'] = $this->language->get('entry_product_downloads');
		$this->data['entry_product_reward_points'] = $this->language->get('entry_product_reward_points');
		$this->data['entry_product_design'] = $this->language->get('entry_product_design');
		
		$this->data['info_quick_edit_category'] = $this->language->get('info_quick_edit_category');
		$this->data['info_category_all_buttons'] = $this->language->get('info_category_all_buttons');
		$this->data['info_category_general_data'] = $this->language->get('info_category_general_data');
		$this->data['info_category_parent'] = $this->language->get('info_category_parent');
		$this->data['info_category_filter'] = $this->language->get('info_category_filter');
		$this->data['info_category_image'] = $this->language->get('info_category_image');
		$this->data['info_category_stores'] = $this->language->get('info_category_stores');
		$this->data['info_category_design'] = $this->language->get('info_category_design');	
		$this->data['info_quick_edit_product'] = $this->language->get('info_quick_edit_product');
		$this->data['info_product_general_data'] = $this->language->get('info_product_general_data');
		$this->data['info_product_categories'] = $this->language->get('info_product_categories');
		$this->data['info_product_filter'] = $this->language->get('info_product_filter');
		$this->data['info_product_related'] = $this->language->get('info_product_related');
		$this->data['info_product_code'] = $this->language->get('info_product_code');
		$this->data['info_product_tax_class'] = $this->language->get('info_product_tax_class');
		$this->data['info_product_minimum'] = $this->language->get('info_product_minimum');
		$this->data['info_product_subtract'] = $this->language->get('info_product_subtract');
		$this->data['info_product_dimension'] = $this->language->get('info_product_dimension');
		$this->data['info_product_weight'] = $this->language->get('info_product_weight');
		$this->data['info_product_date_sort'] = $this->language->get('info_product_date_sort');
		$this->data['info_product_attribute'] = $this->language->get('info_product_attribute');
		$this->data['info_product_options'] = $this->language->get('info_product_options');
		$this->data['info_product_special'] = $this->language->get('info_product_special');
		$this->data['info_product_discount'] = $this->language->get('info_product_discount');
		$this->data['info_product_images'] = $this->language->get('info_product_images');
		$this->data['info_product_stores'] = $this->language->get('info_product_stores');
		$this->data['info_product_downloads'] = $this->language->get('info_product_downloads');
		$this->data['info_product_reward_points'] = $this->language->get('info_product_reward_points');
		$this->data['info_product_design'] = $this->language->get('info_product_design');
		$this->data['info_product_all_buttons'] = $this->language->get('info_product_all_buttons');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_product'] = $this->language->get('button_product');
		$this->data['button_categories'] = $this->language->get('button_categories');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
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
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/quick_edit_product', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/quick_edit_product', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['product'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['categories'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['modules'] = array();
		
		if (isset($this->request->post['quick_edit_product_module'])) {
			$this->data['modules'] = $this->request->post['quick_edit_product_module'];
		} elseif ($this->config->get('quick_edit_product_module')) { 
			$this->data['modules'] = $this->config->get('quick_edit_product_module');
		}			
		
		// Category
		if (isset($this->request->post['config_quick_edit_category'])) {
			$this->data['config_quick_edit_category'] = $this->request->post['config_quick_edit_category'];
		} else {
			$this->data['config_quick_edit_category'] = $this->config->get('config_quick_edit_category');
		}
		
		if (isset($this->request->post['config_category_quick_all_buttons'])) {
			$this->data['config_category_quick_all_buttons'] = $this->request->post['config_category_quick_all_buttons'];
		} else {
			$this->data['config_category_quick_all_buttons'] = $this->config->get('config_category_quick_all_buttons');
		}
		
		if (isset($this->request->post['config_category_general_data'])) {
			$this->data['config_category_general_data'] = $this->request->post['config_category_general_data'];
		} else {
			$this->data['config_category_general_data'] = $this->config->get('config_category_general_data');
		}
		
		if (isset($this->request->post['config_category_parent'])) {
			$this->data['config_category_parent'] = $this->request->post['config_category_parent'];
		} else {
			$this->data['config_category_parent'] = $this->config->get('config_category_parent');
		}
		
		if (isset($this->request->post['config_category_filter'])) {
			$this->data['config_category_filter'] = $this->request->post['config_category_filter'];
		} else {
			$this->data['config_category_filter'] = $this->config->get('config_category_filter');
		}
		
		if (isset($this->request->post['config_category_image'])) {
			$this->data['config_category_image'] = $this->request->post['config_category_image'];
		} else {
			$this->data['config_category_image'] = $this->config->get('config_category_image');
		}
		
		if (isset($this->request->post['config_category_stores'])) {
			$this->data['config_category_stores'] = $this->request->post['config_category_stores'];
		} else {
			$this->data['config_category_stores'] = $this->config->get('config_category_stores');
		}
		if (isset($this->request->post['config_category_design'])) {
			$this->data['config_category_design'] = $this->request->post['config_category_design'];
		} else {
			$this->data['config_category_design'] = $this->config->get('config_category_design');
		}
		
		// Product
		if (isset($this->request->post['config_quick_all_buttons'])) {
			$this->data['config_quick_all_buttons'] = $this->request->post['config_quick_all_buttons'];
		} else {
			$this->data['config_quick_all_buttons'] = $this->config->get('config_quick_all_buttons');
		}
		
		if (isset($this->request->post['config_general_data'])) {
			$this->data['config_general_data'] = $this->request->post['config_general_data'];
		} else {
			$this->data['config_general_data'] = $this->config->get('config_general_data');
		}
		
		if (isset($this->request->post['config_manufacturer_categories'])) {
			$this->data['config_manufacturer_categories'] = $this->request->post['config_manufacturer_categories'];
		} else {
			$this->data['config_manufacturer_categories'] = $this->config->get('config_manufacturer_categories');
		}
		
		if (isset($this->request->post['config_quick_filter'])) {
			$this->data['config_quick_filter'] = $this->request->post['config_quick_filter'];
		} else {
			$this->data['config_quick_filter'] = $this->config->get('config_quick_filter');
		}
		
		if (isset($this->request->post['config_quick_related'])) {
			$this->data['config_quick_related'] = $this->request->post['config_quick_related'];
		} else {
			$this->data['config_quick_related'] = $this->config->get('config_quick_related');
		}
		
		if (isset($this->request->post['config_quick_code'])) {
			$this->data['config_quick_code'] = $this->request->post['config_quick_code'];
		} else {
			$this->data['config_quick_code'] = $this->config->get('config_quick_code');
		}
		
		if (isset($this->request->post['config_quick_tax_class'])) {
			$this->data['config_quick_tax_class'] = $this->request->post['config_quick_tax_class'];
		} else {
			$this->data['config_quick_tax_class'] = $this->config->get('config_quick_tax_class');
		}
		
		if (isset($this->request->post['config_quick_minimum'])) {
			$this->data['config_quick_minimum'] = $this->request->post['config_quick_minimum'];
		} else {
			$this->data['config_quick_minimum'] = $this->config->get('config_quick_minimum');
		}
		
		if (isset($this->request->post['config_quick_subtract'])) {
			$this->data['config_quick_subtract'] = $this->request->post['config_quick_subtract'];
		} else {
			$this->data['config_quick_subtract'] = $this->config->get('config_quick_subtract');
		}
		
		if (isset($this->request->post['config_quick_dimension'])) {
			$this->data['config_quick_dimension'] = $this->request->post['config_quick_dimension'];
		} else {
			$this->data['config_quick_dimension'] = $this->config->get('config_quick_dimension');
		}
		
		if (isset($this->request->post['config_quick_weight'])) {
			$this->data['config_quick_weight'] = $this->request->post['config_quick_weight'];
		} else {
			$this->data['config_quick_weight'] = $this->config->get('config_quick_weight');
		}
		
		if (isset($this->request->post['config_quick_date_sort'])) {
			$this->data['config_quick_date_sort'] = $this->request->post['config_quick_date_sort'];
		} else {
			$this->data['config_quick_date_sort'] = $this->config->get('config_quick_date_sort');
		}
		
		if (isset($this->request->post['config_quick_attribute'])) {
			$this->data['config_quick_attribute'] = $this->request->post['config_quick_attribute'];
		} else {
			$this->data['config_quick_attribute'] = $this->config->get('config_quick_attribute');
		}
		
		if (isset($this->request->post['config_quick_options'])) {
			$this->data['config_quick_options'] = $this->request->post['config_quick_options'];
		} else {
			$this->data['config_quick_options'] = $this->config->get('config_quick_options');
		}
		
		if (isset($this->request->post['config_quick_special'])) {
			$this->data['config_quick_special'] = $this->request->post['config_quick_special'];
		} else {
			$this->data['config_quick_special'] = $this->config->get('config_quick_special');
		}
		
		if (isset($this->request->post['config_quick_discount'])) {
			$this->data['config_quick_discount'] = $this->request->post['config_quick_discount'];
		} else {
			$this->data['config_quick_discount'] = $this->config->get('config_quick_discount');
		}
		
		if (isset($this->request->post['config_quick_images'])) {
			$this->data['config_quick_images'] = $this->request->post['config_quick_images'];
		} else {
			$this->data['config_quick_images'] = $this->config->get('config_quick_images');
		}
		
		if (isset($this->request->post['config_quick_stores'])) {
			$this->data['config_quick_stores'] = $this->request->post['config_quick_stores'];
		} else {
			$this->data['config_quick_stores'] = $this->config->get('config_quick_stores');
		}
		
		if (isset($this->request->post['config_quick_downloads'])) {
			$this->data['config_quick_downloads'] = $this->request->post['config_quick_downloads'];
		} else {
			$this->data['config_quick_downloads'] = $this->config->get('config_quick_downloads');
		}
		
		if (isset($this->request->post['config_quick_reward_points'])) {
			$this->data['config_quick_reward_points'] = $this->request->post['config_quick_reward_points'];
		} else {
			$this->data['config_quick_reward_points'] = $this->config->get('config_quick_reward_points');
		}
		
		if (isset($this->request->post['config_quick_design'])) {
			$this->data['config_quick_design'] = $this->request->post['config_quick_design'];
		} else {
			$this->data['config_quick_design'] = $this->config->get('config_quick_design');
		}
		
		if (isset($this->request->post['config_quick_edit_product'])) {
			$this->data['config_quick_edit_product'] = $this->request->post['config_quick_edit_product'];
		} else {
			$this->data['config_quick_edit_product'] = $this->config->get('config_quick_edit_product');
		}

		$this->template = 'module/quick_edit_product.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/quick_edit_product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>