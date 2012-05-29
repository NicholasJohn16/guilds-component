<?php
	/*
	* @package		Character Manager
	* @subpackage	Components
	* @link			http://www.nicholasjohn16.com
	* @license		GNU/GPL
	*/
	
	defined('_JEXEC') or die('Restricted access'); ?>
	<div class="page-header"><h1>Edit Character</h1></div>
	<form action="index.php" method="post" class="form-horizontal">
		<fieldset style="float:left;border:0 none;padding:0;margin:0;">
			<legend><?php echo JText::_('Character Info'); ?></legend>
			<div class="control-group">
				<label class="control-label" for="user_id">User ID</label>
				<div class="controls">
					<input type="text" id="username" name="user_id" size="32" readonly="readonly" value="<?php echo $this->character->user_id; ?>"/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="username">Username</label>
				<div class="controls">
					<input type="text" id="username" name="username" readonly="readonly" size="32" value="<?php echo $this->character->username; ?>"/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="character_name">Character Name</label>
				<div class="controls">
					<input type="text" name="name" size="32" value="<?php echo $this->character->name; ?>"/>
				</div>	
			</div>
			<div class="control-group">
				<label class="control-label" for="checked">Last Checked</label>
				<div class="controls">
					<div class="input-append">
						
						<input type="text" name="checked" value="<?php echo $this->character->rosterchecked; ?>"/>
						<button class="btn" type="button" style="height:28px;margin-left:-4px;"><i class="icon-calendar"></i></button>
					</div>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="published">Published</label>
				<div class="controls">
<!--					<div class="btn-group" data-toggle="buttons-radio" id="published">-->
<!--						<button class="btn" title="Published"><i class="icon-eye-open"></i></button>-->
<!--						<button class="btn" title="Unpublished"><i class="icon-eye-close"></i></button>-->
<!--					</div>-->
					<label class="radio">
						<input type="radio" <?php if($this->character->published == "1"){echo 'checked="checked"';}?> value="1" name="published">
						Yes
					</label>
					<label class="radio">
						<input type="radio" <?php if($this->character->published == "0"){echo 'checked="checked"';}?> value="0" name="published">
						No
					</label>
				</div>
			</div>
		</fieldset>
		<fieldset style="float:left;border:0 none;padding:0;margin:0;">
			<legend>Categories</legend>
				<?php foreach($this->types as $type):?>
				<?php $type_id = $type->name.'_id';?>
				<div class="control-group">
					<label class="control-label" for="<?php echo $type->name;?>"><?php echo ucfirst($type->name);?></label>
					<div class="controls">
						<select>
							<option value=""><?php echo 'Select '.ucfirst($type->name);?></option>
							<?php foreach($this->categories as $category):?>
								<?php if($category->type == $type->name): ?>
									<option value="<?php echo $category->id
									?>" <?php if($category->id == $this->character->$type_id){echo 'selected="selected"';}
									?> data-parent="<?php echo $category->parent;
									?>"<?php if($category->children != NULL){echo 'data-children="'.$category->children.'"';}
									?>><?php echo $category->name;
									?></option>
								<?php endif;?>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<?php endforeach;?>
		</fieldset>
		<div style="clear:both"></div>
		<div class="form-actions" style="padding-left:685px;">
			<button class="btn btn-primary" type="submit">Submit</button>
			<button class="btn">Cancel</button>
		</div>
		<input type="hidden" name="option" value="com_charactermanager" />
		<input type="hidden" name="view" value="characters" />
		<input type="hidden" name="id" value="<?php echo $this->character->id; ?>" />
		<input type="hidden" name="layout" value="roster"/>
		<input type="hidden" name="task" value="" />
	</form>