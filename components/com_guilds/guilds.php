<?php
    // no direct access
    defined('_JEXEC') or die('Restricted access');

    // Require the base controller
    require_once JPATH_COMPONENT.DS.'controller.php';
    
    //$session =& JFactory::getSession();
    //$registry = $session->get('registry');
    //dump($registry,'Registry');

    // Include the Alerts helper
    include_once JPATH_COMPONENT.DS.'helpers'.DS.'alertsHelper.php';
    // Display stored alerts
    alertsHelper::display();

    //$script = "$('.bottom-right').notify({message:'This is the msg.'}).show();";
    //$document =& JFactory::getDocument();
    //$document->addScriptDeclaration($script);

    // Require specific controll if requested
    if($controller = JRequest::getWord('view')) {
            $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
            if(file_exists($path)) {
                    require_once($path);
            } else {
                    $controller = '';
            }
    }

    // Initialize the controller
    $classname = 'GuildsController'.$controller;
    $controller = new $classname();

    //Perform the Request task
    $controller->execute(JRequest::getWord('task'));

    // Redirect if set by the controller
    $controller->redirect();
	
	