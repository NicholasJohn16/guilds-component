<?php
/*
 * @package		Guilds Manager
 * @subpackage	Components
 * @link			http://www.nicholasjohn16.com
 * @license		GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');

?>
<div class="page-header">
        <h1><?php echo $this->title; ?></h1>
</div>
<form action="<?php echo JRoute::_('index.php?option=com_guilds&view=characters') ?>" method="post" class="form-horizontal">
    <fieldset style="float:left;border:0 none;padding:0;margin:0;">
        <legend><?php echo JText::_('Character Info'); ?></legend>
        <div class="control-group">
            <label class="control-label" for="name">Character Name</label>
            <div class="controls">
                <input type="text" name="name" size="32" value="<?php echo $this->character->name; ?>"/>
            </div>	
        </div>
        <div class="control-group">
            <label class="control-label" for="invite">Request an Invite?</label>
            <div class="controls">
                <label class="radio inline">
                    <input type="radio" name="invite" id="inlineCheckbox1" <?php if($this->character->invite) { echo 'checked="checked"'; } ?> value="1"> Yes
                </label>
                <label class="radio inline">
                    <input type="radio" name="invite" id="inlineCheckbox2" <?php if(!$this->character->invite) { echo 'checked="checked"'; } ?> value="0"> No
                </label>
            </div>
        </div>
    </fieldset>
    <fieldset style="float:left;border:0 none;padding:0;margin:0;">
        <legend>Categories</legend>
        <?php echo categoriesHelper::display($this->types, $this->categories,array('character'=>$this->character)); ?>
    </fieldset>
    <div style="clear:both"></div>
    <div class="form-actions">
        <div class="pull-right">
            <button class="btn btn-primary" type="submit">Submit</button>
            <a class="btn" href="<?php echo JRoute::_('index.php?com_guilds&view=characters'); ?>">Cancel</a>
        </div>
    </div>
    <input type="hidden" name="id" value="<?php echo $this->character->id; ?>" />
    <input type="hidden" name="user_id" value="<?php echo $this->character->user_id; ?>" />
    <input type="hidden" name="task" value="save" />
</form>