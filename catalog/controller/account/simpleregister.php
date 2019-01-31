<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ControllerAccountSimpleRegister extends Controller { 
    public function index($args = null) {

        $this->load->library('simple/simpleregister');

        $this->simpleregister = SimpleRegister::getInstance($this->registry);
        
        if ($this->customer->isLogged()) {
            $this->redirect($this->url->link('account/account','','SSL'));
        }

        $this->language->load('account/register');
        $this->language->load('account/simpleregister');
        
        $this->document->setTitle($this->language->get('heading_title')); 

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        ); 
        
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('account/simpleregister', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );
        
        $this->data['action'] = 'index.php?'.$this->simpleregister->getAdditionalParams().'route=account/simpleregister';

        $this->data['heading_title']        = $this->language->get('heading_title');
        $this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', 'SSL'));
        $this->data['button_continue']      = $this->language->get('button_continue');

        $this->data['error_warning'] = '';

        $this->simpleregister->clearSimpleSession();

        $this->simpleregister->updateFields();

        $this->data['rows'] = $this->simpleregister->getRows();
        $this->data['hidden_rows'] = $this->simpleregister->getHiddenAddressRows();

        $this->data['redirect'] = '';

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->data['agreement'] = !empty($this->request->post['agreement']) ? true : false;
        } else {
            $this->data['agreement'] = $this->simpleregister->getSettingValue('agreementCheckboxInit');
        }
        
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['submitted']) && $this->validate()) {
            $this->load->model('account/customer');
            $this->load->model('account/address');

            $customerInfo = $this->simpleregister->getCustomerInfo();
            $addressInfo = $this->simpleregister->getAddress();

            $info = array_merge($addressInfo, $customerInfo);

            // fix for old versions
            $tmpCustomerGroupId = $this->config->get('config_customer_group_id');
            $this->config->set('config_customer_group_id', $info['customer_group_id']);

            $this->model_account_customer->addCustomer($info);

            $this->config->set('config_customer_group_id', $tmpCustomerGroupId);

            $this->customer->login($info['email'], $info['password']);

            $customerId = 0;
            $addressId = 0;

            if ($this->customer->isLogged()) {
                $customerId = $this->customer->getId();
                $addressId = $this->customer->getAddressId();
            } else {
                $customerInfo = $this->simpleregister->getCustomerInfoByEmail($info['email']);
                $customerId = $customerInfo['customer_id'];
                $addressId = $customerInfo['address_id'];
            }

            $this->simpleregister->saveCustomFields('register', 'customer', $customerId);
            $this->simpleregister->saveCustomFields('register', 'address', $addressId);

            // Default Shipping Address
            if ($this->config->get('config_tax_customer') == 'shipping') {
                $this->session->data['shipping_country_id'] = $addressInfo['country_id'];
                $this->session->data['shipping_zone_id']    = $addressInfo['zone_id'];
                $this->session->data['shipping_postcode']   = $addressInfo['postcode'];
            }

            // Default Payment Address
            if ($this->config->get('config_tax_customer') == 'payment') {
                $this->session->data['payment_country_id'] = $addressInfo['country_id'];
                $this->session->data['payment_zone_id']    = $addressInfo['zone_id'];
            }

            if ($this->simpleregister->isAjaxRequest()) {
                $this->data['redirect'] = $this->url->link('account/success');
            } else {
                $this->redirect($this->url->link('account/success'));
            }
        }

        $this->data['ajax']                       = $this->simpleregister->isAjaxRequest();
        $this->data['additional_path']            = $this->simpleregister->getAdditionalPath();
        $this->data['additional_params']          = $this->simpleregister->getAdditionalParams();
        $this->data['display_agreement_checkbox'] = $this->simpleregister->getSettingValue('displayAgreementCheckbox');
        $this->data['use_autocomplete']           = $this->simpleregister->getSettingValue('useAutocomplete');
        $this->data['use_google_api']             = $this->simpleregister->getSettingValue('useGoogleApi');
        $this->data['scroll_to_error']            = $this->simpleregister->getSettingValue('scrollToError');
        
        $this->data['javascript_callback']        = $this->simpleregister->getJavascriptCallback();

        $this->data['display_error']              = $this->simpleregister->displayError();

        $this->data['popup']     = !empty($args['popup']) ? true : (isset($this->request->get['popup']) ? true : false);
        $this->data['as_module'] = !empty($args['module']) ? true : (isset($this->request->get['module']) ? true : false);

        $langId = ($this->config->get('config_template') == 'shoppica' || $this->config->get('config_template') == 'shoppica2') ? 'text_agree_shoppica' : 'text_agree';
        $title = $this->simpleregister->getInformationTitle($this->simpleregister->getSettingValue('agreementId'));
        $this->data['text_agreement'] = sprintf($this->language->get($langId), $this->url->link('information/information/info', $this->simpleregister->getAdditionalParams() . 'information_id=' . $this->simpleregister->getSettingValue('agreementId'), 'SSL'), $title, $title);
        
        if (!$this->simpleregister->isAjaxRequest() && !$this->data['popup'] && !$this->data['as_module']) {
            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header',
            );
      
            $this->data['simple_header'] = $this->simpleregister->getLinkToHeaderTpl();
            $this->data['simple_footer'] = $this->simpleregister->getLinkToFooterTpl();
        }
              
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/simpleregister.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/simpleregister.tpl';
        } else {
            $this->template = 'default/template/account/simpleregister.tpl';
        }

        $this->response->setOutput($this->render());
    }
    
    private function validate() {
        $error = false;

        if ($this->simpleregister->getSettingValue('displayAgreementCheckbox') && !$this->data['agreement']) {
            $this->data['error_warning'] = sprintf($this->language->get('error_agree'), $this->simpleregister->getInformationTitle($this->simpleregister->getSettingValue('agreementId')));
            $error = true; 
        }

        if (!$this->simpleregister->validateFields()) {
            $error = true;
        }

        return !$error;
    }
}
