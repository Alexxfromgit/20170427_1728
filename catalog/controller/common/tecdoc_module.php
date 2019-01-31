<?php  
class Controllercommontecdocmodule extends Controller {
	public function index() {
		

		//Save customer group ID for TDMod 
		$_SESSION['TDM_CMS_USER_GROUP'] = intval($this->customer->getCustomerGroupId());
		
		//TecDoc
		if(defined('TDM_TITLE')){$this->document->setTitle(TDM_TITLE);}
		if(defined('TDM_KEYWORDS')){$this->document->setKeywords(TDM_KEYWORDS);}
		if(defined('TDM_DESCRIPTION')){$this->document->setDescription(defined('TDM_DESCRIPTION'));}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/tecdoc_module.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/tecdoc_module.tpl';
		} else {
			$this->template = 'default/template/common/tecdoc_module.tpl';
		}
		            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $price = false;
            }
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
										
		$this->response->setOutput($this->render());
	}
}
?>