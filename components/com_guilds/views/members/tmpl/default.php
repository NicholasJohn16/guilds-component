<?php defined('_JEXEC') or die('Restricted access'); ?>
<form id="members-form" class="fluid-container" action="<?php echo JRoute::_('index.php?option=com_guilds&view=members'); ?>" method="post">
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
                    <li><a title="Add Character" href="#">Add Character</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="subnav" style="margin-bottom:5px;">
        <ul class="nav nav-pills">
            <li class="span1"><?php echo $this->sortable("#", "id"); ?></li>
            <li class="span3"><?php echo $this->sortable("Username"); ?></li>
            <li class="span2"><?php echo $this->sortable("@Handle", "sto_handle"); ?></li>
            <li class="span2"><?php echo $this->sortable("Introduction", "appdate"); ?></li>
            <li class="span2"><?php echo $this->sortable("Status"); ?></li>
            <li class="span3">Forum Rank</li>
        </ul>
    </div>
    <div class="accordion">
        <?php dump($this->members,'Members'); ?>
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
                    <div class="span1"><span class="badge badge-inverse"><?php echo $member->id; ?></span></div>
                    <div class="span3">
                        <a href="<?php echo JRoute::_('index.php?option=com_guilds&view=members&task=edit&id=' . $member->id); ?>">
                            <?php echo $member->username; ?>
                        </a>
                        <button class="btn btn-mini action" data-toggle="collapse" data-target="#accordion-body-<?php echo $member->id; ?>" style="float:right;margin-left:2px;" title="Character(s)">
                            <img style="height:16px;width:16px;" src="components/com_guilds/media/img/contacts.png"/>	
                        </button>
                        <a class="btn btn-mini" style="float:right;height:16px;width:16px;" target="_blank" title="Profile" href="index.php?option=com_community&view=profile&userid=<?php echo $member->id; ?>">
                            <img src="components/com_guilds/media/img/contact.png"/>
                        </a>
                    </div>
                    <div class="editable handle span2" data-name="sto_handle" data-pk="<?php echo $member->id; ?>"><?php echo $member->sto_handle; ?></div>
                    <div class="editable intro span2" data-pk="<?php echo $member->id; ?>"><?php echo $member->appdate; ?></div>
                    <div class="span2"><?php echo $member->status; ?></div>
                    <div class="editable rank span3" data-pk="<?php echo $member->id; ?>" data-value="<?php //echo $member->rank_id;  ?>"><?php //echo $member->rank_title; ?></div>
                </div>
                <div style="clear:both;"></div>
                <div class="accordion-body collapse" id="accordion-body-<?php echo $member->id; ?>">
                    <div id="characters-<?php echo $member->id; ?>" class="com-guilds-characters accordion-inner com-guilds-ajax"></div>
                    <div style="clear:both"></div>
                    <div class="accordion-footing">
                        <div class="accordion-inner">
                            <div class="btn-group">
                                <a class="btn" title="Add Character" href="index.php?option=com_guilds&view=character&task=add&id=<?php echo $member->id; ?>" data-user="<?php echo $member->id; ?>" data-username="<?php echo $member->username; ?>">
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
        endif;
    ?>   
    </div>
    <!--<span style="float:left"><?php echo $this->pagination->getPagesCounter(); ?></span> -->
    <div style="clear:both"></div>
    <?php echo $this->pagination(); ?>
    <!--<span style="float:right"><?php echo $this->pagination->getLimitBox(); ?></span> -->
    <input type="hidden" name="limitstart" value="<?php echo $this->pagination->limitstart; ?>"/>
    <input type="hidden" id="order" name="order" value="<?php echo $this->order; ?>" />
    <input type="hidden" id="direction" name="direction" value="<?php echo $this->direction; ?>" />
</form>
<div id="character-form-modal" class="modal hide fade in">
    <?php include_once JPATH_COMPONENT.DS.'views'.DS.'characters'.DS.'tmpl'.DS.'admin-form.php'; ?>
</div>