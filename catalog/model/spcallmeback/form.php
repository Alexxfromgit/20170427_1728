<?php

require_once DIR_APPLICATION.'model/spcommon/zebra/Zebra_Form.php';

global $_text_captcha_word;
$_text_captcha_word = '';

function spCallmeback_checkCaptcha($captcha) {
	global $_text_captcha_word, $_new_captcha_word, $_current_captcha_pos, $_current_captcha_length,
			$_current_captcha_word;
	
	$captcha_pos = $_current_captcha_pos;
    $captcha_length = $_current_captcha_length;
    $captcha_word = $_current_captcha_word;
        
    $correct = (
    	$captcha_pos == 0 ? mb_substr($captcha_word, 0, $captcha_length, 'utf-8') : 
    		mb_substr($captcha_word, -$captcha_length, mb_strlen($captcha_word), 'utf-8')
    );
        
    $res = $captcha && ($correct == $captcha);
    if (!$res) {
	    /*$captcha_pos = rand(0, 1);
	    $captcha_length = rand(2, 5);
	    $captcha_word = $_new_captcha_word;
	    
	    $_SESSION['captcha_pos'] = $captcha_pos;
	    $_SESSION['captcha_length'] = $captcha_length;
	    $_text_captcha_word = $_SESSION['captcha_word'] = $captcha_word;*/
    }
    return $res;
}

class ModelSpcallmebackForm extends Model {
    var
        $fields = array('name', 'phone', 'time', 'email', 'comments', 'additional1', 'additional2');

    public function init($fields) {
        $this->fields = $fields;
    }
    
    public function getFieldName($name)
    {
        return $this->config->get($name) ? $this->config->get($name) : $this->language->get($name.'_default');
    }
        
	public function create() {
        $this->load->language('module/spcallmeback');
        $form = new Zebra_Form('spcallmeback_form_1', 'post', $this->url->link('spcallmeback/ajax', '', 'SSL'), array(
            'class' => 'spcallmeback_form',
            'itemid' => "1"
        ));
        
        $form->csrf(false);
        
        $form->clientside_validation(array(
            'on_ready' => 'function() { 
                formReady_form = true;

                initForm(1);
                
                /*$form = $("#spcallmeback_form_1"); 
                $form.unbind("submit");*/
                /*alert($form);
                $form.unbind("submit");
                $form.bind("submit", onFormSubmit);*/
                }',
            'tips_position' => 'right',
            'validate_on_the_fly' => true,
            'scroll_to_error'       =>  false,
            'validate_all'          =>  true,
            'close_tips'            =>  false
        ));    
        
        $fields = $this->fields;
        $fields_ordered = array();
        foreach($fields as $field)
        {
            $order = $this->config->get($field.'_order') !== NULL ? $this->config->get($field.'_order') : 1;
            if ($order > 0)
                $fields_ordered[$order][] = $field;
        }
        ksort($fields_ordered);
        foreach($fields_ordered as $fields)
        {
            foreach($fields as $field)
            {
                $field_name = $this->getFieldName('name_'.$field);
                $required = $this->config->get($field.'_required') !== NULL ? $this->config->get($field.'_required') : (($field == 'name' || $field == 'phone') ? 'on' : 'off');
                $required = $required == 'on';
                $rules = array();
                if ($field == 'name' || $field == 'email' || $field == 'additional1' || $field == 'additional2')
                {
                    $form->add('label', 'label_'.$field, $field, $field_name.":");
                
                    $obj = $form->add('text', $field, '');
                }
                else if ($field == 'phone')
                {
                    $form->add('label', 'label_phone', 'phone', $field_name.":");
                    $obj = $form->add('text', 'phone', '' /*array('data-prefix' => '(+7)-')*/);
                    if ($required)
                        $rules['regexp'] = array(
                                //'^(\+[0-9]-([0-9]{3}-[0-9]{3}-[0-9]{4})|(8-[0-9]{3}-[0-9]{3}-[0-9]{4})|([0-9]{3}-[0-9]{3}-[0-9]{4}))$',
                                '^((\+[0-9])?[0-9\s-]{6,})$',
                                'error',
                                $this->language->get('error_wrong_phone_format')
                             );
                    $form->add('note', 'note_phone', 'phone', $this->language->get('text_phone_note'));
                }
                else if ($field == 'time')
                {
                    $form->add('label', 'label_'.$field, $field, $field_name.":");
                    $obj = $form->add('timerange', $field, '', array('from_label' => $this->getFieldName('time_from_label'), 'to_label' => $this->getFieldName('time_to_label')/*'data-prefix' => '(+7)-'*/));
                }
                else if ($field == 'comments')
                {
                    $form->add('label', 'label_comments', 'comments', $field_name.":", array('template_style' => 'vertical'/*'inside' => true*/));
                    //<label for="phone" id="label_phone">Телефон:<span class="required">*</span></label>
                    
                    //$form->add('label', 'label_text', 'text', $field_name.":");
                    $obj = $form->add('textarea', 'comments', '', array('class' => "spform_textarea", 'style' => "height:50px;",
                        'cols' => 40
                    ));
                }
                if ($required)
                    $rules['required'] = array('error', sprintf($this->getFieldName('error_missing_value'), $field_name));
                if ($rules)
                    $obj->set_rule($rules);
            }
        }
        
