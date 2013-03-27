<?php
    $document = JFactory::getDocument();
    $document->addScriptDeclaration("
            
    ");
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
                    <input type="text" name="username" tabindex="1" id="username" value=""/>
                    <input type="hidden" name="user" id="user"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="character_name">Character Name</label>
                <div class="controls">
                    <input type="text" tabindex="2" id="character_name" name="character_name" size="32" value=""/>
                </div>	
            </div>
            <div class="control-group">
                <label class="control-label" for="checked">Checked</label>
                <div class="controls">
                    <input size="16" type="text" tabindex="3" value="" id="date">
                </div>
            </div>
        </fieldset>
        <fieldset style="float:left;border:0 none;padding:0;margin:0;">
            <legend>Categories</legend>
            <?php $tab = 4; ?>
            <?php foreach ($this->types as $type): ?>
                <?php $type_id = $type->name . '_id'; ?>
                <div class="control-group">
                    <label class="control-label" for="<?php echo $type->name; ?>"><?php echo ucfirst($type->name); ?></label>
                    <div class="controls">
                        <select tabindex="<?php echo $tab; ?>" name="category[<?php echo $type->name; ?>]">
                            <option value=""><?php echo 'Select ' . ucfirst($type->name); ?></option>
                            <?php foreach ($this->categories as $category): ?>
                                <?php if ($category->type == $type->name): ?>
                                    <option value="<?php echo $category->id
                                    ?>" data-parent="<?php echo $category->parent;
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
    <?php $tab++; ?>
<?php endforeach; ?>
        </fieldset>
    </div>
    <div style="clear:both"></div>
    <div class="modal-footer" style="text-align:right;">
        <button id="close" class="btn">Cancel</button>
        <input tabindex="<?php echo $tab; ?>" type="submit" class="btn btn-primary" value="Add" />
    </div>
</form>