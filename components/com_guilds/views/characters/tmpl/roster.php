<form action="<?php echo JRoute::_('index.php?option=com_guilds&view=characters&layout=roster'); ?>" method="post" id="roster-form">
    <div class="navbar">
        <div class="navbar-inner">
            <div style="width:100%;" class="container">
                <a class="brand" href="#">Roster</a>
                <div class="navbar-search pull-left">
                    <input type="text" 
                           name="search" 
                           class="search-query" 
                           placeholder="Search" 
                           value="<?php echo $this->search; ?>">
                </div>
                <button class="btn btn-inverse btn-small" type="submit">
                    Submit
                </button>
                <button class="btn btn-inverse btn-small" type="reset">
                    Reset
                </button>
                <ul class="nav pull-right">
                    <li>
                        <a id="add-character" href="#" title="Add Character">
                            Add Character
                        </a>
                    </li>
                    <li>
                        <a id="delete-character" href="#" title="Delete Character(s)">
                            Delete Character(s)
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="well" id="filters">
    <?php echo categoriesHelper::display($this->types,$this->categories,array('inline'=>false,'filters'=>$this->filters)); ?>
    </div>
    <div class="com-guilds container-fluid">
        <div class="row-fluid header">
            <div class="span1">
                <input type="checkbox" name="toggle" value="" id="checkAll" />
                <?php echo $this->sortable("ID"); ?>
            </div>
            <div class="span1"><?php echo $this->sortable("Name"); ?></div>
            <div class="span2"><?php echo $this->sortable("Handle"); ?></div>
            <div class="span1"><?php echo 'Status'; ?></div>
            <?php foreach ($this->types as $type): ?>
                <?php $type_name = $type->name . '_name'; ?>
                <div class="span2 com-guilds-<?php echo $type->name; ?>">
                    <?php echo $this->sortable(ucfirst($type->name)); ?>
                </div>
            <?php endforeach; ?>
            <div class="span2 com-guilds-checked"><?php echo $this->sortable("Checked"); ?></div>
            <div class="span2 com-guilds-pubdate"><?php echo $this->sortable("Published"); ?></div>
        </div>
        <?php $i = 0; ?>
        <?php dump($this->characters,'Characters'); ?>
        <?php foreach ($this->characters as $character): ?>
            <div class="row-fluid">
                <div class="span1">
                    <input id="cb<?php echo $i ?>" 
                           type="checkbox" 
                           value="<?php echo $character->id; ?>" 
                           name="characters[]"/>
                     <?php echo $character->id; ?>
                </div>
                <div class="span1 editable" 
                     data-name="name" 
                     data-pk="<?php echo $character->id; ?>" 
                     data-title="Edit Character Name">
                         <?php echo $character->name; ?>
                </div>
                <?php if($character->handle === NULL) : ?>
                    <div class="span2 editable"
                         data-title="Edit Handle"
                         data-name="sto_handle"
                         data-pk="<?php echo $character->user_id; ?>"
                         data-url="index.php?option=com_guilds&view=members&task=ajaxSave&format=raw">
                             <?php echo $character->sto_handle; ?>
                    </div>
                <?php else: ?>
                    <div class="span2 editable"
                         data-title="Edit Character Handle"
                         data-name="handle"
                         data-pk="<?php echo $character->id;?>"
                         data-url="index.php?option=com_guilds&view=characters&task=ajaxSave&format=raw">
                        <?php echo $character->handle; ?>
                    </div>
                <?php endif; ?>
                <div class="span1"><?php echo $character->status; ?></div>
                <?php foreach ($this->types as $type): ?>
                    <?php $type_name = $type->name . '_name'; ?>
                    <?php $type_id = $type->name . '_id'; ?>
                    <div class="span2 editable com-guilds-<?php echo $type->name; ?>" 
                         data-type="select" 
                         data-value="<?php echo $character->$type_id; ?>" 
                         data-title="Edit <?php echo ucfirst($type->name); ?>" 
                         data-pk="<?php echo $character->id; ?>" 
                         data-source="index.php?option=com_guilds&view=categories&format=json&type=<?php echo $type->name; ?>" 
                         data-name="category[<?php echo $type->name; ?>]">
                             <?php echo $character->$type_name; ?>
                    </div>
                <?php endforeach; ?>
                <div class="span2 editable com-guilds-checked" 
                     data-type="date" 
                     data-placement="right" 
                     data-name="checked" 
                     data-title="Edit Checked Date" 
                     data-pk="<?php echo $character->id; ?>">
                         <?php echo $character->checked; ?>
                </div>
                <?php $pub = array('title'=>array('Unpublished','Published'),
                    'icon'=>array('eye-close icon-white','eye-open'),
                    'task'=>array('publish','unpublish'),
                    'class'=>array('btn-inverse','')); ?>
                <div class="span1">
                    <button title="<?php echo $pub['title'][$character->published] . " Character"; ?>" 
                            class="btn btn-mini publish <?php echo $pub['class'][$character->published]; ?>" 
                            data-task="<?php echo $pub['task'][$character->published]; ?>" 
                            data-id="<?php echo $character->id; ?>">
                        <i class="icon-<?php echo $pub['icon'][$character->published]; ?>"></i>
                    </button>
                </div>
            </div>
            <?php $i++; ?>
        <?php endforeach; ?>
    </div>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="order" value="<?php echo $this->order; ?>"/>
    <input type="hidden" name="direction" value="<?php echo $this->direction; ?>"/>
    <input type="hidden" name="limitstart" value="<?php echo $this->pagination->limitstart; ?>"/>
    <div style="clear:both"></div>
    <?php echo $this->pagination(); ?>
</form>
<div id="character-form-modal" class="modal hide fade in">
    <?php include_once JPATH_COMPONENT.DS.'views'.DS.'characters'.DS.'tmpl'.DS.'admin-form.php'; ?>
</div>