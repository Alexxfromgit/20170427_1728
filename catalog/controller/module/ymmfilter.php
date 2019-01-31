<?php
class ControllerModuleYmmFilter extends Controller {
	protected function index($setting) {
		$this->data = array_merge($this->data, $this->language->load('module/ymmfilter'));
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/ymm.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/ymm.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/ymm.css');
		}
 
      	$this->data['setting'] = $setting;
		
      	$this->load->model('module/ymmfilter');
		
		if(isset($this->session->data['ymm'])){
			$this->data['make_name'] = $this->model_module_ymmfilter->getMakeName((int)$this->session->data['ymm']['make']);
			if(isset($this->session->data['ymm']['model']) && !empty($this->session->data['ymm']['model'])){
			$this->data['model_name'] = $this->model_module_ymmfilter->getModelName((int)$this->session->data['ymm']['model']);
			}
			if(isset($this->session->data['ymm']['engine']) && !empty($this->session->data['ymm']['engine'])){
			$this->data['engine_name'] = $this->model_module_ymmfilter->getEngineName((int)$this->session->data['ymm']['engine']);
			}
		} else {
			$this->data['makes'] = $this->model_module_ymmfilter->getMakes();
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/ymmfilter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/ymmfilter.tpl';
		} else {
			$this->template = 'default/template/module/ymmfilter.tpl';
		}

		$this->render();
	}
	
	public function getmodel(){
		$this->data = array_merge($this->data, $this->language->load('module/ymmfilter'));
		
		$this->load->model('module/ymmfilter');
		
		$this->data['models'] = $this->model_module_ymmfilter->getModels((int)$this->request->post['make_id']);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/ymmmodel.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/ymmmodel.tpl';
		} else {
			$this->template = 'default/template/module/ymmmodel.tpl';
		}
		
		$this->response->setOutput($this->render());
	}
	
	public function getengine(){
		$this->data = array_merge($this->data, $this->language->load('module/ymmfilter'));
		
		$this->load->model('module/ymmfilter');
		
		$this->data['engines'] = $this->model_module_ymmfilter->getEngines((int)$this->request->post['make_id'], (int)$this->request->post['model_id']);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/ymmengine.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/ymmengine.tpl';
		} else {
			$this->template = 'default/template/module/ymmengine.tpl';
		}
		
		$this->response->setOutput($this->render());
	}
	
	public function getyear(){
		$this->data = array_merge($this->data, $this->language->load('module/ymmfilter'));
		
		$this->load->model('module/ymmfilter');

		$engine_id = isset($this->request->post['engine_id']) ? $this->request->post['engine_id'] : null;
		
		$this->data['years'] = $this->model_module_ymmfilter->getYears((int)$this->request->post['make_id'], (int)$this->request->post['model_id'], $engine_id);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/ymmyear.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/ymmyear.tpl';
		} else {
			$this->template = 'default/template/module/ymmyear.tpl';
		}
		
		$this->response->setOutput($this->render());
	}
	
	public function setymm(){
		$this->data = array_merge($this->data, $this->language->load('module/ymmfilter'));
		
		$this->load->model('module/ymmfilter');
		
		$this->data['make'] = $this->model_module_ymmfilter->getMakeName((int)$this->request->post['make_id']);
		if(isset($this->request->post['model_id']) && !empty($this->request->post['model_id'])){
			$this->data['model'] = $this->model_module_ymmfilter->getModelName((int)$this->request->post['model_id']);
			$model_id = (int)$this->request->post['model_id'];
		} else {
			$model_id = '';
		}
		if(isset($this->request->post['engine_id']) && !empty($this->request->post['engine_id'])){
			$this->data['engine'] = $this->model_module_ymmfilter->getEngineName((int)$this->request->post['engine_id']);
			$engine_id = (int)$this->request->post['engine_id'];
		} else {
			$engine_id = '';
		}
		if(isset($this->request->post['year']) && !empty($this->request->post['year'])){
			$this->data['year'] = (int)$this->request->post['year'];
			$year = (int)$this->request->post['year'];
		} else {
			$year = '';
		}
		
		$this->session->data['ymm'] = array(
			'make' => (int)$this->request->post['make_id'],
			'model' => $model_id,
			'engine' => $engine_id,
			'year' => $year
		);
		
		if(isset($this->request->post['ymm-remember']) && $this->request->post['ymm-remember'] == 1){
         setcookie('ymm[make]', (int)$this->request->post['make_id'], time()+60*60*24*30, '/');
         setcookie('ymm[model]', $model_id, time()+60*60*24*30, '/');
         setcookie('ymm[engine]', $engine_id, time()+60*60*24*30, '/');
         setcookie('ymm[year]', $year, time()+60*60*24*30, '/');
         }
    
		$json['message'] = 'success';
		
		if (strcmp(VERSION,'1.5.1.3') >= 0) { //v1.5.1.3 or later
			$this->response->setOutput(json_encode($json));
		} else {
			$this->load->library('json');
			
			$this->response->setOutput(Json::encode($json));
		}
	}
	
	public function changeymm(){
		$this->data = array_merge($this->data, $this->language->load('module/ymmfilter'));
		
		$this->load->model('module/ymmfilter');
		
		unset($this->session->data['ymm']);
		
		setcookie('ymm[make]', '', time() - 3600, '/');
         setcookie('ymm[model]', '', time() - 3600, '/');
         setcookie('ymm[engine]', '', time() - 3600, '/');
         setcookie('ymm[year]', '', time() - 3600, '/');
		
		$json['message'] = 'success';

		if (strcmp(VERSION,'1.5.1.3') >= 0) { //v1.5.1.3 or later
			$this->response->setOutput(json_encode($json));
		} else {
			$this->load->library('json');
			
			$this->response->setOutput(Json::encode($json));
		}		
	}
}
?>