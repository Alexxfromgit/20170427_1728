<?php  
class ControllerModuleHtmlBlock extends Controller {
	
	static private $_depth = 0;
	static private $_depth_limit = 7;
	
	public function __construct($registry) {
		
		parent::__construct($registry);
		
		require_once 'html_block.token.php';
		app::registry()->create($registry);
	}
	
	public function index($setting) {
		
		if (isset($setting['depth'])) {
			self::$_depth = $setting['depth'];
		} else {
			self::$_depth = 0;
		}
		
		if (self::$_depth > self::$_depth_limit) return;
		
		$this->data['message'] = $message = '';
		
		if ($block = $this->config->get('html_block_' . $setting['html_block_id'])) {
			
			if (!isset($block['store']) || !in_array($this->config->get('config_store_id'), $block['store'])) {
				return;
			}
			
			if (isset($block['style']) && !empty($block['css'])) {
				
				$file_name = '/catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/html_block.css';
				
				if (!array_key_exists(md5($file_name), $this->document->getStyles())) {
					$this->document->addStyle($file_name);
				}
			}
			
			if (!isset($block['use_cache']) || !$message = $this->cache->get('html_block.content.' . (int)$this->config->get('config_language_id') . '.' . $setting['html_block_id'])) {
				
				$message = $this->_render($block);
				
				if (isset($block['use_cache'])) {
					$this->cache->set('html_block.content.' . (int)$this->config->get('config_language_id') . '.' . $setting['html_block_id'], $message);
				}
			}
			
			$this->data['message'] = $message;
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/html_block.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/html_block.tpl';
		} else {
			$this->template = 'default/template/module/html_block.tpl';
		}
		
		$this->render();
	}
	
	static public function getDepth() {
		return self::$_depth;
	}
	
	public function getOutput() {
		return $this->output;
	}
	
	public function preview() {
		
		if (!isset($this->request->get['key']) || $this->request->get['key'] != 'dmkapd8qweuqiweqjwkeh123123123') {
			exit('access denied');
		}
		
		if ($this->_isAdmin()) {
			$post = $this->session->data['post'] = $this->request->post;
		} elseif (isset($this->session->data['post']))  {
			$post = $this->session->data['post'];
		} else {
			$post = array();
		}
		
		$key = key($post);
		
		if (strpos($key, 'html_block_') === 0 && count($post[$key])) {
			
			$this->load->language('module/html_block');
			
			$this->data['title'] = $this->language->get('heading_title');
			
			$block = $post[$key];
			
			if (isset($block['style']) && !empty($block['css'])) {
				$this->data['css'] = $block['css'];
			}
			
			foreach (array('style', 'stylesheet', 'common', 'front', 'styles', 'css') as $name) {
				
				$file_name = 'view/theme/' . $this->config->get('config_template') . '/stylesheet/' . $name . '.css';
				
				if (file_exists(DIR_APPLICATION . $file_name) && is_file(DIR_APPLICATION . $file_name)) {
					$this->document->addStyle('/catalog/' . $file_name);
				}
			}
			
			$this->data['styles'] = $this->document->getStyles();
			
			$language_id = key($block['content']);
			
			$this->data['message'] = $this->_render($block, $language_id);
			
			$this->template = $this->config->get('config_template') . '/template/module/html_block_preview.tpl';
			
			$this->render();
			
			$html = $this->output;
			
		} else {
			$html = 'ERROR!';
		}
		
		$this->response->setOutput($html);
	}
	
	private function _isAdmin() {
		return (isset($this->request->server['HTTP_REFERER']) && strpos($this->request->server['HTTP_REFERER'], '/admin/index.php?route=module/html_block') !== FALSE);
	}
	
	private function _render($block, $language_id = FALSE) {
		
		$message = '';
		
		if (!is_numeric($language_id)) {
			$language_id = $this->config->get('config_language_id');
		}
		
		$content = $block['content'][$language_id];
		
		$templates = $this->config->get('html_block_theme');
		
		if (isset($block['theme']) && isset($templates[$block['theme_id']]) && !empty($templates[$block['theme_id']]['template'])) {
			
			$parser = TokenParser::getInstance($templates[$block['theme_id']]['template']);
			
			$tokenTitle = new Token();
			$tokenTitle->setReplace($block['title'][$language_id]);
			$parser->AddToken('[title]', $tokenTitle);
			
			$tokenContent = new Token();
			$tokenContent->setReplace($content);
			$parser->AddToken('[content]', $tokenContent);
			
			$content = $parser->replace();
		}
		
		$content = TokenParser::getInstance($content)->replace();
		
		$message = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
		
		if (isset($block['use_php']) && preg_match('|<\?php.+?\?>|isu', $message)) {
			
			ob_start();
			@eval('?>' . $message);
			$message = ob_get_contents();
			ob_end_clean();
			
		}
		
		return $message;
	}
}
?>