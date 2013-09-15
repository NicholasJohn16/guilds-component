<form name="adminForm" action="index.php" method="post">
    <table class="adminlist">
        <thead>
            <tr>
                <th width="25px">
                    <input type="checkbox" name="toggle" onclick="checkAll(<?php echo count($this->categories); ?>);">
                </th>
                <th width="20px">
                    ID
                </th>
                <th>
                    Name
                </th>
                <th width="125px">
                    Type
                </th>
                <th width="125px">
                    Order
                    <?php echo JHTML::_('grid.order',$this->categories); ?>
                </th>
                <th style="width:55px;">
                    Published
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $k = 0; ?>
            <?php $i = 0; ?>
            <?php foreach($this->categories as $category): ?>
                <tr class="row<?php echo $k; ?>">
                    <td>
                        <?php echo JHTML::_('grid.id',$i,$category->id,false,'id'); ?>
                    </td>
                    <td>
                        <?php echo $category->id; ?>
                    </td>
                    <td>
                        <a href="index.php?option=com_guilds&view=categories&task=edit&id=<?php echo $category->id; ?>">
                            <?php echo $category->treename; ?>
                        </a>
                    </td>
                    <td>
                        <?php echo $category->type; ?>
                    </td>
                    <td class="order">
                        <span><?php echo $this->pagination->orderUpIcon($i);?></span>
                        <span><?php echo $this->pagination->orderDownIcon($i,count($this->categories));?></span>
                        <input tabindex="<?php echo $i + 1; ?>" type="text" name="order[]" size="5" value="<?php echo $category->ordering ?>" class="text_area" style="text-align:center" />
                    </td>
                    <td style="text-align:center;">
                        <?php echo JHTML::_('grid.published',$category,$i,'publish_g.png','publish_r.png'); ?>
                    </td>
                </tr>
            <?php $k = 1 - $k; ?>
            <?php $i++; ?>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">
                    <?php //$this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
    </table>
    <input type="hidden" name="option" value="com_guilds"/>
    <input type="hidden" name="view" value="categories"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value=""/>
</form>