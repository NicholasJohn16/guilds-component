<?php if(count($this->characters) == 0): ?>
	<div class="alert alert-block com-guilds-container" style="margin-bottom:0;float:none;">
		<a class="close" data-dismiss="alert" href="#">&times;</a>
		<strong>Uh oh!</strong>  This user doesn't have any characters yet.  Click the <strong>Add Character</strong> button below to add one!
	</div>
<?php else:; ?>
<div class="com-guilds-container">
	<div class="com-guilds-header">
		<div style="width:4%;">ID</div>
		<div style="width:2%"><input type="checkbox" class="checkall" title="Check All"/></div>
		<div style="width:10%;">Name</div>
		<?php foreach($this->types as $type):?>
			<div class="com-guilds-<?php echo $type->name; ?>"><?php echo ucwords($type->name); ?></div>
		<?php endforeach;?>
		<div style="width:8%">Checked</div>
		<div style="width:2%">Published</div>
	</div>
	<?php foreach($this->characters as $character):?>
	<div class="com-guilds-row" data-character="<?php echo $character->id; ?>">
		<div style="width:4%"><?php echo $character->id;?></div>
		<div style="width:2%"><input type="checkbox" name="character" value="<?php echo $character->id; ?>"/></div>
		<div style="width:10%" class="editable"><?php echo $character->name; ?></div>
		<?php foreach($this->types as $type):?>
			<?php $type_name = $type->name.'_name'; ?>
			<?php $type_id = $type->name.'_id'; ?>
			<select class="com-guilds-<?php echo $type->name;?> editable" data-update="<?php echo $type->name; ?>">
				<option value="">-- Select <?php echo ucwords($type->name);?> --</option>
				<?php foreach($this->categories as $category):?>
					<?php if($category->type == $type->name):?>
						<option value="<?php echo $category->id; ?>"<?php if($character->$type_id == $category->id){echo 'selected="selected"';}?> data-parent="<?php echo $category->parent; ?>"><?php echo $category->name;?></option>
					<?php endif;?>
				<?php endforeach;?>
			</select>
		<?php endforeach;?>
		<div class="editable date" style="width:8%"><?php echo $character->checked; ?></div>
		<div style="width:2%"><?php echo $character->published; ?></div>
	</div>
	<?php endforeach; ?>
	<?php dump($this->characters,"Characters");?>
</div>
<?php endif; ?>