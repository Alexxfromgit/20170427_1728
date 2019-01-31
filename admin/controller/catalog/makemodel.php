<?php 
class ControllerCatalogMakemodel extends Controller { 
	private $error = array();
   
  	public function index() {
		$this->data = array_merge($this->data, $this->load->language('catalog/makemodel'));
	
    	$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('view/javascript/jquery/jquery.dataTables.min.js');
		$this->document->addScript('view/javascript/jquery/jquery.jeditable.mini.js');
		$this->document->addStyle('view/stylesheet/demo_table.css');
		
		$this->data['make_callback_url'] = $this->url->link('catalog/makemodel/getmakes', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['make_delete_url'] = $this->url->link('catalog/makemodel/deletemake', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['model_callback_url'] = $this->url->link('catalog/makemodel/getmodels', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['model_delete_url'] = $this->url->link('catalog/makemodel/deletemodel', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['engine_callback_url'] = $this->url->link('catalog/makemodel/getengines', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['engine_delete_url'] = $this->url->link('catalog/makemodel/deleteengine', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['make_insert_url'] = $this->url->link('catalog/makemodel/insertmake', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['model_insert_url'] = $this->url->link('catalog/makemodel/insertmodel', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['engine_insert_url'] = $this->url->link('catalog/makemodel/insertengine', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['edit_url'] = $this->url->link('catalog/makemodel/edit', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/makemodel', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
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
		
    	$this->template = 'catalog/makemodel.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
  	}
              
  	public function getmakes(){
		
		$this->load->model('catalog/makemodel');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$json = $this->model_catalog_makemodel->getMakes($this->request->post);
		}
		
		if (strcmp(VERSION,'1.5.1.3') >= 0) { //v1.5.1.3 or later
			$this->response->setOutput(json_encode($json));
		} else {
			$this->load->library('json');
			
			$this->response->setOutput(Json::encode($json));
		}
		
	}
	
	public function getmodels(){
		
		$this->load->model('catalog/makemodel');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$json = $this->model_catalog_makemodel->getModels($this->request->post);
		}
		
		if (strcmp(VERSION,'1.5.1.3') >= 0) { //v1.5.1.3 or later
			$this->response->setOutput(json_encode($json));
		} else {
			$this->load->library('json');
			
			$this->response->setOutput(Json::encode($json));
		}
	
	}
	
	public function getengines(){
		
		$this->load->model('catalog/makemodel');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$json = $this->model_catalog_makemodel->getEngines($this->request->post);
		}
		
		if (strcmp(VERSION,'1.5.1.3') >= 0) { //v1.5.1.3 or later
			$this->response->setOutput(json_encode($json));
		} else {
			$this->load->library('json');
			
			$this->response->setOutput(Json::encode($json));
		}
	
	}
	
	public function completemake(){
		$this->load->model('catalog/makemodel');
		
		if (isset($this->request->get['term']) && !empty($this->request->get['term'])) {
			$json = $this->model_catalog_makemodel->completeMake($this->request->get['term']);
		}
		
		if (strcmp(VERSION,'1.5.1.3') >= 0) { //v1.5.1.3 or later
			$this->response->setOutput(json_encode($json));
		} else {
			$this->load->library('json');
			
			$this->response->setOutput(Json::encode($json));
		}
	}
	
	public function completemodel(){
		$this->load->model('catalog/makemodel');
		
		if (isset($this->request->get['term']) && !empty($this->request->get['term'])) {
			$json = $this->model_catalog_makemodel->completeModel($this->request->get['term']);
		}
		
		if (strcmp(VERSION,'1.5.1.3') >= 0) { //v1.5.1.3 or later
			$this->response->setOutput(json_encode($json));
		} else {
			$this->load->library('json');
			
			$this->response->setOutput(Json::encode($json));
		}
	}
	
	public function completeengine(){
		$this->load->model('catalog/makemodel');
		
		if (isset($this->request->get['term']) && !empty($this->request->get['term'])) {
			$json = $this->model_catalog_makemodel->completeEngine($this->request->get['term']);
		}
		
		if (strcmp(VERSION,'1.5.1.3') >= 0) { //v1.5.1.3 or later
			$this->response->setOutput(json_encode($json));
		} else {
			$this->load->library('json');
			
			$this->response->setOutput(Json::encode($json));
		}
	}
	
	public function deletemake(){
		
		$this->load->model('catalog/makemodel');
	
		if (isset($this->request->get['id']) && !empty($this->request->get['id']) && $this->validate()) {
			$this->model_catalog_makemodel->deleteMake((int)$this->request->get['id']);
			$json['message'] = 'success';
		} else {
			$json['message'] = $this->error['warning'];
		}
		
		if (strcmp(VERSION,'1.5.1.3') >= 0) { //v1.5.1.3 or later
			$this->response->setOutput(json_encode($json));
		} else {
			$this->load->library('json');
			
			$this->response->setOutput(Json::encode($json));
		}
		
	}
	
	public function deletemodel(){
		
		$this->load->model('catalog/makemodel');
	
		if (isset($this->request->get['id']) && !empty($this->request->get['id']) && $this->validate()) {
			$this->model_catalog_makemodel->deleteModel((int)$this->request->get['id']);
			$json['message'] = 'success';
		} else {
			$json['message'] = $this->error['warning'];
		}
		
		if (strcmp(VERSION,'1.5.1.3') >= 0) { //v1.5.1.3 or later
			$this->response->setOutput(json_encode($json));
		} else {
			$this->load->library('json');
			
			$this->response->setOutput(Json::encode($json));
		}
		
	}
	
	public function deleteengine(){
		
		$this->load->model('catalog/makemodel');
	
		if (isset($this->request->get['id']) && !empty($this->request->get['id']) && $this->validate()) {
			$this->model_catalog_makemodel->deleteEngine((int)$this->request->get['id']);
			$json['message'] = 'success';
		} else {
			$json['message'] = $this->error['warning'];
		}
		
		if (strcmp(VERSION,'1.5.1.3') >= 0) { //v1.5.1.3 or later
			$this->response->setOutput(json_encode($json));
		} else {
			$this->load->library('json');
			
			$this->response->setOutput(Json::encode($json));
		}
		
	}
	
	public function insertmake(){
		
		$this->load->model('catalog/makemodel');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$json['message'] = $this->model_catalog_makemodel->insertMake($this->db->escape($this->request->post['new-make']));
		}
		
		if (strcmp(VERSION,'1.5.1.3') >= 0) { //v1.5.1.3 or later
			$this->response->setOutput(json_encode($json));
		} else {
			$this->load->library('json');
			
			$this->response->setOutput(Json::encode($json));
		}
		
	}
	
	public function insertmodel(){
		
		$this->load->model('catalog/makemodel');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$json['message'] = $this->model_catalog_makemodel->insertModel($this->db->escape($this->request->post['new-model']));
		}
		
		if (strcmp(VERSION,'1.5.1.3') >= 0) { //v1.5.1.3 or later
			$this->response->setOutput(json_encode($json));
		} else {
			$this->load->library('json');
			
			$this->response->setOutput(Json::encode($json));
		}
		
	}
	
	public function insertengine(){
		
		$this->load->model('catalog/makemodel');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$json['message'] = $this->model_catalog_makemodel->insertEngine($this->db->escape($this->request->post['new-engine']));
		}
		
		if (strcmp(VERSION,'1.5.1.3') >= 0) { //v1.5.1.3 or later
			$this->response->setOutput(json_encode($json));
		} else {
			$this->load->library('json');
			
			$this->response->setOutput(Json::encode($json));
		}
		
	}
	
	public function edit(){
		$this->load->model('catalog/makemodel');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if(isset($this->request->post['id']) && strpos($this->request->post['id'], 'make_') !== FALSE){
				$this->model_catalog_makemodel->editMake((int)substr($this->request->post['id'], 5), $this->db->escape($this->request->post['content']));
			} elseif(isset($this->request->post['id']) && strpos($this->request->post['id'], 'model_') !== FALSE) {
				$this->model_catalog_makemodel->editModel((int)substr($this->request->post['id'], 6), $this->db->escape($this->request->post['content']));
			} elseif(isset($this->request->post['id']) && strpos($this->request->post['id'], 'engine_') !== FALSE) {
				$this->model_catalog_makemodel->editEngine((int)substr($this->request->post['id'], 7), $this->db->escape($this->request->post['content']));
			}
		}
	}
  	
	private function validate(){
		if (!$this->user->hasPermission('modify', 'catalog/makemodel')) {
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