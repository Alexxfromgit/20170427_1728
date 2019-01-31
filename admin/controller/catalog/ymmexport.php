<?php 
class ControllerCatalogYmmExport extends Controller { 
	private $error = array();
	
	public function index() {
		$this->data = array_merge($this->data, $this->load->language('catalog/ymmexport'));
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/makemodel');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			if ((isset( $this->request->files['upload'] )) && (is_uploaded_file($this->request->files['upload']['tmp_name']))) {
				$file = $this->request->files['upload']['tmp_name'];
				if(isset($this->request->post['empty'])) {
					$empty = $this->request->post['empty'];
				} else {
					$empty = null;
				}
				if(isset($this->request->post['unique_id'])) {
					$unique_id = $this->request->post['unique_id'];
				} else {
					$unique_id = 'product_id';
				}
				if ($this->model_catalog_makemodel->upload($file, $empty, $unique_id)) {
					$this->session->data['success'] = $this->language->get('text_success');
					$this->redirect($this->url->link('catalog/ymmexport', 'token=' . $this->session->data['token'], 'SSL'));
				} else {
					$this->error['warning'] = $this->language->get('error_upload');
				}
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
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/ymmexport', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		
		$this->data['action'] = $this->url->link('catalog/ymmexport', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['export'] = $this->url->link('catalog/ymmexport/download', 'token=' . $this->session->data['token'], 'SSL');

		$this->template = 'catalog/ymmexport.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
		$this->response->setOutput($this->render());
	}

	public function download(){
		if($this->validate()){
			// set appropriate memory and timeout limits
			ini_set("memory_limit","128M");
			set_time_limit( 1800 );
			
			// Get the data
			$this->load->model('catalog/makemodel');
			
			if(isset($this->request->get['unique_id'])) {
				$uid = $this->request->get['unique_id'];
			} else {
				$uid = 'product_id';
			}
			
			$vehicles = $this->model_catalog_makemodel->download($uid);
			
			// Send data to user.
			$fileName = 'vehicles.csv';
 
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header('Content-Description: File Transfer');
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename={$fileName}");
			header("Expires: 0");
			header("Pragma: public");
 
			$fh = @fopen( 'php://output', 'w' );
			
			$headerDisplayed = false;
			
			foreach ( $vehicles as $data ) {
			    // Add a header row if it hasn't been added yet
			    if ( !$headerDisplayed ) {
			        // Use the keys from $data as the titles
			        fputcsv($fh, array_keys($data));
			        $headerDisplayed = true;
			    }
			 
			    // Put the data into the stream
			    fputcsv($fh, $data);
			}
			// Close the file
			fclose($fh);
			// Make sure nothing else is sent, our file is done
			exit;
		
		} else {
			return $this->forward('error/permission');
		}
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'catalog/ymmexport')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}