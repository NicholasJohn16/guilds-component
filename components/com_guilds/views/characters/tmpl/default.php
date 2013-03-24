<div class="container-fluid">
    <div class="row">
        <div class="btn-toolbar">
            <div class="btn-group">
                <a class="btn" href="<?php echo JRoute::_('index.php?option=com_guilds&view=characters&layout=form'); ?>"><i class="icon-plus"></i> Add Character</a>
            </div>
        </div>
    </div>
</div>
<?php if(count($this->characters) == 0): ?>
    <div class="alert alert-block alert-info">
        <h4 class="alert-heading">No Characters Found</h4>
        <p>You don't have any characters added to your profile yet. Click the <strong>Add Character</strong> button above to add your first character!</p>
    </div>
<?php else: ?>
<?php dump($this->characters); ?>
<div class="com-guilds container-fluid">
    <div class="row-fluid header">
        <div class="span2">Name</div>
        <?php foreach($this->types as $type):?>
            <div class="span2 com-guilds-<?php echo $type->name; ?>"><?php echo ucwords($type->name); ?></div>
        <?php endforeach;?>
        <div class="span2">Checked</div>
        <div class="span2">Actions</div>
    </div>
    <?php foreach($this->characters as $character):?>
    <div class="row-fluid" data-character="<?php echo $character->id; ?>">
        <div class="span2 editable-click" data-title="Edit Character Name" data-name="name" data-pk="<?php echo $character->id; ?>"><?php echo $character->name; ?></div>
        <?php foreach($this->types as $type):?>
            <?php $type_name = $type->name.'_name'; ?>
            <?php $type_id = $type->name.'_id'; ?>
            <div class="span2 com-guilds-<?php echo $type->name;?> editable-click category" data-title="Select Category" data-type="select" data-pk="<?php echo $character->id; ?>" data-name="<?php echo $type->name; ?>" data-source="index.php?option=com_guilds&view=categories&format=json&type=<?php echo $type->name; ?>" data-value="<?php echo $character->$type_id; ?>"><?php echo $character->$type_name; ?></div>
        <?php endforeach;?>
        <div class="span2" data-type="date" data-name="checked" data-placement="right" data-title="Update Date" data-pk="<?php echo $character->id; ?>"><?php echo $character->checked; ?></div>
        <div class="span2">
            <a class="btn btn-mini <?php if($character->invite) {echo "disabled"; } ?>" title="<?php if(!$character->invite) {echo "Request an Invite"; } else { echo "Invite Pending"; } ?>" href="<?php echo JRoute::_('index.php?option=com_guilds&view=characters&task=invite&id='.$character->id); ?>"><i class="icon-share"></i></a>
            <a class="btn btn-mini btn-danger" title="Delete Character"  href="<?php echo JRoute::_('index.php?option=com_guilds&view=characters&task=drop&id='.$character->id); ?>"><i class="icon-remove icon-white"></i></a>
        </div>
    </div>
    <?php
        if($character->invite) {
            dump('Invite: True!');
        } else {
            dump('Invite: False :(');
        }
    ?>
    <?php endforeach; ?>
</div>
<!-- Must be here to make sure that character list has proper bottom padding -->
<div style="clear:both"></div>

<?php endif; ?>
<div class="container-fluid" style="margin-top:10px;">
    <div class="row-fluid">
        <div class="span4">
            <h4>Editing a Character</h4>
            <p>Editing a character is as simple as clicking on the field above, 
            making your corrections in the popup and saving them!</p>
        </div>
        <div class="span4">
            <h4>What is Checked?</h4>
            <p>The date listed in the checked column is the last time that this 
                character was manually checked to be in the listed guild.  This
            field is updated regularly.</p>
        </div>
        <div class="span4">
            <h4>Request an Invite</h4>
            <p>When adding a new character, set Request Invite to Yes or with a 
                current character just click the invite button (<button class="btn"><i class="icon-share"></i></button>).</p>
        </div>
    </div>
</div>