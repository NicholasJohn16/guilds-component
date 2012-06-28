<form action="index.php" method="post" id="roster-form">
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
	<div class="com-guilds-container">
		<div class="com-guilds-header">
			<div style="width:4%"><?php echo $this->sortable("ID");?></div>
			<div style="width:2%"><input type="checkbox" name="toggle" value="" id="checkAll" /></div>
			<div style="width:4%"><?php echo $this->sortable("User ID");?></div>
			<div style="width:10%"><?php echo $this->sortable("Name");?></div>
			<div style="width:12%"><?php echo $this->sortable("Username");?></div>
			<?php foreach($this->types as $type):?>
				<?php $type_name = $type->name.'_name'; ?>
				<div class="com-guilds-<?php echo $type->name;?>"><?php echo $this->sortable(ucfirst($type->name)); ?></a></div>
			<?php endforeach; ?>
			<div style="width:8%"><?php echo $this->sortable("Checked");?></div>
			<div style="width:2%"><?php echo $this->sortable("Published");?></div>
		</div>
		<?php $i=0; ?>
		<?php foreach($this->characters as $character):?>
			<div class="com-guilds-row">
				<div style="width:4%"><?php echo $character->id; //$i+1+$this->pagination->limitstart;?></div>
				<div style="width:2%"><input id="cb<?php echo $i ?>" type="checkbox" value="<?php echo $character->id; ?>" name="characters[]"/></div>
				<div style="width:4%"><?php echo $character->user_id; //$character->id; ?></div>
				<div style="width:10%" title="<?php echo $character->name;?>">
					<a href="index.php?option=com_guilds&view=characters&task=edit&character=<?php echo $character->id;?>">
						<?php echo $character->name;?>
					</a>
				</div>
				<div style="width:12%" title="<?php echo $character->username;?>"><?php echo $character->username;?></div>
				<?php foreach($this->types as $type):?>
					<?php $type_name = $type->name.'_name'; ?>
					<?php $type_id = $type->name.'_id'; ?>
					<select class="com-guilds-<?php echo $type->name;?>" >
						<option value="">-- Select <?php echo ucwords($type->name);?> --</option>
						<?php foreach($this->categories as $category):?>
							<?php if($category->type == $type->name):?>
								<option value="<?php echo $category->id; ?>"<?php if($character->$type_id == $category->id){echo 'selected="selected"';}?> data-parent="<?php echo $category->parent; ?>"><?php echo $category->name;?></option>
							<?php endif;?>
						<?php endforeach;?>
					</select>
				<?php endforeach; ?>
				<div style="width:8%"><?php echo $character->checked;?></div>
				<div style="width:2%">
					<?php
						if($character->published == 1) {
							echo '<img class="com-guilds-icon" src="components/com_guilds/media/img/accept.png" alt="Published" title="Published">';
						} else {
							echo '<img class="com-guilds-icon" src="components/com_guilds/media/img/cancel.png" alt="Unpublished" title="Unpublished">';
						}
					?>
				</div>
			</div>
		<?php $i++; ?>
		<?php endforeach; ?>
	</div>
	<input type="hidden" name="option" value="com_guilds"/>
	<input type="hidden" name="view" value="characters"/>
	<input type="hidden" name="layout" value="roster"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="Itemid" value="99999999"/>
	<input type="hidden" name="order" value="<?php echo $this->order; ?>"/>
	<input type="hidden" name="direction" value="<?php echo $this->direction; ?>"/>
	<input type="hidden" name="limitstart" value="<?php echo $this->pagination->limitstart;?>"/>
	<div style="clear:both"></div>
	<?php echo $this->pagination(); ?>
</form>

