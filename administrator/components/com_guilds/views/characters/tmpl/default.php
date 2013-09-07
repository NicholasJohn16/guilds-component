<?php 
    $document = JFactory::getDocument();
    $document->addScriptDeclaration('
        function tableOrder( order, dir, task ) {
            var form = document.adminForm;
            form.order.value = order;
            form.direction.value = dir;
            submitform( task );
        }
    ');
?>
<form name="adminForm" action="index.php" method="post">
    <table>
        <tr>
            <td width="100%">
                <input type="text" 
                       name="search" 
                       id="search" 
                       value="<?php echo $this->search; ?>" 
                       class="text_area" 
                       onchange="document.adminForm.submit();" />
                <button onclick="this.form.submit()">Search</button>
                <button onclick="document.getElementById('search').value='';document.adminForm.submit();">Reset</button>
            </td>
            <td nowrap="nowrap">
                <?php foreach($this->types as $type) : ?>
                    <?php $type_name = $type->name.'_name'; ?>
                    <select name="category[<?php echo $type->name;?>]" onchange="document.adminForm.submit( );">
                        <option value="">Filter <?php echo ucfirst($type->name); ?></option>
                        <?php foreach($this->categories as $category): ?>
                            <?php $selected = (@$this->filters[$type->name] == $category->id) ? 'selected="selected"' : ''; ?>
                            <?php if($category->type_id == $type->id): ?>
                                <option value="<?php echo $category->id ?>" <?php echo $selected; ?>>
                                    <?php echo $category->name; ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                <?php endforeach; ?>
            </td>
        </tr>
    </table>
    <?php if(!$this->characters): ?>
    <dl id="system-message">
        <dt class="notice">No Characters Found</dt>
        <dd class="notice message fade">
            <ul>
                <li>No Characters were found with your current search criteria.  Please revises your search and try again.</li>
            </ul>
        </dd>
    </dl>
    <?php else: ?>
        <table class="adminlist">
            <thead>
                <th width="25px">
                    <input type="checkbox" name="toggle" onclick="checkAll(<?php echo count($this->characters); ?>);">
                </th>
                <th>
                    <?php echo GuildsHelper::sort('ID','id',$this->direction,$this->order); ?>
                </th>
                <th>
                    <?php echo GuildsHelper::sort('User ID','user_id',$this->direction,$this->order); ?>
                </th>
                <th>
                    <?php echo GuildsHelper::sort('Name','name',$this->direction,$this->order); ?>
                </th>
                <th>
                    <?php echo GuildsHelper::sort('Handle','handle',$this->direction,$this->order); ?>
                </th>
                <th>
                    Status
                </th>
                <th>
                    <?php echo GuildsHelper::sort('Checked','checked',$this->direction,$this->order); ?>
                </th>
                <?php foreach($this->types as $type): ?>
                    <th>
                        <?php echo GuildsHelper::sort(ucfirst($type->name),$type->name,$this->direction,$this->order); ?>
                    </th>
                <?php endforeach;   ?>
                    <th colspan="2">
                        <?php echo GuildsHelper::sort('Published','published',$this->direction,$this->order); ?>
                    </th>
                    <th>
                        <?php echo GuildsHelper::sort('Invite','invite',$this->direction,$this->order); ?>
                    </th>
            </thead>
            <tbody>
                <?php $k = 0; ?>
                <?php $i = 0; ?>
                <?php foreach($this->characters as $character): ?>
                    <tr class="row<?php echo $k;?>">
                        <td>
                            <?php echo JHTML::_('grid.id',$i,$character->id,false,'id'); ?>
                        </td>
                        <td>
                            <?php echo $character->id; ?>
                        </td>
                        <td>
                            <?php echo $character->user_id; ?>
                        </td>
                        <td>
                            <a href="<?php echo JRoute::_('index.php?option=com_guilds&view=characters&task=edit&id='.$character->id,false); ?>">
                               <?php echo $character->name; ?>
                            </a>
                        </td>
                        <td>
                            <?php echo $character->handle; ?>
                        </td>
                        <td>
                            <?php echo $character->status; ?>
                        </td>
                        <td>
                            <?php echo $character->checked; ?>
                        </td>
                        <?php foreach($this->types as $type): ?>
                            <td>
                                <?php $type_name = $type->name.'_name'; ?>
                                <?php echo $character->$type_name; ?>
                            </td>
                        <?php endforeach; ?>
                        <td width="25">
                            <?php echo JHTML::_('grid.published',$character,$i); ?>
                        </td>
                        <td>
                            <?php echo $character->unpublisheddate; ?>
                        </td>
                        <td>
                            <?php echo $this->invite($character->invite); ?>
                        </td>
                    </tr>
                    <?php $k = 1 - $k; // alternates $k between 0 and 1 for row class ?>
                    <?php $i++; ?>
                <?php endforeach; ?>
                
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="<?php echo 10 + count($this->types); ?>">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    <?php endif; ?>
    <input type="hidden" name="boxchecked" value=""/>
    <input type="hidden" name="option" value="com_guilds" />
    <input type="hidden" name="view" value="characters" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="order" value="<?php echo $this->order; ?>" />
    <input type="hidden" name="direction" value="<?php echo $this->direction; ?>" />
</form>