<?php if(count($this->characters) == 0): ?>
    <?php alertsHelper::display(); ?>
<?php else: ?>
<div class="com-guilds container-fluid">
    <div class="row-fluid header">
        <div class="span1"><input type="checkbox" class="checkall" title="Check All"/> ID</div>
        
        <div class="span2">Name</div>
        <?php foreach($this->types as $type):?>
            <div class="span2 com-guilds-<?php echo $type->name; ?>"><?php echo ucwords($type->name); ?></div>
        <?php endforeach;?>
        <div class="span2">Checked</div>
        <div class="span1">Published</div>
    </div>
    <?php foreach($this->characters as $character):?>
    <div class="row-fluid" data-character="<?php echo $character->id; ?>">
        <div class="span1"><input type="checkbox" name="character" value="<?php echo $character->id; ?>"/> <?php echo $character->id;?></div>
        
        <div class="span2 editable"><?php echo $character->name; ?></div>
        <?php foreach($this->types as $type):?>
            <?php $type_name = $type->name.'_name'; ?>
            <?php $type_id = $type->name.'_id'; ?>
            <div class="span2 com-guilds-<?php echo $type->name;?> editable" data-name="<?php echo $type->name; ?>"><?php echo $character->$type_name; ?></div>
        <?php endforeach;?>
        <div class="editable date span2"><?php echo $character->checked; ?></div>
        <div class="span1"><?php echo $character->published; ?></div>
    </div>
    <?php endforeach; ?>
</div>
<!-- Must be here to make sure that character list has proper bottom padding -->
<div style="clear:both"></div>
<?php endif; ?>