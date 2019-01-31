<?php 
class Simple {
    static $version = '4.2.5';
    protected static $_instance;

    protected $_registry = null;
    protected $_opencartVersion = '';

    protected $_isStaticLinksAdded = false;

    protected $_page = '';
    protected $_block = '';

    protected $_settings = array();

    protected $_settingsId = 0;

    protected $_data = array();

    protected $_fields        = array();
    protected $_hiddenFields  = array();
    protected $_resetFields   = array();
    protected $_displayedRows = array();

    protected $_paymentAddressEmpty = false;
    protected $_shippingAddressEmpty = false;

    private $_objects = array(
        'customer' => array(),
        'address'  => array()
    );

    private $_addressBlocks = array('address', 'payment_address', 'shipping_address', 'register');

    private $_fieldTpl     = '';
    private $_headerTpl    = '';
    private $_rowsBeginTpl = '';
    private $_rowsEndTpl   = '';
    private $_hiddenTpl    = '';
    
    public static function getInstance($registry) {
        if (self::$_instance === null) {
            self::$_instance = new self($registry);  
        }

        return self::$_instance;
    }

    private function __clone() {
    }

    private function __wakeup() {
    }     

    public function __get($key) {
        return $this->_registry->get($key);
    }

    public function __set($key, $value) {
        $this->_registry->set($key, $value);
    }

    protected function __construct($registry) {
        $this->_registry = $registry;        
        
        $opencartVersion = explode('.', VERSION);
        $this->_opencartVersion = floatval($opencartVersion[0].$opencartVersion[1].$opencartVersion[2].'.'.(isset($opencartVersion[3]) ? $opencartVersion[3] : 0));
        
        $this->loadSettings();

        $this->addLinksToStaticFiles();

        $this->_fieldTpl     = $this->getTpl('simple_row_field');
        $this->_headerTpl    = $this->getTpl('simple_row_header');
        $this->_rowsBeginTpl = $this->getTpl('simple_rows_begin');
        $this->_rowsEndTpl   = $this->getTpl('simple_rows_end');
        $this->_hiddenTpl    = $this->getTpl('simple_row_hidden');
    }

