<?php
// News Module for Opencart v1.5.5, modified by villagedefrance (contact@villagedefrance.net)

class ModelCatalogNews extends Model {

	public function addNews($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "news SET status = '" . (int)$data['status'] . "', date_added = now()");
	
		$news_id = $this->db->getLastId();
	
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "news SET image = '" . $this->db->escape($data['image']) . "' WHERE news_id = '" . (int)$news_id . "'");
		}
	
		if (isset($data['date_added'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "news SET date_added = '" . $this->db->escape($data['date_added']) . "' WHERE news_id = '" . (int)$news_id . "'");
		}
	
		foreach ($data['news_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "news_description SET news_id = '" . (int)$news_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
	
		if (isset($data['news_store'])) {
			foreach ($data['news_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_to_store SET news_id = '" . (int)$news_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'news_id=" . (int)$news_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('news');
	}

	public function editNews($news_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "news SET status = '" . (int)$data['status'] . "' WHERE news_id = '" . (int)$news_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "news SET image = '" . $this->db->escape($data['image']) . "' WHERE news_id = '" . (int)$news_id . "'");
		}
		
		if (isset($data['date_added'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "news SET date_added = '" . $this->db->escape($data['date_added']) . "' WHERE news_id = '" . (int)$news_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_description WHERE news_id = '" . (int)$news_id . "'");
	
		foreach ($data['news_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "news_description SET news_id = '" . (int)$news_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_to_store WHERE news_id = '" . (int)$news_id . "'");
	
		if (isset($data['news_store'])) {		
			foreach ($data['news_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_to_store SET news_id = '" . (int)$news_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'news_id=" . (int)$news_id . "'");
	
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'news_id=" . (int)$news_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('news');
	}

	public function deleteNews($news_id) { 
		$this->db->query("DELETE FROM " . DB_PREFIX . "news WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_description WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_to_store WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'news_id=" . (int)$news_id . "'");
	
		$this->cache->delete('news');
	}

	public function getNewsStory($news_id) { 
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'news_id=" . (int)$news_id . "') AS keyword FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) WHERE n.news_id = '" . (int)$news_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
		return $query->row;
	}

	public function getNewsDescriptions($news_id) { 
		$news_description_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_description WHERE news_id = '" . (int)$news_id . "'");
	
		foreach ($query->rows as $result) {
			$news_description_data[$result['language_id']] = array(
				'title'            			=> $result['title'],
				'meta_description' 	=> $result['meta_description'],
				'meta_keyword' 	=> $result['meta_keyword'],
				'description'      		=> $result['description']
			);
		}
	
		return $news_description_data;
	}

	public function getNewsStores($news_id) { 
		$newspage_store_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_store WHERE news_id = '" . (int)$news_id . "'");
		
		foreach ($query->rows as $result) {
			$newspage_store_data[] = $result['store_id'];
		}
	
		return $newspage_store_data;
	}

	public function getNews() { 
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY n.date_added");

		return $query->rows;
	}

	public function getTotalNews() { 
		$this->checkNews();
	
     	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "news");
	
		return $query->row['total'];
	}

	public function checkNews() { 
		$create_news = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "news` (`news_id` int(11) NOT NULL auto_increment, `status` int(1) NOT NULL default '0', `image` VARCHAR(255) COLLATE utf8_general_ci default NULL, `image_size` int(1) NOT NULL default '0', `date_added` date default NULL, `viewed` int(5) NOT NULL DEFAULT '0', PRIMARY KEY (`news_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
		$this->db->query($create_news);
	
		$create_news_descriptions = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "news_description` (`news_id` int(11) NOT NULL default '0', `language_id` int(11) NOT NULL default '0', `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `meta_description` VARCHAR(255) COLLATE utf8_general_ci NOT NULL, `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `meta_keyword` varchar(255) COLLATE utf8_general_ci NOT NULL, PRIMARY KEY (`news_id`,`language_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
		$this->db->query($create_news_descriptions);
	
		$create_news_to_store = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "news_to_store` (`news_id` int(11) NOT NULL, `store_id` int(11) NOT NULL, PRIMARY KEY (`news_id`, `store_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
		$this->db->query($create_news_to_store);
	}
}
?>