<?php 

class ModelModulePriceControl extends Model {

	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

		return $query->rows;
	}
	
	public function updatePrices($categories,$action,$num,$unit) {
		$sql1="SELECT b.product_id,b.price FROM `".DB_PREFIX."product_to_category` a INNER JOIN `".DB_PREFIX."product` b ON a.product_id=b.product_id WHERE a.category_id IN('".implode($categories,"','")."')";
		$query1=$this->db->query($sql1);
		$old_prices=$query1->rows;
		$string='';
		if (!empty($old_prices)) {
			foreach ($old_prices as $rec) {
			switch($unit) {
				case 'percent':
					$new_price_str="$rec[price] $action (($num*$rec[price])/100)";
					break;
				case 'number':
					$new_price_str="$rec[price] $action $num";
					break;
			}
			
				$price=eval("return $new_price_str;"); 
				$string.="('".$rec['product_id']."','".$price."'),";
			}
			$string=chop($string, ',');
			$this->db->query("CREATE TEMPORARY TABLE new_prices (product_id INT, price DECIMAL(15,4)); ");
			$sql="INSERT INTO new_prices VALUES ".$string;
			$this->db->query($sql);
			return $this->db->query("UPDATE new_prices a,".DB_PREFIX."product b SET b.price=a.price WHERE b.product_id=a.product_id");
		}
	}

}

?>