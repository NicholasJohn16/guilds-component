<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $game_handle = $this->game_handle; ?>
<form id="members-form" class="fluid-container" action="<?php echo JRoute::_('index.php?option=com_guilds&view=members'); ?>" method="post">
    <div class="navbar navbar-inverse">
        <div class="navbar-inner">
            <div class="container" style="width:100%">
                <a class="brand" href="#">Members</a>
                <div class="navbar-search pull-left">
                    <input type="text" name="search" class="search-query" placeholder="Search" value="<?php echo $this->search; ?>">
                </div>
                <button class="btn btn-inverse btn-small" type="submit">Submit</button>
                <button class="btn btn-inverse btn-small" type="reset">Reset</button>
                <ul class="nav pull-right">
                    <li><a title="Add Character" id="add-character" href="#">Add Character</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="subnav" style="margin-bottom:5px;">
        <ul class="nav nav-pills">
            <li class="span1"><?php $this->sortable("#", "id"); ?></li>
            <li class="span3"><?php $this->sortable("Username"); ?></li>
            <li class="span2"><?php $this->sortable("Handle",$game_handle); ?></li>
            <li class="span2"><?php $this->sortable("Introduction", "appdate"); ?></li>
            <li class="span2">Status</li>
            <li class="span3">Forum Rank</li>
        </ul>
    </div>
    <div class="accordion">
        <?php if(!$this->members): ?>
            <div class="alert alert-block alert-error">
                <h4 class="alert-heading">No Characters Found</h4>
                <p>No matching characters were found.  Revise your search and try again.</p>
            </div>
        <?php else: ?>
        <?php $i = 0; ?>
        <?php foreach ($this->members AS $member): ?>
            <div class="accordion-group fluid-row" data-user="<?php echo $member->id; ?>">
                <div class="accordion-heading">
                    <div class="span1"><span class="badge"><?php echo $member->id; ?></span></div>
                    <div class="span3">
                        <?php if($this->isOfficer): ?>
                            <a href="<?php echo JRoute::_('index.php?option=com_guilds&view=members&task=edit&id='.$member->id); ?>">
                                <?php echo $member->username; ?>
                            </a>
                        <?php else: ?>
                            <?php echo $member->username; ?>
                        <?php endif; ?>
                        <button class="btn btn-mini pull-right character-toggle" data-toggle="collapse" data-target="#accordion-body-<?php echo $member->id; ?>" style="float:right;margin-left:2px;" title="Character(s)">
                            <i class="icon-characters"></i>
                        </button>
                        <a class="btn btn-mini pull-right" target="_blank" title="Profile" href="index.php?option=com_community&view=profile&userid=<?php echo $member->id; ?>">
                            <i class="icon-profile"></i>
                        </a>
                    </div>
                    <div class="editable handle span2" data-name="<?php echo $game_handle; ?>" data-pk="<?php echo $member->id; ?>"><?php echo $member->$game_handle; ?></div>
                    <div class="editable intro span2" data-pk="<?php echo $member->id; ?>"><?php echo $member->appdate; ?></div>
                    <div class="span2 status-<?php echo $member->id;?>"><?php echo $member->status; ?></div>
                    <div class="editable rank span3" data-pk="<?php echo $member->id; ?>" data-value="<?php //echo $member->rank_id;  ?>"><?php //echo $member->rank_title; ?></div>
                </div>
                <div style="clear:both;"></div>
                <div class="accordion-body collapse" id="accordion-body-<?php echo $member->id; ?>">
                    <div id="characters-<?php echo $member->id; ?>" class="com-guilds-characters accordion-inner com-guilds-ajax"></div>
                    <div style="clear:both"></div>
                    <div class="accordion-footing">
                        <div class="accordion-inner">
                            <div class="btn-group">
                                <a class="btn add-character" title="Add Character" href="#" data-user="<?php echo $member->id; ?>" data-username="<?php echo $member->username; ?>">
                                    <i class="icon-plus"></i>
                                </a>
                                <button class="btn delete" title="Delete Character(s)"><i class="icon-remove"></i></button>
                                <button class="btn refresh" title="Refresh Characters"><i class="icon-refresh"></i></button>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $i++;
        endforeach;
        endif;
    ?>   
    </div>
    <div class="row-fluid">
        <?php echo $this->pagination(); ?>
    </div>
    <div style="clear:both"></div>
    <input type="hidden" name="limitstart" value="<?php echo $this->pagination->limitstart; ?>"/>
    <input type="hidden" id="order" name="order" value="<?php echo $this->order; ?>" />
    <input type="hidden" id="direction" name="direction" value="<?php echo $this->direction; ?>" />
</form>
<div id="character-form-modal" class="modal hide fade in">
    <?php include_once JPATH_COMPONENT.DS.'views'.DS.'characters'.DS.'tmpl'.DS.'admin-form.php'; ?>
</div>