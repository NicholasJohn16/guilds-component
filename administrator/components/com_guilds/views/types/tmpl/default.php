<form name="adminForm" action="index.php" method="post">
    <table class="adminlist">
        <thead>
            <tr>
                <th width="25px">
                    <input type="checkbox" name="toggle" onclick="checkAll(<?php echo count($this->types);?>)">
                </th>
                <th width="20px">
                    ID
                </th>
                <th>
                    Name
                </th>
                <th width="125px">
                    Order
                    <?php echo JHTML::_('grid.order',$this->types); ?>
                </th>
                <th width="70px">
                    Published
                </th>
                <th width="70px">
                    Delete
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; ?>
            <?php $k = 0; ?>
            <?php foreach($this->types as $type): ?>
                <tr class="row<?php echo $k;?>">
                    <td>
                        <?php echo JHTML::_('grid.id',$i,$type->id,false,'id'); ?>
                    </td>
                    <td>
                        <?php echo $type->id; ?>
                    </td>
                    <td>
                        <a href="index.php?option=com_guilds&view=types&task=edit&id=<?php echo $type->id;?>">
                            <?php echo $type->name; ?>
                        </a>
                    </td>
                    <td class="order">
                        <span><?php echo $this->pagination->orderUpIcon($i);?></span>
                        <span><?php echo $this->pagination->orderDownIcon($i,count($this->types));?></span>
                        <input type="text" name="order[]" size="5" value="<?php echo $type->ordering ?>" class="text_area" style="text-align:center" />
                    </td>
                    <td style="text-align:center;">
                        <?php echo JHTML::_('grid.published',$type,$i,'publish_g.png','publish_r.png'); ?>
                    </td>
                    <td style="text-align:center;">
                        <a href="#" onclick="if(confirm('Deleting a type will remove all associated data.\nAre you sure?')){return listItemTask('cb<?php echo $i;?>','delete')}">
                            <img src="images/publish_x.png" title="Delete"/>
                        </a>
                    </td>
                </tr>
                <?php $k = 1 - $k; // alternates $k between 0 and 1 for row class ?>
                <?php $i++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <input type="hidden" name="option" value="com_guilds" />
    <input type="hidden" name="view" value="types" />
    <input type="hidden" name="boxchecked" value="" />
    <input type="hidden" name="task" value="" />
</form>