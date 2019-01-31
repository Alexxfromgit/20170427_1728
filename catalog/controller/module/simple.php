<?php  
class ControllerModuleSimple extends Controller {
    protected function index($setting) {
        $route = '';

        if (!empty($setting['page'])) {
            if ($setting['page'] == 'checkout') {
                $route = 'checkout/simplecheckout';
            } elseif ($setting['page'] == 'register') {
                $route = 'account/simpleregister';
            }
        }

        if ($route && empty($setting['scripts'])) {
            $this->data['simple_content'] = $this->getChild($route, array('module' => true, 'group' => (!empty($setting['settingsId']) ? $setting['settingsId'] : 0)));

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/simple.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/module/simple.tpl';
            } else {
                $this->template = 'default/template/module/simple.tpl';
            }
            
            $this->render();
        } elseif (!empty($setting['scripts'])) {
            if ($route == 'checkout/simplecheckout') {
                $this->load->library('simple/simplecheckout');
                SimpleCheckout::getInstance($this->registry, (!empty($setting['settingsId']) ? $setting['settingsId'] : 0));
            } elseif ($route == 'account/simpleregister') {
                $this->load->library('simple/simpleregister');
                SimpleRegister::getInstance($this->registry);
            }
        }
    }
}
?>