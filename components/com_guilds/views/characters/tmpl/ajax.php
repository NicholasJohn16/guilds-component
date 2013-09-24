<?php if(count($this->characters) == 0): ?>
    <div class="alert alert-block alert-info" style="margin-bottom:0px;">
        <h4 class="alert-heading">No Characters Found</h4>
        <p>This member doesn't have any characters yet.  To add a character, click the <strong>Add Character</strong> button below.</p>
    </div>
<?php else: ?>
<div class="com-guilds container-fluid">
    <div class="row-fluid header">
        <div class="span1"><input type="checkbox" class="checkall" title="Check All"/> ID</div>
        <div class="span1">Name</div>
        <div class="span2">Character Handle</div>
        <?php foreach($this->types as $type):?>
            <div class="span2 com-guilds-<?php echo $type->name; ?>"><?php echo ucwords($type->name); ?></div>
        <?php endforeach;?>
        <div class="span2 com-guilds-checked">Checked</div>
        <div class="span1">Published</div>
    </div>
    <?php foreach($this->characters as $character):?>
    <div class="row-fluid" data-character="<?php echo $character->id; ?>">
        <div class="span1" 
             title="<?php echo $character->id; ?>">
            <input type="checkbox" 
                   name="character" 
                   value="<?php echo $character->id; ?>"/> 
                       <?php echo $character->id;?>
        </div>
        <div class="span1 editable-click" 
             data-title="Edit Character Name" 
             data-name="name" 
             data-pk="<?php echo $character->id; ?>">
                 <?php echo $character->name; ?>
        </div>
        <div class="span2 editable-click" 
             data-title="Set Character Handle"
             data-name="handle"
             data-pk="<?php echo $character->id; ?>">
            <?php echo $character->handle; ?>
        </div>
        <?php foreach($this->types as $type):?>
            <?php $type_name = $type->name.'_name'; ?>
            <?php $type_id = $type->name.'_id'; ?>
            <div class="span2 com-guilds-<?php echo $type->name;?> editable-click category" 
                 data-title="Select Category" data-type="select" 
                 data-pk="<?php echo $character->id; ?>" 
                 data-name="category[<?php echo $type->name; ?>]" 
                 data-source="index.php?option=com_guilds&view=categories&format=json&type=<?php echo $type->name; ?>" 
                 data-value="<?php echo $character->$type_id; ?>"><?php echo $character->$type_name; ?></div>
        <?php endforeach;?>
        <div class="editable-click span2 com-guilds-checked" 
             data-type="date" 
             data-name="checked" 
             data-placement="right" 
             data-title="Update Date" 
             data-pk="<?php echo $character->id; ?>">
             <?php echo $character->checked; ?>
        </div>
        <?php $pub = array('title'=>array('Unpublished','Published'),
                           'icon'=>array('eye-close icon-white','eye-open'),
                           'task'=>array('publish','unpublish'),
                           'class'=>array('btn-inverse','')); ?>
        <div class="span1">
            <button title="<?php echo $pub['title'][$character->published] . " Character"; ?>" 
                    class="btn btn-small publish <?php echo $pub['class'][$character->published]; ?>" 
                    data-task="<?php echo $pub['task'][$character->published]; ?>" 
                    data-id="<?php echo $character->id; ?>">
                <i class="icon-<?php echo $pub['icon'][$character->published]; ?>"></i>
            </button>
        </div>
        <div class="span2 com-guilds-pubdate"><?php echo $character->unpublished_date; ?></div>
    </div>
    <?php endforeach; ?>
</div>
<!-- Must be here to make sure that character list has proper bottom padding -->
<div style="clear:both"></div>
<?php endif; ?>