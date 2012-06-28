<?php
	/**
	 * Helper class for creating alerts
	 */
	class alertsHelper extends JObject {

		var $alerts = array();
		
		function __construct() {
			$this->session =& JFactory::getSession();
			$this->alerts =& $this->session->get('com_guilds_alerts',array());
			dump($this->alerts,'alertsHelper was constructed.');
		}
		
        // Example
        // $buttons = array()
        // $buttons[1]['text'] = 'Yay!';
        // $buttons[1]['el'] = 'a';
        // alertsHelper::alert(array('title'=>'Character(s) added.','msg'=>'The character(s) where successfully added.','class'=>'success'
        // ,'buttons'=>array('text'=>'Yay!')));

		static function alert($params) {
			$this->alert = new Alert($params);
			$this->alerts[] = $this->alert; 
			$session =& JFactory::getSession();
			$session->set('com_guilds_alerts',$alerts);
			dump($this->alert,'a new alert was created.');
			return $this->alert;
		}
				
	 	static function display() {
	 		foreach($this->alerts as $alert) {
				$html  = '<div class="alert alert-block alert-'.$class.'">';
				$html .= '<a class="close" data-dismis="alert" href="#">&times;</a>';
				$html .= '<h4 class="alert-heading">'.$this->title.'</h4>';
				$html .= '<p>'.$this->msg.'</p>';
				if(count($this->buttons) > 0) {
					$html .= '<p>';
					foreach($this->buttons as $button) {
						$html .= '<'.$button->el.' class="btn btn-'.$button->class.' href="'.$button->link.'">'.$button->text.'</'.$button->el.'>';
					}
					$html .= '<a class="btn" href="#">Close</a>';
					$html .= '</p>';
				}
				$html .= '</div>';
	 		}
	 		
	 		$this->session->clear('com_guilds_alerts');
	 		return $this->alerts;
		}
	}
	 
	class Alert extends JObject {
		var $class = "info";
		var $id = "";
		var $title = "";
		var $msg = "";
		var $buttons = array();
		
		function __construct($params) {
			$this->buttons = new Buttons($params['buttons']);
		}
	}
	 
	 class Button extends JObject {
	 	var $class = "primary";
	 	var $id = "";
	 	var $el = "button";
	 	var $link = "#";
	 	var $text = "";
        
	    function __construct($params) {
	    	$this->text = $params['text'];
	    }
	 }