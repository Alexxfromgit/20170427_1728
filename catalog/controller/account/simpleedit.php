<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ControllerAccountSimpleEdit extends Controller { 
    public function index($args = null) {

        $this->load->library('simple/simpleedit');

        $this->simpleedit = SimpleEdit::getInstance($this->registry);
        
        if (!$this->customer->isLogged()) {
            $this->redirect($this->url->link('account/login','','SSL'));
        }

        $this->language->load('account/edit');
        
        $this->document->setTitle($this->language->get('heading_title')); 

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        ); 

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_account'),
            'href'      => $this->url->link('account/account', '', 'SSL'),            
            'separator' => $this->language->get('text_separator')
        );
        
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('account/simpleedit', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );
        
        $this->data['action'] = 'index.php?'.$this->simpleedit->getAdditionalParams().'route=account/simpleedit';

        $this->data['heading_title']   = $this->language->get('heading_title');
        $this->data['button_continue'] = $this->language->get('button_continue');

        $this->data['error_warning'] = '';

        $this->simpleedit->updateFields();

        $this->data['rows'] = $this->simpleedit->getRows();

        $this->data['redirect'] = '';

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['submitted']) && $this->validate()) {
            $this->load->model('account/customer');
            
            $customerInfo = $this->simpleedit->getCustomerInfo();
            
            $this->model_account_customer->editCustomer($customerInfo);

            $this->simpleedit->editCustomerGroupId();
            $this->simpleedit->saveCustomFields('edit', 'customer', $this->customer->getId());

            $password = $this->simpleedit->getPassword();

            if ($password) {
                $this->model_account_customer->editPassword($this->customer->getEmail(), $password);
            }

            if ($this->simpleedit->isNewsletterUsed()) {
                $this->model_account_customer->editNewsletter($this->simpleedit->isNewsletterOn());
            }

            $this->session->data['success'] = $this->language->get('text_success');

            if ($this->simpleedit->isAjaxRequest()) {
                $this->data['redirect'] = $this->url->link('account/account', '', 'SSL');
            } else {
                $this->redirect($this->url->link('account/account','','SSL'));
            }
        }

        $this->data['ajax']                = $this->simpleedit->isAjaxRequest();
        $this->data['additional_path']     = $this->simpleedit->getAdditionalPath();
        $this->data['additional_params']   = $this->simpleedit->getAdditionalParams();
        $this->data['scroll_to_error']     = $this->simpleedit->getSettingValue('scrollToError');
        
        $this->data['javascript_callback'] = $this->simpleedit->getJavascriptCallback();
        
        $this->data['display_error']       = $this->simpleedit->displayError();
        
        $this->data['popup']     = !empty($args['popup']) ? true : (isset($this->request->get['popup']) ? true : false);
        $this->data['as_module'] = !empty($args['module']) ? true : (isset($this->request->get['module']) ? true : false);

        if (!$this->simpleedit->isAjaxRequest() && !$this->data['popup'] && !$this->data['as_module']) {
            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header',
            );
      
            $this->data['simple_header'] = $this->simpleedit->getLinkToHeaderTpl();
            $this->data['simple_footer'] = $this->simpleedit->getLinkToFooterTpl();
        }
              
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/simpleedit.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/simpleedit.tpl';
        } else {
            $this->template = 'default/template/account/simpleedit.tpl';
        }

        $this->response->setOutput($this->render());
    }
    
    private function validate() {
        $error = false;

        if (!$this->simpleedit->validateFields()) {
            $error = true;
        }

        return !$error;
    }
}
