<?php

/**
 * Joomla! 1.5 Component Guilds Manager
 *
 * @version 
 * @author Nick Swinford
 * @package Joomla
 * @subpackage Guilds Manager
 * @license Copyright (c) 2011 - All Rights Reserved
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class GuildsViewHoldings extends JView { 
    
    public function display() {
        JHTML::stylesheet('bootstrap.css', 'components/com_guilds/media/css/');
        JHTML::script('jquery.js', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/');
        JHTML::script('bootstrap.js', 'components/com_guilds/media/js/', false);
        
        $fleets = array(
            'swf'=>'Stonewall Fleet',
            'dss'=>'Deep Space Stonewall',
            'hnr'=>'House of Nagh reD',
            'swl'=>'Stonewall Legion',
            'other'=>array(
                'cc'=>'Crafting Corp',
                'swv'=>'Stonewall Vault'
            )
        );
        $holdings = array(
            'swf'=> array(
                'starbase'=>'Starbase Diversity',
                'embassy'=>'Embassy',
                'mine'=>'Dilithium Mine'
                ),
            'dss'=>array(
                'starbase'=>'Starbase Equality',
                'embassy'=>'Embassy',
                'mine'=>'Dilithium Mine'
            ),
            'hnr'=>array(
                'starbase'=>'Starbase Strength',
                'embassy'=>'Hillbane Memorial<br/>Embassy',
                'mine'=>'Dilithium Mine'
            ),
            'swl'=>array(
                'starbase'=>'Starbase',
                'embassy'=>'Embassy',
                'mine'=>'Dilithium Mine'
            )
        );
        
        $tiers = array(
            'starbase' => array(
                // 'starbase'=>array('count'=>5,'color'=>''),
                'starbase'=>5,
                // 'military'=>array('count'=>5,'color'=>'progress-danger'),
                'military'=>5,
                'engineering'=>5,
                'science'=>5
            ),
            'embassy'=> array(
                'embassy'=>3,
                'diplomacy'=>3,
                'recruitment'=>3
            ),
            'mine'=>array(
                'mine'=>3,
                'trade'=>3,
                'development'=>3
            )
        );
        
        $this->assignRef('fleets',$fleets);
        $this->assignRef('holdings',$holdings);
        $this->assignRef('tiers',$tiers);
        
        parent::display();
    }
}