<?php
class ModelModuleYmmfilter extends Model {
	public function getMakes(){
		$query = $this->db->query("SELECT id, make FROM `" . DB_PREFIX . "make` ORDER BY make");
		
		return $query->rows;
	}
	
	public function getModels($make_id){
		$query = $this->db->query("SELECT p2y.model_id as id, mdl.model FROM `" . DB_PREFIX . "product_to_ymm` p2y LEFT JOIN `" . DB_PREFIX . "model` mdl ON mdl.id = p2y.model_id WHERE p2y.make_id = '" . (int)$make_id . "' GROUP BY p2y.model_id ORDER BY model");
		
		return $query->rows;
	}
	
	public function getEngines($make_id, $model_id){
		$query = $this->db->query("SELECT p2y.engine_id as id, e.engine FROM `" . DB_PREFIX . "product_to_ymm` p2y LEFT JOIN `" . DB_PREFIX . "engine` e ON e.id = p2y.engine_id WHERE p2y.make_id = '" . (int)$make_id . "' AND p2y.model_id = '" . (int)$model_id . "' GROUP BY p2y.engine_id ORDER BY e.engine");
		
		return $query->rows;
	}
	
	public function getYears($make_id, $model_id, $engine_id = null){
		if(!empty($engine_id)) {
			$engine_where = " AND engine_id = '" . $engine_id . "'";
		} else {
			$engine_where = '';
		}

		$query = $this->db->query("SELECT MIN(begin_year) as start, MAX(end_year) as end FROM `" . DB_PREFIX . "product_to_ymm` WHERE make_id = '" . (int)$make_id . "' AND model_id = '" . (int)$model_id . "'" . $engine_where . " GROUP BY make_id, model_id");
		
		if($this->config->get('ymmfilter_year_sort') == 'desc') {
			$years = range($query->row['end'], $query->row['start']);
		} else {
			$years = range($query->row['start'], $query->row['end']);
		}
		
		return $years;
	}
	
	public function getMakeName($make_id){
		$query = $this->db->query("SELECT make FROM `" . DB_PREFIX . "make` WHERE id = '" . (int)$make_id . "'");
		
		return $query->row['make'];
	}
	
	public function getModelName($model_id){
		$query = $this->db->query("SELECT model FROM `" . DB_PREFIX . "model` WHERE id = '" . (int)$model_id . "'");
		
		return $query->row['model'];
	}
	
	public function getEngineName($engine_id){
		$query = $this->db->query("SELECT `engine` FROM `" . DB_PREFIX . "engine` WHERE id = '" . (int)$engine_id . "'");
		
		return $query->row['engine'];
	}
	
	public function getVehicles($product_id){
		$check = $this->db->query("SELECT universal FROM `" . DB_PREFIX . "product` WHERE product_id = '" . (int)$product_id . "'");
		
		if(empty($check->row)) {
            return false;
        } elseif($check->row['universal'] == 1){
			return 'universal';
		} else {
			$query = $this->db->query("SELECT ma.make, mo.model, en.engine, p2y.begin_year, p2y.end_year from `" . DB_PREFIX . "product_to_ymm` p2y LEFT JOIN `" . DB_PREFIX . "make` ma ON ma.id = p2y.make_id LEFT JOIN `" . DB_PREFIX . "model` mo ON mo.id = p2y.model_id LEFT JOIN `" . DB_PREFIX . "engine` en ON en.id = p2y.engine_id WHERE p2y.product_id = '" . (int)$product_id . "' ORDER BY ma.make ASC, mo.model ASC, en.engine ASC, p2y.begin_year ASC");
			
			return $query->rows;
		}
	}
}
?>