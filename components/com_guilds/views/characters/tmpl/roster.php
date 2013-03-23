<form action="<?php echo JRoute::_('index.php?option=com_guilds&view=characters&layout=roster'); ?>" method="post" id="roster-form">
	<div class="navbar">
		<div class="navbar-inner">
			<div style="width:100%;" class="container">
				<a class="brand" href="#">Roster</a>
				    <div class="navbar-search pull-left">
    					<input type="text" name="search" class="search-query" placeholder="Search" value="<?php echo $this->search; ?>">
    				</div>
    				<button class="btn btn-inverse btn-small" type="submit">Submit</button><button class="btn btn-inverse btn-small" type="reset">Reset</button>
				<ul class="nav pull-right">
					<li><a href="index.php?option=com_guilds&view=characters&layout=form" data-task="add">Add Character</a></li>
					<li><a href="#" data-task="edit">Edit Character</a></li>
					<li><a href="#" data-task="delete">Delete Character(s)</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="well" id="filters">
	<?php foreach($this->types as $type):?>
		<?php $filter_type = 'filter_'.$type->name;?>
		<select name="filter_type[<?php echo $type->name; ?>]">
			<option value="">Select <?php echo ucwords($type->name);?></option>
				<?php foreach($this->categories as $category):?>
					<?php if($category->type == $type->name): ?>
					<?php $selected = (@$this->filter_type[$type->name] == $category->id ? 'selected="selected"' : ""); ?>
					<option value="<?php echo $category->id;?>" <?php echo $selected;?>><?php echo ucfirst($category->name);?></option>
				<?php endif; ?>
			<?php endforeach;?>
		</select>
	<?php endforeach; ?>
	</div>
	<div class="com-guilds container-fluid">
            <div class="row-fluid header">
                <div class="span1"><input type="checkbox" name="toggle" value="" id="checkAll" /> <?php echo $this->sortable("ID");?></div>
                <div class="span2"><?php echo $this->sortable("Name");?></div>
                <div class="span2"><?php echo $this->sortable("Username");?></div>
                <?php foreach($this->types as $type):?>
                    <?php $type_name = $type->name.'_name'; ?>
                    <div class="span2 com-guilds-<?php echo $type->name;?>"><?php echo $this->sortable(ucfirst($type->name)); ?></a></div>
                <?php endforeach; ?>
                <div class="span2"><?php echo $this->sortable("Checked");?></div>
                <div class="span1"><?php echo $this->sortable("Published");?></div>
            </div>
            <?php $i=0; ?>
            <?php foreach($this->characters as $character):?>
                <div class="row-fluid">
                    <div class="span1"><input id="cb<?php echo $i ?>" type="checkbox" value="<?php echo $character->id; ?>" name="characters[]"/> <a href="index.php?option=com_guilds&view=characters&task=edit&character=<?php echo $character->id;?>"><?php echo $character->id;?></a></div>
                    <div class="span2 editable" data-name="name" data-pk="<?php echo $character->id; ?>" data-title="Edit Character Name"><?php echo $character->name;?></div>
                    <div class="span2" title="<?php //echo $character->sto_handle;?>"><?php //echo $character->sto_handle;?></div>
                    <?php foreach($this->types as $type):?>
                        <?php $type_name = $type->name.'_name'; ?>
                        <?php $type_id = $type->name.'_id'; ?>
                        <div class="span2 editable com-guilds-<?php echo $type->name;?>" data-type="select" data-value="<?php echo $character->$type_id; ?>" data-title="Edit <?php echo ucfirst($type->name); ?>" data-pk="<?php echo $character->id; ?>" data-source="index.php?option=com_guilds&view=categories&format=json&type=<?php echo $type->name; ?>" data-name="<?php echo $type->name; ?>"><?php echo $character->$type_name; ?></div>
                    <?php endforeach; ?>
                    <div class="span2 editable" data-type="date" data-placement="right" data-name="checked" data-title="Edit Checked Date" data-pk="<?php echo $character->id; ?>"><?php echo $character->checked;?></div>
                    <?php $pub = array('title'=>array('Unpublished','Published'),'icon'=>array('eye-close icon-white','eye-open'),'task'=>array('publish','unpublish'),'class'=>array('btn-inverse','')); ?>
                    <div class="span1">
                        <a title="<?php echo $pub['title'][$character->published]; ?>" class="btn btn-mini <?php echo $pub['class'][$character->published]; ?>" href="<?php echo JRoute::_('index.php?option=com_guilds&view=characters&task='.$pub['task'][$character->published].'&id='.$character->id); ?>">
                            <i class="icon-<?php echo $pub['icon'][$character->published]; ?>"></i>
                        </a>
                    </div>
                </div>
            <?php $i++; ?>
            <?php endforeach; ?>
	</div>
<!--	<input type="hidden" name="option" value="com_guilds"/>
	<input type="hidden" name="view" value="characters"/>
	<input type="hidden" name="layout" value="roster"/>-->
	<input type="hidden" name="task" value=""/>
<!--	<input type="hidden" name="Itemid" value="99999999"/>-->
	<input type="hidden" name="order" value="<?php echo $this->order; ?>"/>
	<input type="hidden" name="direction" value="<?php echo $this->direction; ?>"/>
	<input type="hidden" name="limitstart" value="<?php echo $this->pagination->limitstart;?>"/>
	<div style="clear:both"></div>
	<?php echo $this->pagination(); ?>
</form>

