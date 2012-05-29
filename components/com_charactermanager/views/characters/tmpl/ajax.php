<div class="com-mm-container" id="one">
	<div class="com-mm-header">
		<div style="width:4%;">ID</div>
		<div style="width:2%"><input type="checkbox" class="checkall" title="Check All"/></div>
		<div style="width:10%;">Name</div>
		<?php foreach($this->types as $type):?>
			<div class="com-mm-<?php echo $type->name; ?>"><?php echo ucwords($type->name); ?></div>
		<?php endforeach;?>
		<div style="width:8%">Checked</div>
		<div style="width:2%">Published</div>
	</div>
	<?php foreach($this->characters as $character):?>
	<div class="com-mm-row">
		<div style="width:4%"><?php echo $character->id;?></div>
		<div style="width:2%"><input type="checkbox" name="character" value="<?php echo $character->id; ?>"/></div>
		<div style="width:10%" class="editable"><?php echo $character->name; ?></div>
		<?php foreach($this->types as $type):?>
			<?php $type_name = $type->name.'_name'; ?>
			<?php $type_id = $type->name.'_id'; ?>
			<div class="com-mm-<?php echo $type->name;?>">
				<select>
					<option value="">-- Select <?php echo ucwords($type->name);?> --</option>
					<?php foreach($this->categories as $category):?>
						<?php if($category->type == $type->name):?>
							<option value="<?php echo $category->id; ?>"<?php if($character->$type_id == $category->id){echo 'selected="selected"';}?> data-parent="<?php echo $category->parent; ?>"><?php echo $category->name;?></option>
						<?php endif;?>
					<?php endforeach;?>
				</select>
			</div>
		<?php endforeach;?>
		<div class="editable date" style="width:8%"><?php echo $character->rosterchecked; ?></div>
		<div style="width:2%"><?php echo $character->published; ?></div>
	</div>
	<?php endforeach; ?>
</div>
<div style="clear:both"></div>
<?php echo $this->pagination();?>