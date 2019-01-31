<?php
class ModelCatalogCategory extends Model {
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		
		return $query->row;
	}
	
	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

		if(isset($this->session->data['ymm'])){
			$available_categories = $this->getCategoriesWithProducts();
			$data = $query->rows;
			foreach($data as $key => &$value){
				if(!in_array($value['category_id'], $available_categories)){
					unset($data[$key]);
				}
			}
			return $data;
		} else {
			return $query->rows;
		}
	}
	
	public function getCategoryFilters($category_id) {
		$implode = array();
		
		$query = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");
		
		foreach ($query->rows as $result) {
			$implode[] = (int)$result['filter_id'];
		}
		
		
		$filter_group_data = array();
		
		if ($implode) {
			$filter_group_query = $this->db->query("SELECT DISTINCT f.filter_group_id, fgd.name, fg.sort_order FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_group fg ON (f.filter_group_id = fg.filter_group_id) LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY f.filter_group_id ORDER BY fg.sort_order, LCASE(fgd.name)");
			
			foreach ($filter_group_query->rows as $filter_group) {
				$filter_data = array();
				
				$filter_query = $this->db->query("SELECT DISTINCT f.filter_id, fd.name FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND f.filter_group_id = '" . (int)$filter_group['filter_group_id'] . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY f.sort_order, LCASE(fd.name)");
				
				foreach ($filter_query->rows as $filter) {
					$filter_data[] = array(
						'filter_id' => $filter['filter_id'],
						'name'      => $filter['name']			
					);
				}
				
				if ($filter_data) {
					$filter_group_data[] = array(
						'filter_group_id' => $filter_group['filter_group_id'],
						'name'            => $filter_group['name'],
						'filter'          => $filter_data
					);	
				}
			}
		}
		
		return $filter_group_data;
	}
				
	public function getCategoryLayoutId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return $this->config->get('config_layout_category');
		}
	}
					
private function getCategoriesWithProducts(){
		$filter_query = "(p2y.make_id = '" . $this->session->data['ymm']['make'] . "'";
		if(!empty($this->session->data['ymm']['model'])){
			$filter_query .= " AND p2y.model_id = '" . $this->session->data['ymm']['model'] . "'";
		}
		if(!empty($this->session->data['ymm']['engine'])){
			$filter_query .= " AND p2y.engine_id = '" . $this->session->data['ymm']['engine'] . "'";
		}
		if(!empty($this->session->data['ymm']['year'])){
			$filter_query .= " AND p2y.begin_year <= '" . $this->session->data['ymm']['year'] . "' AND p2y.end_year >= '" . $this->session->data['ymm']['year'] . "'";
		}
		$filter_query .= ") OR p.universal = '1'";
		$query = $this->db->query("SELECT DISTINCT category_id FROM " . DB_PREFIX . "product_to_category p2c LEFT JOIN " . DB_PREFIX . "product p on p.product_id = p2c.product_id LEFT JOIN " . DB_PREFIX . "product_to_ymm p2y on p2y.product_id = p.product_id WHERE p.status = '1' AND (" . $filter_query . ")");
		
		$data = array();
		$results = array();
		foreach($query->rows as $row) {
			$results[] = $row['category_id'];
		}
		
		if(strcmp(VERSION, '1.5.5.1') >=0) { //v1.5.5.1 or later
			$data = $this->addPath($results);
		} else {
			$parents = array();
			foreach($results as $result) {
				$parents = array_merge($parents, $this->getCategoryParents($result));
			}
			$data = array_merge($results, $parents);
		}
		return $data;
	}
	
	private function addPath($results) {
		$csv = implode(',', $results);
		$query = $this->db->query('SELECT DISTINCT path_id from ' . DB_PREFIX . 'category_path WHERE category_id IN (' . $csv . ')');
		foreach($query->rows as $row) {
			$data[] = $row['path_id'];
		}
		return $data;
	}

	private function getCategoryParents($category_id) {
		$categories = array();

		$parent_query = $this->db->query("SELECT parent_id from " . DB_PREFIX . "category WHERE category_id = '" . $category_id . "'");

		$parent_id = $parent_query->row['parent_id'];
		if($parent_id != 0) {
			$categories[] = $parent_id;
			$parents = $this->getCategoryParents($parent_id);
			if($parents) {
				$categories = array_merge($parents, $categories);
			}
		}

		return $categories;
	}
	
	public function getTotalCategoriesByCategoryId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		
		return $query->row['total'];
	}
	
	public function getCategoriesByParentId($category_id) {
		$category_data = array();

		$categories = $this->getCategories((int)$category_id);

		foreach ($categories as $category) {
			$category_data[] = $category['category_id'];

			$children = $this->getCategoriesByParentId($category['category_id']);

			if ($children) {
				$category_data = array_merge($children, $category_data);
			}
		}

		return $category_data;
	}
	
}
?>