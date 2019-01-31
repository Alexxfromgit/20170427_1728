<?php
/*
@author  Dmitriy Kubarev
@link    http://www.simpleopencart.com
@link    http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ModelToolSimpleGeo extends Model {
    static $geo = false;
    static $ip_checked = false;
    
    const SIMPLE_GEO_OWN               = 1;
    const SIMPLE_GEO_MAXMIND_EXTENSION = 2;
    const SIMPLE_GEO_MAXMIND_TABLE     = 3;

    public function getGeoDataByIp($mode) {
        
        if (!ModelToolSimpleGeo::$ip_checked) {
            if ($mode == self::SIMPLE_GEO_OWN) {
                ModelToolSimpleGeo::$geo = $this->getGeoIpBySimpleOpenCart();
            } elseif ($mode == self::SIMPLE_GEO_MAXMIND_EXTENSION) {
                ModelToolSimpleGeo::$geo = $this->getGeoIpByMaxMind();
            } elseif ($mode == self::SIMPLE_GEO_MAXMIND_TABLE) {
                ModelToolSimpleGeo::$geo = $this->getGeoIpByMaxMindFromDataBase();
            } else {
                ModelToolSimpleGeo::$geo = array(
                    'country_id' => '',
                    'zone_id' => '',
                    'city' => '',
                    'postcode' => ''
                );
            }
            
            ModelToolSimpleGeo::$ip_checked = true;
        }
        
        return ModelToolSimpleGeo::$geo;
    }
    
    private function getGeoIpByMaxMind() {
        $ip = isset($this->request->server['HTTP_X_FORWARDED_FOR']) && $this->request->server['HTTP_X_FORWARDED_FOR'] ? $this->request->server['HTTP_X_FORWARDED_FOR'] : 0;
        $ip = $ip ? $ip : $this->request->server['REMOTE_ADDR'];
        
        $part = explode(".", $ip);
        $ip_int = 0;
        if (count($part) == 4) {
            $ip_int = $part[3] + 256 * ($part[2] + 256 * ($part[1] + 256 * $part[0]));
        }
        
        $geo = $this->cache->get('maxmind.' . $ip_int);
            
        if (!isset($geo)) {
        
            if (function_exists('apache_note') && $code = apache_note('GEOIP_COUNTRY_CODE')) {
                if ($country_id = $this->getCountryIdbyISO($code)) {
                    $geo = array(
                        'country_id' => $country_id,
                        'zone_id' => '',
                        'city' => '',
                        'postcode' => '',
                    );
                }
            } else if (function_exists('geoip_record_by_name') && $code = geoip_record_by_name($ip)) {
                if ($country_id = $this->getCountryIdbyISO($code['country_code'])) {
                    $geo = array(
                        'country_id' => $country_id,
                        'zone_id' => '',
                        'city' => '',
                        'postcode' => '',
                    );
                }
            }
        
        }
        
        $this->cache->set('maxmind.' . $ip_int, isset($geo) ? $geo : false);
        
        return $geo;
    }
    
    private function getGeoIpByMaxMindFromDataBase() {
    
        $ip = isset($this->request->server['HTTP_X_FORWARDED_FOR']) && $this->request->server['HTTP_X_FORWARDED_FOR'] ? $this->request->server['HTTP_X_FORWARDED_FOR'] : 0;
        $ip = $ip ? $ip : $this->request->server['REMOTE_ADDR'];
        
        $part = explode(".", $ip);
        $ip_int = 0;
        if (count($part) == 4) {
            $ip_int = $part[3] + 256 * ($part[2] + 256 * ($part[1] + 256 * $part[0]));
        }
        
        $geo = $this->cache->get('maxmind.' . $ip_int);
            
        if (!isset($geo)) {
            $query = $this->db->query("SELECT m.iso_code_2, c.country_id FROM maxmind_geo_country m LEFT JOIN " . DB_PREFIX . "country c ON m.iso_code_2 = c.iso_code_2 AND c.status = 1 WHERE start <= '" . $ip_int . "' AND end >= '" . $ip_int . "'");
            
            if ($query->row) {
                $geo = array(
                        'country_id' => $query->row['country_id'],
                        'zone_id' => '',
                        'city' => '',
                        'postcode' => '',
                    );
            }
        }
        
        $this->cache->set('maxmind.' . $ip_int, isset($geo) ? $geo : false);
        
        return $geo;
    }
    
    private function getCountryIdByISO($iso) {
    
        if (!is_string($iso) && strlen($iso) != 2) {
            return false;
        }
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE iso_code_2 = '" . $iso . "' AND status = '1'");

        if ($query->row) {
            return $query->row['country_id'];
        } else {
            return false;
        }
    }
    
    private function getGeoIpBySimpleOpenCart() {
        
        $geo_tables = $this->cache->get('geo_tables');
        
        if (isset($geo_tables) && !$geo_tables) {
            return false;
        }
        
        if (!isset($geo_tables)) {
            $query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "geo_ip'");
            
            if (!$query->rows) {
                $this->cache->set('geo_tables', false);
                return false;
            }
            
            $query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "geo'");
            
            if (!$query->rows) {
                $this->cache->set('geo_tables', false);
                return false;
            }
            
            $this->cache->set('geo_tables', true);
        }
        
        $ip = isset($this->request->server['HTTP_X_FORWARDED_FOR']) && $this->request->server['HTTP_X_FORWARDED_FOR'] ? $this->request->server['HTTP_X_FORWARDED_FOR'] : 0;
        $ip = $ip ? $ip : $this->request->server['REMOTE_ADDR'];
                
        $part = explode(".", $ip);
        if (count($part) == 4) {
            $ip = $part[3] + 256 * ($part[2] + 256 * ($part[1] + 256 * $part[0]));
        }
            
        $geo = $this->cache->get('geo.' . $ip);
            
        if (!isset($geo)) {
        
            $query = $this->db->query("SELECT geo_id FROM " . DB_PREFIX . "geo_ip WHERE start <= '" . $ip . "' AND end >= '" . $ip . "'");
  
            $geo_id = 0;
            if ($query->num_rows) {
                $geo_id = $query->row['geo_id'];
            }
            
            if ($geo_id) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo WHERE id = '" . (int)$geo_id . "'");
            
                if ($query->num_rows) {
                    $geo_data = $query->row;
                
                    $this->load->model('localisation/zone');
                    $this->load->model('localisation/country');
                
                    $zone = $this->model_localisation_zone->getZone($geo_data['zone_id']);            
                    $country = $this->model_localisation_country->getCountry($zone['country_id']);
                        
                    $geo = array(
                        'country_id' => $zone['country_id'],
                        'zone_id' => $geo_data['zone_id'],
                        'city' => $geo_data['name'],
                        'postcode' => $geo_data['postcode']
                    );
                }
            }
        }
        
        $this->cache->set('geo.' . $ip, isset($geo) ? $geo : false);
        
        return $geo;
    }
    
    public function getGeoList($city) {
    
        $city = trim($city);
        
        $key = md5($city);
                
        $geo_data = $this->cache->get('geo.' . $key);
    
        if (!$geo_data) {
        
            $sql = "SELECT g.id,g.full_name,g.name,g.postcode,z.zone_id,z.country_id FROM " . DB_PREFIX . "geo g LEFT JOIN " . DB_PREFIX . "zone z ON g.zone_id = z.zone_id WHERE g.name LIKE '" . $this->db->escape($city) . "%' AND g.postcode <> '' ORDER BY population DESC LIMIT 100";
        
            $geo_data = array();
                        
            $query = $this->db->query($sql);

            $sort_order = array();
        
            foreach ($query->rows as $result) {
                $geo_data[$result['id']] = array(
                    'id' => $result['id'],
                    'city' => $result['name'],
                    'zone_id' => $result['zone_id'],
                    'country_id' => $result['country_id'],
                    'postcode' => $result['postcode'],
                    'full' => $result['full_name']
                );

                $sort_order[$result['id']] = utf8_strlen($result['name']);
            }
            
            if (!$query->num_rows) {
                $geo_data[0] = array(
                    'id' => 0,
                    'city' => '',
                    'zone_id' => 0,
                    'country_id' => 0,
                    'postcode' => '',
                    'full' => 'Совпадений не найдено. Проверьте написание.'
                );

                $sort_order[0] = 0;
            }

            array_multisort($sort_order, SORT_ASC, $geo_data); 
            
            $this->cache->set('geo.' . $key, $geo_data);
        }
        
        return array_slice($geo_data, 0, 15);
    }
}
?>