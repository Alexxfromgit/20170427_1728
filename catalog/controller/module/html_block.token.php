<?php  
class app {
	
	static private $_instance;
	private $registry;
	
	private function __construct() {}
	
	static public function registry() {
		
		if (empty(self::$_instance)) {
			self::$_instance = new app();
		}
		
		return self::$_instance;
	}
	
	public function create(Registry $registry) {
		if($this->registry === null || $registry === null) {
			$this->registry = $registry;
		}
	}
	
	public function get() {
		return $this->registry;
	}
	
	public function __get($key) {
		return $this->registry->get($key);
	}
	
	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
}

class TokenParser {
	
	private $_content;
	private $_tokens = array();
	
	private function __construct($content) {
		
		$this->_content = $content;
		$this->loadTokens();
	}
	
	static public function getInstance($content) {
		return new TokenParser($content);
	}
	
	private function getTokenData($token_string) {
		
		$token = trim($token_string, '[]');
		$parts = explode('::', $token);
		
		return array(
			'controller' => strtolower($parts[0]),
			'argument'	 => isset($parts[1]) ? $parts[1] : FALSE
		);
	}
	
	private function loadTokens() {
		
		preg_match_all('|\[[a-zA-Z_\d::]+?\]|isu', $this->_content, $match);
		
		if (isset($match[0])) {
			
			foreach ($match[0] as $token_string) {
				
				if ($this->tokenExists($token_string)) {
					continue;
				}
				
				$token_data = $this->getTokenData($token_string);
				
				$strategy = 'Token' . $token_data['controller'];
				
				if (class_exists($strategy)) {
					
					$this->AddToken($token_string, new $strategy($token_data['argument']));
				}
			}
		}
		
	}
	
	private function tokenExists($token) {
		return array_key_exists($token, $this->_tokens);
	}
	
	public function AddToken($key, TokenStrategy $token) {
		
		if ($this->tokenExists($key)) {
			return;
		}
		
		$this->_tokens[$key] = $token;
	}
	
	public function removeToken($key) {
		
		if ($this->tokenExists($key)) {
			return;
		}
		
		unset($this->_tokens[$key]);
	}
	
	public function replace() {
		
		$replace = array();
		
		foreach ($this->_tokens as $key => $token) {
			
			$replace_value = $token->getReplace();
			
			if (isset($replace_value)) {
				$replace[$key] = $replace_value;
			}
		}
		
		return strtr($this->_content, $replace);
	}
}

interface TokenStrategy {
	
	public function getReplace();
	public function setReplace($replace);
}

class Token implements TokenStrategy {
	
	protected $_key;
	protected $_replace = NULL;
	
	public function __construct($key = '') {
		$this->_key = $key;
	}
	
	public function getReplace() {
		return $this->_replace;
	}
	
	public function setReplace($replace) {
		$this->_replace = $replace;
	}
}

class TokenConfig extends Token {
	
	public function getReplace() {
		$this->_replace = app::registry()->config->get('config_' . $this->_key);
		return $this->_replace ? $this->_replace : '';
	}
}

class TokenCustomer extends Token {
	
	public function getReplace() {
		
		$method = 'get' . $this->_key;
		
		if (method_exists(app::registry()->customer, $method)) {
			$this->_replace = app::registry()->customer->$method();
		}
		
		return $this->_replace;
	}
}

class TokenCurrency extends Token {
	
	static private $_code;
	
	public function __construct($key = '') {
		
		parent::__construct($key);
		
		self::$_code = app::registry()->currency->getCode();
	} 
	
	public function getReplace() {
		
		switch ($this->_key) {
			case 'code':
				$this->_replace = self::$_code;
				break;
			case 'title':
				app::registry()->load->model('localisation/currency');
				$results = app::registry()->load->model_localisation_currency->getCurrencies();
				foreach ($results as $result) {
					if ($result['status'] && $result['code'] == self::$_code) {
						$this->_replace = $result['title'];
						break;
					}
				}
				break;
		}
		
		return $this->_replace;
	}
}

class TokenLanguage extends Token {
	
	static private $_language;
	
	private function getLanguageData($key) {
		
		if (!self::$_language) {
			
			app::registry()->load->model('localisation/language');
			$results = app::registry()->load->model_localisation_language->getLanguages();
			foreach ($results as $result) {
				if ($result['status'] && $result['code'] == app::registry()->session->data['language']) {
					self::$_language = $result;
				}
			}
		}
		
		return isset(self::$_language[$key]) ? self::$_language[$key] : '';
	}
	
	public function getReplace() {
		
		switch ($this->_key) {
			case 'code':
				$this->_replace = $this->getLanguageData('code');
				break;
			case 'name':
				$this->_replace = $this->getLanguageData('name');
				break;
		}
		
		return $this->_replace;
	}
}	

class TokenBlock extends Token {
	
	public function getReplace() {
		
		if (is_numeric($this->_key)) {
			
			$depth = ControllerModuleHtmlBlock::getDepth();
			
			$args = array(
				'html_block_id' => (int)$this->_key,
				'depth'			=> $depth + 1
			);
			
			$action = new Action('module/html_block', $args);
			$file = $action->getFile();
			$class = $action->getClass();
			
			if (file_exists($file)) {
				
				require_once($file);
	
				$controller = new $class(app::registry()->get());
				$controller->index($args);
				$this->_replace = $controller->getOutput();
			}
		}
		
		return $this->_replace;
	}
}

?>