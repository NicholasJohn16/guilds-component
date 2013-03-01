<?php defined('_JEXEC') or die('Restricted access'); ?>
<form id="members-form" class="fluid-container">
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
			<li class="span1"><?php echo $this->sortable("#","id");?></li>
			<li class="span3"><?php echo $this->sortable("Username");?></li>
			<li class="span2"><?php echo $this->sortable("@Handle","value");?></li>
			<li class="span2"><?php echo $this->sortable("Introduction","appdate");?></li>
			<li class="span2"><?php echo $this->sortable("Status");?></li>
			<li class="span3"><?php echo $this->sortable("Forum Rank","rank_title");?></li>
		</ul>
	</div>
	<div class="accordion">
		<?php
			$i = 0; 
			foreach($this->members AS &$member):
		?>
		<div class="accordion-group fluid-row" data-user="<?php echo $member->id; ?>">
			<div class="accordion-heading">
				<div class="span1"><span class="badge badge-inverse"><?php echo $member->id;?></span></div>
					<div class="span3">
						<a href="index.php?option=com_guilds&view=members&user_id=<?php echo $member->id; ?>&task=edit">
							<?php echo $member->username; ?>
						</a>
						<button class="btn btn-mini action" data-toggle="collapse" data-target="#accordion-body-<?php echo $member->id;?>" style="float:right;margin-left:2px;" title="Character(s)">
							<img style="height:16px;width:16px;" src="components/com_guilds/media/img/contacts.png"/>	
						</button>
						<a class="btn btn-mini" style="float:right;height:16px;width:16px;" target="_blank" title="Profile" href="index.php?option=com_community&view=profile&userid=<?php echo $member->id; ?>">
							<img src="components/com_guilds/media/img/contact.png"/>
						</a>
					</div>
				<div class="editable handle span2" data-pk="<?php echo $member->id; ?>"><?php echo $member->handle?></div>
				<div class="editable intro span2" data-pk="<?php echo $member->id; ?>"><?php echo ($member->appdate == NULL ? date('Y-m-d',strtotime($member->time)) : $member->appdate);?></div>
				<div class="span2"><?php echo $member->status; ?></div>
				<div class="editable rank span3" data-pk="<?php echo $member->id;?>" data-value="<?php echo $member->rank_id;?>"><?php echo $member->rank_title;?></div>
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
				<input type="text" name="username" tabindex="1" id="username" value=""/>
                                <input type="hidden" name="user" id="user"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="character_name">Character Name</label>
			<div class="controls">
				<input type="text" tabindex="2" id="character_name" name="character_name" size="32" value=""/>
			</div>	
		</div>
		<div class="control-group">
                    <label class="control-label" for="checked">Checked</label>
                    <div class="controls">
                        <input size="16" type="text" tabindex="3" value="" id="date">
                    </div>
                </div>
	</fieldset>
	<fieldset style="float:left;border:0 none;padding:0;margin:0;">
		<legend>Categories</legend>
                <?php $tab = 4; ?>
		<?php foreach($this->types as $type): ?>
		<?php $type_id = $type->name.'_id'; ?>
		<div class="control-group">
			<label class="control-label" for="<?php echo $type->name;?>"><?php echo ucfirst($type->name);?></label>
			<div class="controls">
				<select tabindex="<?php echo $tab; ?>" name="category[<?php echo $type->name; ?>]">
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
                <?php $tab++; ?>
		<?php endforeach;?>
	</fieldset>
	</div>
	<div style="clear:both"></div>
	<div class="modal-footer" style="text-align:right;">
		<button id="close" class="btn">Cancel</button>
		<input tabindex="<?php echo $tab; ?>" type="submit" class="btn btn-primary" value="Add" />
	</div>
</form>
<div class="notifications bottom-right">&nbsp;</div>