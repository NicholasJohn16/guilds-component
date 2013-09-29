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
        
        $tracks = array(
            'starbase' => array(
                'starbase'=>array('tiers'=>5,'color'=>''),
                'military'=>array('tiers'=>5,'color'=>'bar-danger'),
                'engineering'=>array('tiers'=>5,'color'=>'bar-warning'),
                'science'=>array('tiers'=>5,'color'=>'bar-success')
            ),
            'embassy'=> array(
                'embassy'=>array('tiers'=>3,'color'=>''),
                'diplomacy'=>array('tiers'=>3,'color'=>'bar-success'),
                'recruitment'=>array('tiers'=>3,'color'=>'bar-warning')
            ),
            'mine'=>array(
                'mine'=>array('tiers'=>3,'color'=>''),
                'trade'=>array('tiers'=>3,'color'=>'bar-warning'),
                'development'=>array('tiers'=>3,'color'=>'bar-danger')
            )
        );
        
        $major_holding = array(1000,3000,3000,3000,3000); // Starbase
        $minor_holding = array(1000,2000,3000); // Embassy & Dilithium Mine
        $major_track = array(10000,15000,25000,50000,150000); // Military, Science, etc.
        $minor_track = array(8500,17000,34000); // Diplomacy, Trade, etc.
        
//        $major_holding = array(1000,4000,7000,10000,13000); // Starbase
//        $minor_holding = array(1000,3000,6000); // Embassy & Dilithium Mine
//        $major_track = array(10000,25000,50000,100000,250000); // Military, Science, etc.
//        $minor_track = array(8500,25000,59500); // Diplomacy, Trade, etc.
        
        $levels = array(
            'starbase' => $major_holding,
            'military' => $major_track,
            'engineering' => $major_track,
            'science' => $major_track,
            'embassy' => $minor_holding,
            'diplomacy' => $minor_track,
            'recruitment' => $minor_track,
            'mine' => $minor_holding,
            'trade' => $minor_track,
            'development' => $minor_track
        );
        
        $params = JComponentHelper::getParams('com_guilds');
        $xp = array();
        
        foreach($fleets as $code => $fleet) {
            if(!is_array($fleet)) {
                $xp[$code] = array();
                foreach($tracks as $holding) {
                    foreach($holding as $tier => $count) {
                        $xp[$code][$tier] = intval($params->get($code.'_'.$tier));
                    }
                }
            }
        }
        
        $numerals = array('I','II','III','IV','V');
        
        $this->assignRef('fleets',$fleets);
        $this->assignRef('holdings',$holdings);
        $this->assignRef('tracks',$tracks);
        $this->assignRef('levels',$levels);
        $this->assignRef('xp',$xp);
        $this->assignRef('numerals',$numerals);
        
        parent::display();
    }
}