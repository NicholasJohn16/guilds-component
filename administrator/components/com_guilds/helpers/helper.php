<?php

class GuildsHelper {

    static function sort($title, $order, $direction = 'asc', $selected = 0, $task = NULL) {
        $direction = strtolower($direction);
        $images = array('sort_asc.png', 'sort_desc.png');
        $index = intval($direction == 'desc');
        $direction = ($direction == 'desc') ? 'asc' : 'desc';

        $html = '<a href="javascript:tableOrder(\'' . $order . '\',\'' . $direction . '\',\'' . $task . '\');" title="' . JText::_('Click to sort this column') . '">';
        $html .= JText::_($title);
        if ($order == $selected) {
            $html .= JHTML::_('image.administrator', $images[$index], '/images/', NULL, NULL);
        }
        $html .= '</a>';
        return $html;
    }

}
