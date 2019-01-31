<?php
// News Module for Opencart v1.5.5, modified by villagedefrance (contact@villagedefrance.net)

class ControllerInformationNews extends Controller {

	public function index() {
	
    	$this->language->load('information/news');
	
		$this->load->model('catalog/news');
	
		$this->data['breadcrumbs'] = array();
	
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
	
		if (isset($this->request->get['news_id'])) {
			$news_id = $this->request->get['news_id'];
		} else {
			$news_id = 0;
		}
	
		$news_info = $this->model_catalog_news->getNewsStory($news_id);
	
		if ($news_info) {
	  	
			$this->document->addStyle('catalog/view/theme/default/stylesheet/news.css');
			$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
		
			$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
		
			$this->data['breadcrumbs'][] = array(
				'href'      => $this->url->link('information/news'),
				'text'      => $this->language->get('heading_title'),
				'separator' => $this->language->get('text_separator')
			);
		
			$this->data['breadcrumbs'][] = array(
				'href'      => $this->url->link('information/news', 'news_id=' . $this->request->get['news_id']),
				'text'      => $news_info['title'],
				'separator' => $this->language->get('text_separator')
			);
			
			$this->document->setTitle($news_info['title']);
			$this->document->setDescription($news_info['meta_description']);
			$this->document->setKeywords($news_info['meta_keyword']);
			$this->document->addLink($this->url->link('information/news', 'news_id=' . $this->request->get['news_id']), 'canonical');
		
     		$this->data['news_info'] = $news_info;
		
     		$this->data['heading_title'] = $news_info['title'];
     		
			$this->data['description'] = html_entity_decode($news_info['description']);
			
     		$this->data['meta_keyword'] = html_entity_decode($news_info['meta_keyword']);
			
			$this->data['viewed'] = sprintf($this->language->get('text_viewed'), $news_info['viewed']);
		
			$this->data['addthis'] = $this->config->get('news_newspage_addthis');
		
			$this->data['min_height'] = $this->config->get('news_thumb_height');
		
			$this->load->model('tool/image');
		
			if ($news_info['image']) { $this->data['image'] = TRUE; } else { $this->data['image'] = FALSE; }
		
			$this->data['thumb'] = $this->model_tool_image->resize($news_info['image'], $this->config->get('news_thumb_width'), $this->config->get('news_thumb_height'));
			$this->data['popup'] = $this->model_tool_image->resize($news_info['image'], $this->config->get('news_popup_width'), $this->config->get('news_popup_height'));
		
     		$this->data['button_news'] = $this->language->get('button_news');
			$this->data['button_continue'] = $this->language->get('button_continue');
		
			$this->data['news'] = $this->url->link('information/news');
			$this->data['continue'] = $this->url->link('common/home');
			
			if (isset($_SERVER['HTTP_REFERER'])) {
			$this->data['referred'] = $_SERVER['HTTP_REFERER'];
			}
 
			$this->data['refreshed'] = 'http://' . $_SERVER['HTTP_HOST'] . '' . $_SERVER['REQUEST_URI'];
			
			if (isset($this->data['referred'])) {
				$this->model_catalog_news->updateViewed($this->request->get['news_id']);
			}
		
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/news.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/information/news.tpl';
			} else {
				$this->template = 'default/template/information/news.tpl';
			}
		
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
		
			$this->response->setOutput($this->render());
		
	  	} else {
		
		    	$url = '';
			
				if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				$url .= '&page=' . $this->request->get['page'];
				} else { 
				$page = 1;
				}
				
				$limit = $this->config->get('config_catalog_limit');
		
				$data = array(
				'page' => $page,
				'limit' => $limit,
				'start' => $limit * ($page - 1),
				);
		
				$total = $this->model_catalog_news->getTotalNews();
		
				$pagination = new Pagination();
				$pagination->total = $total;
				$pagination->page = $page;
				$pagination->limit = $limit;
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->url->link('information/news', $url . '&page={page}', 'SSL');
		
				$this->data['pagination'] = $pagination->render();
		
	  		$news_data = $this->model_catalog_news->getNews($data);
		
	  		if ($news_data) {
			
				$this->document->setTitle($this->language->get('heading_title'));
			
				$this->data['breadcrumbs'][] = array(
					'href'      => $this->url->link('information/news'),
					'text'      => $this->language->get('heading_title'),
					'separator' => $this->language->get('text_separator')
				);
			
				$this->data['heading_title'] = $this->language->get('heading_title');
			
				$this->document->addStyle('catalog/view/javascript/jquery/panels/main.css');
				$this->document->addScript('catalog/view/javascript/jquery/panels/utils.js');
			
				$this->data['text_more'] = $this->language->get('text_more');
				$this->data['text_posted'] = $this->language->get('text_posted');
				
				$chars = $this->config->get('news_headline_chars');
				$this->load->model('tool/image');
			
				foreach ($news_data as $result) {
					$this->data['news_data'][] = array(
						'id'  				=> $result['news_id'],
						'title'        		=> $result['title'],
						'thumb'				=> $this->model_tool_image->resize($result['image'], $this->config->get('news_thumb_width'), $this->config->get('news_thumb_height')),
						'description'  	=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $chars),
						'href'         		=> $this->url->link('information/news', 'news_id=' . $result['news_id']),
						'posted'   		=> date($this->language->get('date_format_short'), strtotime($result['date_added']))
					);
				}
			
				$this->data['button_continue'] = $this->language->get('button_continue');
			
				$this->data['continue'] = $this->url->link('common/home');
			
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/news.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/information/news.tpl';
				} else {
					$this->template = 'default/template/information/news.tpl';
				}
			
				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
			
				$this->response->setOutput($this->render());
			
	    	} else {
			
		  		$this->document->setTitle($this->language->get('text_error'));
			
	     		$this->document->breadcrumbs[] = array(
	        		'href'      => $this->url->link('information/news'),
	        		'text'      => $this->language->get('text_error'),
	        		'separator' => $this->language->get('text_separator')
	     		);
			
				$this->data['heading_title'] = $this->language->get('text_error');
			
				$this->data['text_error'] = $this->language->get('text_error');
			
				$this->data['button_continue'] = $this->language->get('button_continue');
			
				$this->data['continue'] = $this->url->link('common/home');
			
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
				} else {
					$this->template = 'default/template/error/not_found.tpl';
				}
			
				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
			
				$this->response->setOutput($this->render());
		  	}
		}
	}
}
?>
