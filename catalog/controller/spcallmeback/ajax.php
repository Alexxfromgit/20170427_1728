<?php 
class ControllerSpcallmebackAjax extends Controller {
	public function index() {  
		global $session, $languages;
		$lang_code = $session->data['language'];
		
        $this->language->load('module/spcallmeback');
        
        $this->load->model('spcallmeback/form');
                
        $this->load->model('spcommon/language');
                
        $form = $this->model_spcallmeback_form->create();
                        
        $this->data = array_merge($this->data, $this->model_spcommon_language->getByIds(array('heading_title',
        	'text_anonym', 'text_email_subject', 'text_email_body', 'text_email_hours_range', 'text_email_delivery_success',
        	'text_email_delivery_failure')));
                
        $this->document->addScript('catalog/view/javascript/module-spcallmeback.js');
        $this->document->addScript('catalog/view/javascript/zebra_form/zebra_form.src.js');
        $this->document->addScript('catalog/view/javascript/fancybox/jquery.mousewheel-3.0.4.pack.js');
        $this->document->addScript('catalog/view/javascript/fancybox/jquery.fancybox-1.3.4.js');
        $this->document->addStyle('catalog/view/javascript/fancybox/jquery.fancybox-1.3.4.css');

        
        //$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
        
        //$this->data['message'] = html_entity_decode($setting['description'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
        
        $form_caption = html_entity_decode($this->model_spcallmeback_form->getFieldName('form_caption'));
        $form_subcaption = nl2br(html_entity_decode($this->model_spcallmeback_form->getFieldName('form_subcaption')));
        
        
        if ($form === true)
        {
            echo('<div class="spcallmeback_wrap"><h4>'.$form_caption.'</h4>
                <br />');
                            
            /*$additional1 = isset($_POST['additional1']) ? stripslashes($_POST['additional1']) : '';
            $additional2 = isset($_POST['additional2']) ? stripslashes($_POST['additional2']) : '';
            $name = stripslashes($_POST['name']);
            $email = stripslashes($_POST['email']);
            $phone = stripslashes($_POST['phone']);*/
            
            $name = html_entity_decode($_POST['name']);
            if (!$name)
                $name = $this->data['text_anonym'];
            
            $our_email = $this->model_spcallmeback_form->getFieldName('email');
            $subject = sprintf($this->data['text_email_subject'], strip_tags($form_caption));
            $from = strip_tags($form_caption);
            /*if ($lang_code == 'ru') {
	            $from = convert_cyr_string($this->Utf8($from), "w", "k" );
	            $from = '=?koi8-r?B?'.base64_encode($from).'?=';
            }*/
            $from = '=?utf-8?B?'.base64_encode($from).'?=';
            
            
            $res1 = true;
            
            $body = sprintf($this->data['text_email_body'], $name, strip_tags($form_caption));
            foreach($this->model_spcallmeback_form->fields as $field)
            {
                $order = $this->config->get($field.'_order') !== NULL ? $this->config->get($field.'_order') : 1;
                if ($order > 0)
                {
                    $value = nl2br(html_entity_decode($_POST[$field]));
                    if ($field == 'time')
                    {
                        $time = explode('-', trim(strtolower($value)));
                        $selected_hour1 = $selected_hour2 = '-';
                        /*if (!isset($time[0]) || $time[0] === '')
                            $time[0] = '';
                        if (!isset($time[1]) || $time[1] === '')
                            $time[1] = '';*/
                        if (isset($time[0]) && $time[0] !== '')
                            $selected_hour1 = $time[0];
                        if (isset($time[1]) && $time[1] !== '')
                            $selected_hour2 = $time[1];
                        $value = sprintf($this->data['text_email_hours_range'], $selected_hour1, $selected_hour2);
                        
                    }
                    $label = html_entity_decode($this->model_spcallmeback_form->getFieldName('name_'.$field));
                    $body .= "<b>$label</b>: ".$value."<br />";
                }
            }
            $body .= "<br /><br /><br />";
            $res2 = $this->sendHtmlMessage($our_email, $subject, $body, $from, $our_email);

            if ($res1 && $res2)
              echo $this->data['text_email_delivery_success'];
            else
              echo $this->data['text_email_delivery_failure'];
            die('</div>');
        }
        
        echo('            <h4>'.$form_caption.'</h4>
            <div>'.$form_subcaption.'</div>
            <br />');
        die(html_entity_decode($form));
	}
    
    function sendHtmlMessage($email, $subject, $body, $fromName, $fromEmail) {
        if (substr($subject, 0, 2) != "=?") {
          //$subject = convert_cyr_string($this->Utf8($subject), "w", "k" );
          //$subject = '=?koi8-r?B?'.base64_encode($subject).'?=';
          $subject = '=?utf-8?B?'.base64_encode($subject).'?=';
        }

        $from = "From: \"$fromName\" <$fromEmail>";
        $from = "Content-Type: text/html; charset=utf-8\nContent-Transfer-Encoding: quoted-printable\n".$from;
        $newbody = "";
        for ($i = 0; $i < strlen($body); $i++) {
          $newbody .= "=".sprintf("%02X", ord($body[$i]));
        }
        //echo $email,$subject,$newbody,$from;
        return mail($email,$subject,$newbody,$from);
    }
    
    function Utf8($s, $sTo = 'utf2win') {
        $a = array();
      for ($i=128; $i <= 191; $i++){
          $a['utf'][] = ($i<144) ? chr(209).chr($i) : chr(208).chr($i);
        $a['win'][] = ($i<144) ? chr($i + 112) : chr($i + 48) ;
      }
      $a['utf'][] = chr(208) . chr(129);
      $a['win'][] = chr(168);
      $a['utf'][] = chr(209) . chr(145);
      $a['win'][] = chr(184);

        if(in_array(strtolower($sTo), array('utf2win','w','cp1251','windows-1251')))
            return str_replace($a['utf'], $a['win'], $s);
        if(in_array(strtolower($sTo), array('win2utf','u','utf8','utf-8')))
            return str_replace($a['win'], $a['utf'], $s);
    }
}
?>