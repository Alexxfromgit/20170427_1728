<?php
class ModelSpcommonLanguage extends Model {
	public function getByIds($ids) {
        $result = array();
        if (!is_array($ids))
            $ids = array($ids);
        foreach($ids as $id)
        {
            $result[$id] = $this->language->get($id);//sprintf($this->language->get($id), $this->config->get('config_name'));
        }
        return $result;
	}
}
?>