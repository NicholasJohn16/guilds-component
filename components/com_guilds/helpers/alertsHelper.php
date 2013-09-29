<?php

/**
 * Helper class for creating alerts
 */
class alertsHelper extends JObject {

    /**
     * Creates an alert and stores the params in the session
     * @param array() $params
     */
    static function alert($params) {
        // get the session so we can keep track of all the alerts
        $session = & JFactory::getSession();
        // get the alerts from the session
        $alerts = $session->get('com_guilds_alerts', array());
        // insert params into alerts array
        $alerts[] = $params;
        // put them back into the session to be displayed later
        $session->set('com_guilds_alerts', $alerts);
    }

    /**
     * Creates the alert and button objects then outputs the html
     * @return string
     */
    static function display() {
        $session = & JFactory::getSession();
        $params = $session->get('com_guilds_alerts', array());
        $alerts = array();
        $html = "";

        foreach ($params as $param) {
            $alert = new Alert($param);
            $alerts[] = $alert;
        }

        foreach ($alerts as $alert) {
            $html .= $alert->display();
        }

        $session->clear('com_guilds_alerts');
        echo $html;
    }

}

class Alert {

    var $class = "info";
    var $style = "";
    var $id = "";
    var $title = "";
    var $msg = "";
    var $buttons = array();

    function __construct($params) {
        $this->class = array_key_exists('class', $params) ? $params['class'] : $this->class;
        $this->id = array_key_exists('id', $params) ? $params['id'] : $this->id;
        $this->title = array_key_exists('title', $params) ? $params['title'] : $this->title;
        $this->msg = $params['msg'];
        $this->style = array_key_exists('style', $params) ? $params['style'] : $this->style;
        $this->buttons = array_key_exists('buttons', $params) ? $params['buttons'] : $this->buttons;
        $buttons = array();

        foreach ($this->buttons as $params) {
            $buttons[] = new Button($params);
        }
        $this->buttons = $buttons;
    }
    
    function display() {
        $this->id = $this->id == "" ? "" : 'id="' . $this->id . '"';
        $this->style = $this->style == "" ? "" : 'style="' . $this->style . '"';

        $html = '<div class="alert alertHelper alert-block alert-' . $this->class . '" ' . $this->id . ' ' . $this->style . '>';
        $html .= '<a class="close" data-dismiss="alert" href="#">&times;</a>';
        $html .= '<h4 class="alert-heading">' . $this->title . '</h4>';
        $html .= '<p>' . $this->msg . '</p>';

        if (count($this->buttons) > 0) {
            $html .= '<p>';
            foreach ($this->buttons as $button) {
                $html .= $button->display();
                $html .= "&nbsp;";
            }
            $html .= '</p>';
        }
        $html .= '</div>';

        //$script = "$('.bottom-right').notify({message:".$this->msg.",type:".$this->class."}).show();";
        //$document =& JFactory::getDocument();
        //$document->addScriptDeclaration($script);

        return $html;
    }

}

class Button {

    var $class = "primary";
    var $id = "";
    var $el = "button";
    var $link = "";
    var $text = "";

    function __construct($params) {
        $this->class = array_key_exists('class', $params) ? $params['class'] : $this->class;
        $this->el = array_key_exists('el', $params) ? $params['el'] : $this->el;
        $this->link = array_key_exists('link', $params) ? $params['link'] : $this->link;
        $this->text = array_key_exists('text', $params) ? $params['text'] : $this->text;
        
        if(array_key_exists('id',$params) && $params['id'] == 'close') {
            $this->id = $params['id'];
            
            $document = JFactory::getDocument();
            $document->addScriptDeclaration("
                $(document).ready(function() {
                    $('#close').click(function() {
                        $('.alertHelper').alert('close');
                    });
                });   
         ");
            
        }elseif(array_key_exists('id', $params)) {
            $this->id = $params['id'];
        }else{
           $this->id = $this->id; 
        }
    }

    function display() {
        $this->link = $this->link == "" ? "" : 'href="' . $this->link . '"';
        $this->id = $this->id == "" ? "" : 'id="' . $this->id . '"';
        $html = '<' . $this->el . ' class="btn btn-' . $this->class . '"' . $this->link . ' ' . $this->id . '>' . $this->text . '</' . $this->el . '>';
        return $html;
    }
}