<?php
 
class categoriesHelper extends JObject {
    
    static function display($types,$categories,$params = NULL) {
        
        if(!isset($params['tab'])) {
            $params['tab'] = false;
        }
        
        if(isset($params['id_prefix'])) {
            $params['id_prefix'] = $params['id_prefix'].'-category-';
        } else {
            $params['id_prefix'] = 'category-';
        }
        
        if(!isset($params['inline'])) {
            $params['inline'] = true;
        }
        
        if(!isset($params['character'])) {
            $params['character'] = false;
        }
        
        if(!isset($params['filters'])) {
            $params['filters'] = false;
        }
        
        $selects = array();
        $html = "";
        
        foreach($types as $type) {
            
            $selects[$type->name] = new Select($type,$params);
            
            // Create default option
            $option = new Option();
            $option->value = "";
            $option->text = " Select ".ucfirst($type->name);
            $option->children = NULL;
            $option->parent = NULL;
            $option->type = NULL;
            $option->type_id = NULL;
            $selects[$type->name]->options['default'] = $option;
            
            foreach($categories as $category) {
                if($type->id == $category->type_id) {
                    $selects[$type->name]->options[$category->name] = new Option($category,$params);
                }
            }
            if($params['tab']) {
                $params['tab']++; // gonna start a new select so increment tab
            }
        }
        
        foreach($selects as $select) {
            $html .= $select->display();
        }
        
        return $html;
    }
}

class Select {
    var $id = "";
    var $name = "";
    var $tab = "";
    var $id_prefix = "";
    var $options = array();
    
    public function __construct($select,$params) {
        $this->name = $select->name;
        $this->id = $params['id_prefix'] . $this->name;
        $this->tab = $params['tab'];
        $this->inline = $params['inline'];
    }
    
    public function display() {
        $html = "";
        
        if($this->inline) {
            $html .= '<div class="control-group">';

            $html .= '<label class="control-label" ';
            $html .= ' for="'.$this->id.'" >';
            $html .= ucfirst($this->name). '</label>';

            $html .= '<div class="controls">';
        }
        $html .= ' <select ';
        if($this->tab) {
            $html .= ' tabindex="'.$this->tab.'" ';
        }
        $html .= ' id="'.$this->id.'" ';
        $html .= ' name="category['.$this->name.']"';
        $html .= ' > ';
        
        foreach($this->options as $option) {
            $html .= $option->display();
        }
        
        $html .= '</select>';
        if($this->inline) {
            $html .= '</div>'; // end control-group
            $html .= '</div>'; // end controls
        }
        
        return $html;
    }
    
}

class Option {
    var $value = "";
    var $text = "";
    var $selected = false;
    
    var $parent = "";
    var $children = "";
    
    var $type = "";
    var $type_id = "";
    
    public function __construct($category = NULL,$params = NULL) {
        $this->children = (isset($category->children)) ? $category->children : $this->children;
        $this->value = (isset($category->id)) ? $category->id : $this->value;
        $this->text = (isset($category->name)) ? $category->name : $this->text;
        $this->parent = (isset($category->parent)) ? $category->parent : $this->parent;
        $this->type = (isset($category->type)) ? $category->type : $this->type;
        $this->type_id = (isset($category->type_id)) ? $category->type_id : $this->type_id;
        
        if(($params['character'])) {
            $values = $params['character'];
            $type_id = $this->type."_id";
            
            if($this->value === $values->$type_id ) {
                $this->selected = true;
            }
        } elseif ($params['filters']) {
            
            if($params['filters'][$this->type] === $this->value ) {
                $this->selected = true;
            }
        }
        
    }
    
    public function display() {
        $html  = ' <option ';
        $html .= ' value="'.$this->value.'" ';
        if($this->selected) {
            $html .= ' selected="selected" ';
        }
        if($this->parent != NULL) {
            $html .= ' data-parent="'.$this->parent.'" ';
        }
        if($this->children != NULL) {
            $html .= ' data-children="'.$this->children.'" ';
        }
        $html .= ' >'.$this->text.'</option>';
        return $html;
    }
    
}


?>
