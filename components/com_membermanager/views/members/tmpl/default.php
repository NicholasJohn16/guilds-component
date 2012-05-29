<?php defined('_JEXEC') or die('Restricted access'); ?>
<div style="clear:both"></div>
<form id="members-form">
<div class="navbar">
	<div class="navbar-inner">
		<div class="container" style="width:100%">
			<a class="brand" href="#">Members</a>
			<div class="navbar-search pull-left">
    			<input type="text" name="search" class="search-query" placeholder="Search">
    		</div>
    		<button class="btn btn-inverse btn-small" type="submit">Submit</button>
    		<button class="btn btn-inverse btn-small" type="reset">Reset</button>
			<ul class="nav pull-right">
				<?php foreach($this->types as $type):?>
					<li>
						<select>
							<option>Select <?php echo ucwords($type->name);  ?></option>
							<?php foreach($this->categories as $category):?>
								<?php if($category->type == $type->name): ?>
									<option value="<?php echo $category->id;?>" ><?php echo ucwords($category->name);?></option>
								<?php endif; ?>
							<?php endforeach;?>
						</select>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>
<div class="subnav" style="margin-bottom:5px;">
	<ul class="nav nav-pills">
		<li style="width:2%;">#</li>
		<li style="width:20%;">Username</li>
		<li style="width:135px;">@Handle</li>
		<li style="width:70px;">Intro Date</li>
		<li style="width:12%;">Status</li>
		<li style="width:106px;">TBD</li>
		<li style="width:20%;">Forum Rank</li>
	</ul>
</div>
<div class="accordion">
	<?php
		$i = 0; 
		foreach($this->members AS &$member):
	?>
	<div style="clear:both"></div>
	<div class="accordion-group" id="<?php echo $member->id; ?>">
		<div class="accordion-heading">
			<div style="width:2%;text-align:right;"><?php echo $i+1+$this->pagination->limitstart; ?></div>
				<div style="width:20%;">
					<a href="index.php?option=com_membermanager&view=members&task=edit&user_id=<?php echo $member->id; ?>">
						<?php echo $member->username; ?>
					</a>
					<button id="characterstoggle-<?php echo $member->id?>" class="btn btn-mini action" data-toggle="collapse" data-target="#accordion-body-<?php echo $member->id;?>" style="float:right;margin-left:2px;" title="Character(s)">
						<img style="height:16px;width:16px;" src="components/com_membermanager/media/img/contacts.png"/>	
					</button>
					<a class="btn btn-mini" style="float:right;height:16px;width:16px;" target="_blank" title="Profile" href="index.php?option=com_community&view=profile&userid=<?php echo $member->id; ?>">
						<img src="components/com_membermanager/media/img/contact.png"/>
					</a>
				</div>
			<div style="width:135px;" class="editable" id="handle-<?php echo $member->id;?>"><?php echo $member->value; ?></div>
			<div style="width:70px;" class="editable date" id="appdate-<?php echo $member->id;?>"><?php echo ($member->appdate == NULL ? date('Y-m-d',strtotime($member->time)) : $member->appdate);?></div>
			<div style="width:12%;" ><?php echo $member->status; ?></div>
			<div style="width:106px;" class="editable" id="tbd-<?php echo $member->id;?>"><?php echo $member->tbd; ?></div>
			<div style="width:20%;padding:5px 10px;">
				<select id="rank-<?php echo $member->id; ?>" style="width:200px;">
					<?php foreach($this->ranks as $rank):?>
						<option value="<?php echo $rank->id;?>" <?php if($member->rank_id == $rank->id){echo 'selected="selected"';}?>><?php echo $rank->title?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div style="clear:both;"></div>
		<div class="accordion-body collapse" id="accordion-body-<?php echo $member->id;?>">
			<div id="characters-<?php echo $member->id;?>" class="accordion-inner com-mm-ajax"></div>
			<div style="clear:both"></div>
			<div class="accordion-footing">
				<div class="accordion-inner">
					<div class="btn-group">
						<a class="btn" title="Add Character" href="index.php?option=com_charactermanager&view=character&task=add&id=<?php echo $member->id;?>" data-toggle="modal" data-target="#character-form" data-user="<?php echo $member->id; ?>" data-username="<?php echo $member->username; ?>">
							<i class="icon-plus"></i>
						</a>
						<button class="btn action" title="Delete Character(s)" id="deletechars-<?php echo $member->id;?>"><i class="icon-remove"></i></button>
						<button class="btn action" title="Refresh Characters" id="refresh-<?php echo $member->id;?>"><i class="icon-refresh"></i></button>
					</div>	
				</div>
			</div>
		</div>
		
	</div>
	<?php
		$i++;
		endforeach;
	?>
		<div class="com-mm-footer">
			
		</div>
		<input type="hidden" name="option" value="com_membermanager"/>
		<input type="hidden" name="view" value="members"/>
		<input type="hidden" name="limitstart" value="<?php echo $this->pagination->limitstart;?>"/>
</div>
	<!--<span style="float:left"><?php echo $this->pagination->getPagesCounter();?></span> -->
	<div style="clear:both"></div>
	<?php echo $this->pagination();?>
	<!--<span style="float:right"><?php echo $this->pagination->getLimitBox();?></span> -->
</form>

<div class="modal hide fade in" id="character-form">
	<div class="modal-header" style="background-color:#F5F5F5;border-bottom:1px solid #DDDDDD;box-shadow: 0 -1px 0 #FFFFFF inset;">
		<button class="close" data-dismiss="modal">&times;</button>
		<h3>Add Character</h3>
	</div>
	<div class="modal-body">
		<form class="form-horizontal">
			<fieldset style="float:left;border:0 none;padding:0;margin:0;">
			<legend><?php echo JText::_('Character Info'); ?></legend>
			<div class="control-group">
				<label class="control-label" for="user">User ID</label>
				<div class="controls">
					<input type="text" id="user" name="user" size="32" readonly="readonly" value=""/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="username">Username</label>
				<div class="controls">
					<input type="text" id="username" name="username" readonly="readonly" size="32" value=""/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="character_name">Character Name</label>
				<div class="controls">
					<input type="text" id="character_name" name="character_name" size="32" value=""/>
				</div>	
			</div>
			<div class="control-group">
				<label class="control-label" for="checked">Last Checked</label>
				<div class="controls">
					<div class="input-append">
						<input type="text" name="checked" id="checked" value=""/>
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
						<input type="radio" value="1" name="published">
						Yes
					</label>
					<label class="radio">
						<input type="radio" value="0" name="published">
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
		<div style="clear:both"></div>
		<input type="hidden" name="option" value="com_charactermanager" />
		<input type="hidden" name="view" value="characters" />
		<input type="hidden" name="id" value="" />
		<input type="hidden" name="layout" value="roster"/>
		<input type="hidden" name="task" value="" />
		</form>
	</div>
	<div class="modal-footer" style="text-align:right;">
		<button id="close" class="btn">Cancel</button>
		<button class="btn btn-primary">Save</button>
	</div>
</div>