    private function getTpl($name) {
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/'.$name.'.tpl')) {
            return $this->config->get('config_template') . '/template/common/'.$name.'.tpl';
        } else {
            return 'default/template/common/'.$name.'.tpl';
        }
    }

    public function setPage($page) {
        $this->_page = $page;
    }

    public function getOpencartVersion() {
        return $this->_opencartVersion;
    }

    public function isAjaxRequest() {
        $ajax = false;

        if ((!empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || (!isset($this->request->server['HTTP_X_REQUESTED_WITH']) && $this->request->server['REQUEST_METHOD'] == 'POST' && !empty($this->request->post['ajax']))) {
            $ajax = true;
        }

        return $ajax;
    }

    public function getLinkToHeaderTpl() {
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/simple_header.tpl')) {
            return DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/simple_header.tpl';
        } else {
            return DIR_TEMPLATE . 'default/template/common/simple_header.tpl';
        }
    }

    public function getLinkToFooterTpl() {
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/simple_footer.tpl')) {
            return DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/simple_footer.tpl';
        } else {
            return DIR_TEMPLATE . 'default/template/common/simple_footer.tpl';
        }
    }

    public function loadSettings() {
        $this->_settings = @json_decode($this->config->get('simple_settings'), true);

        if (empty($this->_settings)) {
            $this->_settings = @json_decode(@file_get_contents(DIR_SYSTEM.'library/simple/simple.settings'), true);
        }
    }

    public function getSettingValue($name) {
        return $this->getSettingValueDirectly($this->_page, $this->_block, $name);
    }

    protected function getSettingValueDirectly($page, $block, $name) {
        $block = $this->camelize($block);
        
        if (empty($this->_settings['checkout'][$this->_settingsId])) {
            $this->_settingsId = 0;
        }

        if ($page) {
            if ($page == 'checkout') {
                if ($block == '' || $block == 'common') {
                    $block = '';
                    if (!empty($this->_settings['checkout'][$this->_settingsId][$name])) {
                        return $this->_settings['checkout'][$this->_settingsId][$name];
                    }
                } else {
                    if (!empty($this->_settings['checkout'][$this->_settingsId][$block][$name])) {
                        return $this->_settings['checkout'][$this->_settingsId][$block][$name];
                    }
                }
            } else {
                if (!empty($this->_settings[$page][$name])) {
                    return $this->_settings[$page][$name];
                }
            }
        } else {
            if (!empty($this->_settings[$name])) {
                return $this->_settings[$name];
            }
        }
        
        return '';
    }

    public function getAdditionalPath() {
        return $this->getSettingValueDirectly('', '', 'additionalPath');
    }

    public function getAdditionalParams() {
        $value = $this->getSettingValueDirectly('', '', 'additionalParams');

        return !empty($value) ? $value.'&' : '';
    }

    public function getJavascriptCallback() {
        return htmlspecialchars_decode($this->getSettingValueDirectly('', '', 'javascriptCallback'));
    }

    public function addLinksToStaticFiles() {
        $template = $this->config->get('config_template');

        $minify = $this->getSettingValueDirectly('', '', 'minify');
        
        $version = '';
        if (!$minify) {
            $version = '?v='.self::$version;
        }

        $this->document->addStyle('catalog/view/theme/'.$template.'/stylesheet/simple.css'.$version);

        $direction = $this->language->get('direction');

        if ($direction == 'rtl') {
            $this->document->addStyle('catalog/view/theme/'.$template.'/stylesheet/simple.rtl.css'.$version);
        }

        if (strpos($this->getAdditionalPath(), 'mijo') !== false) {
            $this->document->addScript('plugins/system/mijoshopjquery/mijoshopjquery/ajaxupload.js');
        } else {
            $this->document->addScript('catalog/view/javascript/jquery/ajaxupload.js');
        }
        
        $this->document->addScript('catalog/view/javascript/easyTooltip.js');
        $this->document->addScript('catalog/view/javascript/simple.js'.$version);
        
        if ($this->_page) {
            $page = 'page';
            
            if ($this->_page == 'checkout') {
                $page = 'checkout';
            }

            $this->document->addScript('catalog/view/javascript/simple'.$page.'.js'.$version);
        }

        $this->document->addScript('catalog/view/javascript/jquery/jquery.maskedinput-1.3.min.js');
        $this->document->addScript('catalog/view/javascript/jquery/jquery-ui-timepicker-addon.js');

        if ($this->getSettingValue('useGoogleApi')) {
            $this->document->addScript('https://maps.googleapis.com/maps/api/js?key='.$this->getSettingValueDirectly('', '', 'googleapiKey').'&sensor=false');
        }

        if ($this->_page == 'register' || $this->_page == 'checkout') {
            if ($this->_opencartVersion >= 155) {
                if ($this->getSettingValueDirectly('', '', 'colorbox')) {
                    if (strpos($this->getAdditionalPath(), 'mijo') !== false) {
                        $this->document->addScript('plugins/system/mijoshopjquery/mijoshopjquery/colorbox/jquery.colorbox-min.js');
                        $this->document->addStyle('plugins/system/mijoshopjquery/mijoshopjquery/colorbox/colorbox.css');
                    } else {
                        $this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
                        $this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
                    }
                }
            }

            if ($template == 'shoppica') {
                $this->document->addScript('catalog/view/theme/shoppica/js/jquery/jquery.prettyPhoto.js');
                $this->document->addStyle('catalog/view/theme/shoppica/stylesheet/prettyPhoto.css');
            }

            if ($template == 'shoppica2') {
                $this->document->addScript('catalog/view/theme/shoppica2/javascript/prettyphoto/js/jquery.prettyPhoto.js');
                $this->document->addStyle('catalog/view/theme/shoppica2/javascript/prettyphoto/css/prettyPhoto.css');      
            }
        }
    }

    public function getInformationTitle($id) {
        $this->load->model('catalog/information');

        $information = $this->model_catalog_information->getInformation($id);
        
        if ($information) {
            return $information['title'];
        }

        return '';
    }

    public function prepareAddress($zone_id, $country_id = 0) {
        $this->load->model('localisation/zone');
        $this->load->model('localisation/country');

        $result = array(
            'address_id'     => 0,
            'firstname'      => '',
            'lastname'       => '',
            'company'        => '',
            'company_id'     => '',
            'tax_id'         => '',
            'address_1'      => '',
            'address_2'      => '',
            'postcode'       => '',
            'city'           => '',
            'zone_id'        => 0,
            'zone'           => '',
            'zone_code'      => '',
            'country_id'     => 0,
            'country'        => '', 
            'iso_code_2'     => '',
            'iso_code_3'     => '',
            'address_format' => ''
        );
    
        if ($zone_id) {
            $zone = $this->model_localisation_zone->getZone($zone_id);
            if ($zone) {
                if ($zone['country_id'] != $country_id) {
                    $zone_id = 0;
                } else {
                    $country = $this->model_localisation_country->getCountry($zone['country_id']);
                    
                    if ($country) {
                        $result = array(
                            'address_id'     => 0,
                            'firstname'      => '',
                            'lastname'       => '',
                            'company'        => '',
                            'company_id'     => '',
                            'tax_id'         => '',
                            'address_1'      => '',
                            'address_2'      => '',
                            'postcode'       => '',
                            'city'           => '',
                            'zone_id'        => $zone['zone_id'],
                            'zone'           => $zone['name'],
                            'zone_code'      => $zone['code'],
                            'country_id'     => $zone['country_id'],
                            'country'        => $country['name'],   
                            'iso_code_2'     => $country['iso_code_2'],
                            'iso_code_3'     => $country['iso_code_3'],
                            'address_format' => $country['address_format']
                        );
                    }
                }
            }
        } 

        if ($country_id && !$zone_id) {
            $country = $this->model_localisation_country->getCountry($country_id);
                
            if ($country) {
                $result = array(
                    'address_id'     => 0,
                    'firstname'      => '',
                    'lastname'       => '',
                    'company'        => '',
                    'company_id'     => '',
                    'tax_id'         => '',
                    'address_1'      => '',
                    'address_2'      => '',
                    'postcode'       => '',
                    'city'           => '',
                    'zone_id'        => $zone_id,
                    'zone'           => '',
                    'zone_code'      => '',
                    'country_id'     => $country['country_id'],
                    'country'        => $country['name'],   
                    'iso_code_2'     => $country['iso_code_2'],
                    'iso_code_3'     => $country['iso_code_3'],
                    'address_format' => $country['address_format']
                );
            }
        }

        $addressFormats = $this->getSettingValueDirectly('', '', 'addressFormats');

        $customerGroupId = $this->getCustomerGroupId();
        $languageCode = $this->getCurrentLanguageCode();

        if ($customerGroupId && $languageCode && isset($addressFormats[$customerGroupId]) && isset($addressFormats[$customerGroupId][$languageCode]) && $addressFormats[$customerGroupId][$languageCode]) {
            $result['address_format'] = $addressFormats[$customerGroupId][$languageCode];
        }

        return $result;
    }

    public function getCurrentLanguageCode() {
        if (empty($this->_data['language_code'])) {
            $this->_data['language_code'] = trim(str_replace('-', '_', strtolower($this->config->get('config_language'))), '.');
        }
        return $this->_data['language_code'];
    }

    public function getCustomerGroupId() {
        if ($this->_page == 'checkout') {
            $block = $this->_block;
            $this->_block = 'customer';
            $value = $this->getFieldValue('customer_group_id');
            $this->_block = $block;
        } else {
            $value = $this->getFieldValue('customer_group_id');
        }

        if (!$value) {
            $value = $this->config->get('config_customer_group_id');
        } else {
            // fix for different prices for each customer group
            //$this->config->set('config_customer_group_id', $value);
        }

        return $value;
    }

    private function getFieldSettings($id) {
        $fields = $this->getSettingValueDirectly('', '', 'fields');

        foreach ($fields as $field) {
            if ($field['id'] == $id) {
                return $field;
            }
        }

        return array();
    }

    private function getHeaderSettings($id) {
        $headers = $this->getSettingValueDirectly('', '', 'headers');

        foreach ($headers as $header) {
            if ($header['id'] == $id) {
                return $header;
            }
        }

        return array();
    }

    protected function getFieldsBlockName() {
        $block = $this->_block;

        if ($this->_page != 'checkout') {
            $block = $this->_page;
        }

        return $block;
    }

    protected function initCustomerInfoFromDatabase() {
        if (empty($this->_objects['customer'])) {
            $this->load->model('account/customer');

            $mainCustomerInfo = $this->model_account_customer->getCustomer($this->customer->getId());
            $customCustomerInfo = $this->getCustomFieldsFromDataBase('customer', $this->customer->getId());

            $mainCustomerInfo = is_array($mainCustomerInfo) ? $mainCustomerInfo : array();
            $customCustomerInfo = is_array($customCustomerInfo) ? $customCustomerInfo : array();

            $this->_objects['customer'] = array_merge($customCustomerInfo, $mainCustomerInfo);
        }
    }

    protected function initAddressInfoFromDatabase() {
        $this->load->model('account/address');

        $mainAddressInfo = $this->model_account_address->getAddress($this->getCurrentAddressId());
        $customAddressInfo = !empty($mainAddressInfo) ? $this->getCustomFieldsFromDataBase('address', $this->getCurrentAddressId()) : array();

        $mainAddressInfo = is_array($mainAddressInfo) ? $mainAddressInfo : array();
        $customAddressInfo = is_array($customAddressInfo) ? $customAddressInfo : array();

        $this->_objects['address'] = array_merge($customAddressInfo, $mainAddressInfo);
    }

    private function loadObjects() {
        if ($this->customer->isLogged()) {
            $this->initCustomerInfoFromDatabase();
            $this->initAddressInfoFromDatabase(); 
        }
    }

    private function loadSimpleSessionByGeoIp() {
        if ($this->_page != 'checkout') {
            $useGeoIp = $this->getSettingValue('useGeoIp');
            $geoIpMode = $this->getSettingValue('geoIpMode');
        } else {
            $useGeoIp = $this->getSettingValueDirectly('checkout', '', 'useGeoIp');
            $geoIpMode = $this->getSettingValueDirectly('checkout', '', 'geoIpMode');
        }
        
        if (!$this->customer->isLogged() && $useGeoIp) {
            $block = $this->getFieldsBlockName();

            $this->load->model('tool/simplegeo');

            $info = $this->model_tool_simplegeo->getGeoDataByIp($geoIpMode);

            if (!empty($info) && is_array($info)) {
                foreach ($info as $key => $value) {
                    if (!isset($this->session->data['simple'][$block][$key])) {
                        $this->session->data['simple'][$block][$key] = $value;
                    }
                }
            }
        }
    }

    public function getCurrentAddressId() {
        if ($this->customer->isLogged()) {
            $block = $this->getFieldsBlockName();

            $this->overridePostValue('address_id');

            if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post[$block]['address_id']) && !isset($this->request->post['ignore_post'])) {
                return $this->request->post[$block]['address_id'];
            } else if ($this->request->server['REQUEST_METHOD'] == 'GET' && isset($this->request->get['address_id'])) {
                return $this->request->get['address_id'];
            } else {
                return $this->customer->getAddressId();
            }
        }

        return 0;
    }

    public function updateFields() {
        $block = $this->getFieldsBlockName();
        $round = array();
        $sort  = array();
        $skip  = false;

        $this->loadObjects();
        $this->loadSimpleSessionByGeoIp();
        
        $fields = $this->getSettingValueDirectly('', '', 'fields');

        $this->_fields[$block]       = array();
        $this->_hiddenFields[$block] = array();
        $this->_resetFields[$block]  = array();

        if (empty($fields)) {
            return;
        }

        $toEnd = 1000;
        foreach ($fields as $fieldSettings) {
            $skip = false;

            if ($fieldSettings['id'] == 'address_id') {
                $round[$fieldSettings['id']] = $fieldSettings;
                $sort[$fieldSettings['id']] = 0;
                $skip = true;
            }

            if (!empty($fieldSettings['values']['source']) && $fieldSettings['values']['source'] == 'model' && !empty($fieldSettings['values']['method']) && !empty($fieldSettings['values']['filter'])) {
                $round[$fieldSettings['id']] = $fieldSettings;
                $sort[$fieldSettings['id']] = isset($sort[$fieldSettings['values']['filter']]) ? ($sort[$fieldSettings['values']['filter']] + 1) : ($toEnd++);
                $skip = true;
            }

            if (!empty($fieldSettings['default']['source']) && $fieldSettings['default']['source'] == 'model' && !empty($fieldSettings['default']['method']) && !empty($fieldSettings['default']['filter'])) {
                $round[$fieldSettings['id']] = $fieldSettings;
                $sort[$fieldSettings['id']] = isset($sort[$fieldSettings['default']['filter']]) ? ($sort[$fieldSettings['default']['filter']] + 1) : ($toEnd++);
                $skip = true;
            }

            if (!empty($fieldSettings['mask']['source']) && $fieldSettings['mask']['source'] == 'model' && !empty($fieldSettings['mask']['method']) && !empty($fieldSettings['mask']['filter'])) {
                $round[$fieldSettings['id']] = $fieldSettings;
                $sort[$fieldSettings['id']] = isset($sort[$fieldSettings['mask']['filter']]) ? ($sort[$fieldSettings['mask']['filter']] + 1) : ($toEnd++);
                $skip = true;
            }

            if (!empty($fieldSettings['rules']['api']['enabled']) && !empty($fieldSettings['rules']['api']['filter'])) {
                $round[$fieldSettings['id']] = $fieldSettings;
                $sort[$fieldSettings['id']] = isset($sort[$fieldSettings['rules']['api']['filter']]) ? ($sort[$fieldSettings['rules']['api']['filter']] + 1) : ($toEnd++);
                $skip = true;
            }
            
            if (!$skip) {
                $round[$fieldSettings['id']] = $fieldSettings;
                $sort[$fieldSettings['id']] = 1;
            }
        }

        array_multisort($sort, SORT_ASC, $round);

        foreach ($round as $fieldSettings) {
            $value = $this->getFieldValue($fieldSettings['id']);

            $this->_fields[$block][$fieldSettings['id']] = array(
                'id'            => $fieldSettings['id'],
                'type'          => $fieldSettings['type'],
                'value'         => $value,
                'values'        => $this->getFieldValues($fieldSettings['id']),
                'valid'         => true,
                'custom'        => !empty($fieldSettings['custom']) ? true : false,
                'saveToComment' => !empty($fieldSettings['custom']) && !empty($fieldSettings['saveToComment']) ? true : false,
                'object'        => $this->getFieldObject($fieldSettings['id'])
            );

            if ($value === '' && !empty($fieldSettings['autoreload'])) {
                if ($block == 'payment_address') {
                    $this->_paymentAddressEmpty = true;
                } elseif ($block == 'shipping_address') {
                    $this->_shippingAddressEmpty = true;
                }
            }

            if ($fieldSettings['id'] == 'address_id' && in_array($block, $this->_addressBlocks)) {
                $this->_hiddenFields[$block]['address_id'] = array(
                    'id'    => 'address_id',
                    'name'  => $block.'[address_id]',
                    'id'    => $block.'_address_id',
                    'value' => $value
                );

                $this->_hiddenFields[$block]['current_address_id'] = array(
                    'id'    => 'current_address_id',
                    'name'  => $block.'[current_address_id]',
                    'id'    => $block.'_current_address_id',
                    'value' => $value
                );
            }

            if (in_array($block, $this->_addressBlocks) && in_array($fieldSettings['id'], array('country_id', 'zone_id', 'postcode', 'city'))) {
                $this->_hiddenFields[$block][$fieldSettings['id']] = array(
                    'id'    => $fieldSettings['id'],
                    'name'  => $block.'['.$fieldSettings['id'].']',
                    'id'    => $block.'_'.$fieldSettings['id'],
                    'value' => $value
                );
            }

            $this->_resetFields[$block][$fieldSettings['id']] = $fieldSettings['id'];
        }
    }

    public function getRows() {
        $langCode     = $this->getCurrentLanguageCode();
        $block        = $this->getFieldsBlockName();
        $reloadFields = array();
        $sort         = array();
        $sort1        = array();
        $sort2        = array();
        $round        = array();
        $skip         = false;

        $allRowSets = $this->getSettingValue('rows');
        $rows = array();

        $shippingCode = '';
        if (!empty($this->_data['shipping_method']['code'])) {
            $shippingCode = $this->_data['shipping_method']['code'];
        }

        $paymentCode = '';
        if (!empty($this->_data['payment_method']['code'])) {
            $paymentCode = $this->_data['payment_method']['code'];
        }

        if (!empty($allRowSets[$shippingCode.'|'.$paymentCode])) {
            $rows = $allRowSets[$shippingCode.'|'.$paymentCode];
        } 

        if (empty($rows) && !empty($allRowSets[$shippingCode.'|'])) {
            $rows = $allRowSets[$shippingCode.'|'];
        }

        if (empty($rows) && !empty($allRowSets['|'.$paymentCode])) {
            $rows = $allRowSets['|'.$paymentCode];
        }

        if (empty($rows) && !empty($allRowSets['default'])) {
            $rows = $allRowSets['default'];
        }

        if (empty($rows)) {
            return array();
        }

        $toEnd = 1000;
        foreach ($rows as $row) {
            $skip = false;
            if ($row['type'] == 'field') {
                $fieldSettings = $this->getFieldSettings($row['id']);

                if ($fieldSettings['id'] == 'address_id') {
                    $reloadFields[] = 'address_id';
                    $round[$row['type'].'_'.$row['id']] = $row;
                    $sort[$row['type'].'_'.$row['id']] = 0;
                    $skip = true;
                }

                if (!empty($row['masterField'])) {
                    $reloadFields[] = $row['masterField'];
                    $round[$row['type'].'_'.$row['id']] = $row;
                    $sort[$row['type'].'_'.$row['id']] = isset($sort[$row['masterField']]) ? ($sort[$row['masterField']] + 1) : ($toEnd++);
                    $skip = true;
                }

                if (!empty($fieldSettings['values']['source']) && $fieldSettings['values']['source'] == 'model' && !empty($fieldSettings['values']['method']) && !empty($fieldSettings['values']['filter'])) {
                    $reloadFields[] = $fieldSettings['values']['filter'];
                    $round[$row['type'].'_'.$row['id']] = $row;
                    $sort[$row['type'].'_'.$row['id']] = isset($sort[$fieldSettings['values']['filter']]) ? ($sort[$fieldSettings['values']['filter']] + 1) : ($toEnd++);
                    $skip = true;
                }

                if (!empty($fieldSettings['default']['source']) && $fieldSettings['default']['source'] == 'model' && !empty($fieldSettings['default']['method']) && !empty($fieldSettings['default']['filter'])) {
                    $reloadFields[] = $fieldSettings['default']['filter'];
                    $round[$row['type'].'_'.$row['id']] = $row;
                    $sort[$row['type'].'_'.$row['id']] = isset($sort[$fieldSettings['default']['filter']]) ? ($sort[$fieldSettings['default']['filter']] + 1) : ($toEnd++);
                    $skip = true;
                }

                if (!empty($fieldSettings['mask']['source']) && $fieldSettings['mask']['source'] == 'model' && !empty($fieldSettings['mask']['method']) && !empty($fieldSettings['mask']['filter'])) {
                    $reloadFields[] = $fieldSettings['mask']['filter'];
                    $round[$row['type'].'_'.$row['id']] = $row;
                    $sort[$row['type'].'_'.$row['id']] = isset($sort[$fieldSettings['mask']['filter']]) ? ($sort[$fieldSettings['mask']['filter']] + 1) : ($toEnd++);
                    $skip = true;
                }

                if (!empty($fieldSettings['rules']['api']['enabled']) && !empty($fieldSettings['rules']['api']['filter'])) {
                    $reloadFields[] = $fieldSettings['rules']['api']['filter'];
                    $round[$row['type'].'_'.$row['id']] = $row;
                    $sort[$row['type'].'_'.$row['id']] = isset($sort[$fieldSettings['rules']['api']['filter']]) ? ($sort[$fieldSettings['rules']['api']['filter']] + 1) : ($toEnd++);
                    $skip = true;
                }
            }

            if (!$skip) {
                $round[$row['type'].'_'.$row['id']] = $row;
                $sort[$row['type'].'_'.$row['id']] = 1;
            }

            $sort1[$row['type'].'_'.$row['id']] = $row['sortOrder'];
        }

        array_multisort($sort, SORT_ASC, $round);

        $this->_displayedRows[$block] = array();

        foreach ($round as $row) {
            $tmp = array();
            if ($row['type'] == 'header') {
                if (!$this->isRowDisplayed($row)) {
                    continue;
                }
                
                $headerSettings = $this->getHeaderSettings($row['id']);

                $tmp = array(
                    'id'      => $headerSettings['id'],
                    'rowType' => 'header',
                    'label'   => !empty($headerSettings['label'][$langCode]) ? $headerSettings['label'][$langCode] : $headerSettings['id']
                );
            } elseif ($row['type'] == 'field') {
                if (!$this->isRowDisplayed($row)) {
                    continue;
                }

                $fieldSettings = $this->getFieldSettings($row['id']);

                if (empty($fieldSettings)) {
                    continue;
                }

                if (!empty($this->_hiddenFields[$block][$fieldSettings['id']])) {
                    unset($this->_hiddenFields[$block][$fieldSettings['id']]);
                }

                if (!empty($this->_resetFields[$block][$fieldSettings['id']])) {
                    unset($this->_resetFields[$block][$fieldSettings['id']]);
                }

                $filename = '';
                if ($fieldSettings['type'] == 'file') {
                    $fullName = $this->_fields[$block][$row['id']]['value'];
                    $filename = basename(utf8_substr($fullName, 0, utf8_strrpos($fullName, '.')));
                    $filename = $filename ? $filename : basename($fullName);
                }

                $tmp = array(
                    'id'          => $fieldSettings['id'],
                    'rowType'     => 'field',
                    'label'       => !empty($fieldSettings['label'][$langCode]) ? $fieldSettings['label'][$langCode] : $fieldSettings['id'],
                    'type'        => !empty($fieldSettings['type']) ? $fieldSettings['type'] : 'text',
                    'name'        => $block.'['.$fieldSettings['id'].']',
                    'id'          => $block.'_'.$fieldSettings['id'],
                    'required'    => $this->isFieldRequired($row),
                    'valid'       => $this->_fields[$block][$row['id']]['valid'],
                    'rules'       => array(),
                    'reload'      => ($this->_page == 'checkout' && !empty($fieldSettings['autoreload'])) || $row['id'] == 'address_id' || in_array($fieldSettings['id'], $reloadFields) ? true : false,
                    'value'       => $this->_fields[$block][$row['id']]['value'],
                    'values'      => $this->_fields[$block][$row['id']]['values'],
                    'placeholder' => !empty($fieldSettings['placeholder'][$langCode]) ? $fieldSettings['placeholder'][$langCode] : '',
                    'description' => !empty($fieldSettings['description'][$langCode]) ? htmlspecialchars_decode($fieldSettings['description'][$langCode]) : '',
                    'attrs'       => $this->getFieldAttrs($fieldSettings['id']),
                    'object'      => $this->getFieldObject($fieldSettings['id']),
                    'filename'    => $filename
                );

                
                $rules = $this->getFieldValidationRules($row['id'], $tmp['required']);
                $valid = true;

                foreach ($rules as $rule) {
                    if (!$rule['passed']) {
                        $valid = false;
                    }
                }

                $tmp['rules'] = $rules;
                $tmp['valid'] = $valid;

                $this->_fields[$block][$row['id']]['valid'] = $valid;
            } elseif ($row['type'] == 'splitter') {
                $tmp = array(
                    'id'      => 'splitter',
                    'rowType' => 'splitter'
                );
            }

            $this->_displayedRows[$block][$row['type'].'_'.$row['id']] = $tmp;
            $sort2[$row['type'].'_'.$row['id']] = $sort1[$row['type'].'_'.$row['id']];
        }

        array_multisort($sort2, SORT_ASC, $this->_displayedRows[$block]);

        if (in_array($block, $this->_addressBlocks)) {
            unset($this->_resetFields[$block]['address_id']);
            unset($this->_resetFields[$block]['country_id']);
            unset($this->_resetFields[$block]['zone_id']);
            unset($this->_resetFields[$block]['city']);
            unset($this->_resetFields[$block]['postcode']);
        }

        foreach ($this->_resetFields[$block] as $id) {
            unset($this->_fields[$block][$id]);
        }

        return $this->getRowsAsHtml();
    }

    public function getRowsAsHtml() {
        $block = $this->getFieldsBlockName();

        $template = new Template();

        $result = array();

        $result[] = $template->fetch($this->_rowsBeginTpl);

        foreach ($this->_displayedRows[$block] as $row) {
            if ($row['rowType'] == 'header') {
                $template->data = $row;
                $result[] = $template->fetch($this->_headerTpl);
            } elseif ($row['rowType'] == 'field') {
                $template->data = $row;
                $template->data['additional_path'] = $this->getAdditionalPath();
                $template->data['button_upload'] = $this->language->get('button_upload');
                $result[] = $template->fetch($this->_fieldTpl);
            } elseif ($row['rowType'] == 'splitter') {
                $result[] = $template->fetch($this->_rowsEndTpl);
                $result[] = $template->fetch($this->_rowsBeginTpl);
            }
        }   
        
        $result[] = $template->fetch($this->_rowsEndTpl);

        return $result;
    }

    public function getHiddenAddressRows() {
        $block = $this->getFieldsBlockName();
        $result = array();

        $template = new Template();

        foreach ($this->_hiddenFields[$block] as $field) {
            $template->data = $field;
            $result[] = $template->fetch($this->_hiddenTpl);
        }   

        return $result;
    }

    public function displayError() {
        return $this->request->server['REQUEST_METHOD'] == 'POST' ? true : false;
    }

    private function getFieldAttrs($id) {
        $fieldSettings = $this->getFieldSettings($id);
        $type = !empty($fieldSettings['type']) ? $fieldSettings['type'] : 'text';

        $attrs = array();

        if ($type == 'text' || $type == 'phone' || $type == 'tel') {
            $attrs[] = 'data-mask="'.$this->getFieldMask($fieldSettings['id']).'"';
        } elseif ($type == 'date') {
            $startUsed = false;
            $attrs[] = 'data-type="date"';
            if ($fieldSettings['dateStartType'] == 'fixed' && !empty($fieldSettings['dateStartDay'])) {
                $attrs[] = 'data-start-day="'.$fieldSettings['dateStartDay'].'"';
                $startUsed = true;
            }
            if ($fieldSettings['dateStartType'] == 'calculated' && !empty($fieldSettings['dateStartAfter'])) {
                $attrs[] = 'data-start-after="'.$fieldSettings['dateStartAfter'].'"';
                $startUsed = true;
            }

            if (!$startUsed) {
                $attrs[] = 'data-start-after="0"';
            }

            $endUsed = false;
            if ($fieldSettings['dateEndType'] == 'fixed' && !empty($fieldSettings['dateEndDay'])) {
                $attrs[] = 'data-end-day="'.$fieldSettings['dateEndDay'].'"';
                $endUsed = true;
            }
            if ($fieldSettings['dateEndType'] == 'calculated' && !empty($fieldSettings['dateEndAfter'])) {
                $attrs[] = 'data-end-after="'.$fieldSettings['dateEndAfter'].'"';
                $endUsed = true;
            }

            if (!$endUsed) {
                $attrs[] = 'data-end-after="356"';
            }

            if (!empty($fieldSettings['dateWeekdaysOnly'])) {
                $attrs[] = 'data-weekdays-only="1"';
            }
            if (!empty($fieldSettings['dateSelected']) && is_array($fieldSettings['dateSelected'])) {
                $tmp = array();
                foreach ($fieldSettings['dateSelected'] as $key => $value) {
                    if ($value) {
                        $tmp[] = $key;
                    }
                }
                if (!empty($tmp)) {
                    $attrs[] = 'data-days-only="'.implode(',', $tmp).'"';
                }
            }

        } elseif ($type == 'time') {
            if (!empty($fieldSettings['timeHoursOnly'])) {
                $attrs[] = 'data-hours-only="'.$fieldSettings['timeHoursOnly'].'"';
            }
            if (!empty($fieldSettings['timeMin'])) {
                $attrs[] = 'data-min-time="'.$fieldSettings['timeMin'].'"';
            } else {
                $attrs[] = 'data-min-time="00:00"';
            }
            if (!empty($fieldSettings['timeMax'])) {
                $attrs[] = 'data-max-time="'.$fieldSettings['timeMax'].'"';
            } else {
                $attrs[] = 'data-max-time="24:00"';
            }
        }

        return implode(' ', $attrs);
    }

    private function getFieldValidationRules($id, $required) {
        $block         = $this->getFieldsBlockName();
        $fieldSettings = $this->getFieldSettings($id);
        $langCode      = $this->getCurrentLanguageCode();

        $result = array();

        if (!empty($fieldSettings['rules']) && is_array($fieldSettings['rules'])) {
            foreach ($fieldSettings['rules'] as $key => $rule) {
                if (!empty($rule['enabled'])) {
                    $passed = true;
                    $attrs = array();

                    switch ($key) {
                        case 'notEmpty':
                            if (!$required) {
                                $passed = true;
                            } else {
                                if (isset($this->_fields[$block][$id]['value']) && $this->convertValueToText($this->_fields[$block][$id]['value']) !== '') {
                                    $passed = true;
                                } else {
                                    $passed = false;
                                }
                            }
                            $attrs[] = 'data-not-empty="1"';
                        break;
                        case 'equal':
                            if (!empty($rule['fieldId']) && isset($this->_fields[$block][$id]['value']) && isset($this->_fields[$block][$rule['fieldId']]['value'])) {
                                if ($this->convertValueToText($this->_fields[$block][$id]['value']) == $this->convertValueToText($this->_fields[$block][$rule['fieldId']]['value'])) {
                                    $passed = true;
                                } else {
                                    $passed = false;
                                }
                            }

                            if (!empty($rule['fieldId'])) {
                                $attrs[] = 'data-equal="'.$block.'_'.$rule['fieldId'].'"';
                            }
                        break;
                        case 'byLength':
                            if (!in_array($this->_fields[$block][$id]['type'], array('text','email','tel','password','textarea','date','time','captcha'))) {
                                continue;
                            }

                            $min = isset($rule['min']) ? (int)$rule['min'] : 0;
                            $max = isset($rule['max']) ? (int)$rule['max'] : 1000;
                            
                            if (isset($this->_fields[$block][$id]['value'])) {
                                $value = $this->convertValueToText($this->_fields[$block][$id]['value']);

                                if (!$value && !$required) {
                                    $passed = true;
                                } else {
                                    if (utf8_strlen($value) >= $min && utf8_strlen($value) <= $max) {
                                        $passed = true;
                                    } else {
                                        $passed = false;
                                    }
                                }
                            }

                            $attrs[] = 'data-length-min="'.$min.'"';
                            $attrs[] = 'data-length-max="'.$max.'"';
                        break;
                        case 'regexp':
                            if (!in_array($this->_fields[$block][$id]['type'], array('text','email','tel','password','textarea','date','time','captcha'))) {
                                continue;
                            }
                            
                            if (isset($this->_fields[$block][$id]['value']) && !empty($rule['value'])) {
                                $value = $this->convertValueToText($this->_fields[$block][$id]['value']);

                                if (!$value && !$required) {
                                    $passed = true;
                                } else {
                                    if (preg_match('/'.$rule['value'].'/usi', $value)) {
                                        $passed = true;
                                    } else {
                                        $passed = false;
                                    }
                                }
                            }

                            if (!empty($rule['value'])) {
                                $attrs[] = 'data-regexp="'.$rule['value'].'"';
                            }
                        break;
                        case 'api':
                            $custom = !empty($fieldSettings['custom']) ? true : false;

                            if (empty($rule['method'])) {
                                continue;
                            }
                            
                            $method = $rule['method'];
                            
                            $filter = '';
                            $filterId = '';
                            $blockOfMaster = $block;

                            if (!empty($rule['filter'])) {
                                $masterObject = $this->getFieldObject($rule['filter']);
                                if ($this->_page == 'checkout') {
                                    if ($masterObject == 'customer' && $block != 'customer') {
                                        $blockOfMaster = 'customer';
                                    } elseif ($masterObject == 'address' && $blockOfMaster != 'address') {
                                        if (array_key_exists('payment_address', $this->_fields) && array_key_exists('shipping_address', $this->_fields)) {
                                            $blockOfMaster = 'payment_address';
                                        } elseif (array_key_exists('shipping_address', $this->_fields)) {
                                            $blockOfMaster = 'shipping_address';
                                        } elseif (array_key_exists('payment_address', $this->_fields)) {
                                            $blockOfMaster = 'payment_address';
                                        }                      
                                    }
                                }
                                if (isset($this->_fields[$blockOfMaster][$rule['filter']]['value'])) {
                                    $filter = $this->_fields[$blockOfMaster][$rule['filter']]['value'];
                                }

                                $filterId = $blockOfMaster.'_'.$rule['filter'];
                            }

                            if (isset($this->_fields[$block][$id]['value'])) {
                                $passed = $this->checkValueViaApi($custom, $method, $this->_fields[$block][$id]['value'], $filter);
                            }

                            if ($custom) {
                                $attrs[] = 'data-custom="1"';
                            }

                            $attrs[] = 'data-method="'.$method.'"';
                            $attrs[] = 'data-filter="'.$filterId.'"';
                            $attrs[] = 'data-filter-value="'.$filter.'"';
                        break;
                    }

                    if ($required) {
                        $attrs[] = 'data-required="true"';
                    }

                    $result[] = array(
                        'id'      => $key,
                        'passed'  => $passed,
                        'display' => $this->displayError(),
                        'attrs'   => implode(' ', $attrs),
                        'text'    => !empty($rule['errorText'][$langCode]) ? $rule['errorText'][$langCode] : 'error'
                    );
                }
            }
        }

        return $result;
    }

    public function validateFields() {
        $block = $this->getFieldsBlockName();

        foreach ($this->_resetFields[$block] as $id) {
            unset($this->_fields[$block][$id]);
        }

        foreach ($this->_fields[$block] as $field) {
            if (!$field['valid']) {
                return false;
            }
        }

        return true;
    }

    private function getMasterFieldValue($masterId) {
        $block = $this->getFieldsBlockName();
        $object = $this->getFieldObject($masterId);

        $masterValue = '';
        
        if ($this->isFieldUsed($block, $masterId)) {
            $masterValue = isset($this->_fields[$block][$masterId]['value']) ? $this->_fields[$block][$masterId]['value'] : '';
        } else {
            $tmpBlock = '';

            if ($this->_page == 'checkout') {
                $tmpBlock = $this->_block;
                if ($object == 'customer' && $this->_block != 'customer') {
                    $this->_block = 'customer';
                } elseif ($object == 'address' && $this->_block != 'payment_address' && $this->_block != 'shipping_address') {
                    if (array_key_exists('payment_address', $this->_fields) && array_key_exists('shipping_address', $this->_fields)) {
                        $this->_block = 'payment_address';
                    } elseif (array_key_exists('shipping_address', $this->_fields)) {
                        $this->_block = 'shipping_address';
                    } elseif (array_key_exists('payment_address', $this->_fields)) {
                        $this->_block = 'payment_address';
                    }                      
                }
            }           

            $masterValue = $this->getFieldValue($masterId);

            if ($tmpBlock) {
                $this->_block = $tmpBlock;
            }
        }

        return $masterValue;
    }

    private function isRowDisplayed($rowSettings) {
        $block = $this->getFieldsBlockName();

        if ($this->customer->isLogged() && !empty($rowSettings['hideForLogged'])) {
            return false;
        }

        if (!$this->customer->isLogged() && !empty($rowSettings['hideForGuest'])) {
            return false;
        }

        if (!empty($rowSettings['masterField'])) {
            $masterValue = $this->getMasterFieldValue($rowSettings['masterField']);
            if (is_array($masterValue) && is_array($rowSettings['displayWhen'])) {
                $result = true;
                foreach ($rowSettings['displayWhen'] as $key => $value) {
                    if (!$value) {
                        continue;
                    }
                    if (empty($masterValue[$key])) {
                        $result = false; 
                    }
                }
                return $result;
            } else {
                if ($masterValue !== '' && !empty($rowSettings['displayWhen'][$masterValue])) {
                    return true;
                } else {
                    return false;
                }
            }            
        }

        return true;
    }

    private function isFieldRequired($rowSettings) {
        $block = $this->getFieldsBlockName();
        
        if (empty($rowSettings['required'])) {
            return false;
        } elseif ($rowSettings['required'] == 1) {
            return true;
        } elseif ($rowSettings['required'] == 2 && !empty($rowSettings['masterField'])) {
            $masterValue = $this->getMasterFieldValue($rowSettings['masterField']);
            if (is_array($masterValue) && is_array($rowSettings['requireWhen'])) {
                $result = true;
                foreach ($rowSettings['requireWhen'] as $key => $value) {
                    if (!$value) {
                        continue;
                    }
                    if (empty($masterValue[$key])) {
                        $result = false; 
                    }
                }
                return $result;
            } else {
                if ($masterValue !== '' && !empty($rowSettings['requireWhen'][$masterValue])) {
                    return true;
                } else {
                    return false;
                }
            } 
        }

        return false;
    }

    private function getFieldObject($id) {
        $block = $this->getFieldsBlockName();
        $fieldSettings = $this->getFieldSettings($id);

        $object = '';

        if (!$fieldSettings['custom']) {
            if (!empty($fieldSettings['objects']['customer']) && !empty($fieldSettings['objects']['address'])) {
                if ($this->_page == 'edit') {
                    return 'customer';
                } elseif ($this->_page == 'address') {
                    return 'address';
                } elseif ($this->_page == 'checkout') {
                    if ($block == 'customer') {
                        return 'customer';
                    }
                    return 'address';
                }
            } elseif (!empty($fieldSettings['objects']['address'])) {
                $object = 'address';
            } elseif (!empty($fieldSettings['objects']['customer'])) {
                $object = 'customer';
            }
        } else {
            $object = $fieldSettings['object'];
        }

        return $object;
    }

    private function usePostValue($id) {
        $block  = $this->getFieldsBlockName();
        $object = $this->getFieldObject($id);

        if (isset($this->request->post['ignore_post'])) {
            return false;
        }

        $addressChanged = false;

        if ($this->customer->isLogged() && $this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post[$block]['address_id']) && isset($this->request->post[$block]['current_address_id']) && $this->request->post[$block]['address_id'] != $this->request->post[$block]['current_address_id']) {
            $addressChanged = true;
        }

        if ($id == 'register' && $this->_page == 'register') {
            return false;
        }

        return $id == 'address_id' || $object != 'address' || ($object == 'address' && !$addressChanged);
    }

    protected function replacePostValue($block, $id, $value) {
        $this->session->data['simple'][$block]['override_'.$id] = $value;

        if (isset($this->_fields[$block][$id])) {
            $this->_fields[$block][$id]['value'] = $value;
        }
    }

    private function overridePostValue($id) {
        $block  = $this->getFieldsBlockName();

        if (isset($this->session->data['simple'][$block]['override_'.$id])) {
            $this->request->post[$block][$id] = $this->session->data['simple'][$block]['override_'.$id];
            unset($this->session->data['simple'][$block]['override_'.$id]);
        }
    }

    protected function getFieldValue($id) {
        $block         = $this->getFieldsBlockName();
        $fieldSettings = $this->getFieldSettings($id);
        $object        = $this->getFieldObject($id);

        $this->overridePostValue($fieldSettings['id']);

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post[$block][$fieldSettings['id']]) && $this->usePostValue($fieldSettings['id'])) {
            $value = !is_array($this->request->post[$block][$fieldSettings['id']]) ? trim($this->request->post[$block][$fieldSettings['id']]) : $this->request->post[$block][$fieldSettings['id']];
            
            if ($fieldSettings['type'] != 'password' && $object != 'payment') {
                $this->session->data['simple'][$block][$id] = $value;
            }
            
            return $value;
        } else {
            if ($this->customer->isLogged()) {
                if ($object && !empty($this->_objects[$object]) && isset($this->_objects[$object][$id]) && $id != 'password') {
                    $value = $this->_objects[$object][$id];

                    if ($fieldSettings['type'] == 'checkbox') {
                        $tmp = explode(',', $value);
                        $value = array();
                        foreach ($tmp as $v) {
                            $value[$v] = 1;
                        }
                    }

                    return $value;
                }
            } elseif (isset($this->session->data['simple'][$block][$id])) {
                return $this->session->data['simple'][$block][$id];
            }

            return $this->getFieldDefaultValue($id);
        }

        return '';
    }

    protected function getFieldDefaultValue($id) {
        $block         = $this->getFieldsBlockName();
        $fieldSettings = $this->getFieldSettings($id);

        // fix for some fields
        if ($this->_page == 'register') {
            if ($id == 'register') {
                return 1;
            }
        }

        if (!empty($fieldSettings['default']['source']) && $fieldSettings['default']['source'] == 'saved' && isset($fieldSettings['default']['saved'])) {
            return $fieldSettings['default']['saved'];
        }

        if (!empty($fieldSettings['default']['source']) && $fieldSettings['default']['source'] == 'model' && !empty($fieldSettings['default']['method'])) {
            $custom = !empty($fieldSettings['custom']) ? true : false;
            $method = $fieldSettings['default']['method'];
            
            $filter = '';
            if (!empty($fieldSettings['default']['filter'])) {
                $filter = $this->getMasterFieldValue($fieldSettings['default']['filter']);
            }

            return $this->getValueFromApi($custom, $method, $filter);
        }

        return '';
    }

    protected function convertValueToText($value) {
        if (is_array($value)) {
            $tmp = array();

            foreach ($value as $key => $value) {
                if ($value) {
                    $tmp[] = $key;
                }
            }

            return implode(',', $tmp);
        }

        return $value;
    }

    private function explodeValues($text) {
        $result = array();
        $rows = explode(';', $text);

        foreach ($rows as $row) {
            $pair = explode('=', $row);
            if (count($pair) == 2) {
                $result[] = array(
                    'id'   => trim($pair[0]),
                    'text' => trim($pair[1])
                );
            }
        }

        return $result;
    }

    private function getFieldValues($id) {
        $fieldSettings = $this->getFieldSettings($id);
        $block = $this->getFieldsBlockName();
                
        if (!empty($fieldSettings['values']['source']) && $fieldSettings['values']['source'] == 'saved' && !empty($fieldSettings['values']['saved'])) {
            $langCode = $this->getCurrentLanguageCode();

            $valuesText = !empty($fieldSettings['values']['saved'][$langCode]) ? $fieldSettings['values']['saved'][$langCode] : '';

            return $this->explodeValues($valuesText);
        }

        if (!empty($fieldSettings['values']['source']) && $fieldSettings['values']['source'] == 'model' && !empty($fieldSettings['values']['method'])) {
            $custom = !empty($fieldSettings['custom']) ? true : false;
            $method = $fieldSettings['values']['method'];

            $filter = '';
            if (!empty($fieldSettings['values']['filter'])) {
                $filter = $this->getMasterFieldValue($fieldSettings['values']['filter']);
            }

            $values = $this->getValueFromApi($custom, $method, $filter);

            return !empty($values) ? $values : array();
        }

        return array();
    }

    private function getFieldMask($id) {
        $fieldSettings = $this->getFieldSettings($id);
        $block = $this->getFieldsBlockName();
                
        if (!empty($fieldSettings['mask']['source']) && $fieldSettings['mask']['source'] == 'saved' && !empty($fieldSettings['mask']['saved'])) {
            return $fieldSettings['mask']['saved'];
        }

        if (!empty($fieldSettings['mask']['source']) && $fieldSettings['mask']['source'] == 'model' && !empty($fieldSettings['mask']['method'])) {
            $custom = !empty($fieldSettings['custom']) ? true : false;
            $method = $fieldSettings['mask']['method'];
            
            $filter = '';
            if (!empty($fieldSettings['mask']['filter'])) {
                $filter = $this->getMasterFieldValue($fieldSettings['mask']['filter']);
            }

            return $this->getValueFromApi($custom, $method, $filter);
        }

        return '';
    }

    private function getValueFromApi($custom, $method, $filter) {
        if (!$custom) {
            $this->load->model('tool/simpleapimain');

            if (method_exists($this->model_tool_simpleapimain, $method)) {
                return $this->model_tool_simpleapimain->{$method}($filter);
            }
        } else {
            $this->load->model('tool/simpleapicustom');

            if (method_exists($this->model_tool_simpleapicustom, $method)) {
                return $this->model_tool_simpleapicustom->{$method}($filter);
            }
        }

        return '';
    }

    private function checkValueViaApi($custom, $method, $value, $filter) {
        if (!$custom) {
            $this->load->model('tool/simpleapimain');

            if (method_exists($this->model_tool_simpleapimain, $method)) {
                return $this->model_tool_simpleapimain->{$method}($value, $filter);
            }
        } else {
            $this->load->model('tool/simpleapicustom');

            if (method_exists($this->model_tool_simpleapicustom, $method)) {
                return $this->model_tool_simpleapicustom->{$method}($value, $filter);
            }
        }

        return '';
    }

    public function getCustomFields($block, $object) {
        $result = array();

        $blocks = is_array($block) ? $block : array($block);

        $fields = $this->getSettingValueDirectly('', '', 'fields');

        $savedInfo = array();
        if ($this->customer->isLogged() && ($object == 'address' || $object == 'customer')) {
            $objectId = 0;
            
            if ($object == 'address') {
                $objectId = $this->getCurrentAddressId();
            } elseif ($object == 'customer') {
                $objectId = $this->customer->getId();
            }

            if ($objectId) {
                $savedInfo = $this->getCustomFieldsFromDataBase($object, $objectId);
            }
        }

        foreach ($fields as $fieldInfo) {
            if (!empty($fieldInfo['custom'])) {
                if ($object == 'order') {
                    if ($fieldInfo['object'] == 'address') {
                        $result['payment_'.$fieldInfo['id']] = '';
                        $result['shipping_'.$fieldInfo['id']] = '';
                    } else {
                        $result[$fieldInfo['id']] = '';
                    }
                } else {
                    if ($fieldInfo['object'] == $object) {
                        $result[$fieldInfo['id']] = isset($savedInfo[$fieldInfo['id']]) ? $savedInfo[$fieldInfo['id']] : '';
                    }
                }
            }
        }

        foreach ($this->_fields as $set => $fields) {
            if (!in_array($set, $blocks)) {
                continue;
            }
            
            if (!empty($fields) && is_array($fields)) {
                foreach ($fields as $id => $fieldInfo) {
                    if (!empty($fieldInfo['custom'])) {
                        if ($object == 'order') {
                            if ($fieldInfo['object'] == 'address') {
                                if (($set == 'payment_address' || $set == 'payment') && isset($result['payment_'.$id])) {
                                    $result['payment_'.$id] = $this->convertValueToText($fieldInfo['value']);
                                } elseif (($set == 'shipping_address' || $set == 'shipping') && isset($result['shipping_'.$id])) {
                                    $result['shipping_'.$id] = $this->convertValueToText($fieldInfo['value']);
                                }
                            } else {
                                if (isset($result[$id])) {
                                    $result[$id] = $this->convertValueToText($fieldInfo['value']);
                                }
                            }
                        } else {
                            if ($fieldInfo['object'] == $object && isset($result[$id])) {
                                $result[$id] = $this->convertValueToText($fieldInfo['value']);
                            }
                        }
                    }
                }
            }
        }

        return $result;
    }

    public function saveCustomFields($block, $object, $objectId) {
        $data = $this->getCustomFields($block, $object);
        
        if (!empty($data)) {
            $tmp = array();

            $tmp[] = '`'.$object.'_id`=\''.$objectId.'\'';

            foreach ($data as $key => $value) {
                $tmp[] = '`'.$key.'`=\''.$value.'\'';
            }

            $text = implode(',', $tmp);

            $this->db->query('INSERT INTO `' . DB_PREFIX . $object . '_simple_fields` SET ' . $text . ' ON DUPLICATE KEY UPDATE ' . $text);
        }
    }

    public function getCustomFieldsFromDataBase($object, $objectId) {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . $object . '_simple_fields` WHERE `'.$object.'_id` = \'' . $objectId . '\' LIMIT 1');
        
        return $query->row;
    }

    private function camelize($text) {
        if (strpos($text, '_')) {
            $tmp = explode('_', $text);
            $result = array();

            $first = true;
            foreach ($tmp as $part) {
                $lower =  strtolower($part);
                
                if (!$first) {
                    $lower = strtoupper(substr($lower, 0, 1)).utf8_substr($lower, 1);
                } else {
                    $first = false;
                }

                $result[] = $lower;
            }

            return implode('', $result);
        } else {
            return $text;
        }
    }

    public function isFieldUsed($block, $id) {
        if (!empty($this->_displayedRows[$block]) && is_array($this->_displayedRows[$block]) && array_key_exists('field_'.$id, $this->_displayedRows[$block])) {
            return true;
        }

        return false;
    }

    public function getCustomerInfoByEmail($email) {
        $customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(strtolower($email)) . "' AND status = '1'");
        
        if ($customer_query->num_rows) {
            return array(
                'customer_id'       => $customer_query->row['customer_id'], 
                'address_id'        => $customer_query->row['address_id'],
                'customer_group_id' => $customer_query->row['customer_group_id']
            );
        }

        return array(
            'customer_id'       => 0, 
            'address_id'        => 0,
            'customer_group_id' => 0
        );
    }
}