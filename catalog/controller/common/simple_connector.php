<?php  
class ControllerCommonSimpleConnector extends Controller {
    public function index() {
        $custom = isset($this->request->get['custom']) ? true : false;
        $method = isset($this->request->get['method']) ? trim($this->request->get['method']) : '';
        $filter = isset($this->request->get['filter']) ? trim($this->request->get['filter']) : '';

        if (!$method) {
            exit;
        }

        if (!$custom) {
            $this->load->model('tool/simpleapimain');

            if (method_exists($this->model_tool_simpleapimain, $method)) {
                $this->response->setOutput(json_encode($this->model_tool_simpleapimain->{$method}($filter)));
            }
        } else {
            $this->load->model('tool/simpleapicustom');

            if (method_exists($this->model_tool_simpleapicustom, $method)) {
                $this->response->setOutput(json_encode($this->model_tool_simpleapicustom->{$method}($filter)));
            }
        }
    }

    public function validate() {
        $custom = isset($this->request->get['custom']) ? true : false;
        $method = isset($this->request->get['method']) ? trim($this->request->get['method']) : '';
        $filter = isset($this->request->get['filter']) ? trim($this->request->get['filter']) : '';
        $value = isset($this->request->get['value']) ? trim($this->request->get['value']) : '';

        if (!$method) {
            exit;
        }

        if (!$custom) {
            $this->load->model('tool/simpleapimain');

            if (method_exists($this->model_tool_simpleapimain, $method)) {
                $this->response->setOutput($this->model_tool_simpleapimain->{$method}($value, $filter) ? "valid" : "invalid");
            }
        } else {
            $this->load->model('tool/simpleapicustom');

            if (method_exists($this->model_tool_simpleapicustom, $method)) {
                $this->response->setOutput($this->model_tool_simpleapicustom->{$method}($value, $filter) ? "valid" : "invalid");
            }
        }
    }

    public function zone() {
        $output = '<option value="">' . $this->language->get('text_select') . '</option>';

        $this->load->model('localisation/zone');

        $results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);

        foreach ($results as $result) {
            $output .= '<option value="' . $result['zone_id'] . '"';

            if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
                $output .= ' selected="selected"';
            }

            $output .= '>' . $result['name'] . '</option>';
        }

        if (!$results) {
            $output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
        }

        $this->response->setOutput($output);
    }

    public function geo() {
        $this->load->model('tool/simplegeo');

        $term = $this->request->get['term'];

        $this->response->setOutput(json_encode($this->model_tool_simplegeo->getGeoList($term)));
    }

    public function upload() {
        $this->language->load('checkout/simplecheckout');
        
        $json = array();
        
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if (!empty($this->request->files['file']['name'])) {
                $filename = html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8');
                
                if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
                    $json['error'] = $this->language->get('error_filename');
                }       
                
                // Allowed file extension types
                $allowed = array();
                
                $filetypes = explode("\n", $this->config->get('config_file_extension_allowed'));
                
                foreach ($filetypes as $filetype) {
                    $allowed[] = trim($filetype);
                }
                
                if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }   
                
                // Allowed file mime types      
                $allowed = array();
                
                $filetypes = explode("\n", $this->config->get('config_file_mime_allowed'));
                
                foreach ($filetypes as $filetype) {
                    $allowed[] = trim($filetype);
                }
                                
                if (!in_array($this->request->files['file']['type'], $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }
                            
                if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
                    $json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
                }
            } else {
                $json['error'] = $this->language->get('error_upload');
            }
        
            if (!isset($json['error'])) {
                if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
                    $file = basename($filename) . '.' . md5(mt_rand());
                    
                    $json['file'] = $file;
                    
                    move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
                }
            }   
        }
        
        $this->response->setOutput(json_encode($json));
    }
}
?>