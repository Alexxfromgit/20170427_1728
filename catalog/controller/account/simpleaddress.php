<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ControllerAccountSimpleaddress extends Controller { 
    public function insert($args = null) {

        $this->load->library('simple/simpleaddress');

        $this->simpleaddress = Simpleaddress::getInstance($this->registry);
        
        if (!$this->customer->isLogged()) {
            $this->redirect($this->url->link('account/login','','SSL'));
        }

        $this->language->load('account/address');
        
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
            'href'      => $this->url->link('account/address', '', 'SSL'),          
            'separator' => $this->language->get('text_separator')
        );
        
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_edit_address'),
            'href'      => $this->url->link('account/simpleaddress/insert', '', 'SSL'),            
            'separator' => $this->language->get('text_separator')
        );
        
        $this->data['action'] = 'index.php?'.$this->simpleaddress->getAdditionalParams().'route=account/simpleaddress/insert';

        $this->data['heading_title']   = $this->language->get('heading_title');
        $this->data['button_continue'] = $this->language->get('button_continue');

        $this->data['error_warning'] = '';

        $this->request->get['address_id'] = 0;

        $this->simpleaddress->updateFields();

        $this->data['rows'] = $this->simpleaddress->getRows();
        $this->data['hidden_rows'] = $this->simpleaddress->getHiddenAddressRows();

        $this->data['redirect'] = '';
        
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['submitted']) && $this->validate()) {
            $this->load->model('account/address');
            
            $addressInfo = $this->simpleaddress->getAddress();

            $addressId = $this->model_account_address->addAddress($addressInfo);

            $this->simpleaddress->saveCustomFields('address', 'address', $addressId);

            $this->session->data['success'] = $this->language->get('text_insert');

            if ($this->simpleaddress->isAjaxRequest()) {
                $this->data['redirect'] = $this->url->link('account/address', '', 'SSL');
            } else {
                $this->redirect($this->url->link('account/address','','SSL'));
            }
        }

        $this->data['ajax']                = $this->simpleaddress->isAjaxRequest();
        $this->data['additional_path']     = $this->simpleaddress->getAdditionalPath();
        $this->data['additional_params']   = $this->simpleaddress->getAdditionalParams();
        $this->data['use_autocomplete']    = $this->simpleaddress->getSettingValue('useAutocomplete');
        $this->data['use_google_api']      = $this->simpleaddress->getSettingValue('useGoogleApi');
        $this->data['scroll_to_error']     = $this->simpleaddress->getSettingValue('scrollToError');
        
        $this->data['javascript_callback'] = $this->simpleaddress->getJavascriptCallback();
        
        $this->data['display_error']       = $this->simpleaddress->displayError();
        
        $this->data['popup']     = !empty($args['popup']) ? true : (isset($this->request->get['popup']) ? true : false);
        $this->data['as_module'] = !empty($args['module']) ? true : (isset($this->request->get['module']) ? true : false);

        if (!$this->simpleaddress->isAjaxRequest() && !$this->data['popup'] && !$this->data['as_module']) {
            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header',
            );
      
            $this->data['simple_header'] = $this->simpleaddress->getLinkToHeaderTpl();
            $this->data['simple_footer'] = $this->simpleaddress->getLinkToFooterTpl();
        }
              
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/simpleaddress.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/simpleaddress.tpl';
        } else {
            $this->template = 'default/template/account/simpleaddress.tpl';
        }

        $this->response->setOutput($this->render());
    }

    public function update($args = null) {

        $this->load->library('simple/simpleaddress');

        $this->simpleaddress = Simpleaddress::getInstance($this->registry);
        
        if (!$this->customer->isLogged()) {
            $this->redirect($this->url->link('account/login','','SSL'));
        }

        $this->language->load('account/address');
        
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
            'href'      => $this->url->link('account/address', '', 'SSL'),          
            'separator' => $this->language->get('text_separator')
        );
        
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_edit_address'),
            'href'      => $this->url->link('account/simpleaddress/update', 'address_id=' . $this->request->get['address_id'], 'SSL'),            
            'separator' => $this->language->get('text_separator')
        );
        
        $this->data['action'] = 'index.php?'.$this->simpleaddress->getAdditionalParams().'route=account/simpleaddress/update&address_id=' . $this->request->get['address_id'];

        $this->data['heading_title']   = $this->language->get('heading_title');
        $this->data['button_continue'] = $this->language->get('button_continue');

        $this->data['error_warning'] = '';

        $this->simpleaddress->updateFields();

        $this->data['rows'] = $this->simpleaddress->getRows();
        $this->data['hidden_rows'] = $this->simpleaddress->getHiddenAddressRows();

        $this->data['redirect'] = '';

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['submitted']) && $this->validate()) {
            $this->load->model('account/address');
            
            $addressInfo = $this->simpleaddress->getAddress();

            $this->model_account_address->editAddress($addressInfo['address_id'], $addressInfo);

            $this->simpleaddress->saveCustomFields('address', 'address', $addressInfo['address_id']);

            // Default Shipping Address
            if (isset($this->session->data['shipping_address_id']) && ($this->request->get['address_id'] == $this->session->data['shipping_address_id'])) {
                $this->session->data['shipping_country_id'] = $addressInfo['country_id'];
                $this->session->data['shipping_zone_id'] = $addressInfo['zone_id'];
                $this->session->data['shipping_postcode'] = $addressInfo['postcode'];
                
                unset($this->session->data['shipping_method']);    
                unset($this->session->data['shipping_methods']);
            }
            
            // Default Payment Address
            if (isset($this->session->data['payment_address_id']) && ($this->request->get['address_id'] == $this->session->data['payment_address_id'])) {
                $this->session->data['payment_country_id'] = $addressInfo['country_id'];
                $this->session->data['payment_zone_id'] = $addressInfo['zone_id'];
                  
                unset($this->session->data['payment_method']);
                unset($this->session->data['payment_methods']);
            }
            
            $this->session->data['success'] = $this->language->get('text_update');

            if ($this->simpleaddress->isAjaxRequest()) {
               $this->data['redirect'] = $this->url->link('account/address', '', 'SSL');
            } else {
                $this->redirect($this->url->link('account/address','','SSL'));
            }
        }

        $this->data['ajax']                = $this->simpleaddress->isAjaxRequest();
        $this->data['additional_path']     = $this->simpleaddress->getAdditionalPath();
        $this->data['additional_params']   = $this->simpleaddress->getAdditionalParams();
        $this->data['use_autocomplete']    = $this->simpleaddress->getSettingValue('useAutocomplete');
        $this->data['use_google_api']      = $this->simpleaddress->getSettingValue('useGoogleApi');
        $this->data['scroll_to_error']     = $this->simpleaddress->getSettingValue('scrollToError');
        
        $this->data['javascript_callback'] = $this->simpleaddress->getJavascriptCallback();
        
        $this->data['display_error']       = $this->simpleaddress->displayError();
        
        $this->data['popup']     = !empty($args['popup']) ? true : (isset($this->request->get['popup']) ? true : false);
        $this->data['as_module'] = !empty($args['module']) ? true : (isset($this->request->get['module']) ? true : false);

        if (!$this->simpleaddress->isAjaxRequest() && !$this->data['popup'] && !$this->data['as_module']) {
            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header',
            );
      
            $this->data['simple_header'] = $this->simpleaddress->getLinkToHeaderTpl();
            $this->data['simple_footer'] = $this->simpleaddress->getLinkToFooterTpl();
        }
              
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/simpleaddress.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/simpleaddress.tpl';
        } else {
            $this->template = 'default/template/account/simpleaddress.tpl';
        }

        $this->response->setOutput($this->render());
    }
    
    private function validate() {
        $error = false;

        if (!$this->simpleaddress->validateFields()) {
            $error = true;
        }

        return !$error;
    }
}
