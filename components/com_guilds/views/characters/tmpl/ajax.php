<?php if(count($this->characters) == 0): ?>
    <?php alertsHelper::display(); ?>
<?php else: ?>
<div class="com-guilds container-fluid">
    <?php dump($this->characters); ?>
    <div class="row-fluid header">
        <div class="span1"><input type="checkbox" class="checkall" title="Check All"/> ID</div>
        <div class="span2">Name</div>
        <?php foreach($this->types as $type):?>
            <div class="span2 com-guilds-<?php echo $type->name; ?>"><?php echo ucwords($type->name); ?></div>
        <?php endforeach;?>
        <div class="span2">Checked</div>
        <div class="span2">Published</div>
    </div>
    <?php foreach($this->characters as $character):?>
    <div class="row-fluid" data-character="<?php echo $character->id; ?>">
        <div class="span1" title="<?php echo $character->id; ?>"><input type="checkbox" name="character" value="<?php echo $character->id; ?>"/> <?php echo $character->id;?></div>
        <div class="span2 editable-click" data-title="Edit Character Name" data-name="name" data-pk="<?php echo $character->id; ?>"><?php echo $character->name; ?></div>
        <?php foreach($this->types as $type):?>
            <?php $type_name = $type->name.'_name'; ?>
            <?php $type_id = $type->name.'_id'; ?>
            <div class="span2 com-guilds-<?php echo $type->name;?> editable-click category" data-title="Select Category" data-type="select" data-pk="<?php echo $character->id; ?>" data-name="<?php echo $type->name; ?>" data-source="index.php?option=com_guilds&view=categories&format=json&type=<?php echo $type->name; ?>" data-value="<?php echo $character->$type_id; ?>"><?php echo $character->$type_name; ?></div>
        <?php endforeach;?>
        <div class="editable-click span2" data-type="date" data-name="checked" data-placement="right" data-title="Update Date" data-pk="<?php echo $character->id; ?>"><?php echo $character->checked; ?></div>
        <div class="span1"><?php echo $character->published; ?></div>
        <div class="span2"><?php echo $character->unpublisheddate; ?></div>
    </div>
    <?php endforeach; ?>
</div>
<!-- Must be here to make sure that character list has proper bottom padding -->
<div style="clear:both"></div>
<?php endif; ?>