<h2>Pending Invites</h2>
<?php if(count($this->invites) == 0): ?>
    <div class="alert alert-block alert-info">
        <h4 class="alert-heading">No Pending Invites!</h4>
        <p>Yay! Go relax and play the game!</p>
    </div>
<?php else: ?>
<div class="com-guilds container-fluid">
    <div class="row-fluid header">
        <div class="span2">Name</div>
        <div class="span2">Handle</div>
        <?php foreach($this->types as $type):?>
            <div class="span2 com-guilds-<?php echo $type->name; ?>">
                <?php echo ucwords($type->name); ?>
            </div>
        <?php endforeach;?>
        <div class="span2 com-guilds-checked">Checked</div>
        <div class="span2">Actions</div>
    </div>
    <?php foreach($this->invites as $invite):?>
    <div class="row-fluid">
        <div class="span2 editable-click" 
             data-title="Edit Character Name" 
             data-name="name" 
             data-pk="<?php echo $invite->id; ?>">
             <?php echo $invite->name; ?>
        </div>
        <?php if($invite->handle === NULL): ?>
            <div class="span2 editable-click" 
                 data-title="Update Handle" 
                 data-name="sto_handle" 
                 data-pk="<?php $invite->id; ?>">
                 <?php echo $invite->sto_handle; ?>
            </div>
        <?php else: ?>
            <div class="span2 editable-click" 
                 data-title="Update Handle" 
                 data-name="handle" 
                 data-pk="<?php $invite->id; ?>">
                 <?php echo $invite->handle; ?>
            </div>
        <?php endif; ?>
        <?php foreach($this->types as $type):?>
            <?php $type_name = $type->name.'_name'; ?>
            <?php $type_id = $type->name.'_id'; ?>
            <div class="span2 com-guilds-<?php echo $type->name;?> editable-click" 
                 data-title="Select Category" 
                 data-type="select" 
                 data-pk="<?php echo $invite->id; ?>" 
                 data-name="<?php echo $type->name; ?>" 
                 data-source="index.php?option=com_guilds&view=categories&format=json&type=<?php echo $type->name; ?>" 
                 data-value="<?php echo $invite->$type_id; ?>">
                 <?php echo $invite->$type_name; ?>
            </div>
        <?php endforeach;?>
        <div class="editable-click span2 com-guilds-checked" 
             data-type="date" 
             data-name="checked" 
             data-placement="right" 
             data-title="Update Date" 
             data-pk="<?php echo $invite->id; ?>">
             <?php echo $invite->checked; ?>
        </div>
        <div class="span2">
            <a class="btn btn-mini" 
               title="Invite Sent!" 
               href="<?php echo JRoute::_('index.php?option=com_guilds&view=characters&task=invited&id='.$invite->id); ?>">
                <i class="icon-share"></i>
            </a>
        </div>
    </div>
    <?php endforeach; ?>
<!-- Must be here to make sure that character list has proper bottom padding -->
<div style="clear:both"></div>
<?php endif; ?>
</div>
<h2>Pending Promotions</h2>
<?php if(count($this->promotions) == 0): ?>
    <div class="alert alert-block alert-info">
        <h4 class="alert-heading">No Pending Promotions!</h4>
        <p>Hurrah! Nothing to take care of here! Go watch some tv, maybe?</p>
    </div>
<?php else: ?>
<div class="com-guilds container-fluid">
    <div class="row-fluid header">
        <div class="span2">Name</div>
        <div class="span2">Handle</div>
        <?php foreach($this->types as $type):?>
            <div class="span2 com-guilds-<?php echo $type->name; ?>">
                <?php echo ucwords($type->name); ?>
            </div>
        <?php endforeach;?>
        <div class="span2 com-guilds-appdate">Intro Date</div>
        <div class="span2 com-guilds-checked">Checked</div>
        <div class="span1">Actions</div>
    </div>
    <?php foreach($this->promotions as $promotion):?>
    <div class="row-fluid">
        <div class="span2 editable-click" 
             data-title="Edit Character Name" 
             data-name="name" 
             data-pk="<?php echo $promotion->id; ?>">
                 <?php echo $promotion->name; ?>
        </div>
        <?php if($invite->handle === NULL): ?>
            <div class="span2 editable-click" 
                 data-title="Update Handle" 
                 data-name="sto_handle" 
                 data-pk="<?php $promotion->id; ?>">
                 <?php echo $promotion->sto_handle; ?>
            </div>
        <?php else: ?>
            <div class="span2 editable-click" 
                 data-title="Update Handle" 
                 data-name="handle" 
                 data-pk="<?php $promotion->id; ?>">
                 <?php echo $promotion->handle; ?>
            </div>
        <?php endif; ?>
        <?php foreach($this->types as $type):?>
            <?php $type_name = $type->name.'_name'; ?>
            <?php $type_id = $type->name.'_id'; ?>
            <div class="span2 com-guilds-<?php echo $type->name;?> editable-click" 
                 data-title="Select Category" 
                 data-type="select" 
                 data-pk="<?php echo $promotion->id; ?>" 
                 data-name="<?php echo $type->name; ?>" 
                 data-source="index.php?option=com_guilds&view=categories&format=json&type=<?php echo $type->name; ?>" 
                 data-value="<?php echo $promotion->$type_id; ?>">
                 <?php echo $promotion->$type_name; ?>
            </div>
        <?php endforeach;?>
        <div class="span2 com-guilds-appdate">
            <?php echo $promotion->appdate; ?>
        </div>
        <div class="editable-click span2 com-guilds-checked" 
             data-type="date" data-name="checked" 
             data-placement="right" 
             data-title="Update Date" 
             data-pk="<?php echo $promotion->id; ?>">
             <?php echo $promotion->checked; ?>
        </div>
        <div class="span1">
            <a class="btn btn-mini" 
               title="Character Promoted!" 
               href="<?php echo JRoute::_('index.php?option=com_guilds&view=characters&layout=pending&task=promoted&id='.$promotion->id); ?>">
                <i class="icon-check"></i>
            </a>
        </div>
    </div>
    <?php endforeach; ?>
<!-- Must be here to make sure that character list has proper bottom padding -->
<div style="clear:both"></div>
<?php endif; ?>
</div>