        if ($this->config->get('use_captcha') == 'on') {
			global $_text_captcha_word, $_new_captcha_word, $_current_captcha_pos, $_current_captcha_length,
				$_current_captcha_word;
	        if (isset($_POST) && count($_POST)) {
        		//echo '';var_dump($_POST);
				/*$captcha_pos = $_SESSION['captcha_pos'];
				$captcha_length = $_SESSION['captcha_length'];
				$_text_captcha_word = $captcha_word = $_SESSION['captcha_word'];*/

				$_current_captcha_pos = $_SESSION['captcha_pos'];
    			$_current_captcha_length = $_SESSION['captcha_length'];
    			$_current_captcha_word = $_SESSION['captcha_word'];
    			
    			if (isset($_POST['masked_field']) && spCallmeback_checkCaptcha($_POST['masked_field'])) {
					$captcha_pos = $_SESSION['captcha_pos'];
					$captcha_length = $_SESSION['captcha_length'];
					$captcha_word = $_SESSION['captcha_word'];
    			}
    			else {
			        $captcha_pos = rand(0, 1);
			        $captcha_length = rand(2, 5);
			        $captcha_word_index = rand(0, 12);
			        $_text_captcha_word = $captcha_word = $this->language->get('text_captcha_word_' . $captcha_word_index);
			        
			        // regen captcha for next usage
			        $_SESSION['captcha_pos'] = $captcha_pos;
			        $_SESSION['captcha_length'] = $captcha_length;
			        $_SESSION['captcha_word'] = $captcha_word;
					
					// for new captcha, if post is wrong
					$_new_captcha_word = $captcha_word;
				}
			}
			else {
		        $captcha_pos = rand(0, 1);
		        $captcha_length = rand(2, 5);
		        $captcha_word_index = rand(0, 12);
		        $_text_captcha_word = $captcha_word = $this->language->get('text_captcha_word_' . $captcha_word_index);
		        
		        $_SESSION['captcha_pos'] = $captcha_pos;
		        $_SESSION['captcha_length'] = $captcha_length;
		        $_SESSION['captcha_word'] = $captcha_word;
			}
	        
	        $captcha_text = $this->language->get('text_captcha_text_1') . ' ' . ($this->language->get('text_captcha_text_2_'.$captcha_pos)) . ' ' .
        		$captcha_length . ' ' . ($captcha_length >= 5 ? $this->language->get('text_captcha_text_3_1') : $this->language->get('text_captcha_text_3_0')
        		) . ' ' . $this->language->get('text_captcha_text_4') . ' &ldquo;' . $captcha_word . '&rdquo;';

	        $field_name = $this->language->get('text_captcha');
	        $rules = array();
	        $form->add('label', 'label_masked_field', 'masked_field', $field_name.":", array('template_style' => 'vertical'/*'inside' => true*/));
	        //<label for="phone" id="label_phone">Телефон:<span class="required">*</span></label>
	        
	        //$form->add('label', 'label_text', 'text', $field_name.":");
	        $obj = $form->add('text', 'masked_field', '');
	        $rules['required'] = array('error', sprintf($this->getFieldName('error_missing_value'), $field_name));
	        $rules['custom'] = array('spCallmeback_checkCaptcha', 'error', $this->language->get('error_wrong_captcha'));
	        if ($rules)
	            $obj->set_rule($rules);
	        $form->add('note', 'note_masked_field', 'masked_field', $captcha_text);
		}
        
        //$form->clientside_validation(false);
        

        //$form->add('switchstyle', 'xxx', '*horizontal');
        
        // "submit"
        //$form->add('submit', 'btnsubmit', 'Submit');
        
        $form->show_all_error_messages(true);

        /*if (isset($_REQUEST['name']))
            $form->add_error('error', 'Value: '.$_REQUEST['name']. "!");*/
        
        $echo = "";
        
        // if the form is valid
        if ($form->validate() && empty($form->errors)) {
            return true;
        // otherwise
        } else
            // auto generate output, labels above form elements
            $echo = $form->render("*horizontal", true);
        
        $pos = strrpos($echo, '</tr></table></form>');

        if($pos !== false)
        {
            $echo = substr_replace($echo, '', $pos, strlen('</tr></table></form>'));
        }
        
        $echo .= '            <tr class="row last">
                <td colspan="3"  style="width:300px">
                    <span class="nofocus"><input class="spcallmeback_submit" type="submit" value="'.$this->getFieldName('form_button_caption').'" /></span>
                    <span class="spcallmeback_close_btn">'.$this->language->get('text_button_close').'</span>
                </td>
            </tr>
            </table></form>
            <script type="text/javascript">
                $(".spcallmeback_close_btn").click(function(e) {  
                    $.fancybox.close();  
                });
            </script>
';
          
        return $echo;

        /*$html = "
    <table style=\"width:100%\" class=\"spcallmeback_table\">
        <tr>
            <td style=\"text-align:right\">Ваше имя:</td>
            <td><input type=\"text\" size=\"30\" /></td>
        </tr>
        <tr>
            <td style=\"text-align:right\">Телефон:</td>
            <td><input type=\"text\" size=\"30\" /></td>
        </tr>
        <tr>
    </table>
    <table style=\"width:100%\">
        <tr>
            <td style=\"text-align:left\">Вопрос или комментарий:
        </tr>
    </table>
    <table style=\"width:100%; \">
        <tr><td style=\"text-align:right\">
            <textarea class=\"spform_textarea\" style=\"height:50px;\"></textarea></td>
        </tr>
    </table>
";
        return $html;*/
	}
}
?>