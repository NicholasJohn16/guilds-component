<?php
/*
 * @package		Guilds Manager
 * @subpackage	Components
 * @link			http://www.nicholasjohn16.com
 * @license		GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');

?>
<?php
    $document = JFactory::getDocument();
    $document->addScriptDeclaration("
        $(document).ready(function() {
            $('#datepicker').datepicker({
                format:'yyyy-mm-dd',
                todayBtn:'linked'
            });
        });
    ");
?>
<div class="page-header"><h1>Edit Character</h1></div>
<form action="<?php echo JRoute::_('index.php?option=com_guilds&view=characters') ?>" method="post" class="form-horizontal">
    <fieldset style="float:left;border:0 none;padding:0;margin:0;">
        <legend><?php echo JText::_('Character Info'); ?></legend>
        <div class="control-group">
            <label class="control-label" for="user_id">User ID</label>
            <div class="controls">
                <input type="text" id="user_id" name="user_id" size="32" readonly="readonly" value="<?php echo $this->character->user_id; ?>"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="username">Username</label>
            <div class="controls">
                <input type="text" id="username" name="username" readonly="readonly" size="32" value="<?php echo $this->character->username; ?>"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="name">Character Name</label>
            <div class="controls">
                <input type="text" name="name" size="32" value="<?php echo $this->character->name; ?>"/>
            </div>	
        </div>
<!--        <?php if($this->isAdmin): ?>
        <div class="control-group">
            <label class="control-label" for="checked">Checked</label>
            <div class="controls">
                <input type="text" name="checked" id="datepicker" value="<?php $this->character->checked ?>"/>
            </div>
        </div>-->
        <?php endif; ?>
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
        <?php if($this->isAdmin): ?>
<!--        <div class="control-group">
            <label class="control-label" for="published">Published</label>
            <div class="controls">
                <label class="radio inline">
                    <input type="radio" name="published" id="inlineCheckbox1" <?php if($this->character->published) { echo 'checked="checked"'; } ?> value="1"> Yes
                </label>
                <label class="radio inline">
                    <input type="radio" name="published" id="inlineCheckbox2" <?php if(!$this->character->published) { echo 'checked="checked"'; } ?> value="0"> No
                </label>
            </div>
        </div>-->
        <?php endif; ?>
    </fieldset>
    <fieldset style="float:left;border:0 none;padding:0;margin:0;">
        <legend>Categories</legend>
<?php foreach ($this->types as $type): ?>
                        <?php $type_id = $type->name . '_id'; ?>
            <div class="control-group">
                <label class="control-label" for="<?php echo $type->name; ?>"><?php echo ucfirst($type->name); ?></label>
                <div class="controls">
                    <select name="category[<?php echo $type->name; ?>]">
                        <option value=""><?php echo 'Select ' . ucfirst($type->name); ?></option>
                                <?php foreach ($this->categories as $category): ?>
                                    <?php if ($category->type == $type->name): ?>
                                <option value="<?php echo $category->id
                                        ?>" <?php if ($category->id == $this->character->$type_id) {
                        echo 'selected="selected"';
                    }
                    ?> data-parent="<?php echo $category->parent;
                    ?>"<?php if ($category->children != NULL) {
                        echo 'data-children="' . $category->children . '"';
                    }
                    ?>><?php echo $category->name;
                    ?></option>
        <?php endif; ?>
    <?php endforeach; ?>
                    </select>
                </div>
            </div>
<?php endforeach; ?>
    </fieldset>
    <div style="clear:both"></div>
    <div class="form-actions">
        <button class="btn btn-primary pull-right" type="submit">Submit</button>
            <a class="btn pull-right" href="<?php echo JRoute::_('index.php?com_guilds&view=characters'); ?>">Cancel</a>
    </div>
    <input type="hidden" name="id" value="<?php echo $this->character->id; ?>" />
    <input type="hidden" name="task" value="save" />
</form>