<?php
    /**
     * Joomla! 1.5 Component Guilds Manager
     *
     * @version $Id: controller.php 2011-10-28 10:20:36 svn $
     * @author Nick Swinford
     * @package Joomla
     * @subpackage Guilds Manager
     * @license Copyright (c) 2011 - All Rights Reserved
     */

    // no direct access
    defined('_JEXEC') or die('Restricted access');

    jimport('joomla.application.component.controller');

    /**
     * Guilds Manager Component Controller
     */
    class GuildsControllerMembers extends JController {

        function __construct() {
            global $mainframe;
            $this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models');
            $user =& JFactory::getUser();
            if($user->guest){$mainframe->redirect('index.php?option=com_user&view=login');}

            parent::__construct();
        } 
	
	function display(){
                $view = $this->getView('members','html');
                $mTypes = $this->getModel('types');
                $mCategories = $this->getModel('categories');
                $mMembers = $this->getModel('members');
                $view->setModel($mMembers,true);
                $view->setModel($mTypes);
                $view->setModel($mCategories);
		$view->display();
	}
	
        function edit() {
		$id = JRequest::getVar('id',null,'','int');
		
		if($id === null){
			// Report an error if there was no id
			JError::raiseError(500,'ID parameter missing from the request');
		}
		$view = $this->getView("members","html");
                
		$model = $this->getModel("Members");
		$model->setState('id',$id);
		$view->setModel($model,true);
		$view->setLayout('form');
		
		$view->display();
	}
        
        function save() {
            $post = JRequest::get('post');
            dump($post);
            $id = JRequest::getVar('id',null,'','int');
            $sto_handle = JRequest::getVar('sto_handle',null,'','string');
            $tor_handle = JRequest::getVar('tor_handle',null,'','string');
            $gw2_handle = JRequest::getVar('gw2_handle',null,'','string');
            $appdate = (JRequest::getVar('appdate',null,'','string') == "") ? NULL : JRequest::getVar('appdate',null,'','string');
            $notes = JRequest::getVar('notes',null,'','string');
            
            $model = $this->getModel('members');
            $model->setState('id',$id);
            $model->setState('sto_handle',$sto_handle);
            $model->setState('tor_handle',$tor_handle);
            $model->setState('gw2_handle',$gw2_handle);
            $model->setState('appdate',$appdate);
            $model->setState('notes',$notes);
            $result = $model->save();
            
            if($result) {
                alertsHelper::alert(array('title'=>'Member edited.','msg'=>'The modifications were saved','class'=>'success'));
            } else {
                alertsHelper::alert(array('title'=>'Edit failed.','msg'=>'The modifications weren\'t saved','class'=>'error'));
            }
            
            $this->setRedirect(JRoute::_('index.php?option=com_guilds&view=members'));
        }
        
	function update() {
            $name = JRequest::getVar('name',NULL,'','string');
            $id = JRequest::getVar('pk',NULL,'','int');
            $value = JRequest::getVar('value',NULL,'','string');
            $value = ($value == '') ? NULL : $value;
            $model = $this->getModel('members');
            dump($value,'Value');
            dump($name,'Name');
            if($name == NULL || $id == NULL ) {
                JError::raiseError('500','Invalid Request');
            }
            
            $model->update($name,$id,$value);
            
        }
	
	function getRanks() {
		$model = $this->getModel('members');
		$ranks = $model->getRanks();
                echo json_encode($ranks);
	}
}
?>