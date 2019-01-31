<?php
class ControllerModuleHtmlBlock extends Controller {
	private $error = array(); 
	
	public function index() {
		
		$this->document->addStyle('view/stylesheet/html_block.css');
		$this->load->language('module/html_block');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			if ($this->request->post['apply']) {
				$url = $this->url->link('module/html_block', 'token=' . $this->session->data['token'], 'SSL');
			} else {
				$url = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
			}
			
			unset($this->request->post['apply']);
			
			$this->model_setting_setting->editSetting('html_block', $this->request->post);
			
			$this->_clearCache();
			$this->saveStyle($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($url);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_required'] = $this->language->get('text_required');
		$this->data['text_preview'] = $this->language->get('text_preview');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_php_help'] = $this->language->get('text_php_help');
		$this->data['text_php_help_editor'] = $this->language->get('text_php_help_editor');
		$this->data['text_tokens'] = $this->language->get('text_tokens');
		$this->data['text_replace_title'] = $this->language->get('text_replace_title');
		$this->data['text_replace_content'] = $this->language->get('text_replace_content');
		$this->data['text_block'] = $this->language->get('text_block');
		$this->data['text_theme'] = $this->language->get('text_theme');
		$this->data['text_enabled_editor'] = $this->language->get('text_enabled_editor');
		$this->data['text_disable_editor'] = $this->language->get('text_disable_editor');
		$this->data['text_confirm_remove'] = $this->language->get('text_confirm_remove');
		$this->data['text_confirm_remove_theme'] = $this->language->get('text_confirm_remove_theme');
		$this->data['text_help_machine_name'] = $this->language->get('text_help_machine_name');
		$this->data['text_title_shop'] = $this->language->get('text_title_shop');
		$this->data['text_token_config_name'] = $this->language->get('text_token_config_name');
		$this->data['text_token_config_title'] = $this->language->get('text_token_config_title');
		$this->data['text_token_config_owner'] = $this->language->get('text_token_config_owner');
		$this->data['text_token_config_address'] = $this->language->get('text_token_config_address');
		$this->data['text_token_config_email'] = $this->language->get('text_token_config_email');
		$this->data['text_token_config_telephone'] = $this->language->get('text_token_config_telephone');
		$this->data['text_token_config_fax'] = $this->language->get('text_token_config_fax');
		$this->data['text_title_customer'] = $this->language->get('text_title_customer');
		$this->data['text_token_customer_firstname']	= $this->language->get('text_token_customer_firstname');
		$this->data['text_token_customer_lastname'] = $this->language->get('text_token_customer_lastname');
		$this->data['text_token_customer_email'] = $this->language->get('text_token_customer_email');
		$this->data['text_token_customer_telephone']	= $this->language->get('text_token_customer_telephone');
		$this->data['text_token_customer_fax'] = $this->language->get('text_token_customer_fax');
		$this->data['text_token_customer_reward'] = $this->language->get('text_token_customer_reward');
		$this->data['text_title_over'] = $this->language->get('text_title_over');
		$this->data['text_token_currency_code'] = $this->language->get('text_token_currency_code');
		$this->data['text_token_currency_title'] = $this->language->get('text_token_currency_title');
		$this->data['text_token_language_code'] = $this->language->get('text_token_language_code');
		$this->data['text_token_language_name'] = $this->language->get('text_token_language_name');
		$this->data['text_token_block'] = $this->language->get('text_token_block');
		$this->data['text_help_tokens_customer'] = $this->language->get('text_help_tokens_customer');
		
		$this->data['entry_html_block'] = $this->language->get('entry_html_block');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_php'] = $this->language->get('entry_php');
		$this->data['entry_cache'] = $this->language->get('entry_cache');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_theme'] = $this->language->get('entry_theme');
		$this->data['entry_select_theme'] = $this->language->get('entry_select_theme');
		$this->data['entry_use_theme'] = $this->language->get('entry_use_theme');
		$this->data['entry_style'] = $this->language->get('entry_style');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_theme_title'] = $this->language->get('entry_theme_title');
		$this->data['entry_block_title'] = $this->language->get('entry_block_title');
		$this->data['entry_content'] = $this->language->get('entry_content');
		
		$this->data['column_token'] = $this->language->get('column_token');
		$this->data['column_value'] = $this->language->get('column_value');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_apply'] = $this->language->get('button_apply');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['button_add_block'] = $this->language->get('button_add_block');
		$this->data['button_add_theme'] = $this->language->get('button_add_theme');
		$this->data['button_clear_cache'] = $this->language->get('button_clear_cache');
		$this->data['button_live_view'] = $this->language->get('button_live_view');
		
		$this->data['tab_position'] = $this->language->get('tab_position');
		$this->data['tab_blocks'] = $this->language->get('tab_blocks');
		$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_config'] = $this->language->get('tab_config');
		$this->data['tab_themes'] = $this->language->get('tab_themes');
		
		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
			
			unset($this->error['warning']);
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->data['error'] = $this->error;
		
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
       		'text'      => strip_tags($this->language->get('heading_title')),
			'href'      => $this->url->link('module/html_block', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/html_block', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['clear_cache'] = $this->url->link('module/html_block/clear_cache', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['html_block_module'])) {
			$this->data['modules'] = $this->request->post['html_block_module'];
		} elseif ($this->config->get('html_block_module')) { 
			$this->data['modules'] = $this->config->get('html_block_module');
		}
		
		if (isset($this->request->post['html_block_theme'])) {
			$this->data['html_block_theme'] = $this->request->post['html_block_theme'];
		} elseif ($this->config->get('html_block_theme')) { 
			$this->data['html_block_theme'] = $this->config->get('html_block_theme');
		} else {
			$this->data['html_block_theme'] = array();
		}
		
		$old_blocks = array();
		
		foreach ($this->data['html_block_theme'] as $theme_id => $theme_info) {
			
			$theme_info['short_title'] = $this->_getShortTitle($theme_info['machine_name']);
			$theme_info['default'] = $this->data['text_theme'] . ' ' . $theme_id;
			
			$this->data['html_block_theme'][$theme_id] = $theme_info;
		}
		
		if (count($this->request->post)) {
			$html_blocks = $this->request->post;
		} else {
			$this->load->model('module/html_block');
			$html_blocks = $this->model_module_html_block->getSetting('html_block', (int)$this->config->get('config_store_id'));
		}
		
		unset($html_blocks['html_block_module']);
		unset($html_blocks['html_block_theme']);
		
		$this->data['html_block_content'] = array();
		
		foreach ($html_blocks as $key => $value) {
			if (strpos($key, 'html_block_') === 0 && is_array($value)) {
				$block_id = substr($key, 11);
				
				$value['short_title'] = $this->_getShortTitle($value['machine_name']);
				$value['default'] = $this->data['text_block'] . ' ' . $block_id;
				
				if (isset($value['template']) && $value['theme'] == 'on') {
					
					if (count($old_blocks)) {
						$theme_id = max(array_keys($old_blocks)) + 1;
					} else {
						$theme_id = 1;
					}
					
					$old_blocks[$theme_id] = array(
						'machine_name'	=> '',
						'short_title'	=> '',
						'default'		=> $this->data['text_theme'] . ' ' . $theme_id,
						'template'		=> $value['template']
					);
					
					$value['theme_id'] = $theme_id;
					
				}
				
				$this->data['html_block_content'][$block_id] = $value;
			}
		}
		
		
		
		if (!empty($old_blocks)) {
			$this->data['html_block_theme'] = $old_blocks;
		}
		
		ksort($this->data['html_block_content']);
				
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->load->model('setting/store');
		
		$this->data['stores'] = array();
		$this->data['stores'][] = array(
			'store_id' => 0,
			'name'	   => $this->language->get('text_store_default')
		);
		
		$this->data['stores'] = array_merge($this->data['stores'], $this->model_setting_store->getStores());

		$this->template = 'module/html_block.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function _getShortTitle($full_name) {
		
		if (function_exists('mb_strlen')) {
			$strlen = mb_strlen($full_name, 'UTF-8');
		} else {
			$strlen = preg_match_all("/.{1}/us", $full_name, $match);
		}
		
		if ($strlen > 30) {
			$short_title = utf8_substr($full_name, 0, 30) . '...';
		} else {
			$short_title = $full_name;
		}
		
		return $short_title;
	}
	
	public function clear_cache() {
		
		if (isset($this->request->server['HTTP_REFERER']) && strpos($this->request->server['HTTP_REFERER'], 'module/html_block')) {
			
			$this->_clearCache();
			
			$this->load->language('module/html_block');
			$this->session->data['success'] = $this->language->get('text_success_clear_cache');
		}
		
		$this->redirect($this->url->link('module/html_block', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	private function _clearCache() {
		
		$files = glob(DIR_CACHE . 'cache.html_block.content.*');
			
		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					
					unlink($file);
					clearstatcache();
				}
			}
		}
	}
	
	private function saveStyle($post) {
		
		unset($post['html_block_module']);
		
		if (count($post)) {
			
			$file_name = DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/html_block.css';
			
			$css = '';
			
			foreach ($post as $key => $value) {
				if (strpos($key, 'html_block_') === 0 && is_array($value)) {
					
					$block_id = substr($key, 11);
					
					if (isset($value['style']) && !empty($value['css'])) {
						$css .= "/* CSS BLOCK #" . $block_id . " */\r\n";
						$css .= trim($value['css']);
						$css .= "\r\n\r\n";
					}
				}
			}
			
			$handle = fopen($file_name, 'w');
			fwrite($handle,  trim($css));
			fclose($handle);
			
		}
	}
	
	private function validate() {
		
		if (!$this->user->hasPermission('modify', 'module/html_block')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} else {
			
			if (isset($this->request->post['html_block_module'])) {
				foreach ($this->request->post['html_block_module'] as $key => $value) {
					if (!$value['html_block_id']) {
						$this->error['content'][$key] = $this->language->get('error_content');
					}			
				}
			}
			
			$file_name = DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/stylesheet/html_block.css';
			
			if (!file_exists($file_name) || !is_file($file_name)) {
				$this->error['warning'] = sprintf($this->language->get('error_file_not_found'), $file_name);
			} elseif (!is_writable($file_name)) {
				$this->error['warning'] = sprintf($this->language->get('error_writable'), $file_name);
			}
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>