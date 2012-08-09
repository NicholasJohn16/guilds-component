<?php defined('_JEXEC') or die('Restricted access'); ?>
<form id="members-form">
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container" style="width:100%">
				<a class="brand" href="#">Members</a>
				<div class="navbar-search pull-left">
	    			<input type="text" name="search" class="search-query" placeholder="Search" value="<?php echo $this->search; ?>">
	    		</div>
	    		<button class="btn btn-inverse btn-small" type="submit">Submit</button>
	    		<button class="btn btn-inverse btn-small" type="reset">Reset</button>
	    		<ul class="nav pull-right">
	    			<li><a data-target="#character-form" data-toggle="modal" title="Add Character" href="#">Add Character</a></li>
	    		</ul>
			</div>
		</div>
	</div>
	<div class="subnav" style="margin-bottom:5px;">
		<ul class="nav nav-pills">
			<li style="width:3%;"><?php echo $this->sortable("#","id");?></li>
			<li style="width:20%;"><?php echo $this->sortable("Username");?></li>
			<li style="width:135px;"><?php echo $this->sortable("@Handle","value");?></li>
			<li style="width:70px;"><?php echo $this->sortable("Intro Date","appdate");?></li>
			<li style="width:10%;"><?php echo $this->sortable("Status");?></li>
			<li style="width:106px;"><?php echo $this->sortable("TBD");?></li>
			<li style="width:20%;"><?php echo $this->sortable("Forum Rank","rank_title");?></li>
		</ul>
	</div>
	<div class="accordion">
		<?php
			$i = 0; 
			foreach($this->members AS &$member):
		?>
		<div class="accordion-group" data-user="<?php echo $member->id; ?>">
			<div class="accordion-heading">
				<div style="width:3%;text-align:right;"><?php echo $member->id; //$i+1+$this->pagination->limitstart; ?></div>
					<div style="width:20%;">
						<a href="index.php?option=com_guilds&view=members&task=edit&user_id=<?php echo $member->id; ?>">
							<?php echo $member->username; ?>
						</a>
						<button class="btn btn-mini action" data-toggle="collapse" data-target="#accordion-body-<?php echo $member->id;?>" style="float:right;margin-left:2px;" title="Character(s)">
							<img style="height:16px;width:16px;" src="components/com_guilds/media/img/contacts.png"/>	
						</button>
						<a class="btn btn-mini" style="float:right;height:16px;width:16px;" target="_blank" title="Profile" href="index.php?option=com_community&view=profile&userid=<?php echo $member->id; ?>">
							<img src="components/com_guilds/media/img/contact.png"/>
						</a>
					</div>
				<div style="width:135px;" class="editable" data-update="handle"><?php echo $member->handle?></div>
				<div style="width:70px;" class="editable date" data-update="checked"><?php echo ($member->appdate == NULL ? date('Y-m-d',strtotime($member->time)) : $member->appdate);?></div>
				<div style="width:10%;"><?php echo $member->status; ?></div>
				<div style="width:106px;" class="editable" data-update="tbd"><?php echo $member->tbd; ?></div>
				<div style="width:20%;padding:2px 5px;">
					<select data-update="rank" class="editable" style="width:200px;">
						<?php foreach($this->ranks as $rank):?>
							<option value="<?php echo $rank->id;?>" <?php if($member->rank_id == $rank->id){echo 'selected="selected"';}?>><?php echo $rank->title?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div style="clear:both;"></div>
			<div class="accordion-body collapse" id="accordion-body-<?php echo $member->id;?>">
				<div id="characters-<?php echo $member->id;?>" class="com-guilds-characters accordion-inner com-guilds-ajax"></div>
				<div style="clear:both"></div>
				<div class="accordion-footing">
					<div class="accordion-inner">
						<div class="btn-group">
							<a class="btn" title="Add Character" href="index.php?option=com_guilds&view=character&task=add&id=<?php echo $member->id;?>" data-toggle="modal" data-target="#character-form" data-user="<?php echo $member->id; ?>" data-username="<?php echo $member->username; ?>">
								<i class="icon-plus"></i>
							</a>
							<button class="btn action" title="Delete Character(s)"><i class="icon-remove"></i></button>
							<button class="btn action" title="Refresh Characters"><i class="icon-refresh"></i></button>
						</div>	
					</div>
				</div>
			</div>
		</div>
		<?php
			$i++;
			endforeach;
		?>
		<input type="hidden" name="option" value="com_guilds"/>
		<input type="hidden" name="view" value="members"/>
		<input type="hidden" name="limitstart" value="<?php echo $this->pagination->limitstart;?>"/>
		<input type="hidden" name="order" value="<?php echo $this->order; ?>" />
		<input type="hidden" name="direction" value="<?php echo $this->direction; ?>" />
	</div>
	<!--<span style="float:left"><?php echo $this->pagination->getPagesCounter();?></span> -->
	<div style="clear:both"></div>
	<?php echo $this->pagination();?>
	<!--<span style="float:right"><?php echo $this->pagination->getLimitBox();?></span> -->
</form>
<form class="form-horizontal modal hide fade in" id="character-form">
	<div class="modal-header" style="background-color:#F5F5F5;border-bottom:1px solid #DDDDDD;box-shadow: 0 -1px 0 #FFFFFF inset;">
		<button class="close" data-dismiss="modal">&times;</button>
		<h3>Add Character</h3>
	</div>
	<div class="modal-body">
		<fieldset style="float:left;border:0 none;padding:0;margin:0;">
		<legend><?php echo JText::_('Character Info'); ?></legend>
		<div class="control-group">
			<label class="control-label" for="username">User</label>
			<div class="controls">
				<input type="text" name="user" value=""/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="character_name">Character Name</label>
			<div class="controls">
				<input type="text" id="character_name" name="character_name" size="32" value=""/>
			</div>	
		</div>
		<div class="control-group">
			<label class="control-label" for="checked">Checked</label>
			<div class="controls">
				<div class="input-append">
					<input type="text" name="checked" id="checked" value=""/>
					<button class="btn" type="button" style="height:28px;margin-left:-4px;"><i class="icon-calendar"></i></button>
				</div>
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
				<select name="category[<?php echo $type->name; ?>]">
					<option value=""><?php echo 'Select '.ucfirst($type->name);?></option>
					<?php foreach($this->categories as $category):?>
						<?php if($category->type == $type->name): ?>
							<option value="<?php echo $category->id
							?>" data-parent="<?php echo $category->parent;
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
	</div>
	<div style="clear:both"></div>
	<div class="modal-footer" style="text-align:right;">
		<button id="close" class="btn">Cancel</button>
		<input type="submit" class="btn btn-primary" value="Add" />
	</div>
</form>