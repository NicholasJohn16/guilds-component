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
		
		function newAlert() {
			$this->alert = new Alert();
			$this->alerts[] = $this->alert; 
			$session =& JFactory::getSession();
			$session->set('com_guilds_alerts',$alerts);
			dump($this->alert,'a new alert was created.');
			return $this->alert;
		}
		
		function addButton() {
			$button = new Button();
			$this->alert->buttons[] = $button;
			return $button;
		}
		
	 	function displayAlerts() {
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
	 }
	 
	 class Button extends JObject {
	 	var $class = "primary";
	 	var $id = "";
	 	var $el = "button";
	 	var $link = "#";
	 	var $text = "";
	 }