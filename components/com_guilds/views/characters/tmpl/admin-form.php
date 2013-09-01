<?php
    $document = JFactory::getDocument();
    $document->addScript('components/com_guilds/media/js/character-form.js');
    $document->addScript('components/com_guilds/media/js/category-validation.js');
    $document->addScript('components/com_guilds/media/js/select2.min.js');
    $document->addStylesheet('components/com_guilds/media/css/select2.css');
?>
<form class="form-horizontal" id="character-form">
    <div class="modal-header" style="background-color:#F5F5F5;border-bottom:1px solid #DDDDDD;box-shadow: 0 -1px 0 #FFFFFF inset;">
        <button class="close" data-dismiss="modal">&times;</button>
        <h3>Add Character</h3>
    </div>
    <div class="modal-body">
        <fieldset style="float:left;border:0 none;padding:0;margin:0;">
            <legend><?php echo JText::_('Character Info'); ?></legend>
            <div class="control-group">
                <label class="control-label" for="username">User</label>
                <div class="controls">
                    <input tabindex="1" type="hidden" name="user_id" id="character-form-user_id" style="width:220px;"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="name">Character Name</label>
                <div class="controls">
                    <input tabindex="2" type="hidden" name="name" id="character-form-character" style="width:220px;"/>
                    <!-- <input type="text" tabindex="2" id="name" name="name" size="32" value=""/> -->
                </div>	
            </div>
            <div class="control-group">
                <label class="control-label" for="name">Handle</label>
                <div class="controls">
                    <a href="#" tabindex="3" style="line-height:28px;" id="character-form-handle-link">Add a different handle for this character?</a>
                    <input style="display:none;" tabindex="3" type="text" id="character-form-handle" name="handle" size="32" value=""/>
                    <a href="#" style="margin-left:5px;display:none;" id="character-form-handle-cancel">Cancel</a>
                </div>	
            </div>
            <div class="control-group">
                <label class="control-label" for="checked">Checked</label>
                <div class="controls">
                    <input size="16" type="text" tabindex="3" value="" name="checked" id="character-form-checked">
                </div>
            </div>
        </fieldset>
        <fieldset style="float:left;border:0 none;padding:0;margin:0;">
            <legend>Categories</legend>
            
            <?php $tab = 4; ?>
            <?php echo categoriesHelper::display($this->types,$this->categories,array('tab'=>4,'id_prefix'=>'character-form')); ?>
        </fieldset>
    </div>
    <div style="clear:both"></div>
    <div class="modal-footer" style="text-align:right;">
        <button id="character-form-close" class="btn">Cancel</button>
        <input tabindex="<?php echo $tab; ?>" id="character-form-submit" type="submit" class="btn btn-primary" value="Add" />
    </div>
</form>