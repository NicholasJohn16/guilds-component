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
            <td>
                <input type="text" 
                       name="search" 
                       id="search" 
                       value="<?php echo $this->search; ?>" 
                       class="text_area" 
                       onchange="document.adminForm.submit();" />
                <button onclick="this.form.submit()">Search</button>
                <button onclick="document.getElementById('search').value='';document.adminForm.submit();">Reset</button>
            </td>
        </tr>
    </table>
    <?php if(!$this->members): ?>
        No Members found.  Revise search criteria.
    <?php else: ?>
        <table class="adminlist">
            <thead>
                <tr>
                    <th width="25px">
                        <input type="checkbox" name="toggle" onclick="checkAll(<?php echo count($this->members); ?>);">
                    </th>
                    <th>
                        <?php echo GuildsHelper::sort('ID','user_id',$this->direction,$this->order); ?>
                    </th>
                    <th>
                        <?php echo GuildsHelper::sort('Username','username',$this->direction,$this->order); ?>
                    </th>
                    <th>
                        <?php echo GuildsHelper::sort('Intro Date','appdate',$this->direction,$this->order); ?>
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        <?php echo GuildsHelper::sort('STO Handle','sto_handle',$this->direction,$this->order); ?>
                    </th>
                    <th>
                        <?php echo GuildsHelper::sort('TOR Handle','tor_handle',$this->direction,$this->order); ?>
                    </th>
                    <th>
                        <?php echo GuildsHelper::sort('GW2 Handle','gw2_handle',$this->direction,$this->order); ?>
                    </th>
                </tr>
            </thead>
            <?php $k = 0; ?>
            <?php $i = 0; ?>
            <?php foreach($this->members as $member): ?>
                <tr class="row<?php echo $k;?>">
                    <td>
                        <?php echo JHTML::_('grid.id',$i,$member->id,false,'id'); ?>
                    </td>
                    <td>
                        <?php echo $member->id; ?>
                    </td>
                    <td>
                        <a href="index.php?option=com_guilds&view=members&task=edit&id=<?php echo $member->id;?>">
                            <?php echo $member->username; ?>
                        </a>
                    </td>
                    <td>
                        <?php echo $member->appdate; ?>
                    </td>
                    <td>
                        <?php echo $member->status; ?>
                    </td>
                    <td>
                        <?php echo $member->sto_handle; ?>
                    </td>
                    <td>
                        <?php echo $member->tor_handle; ?>
                    </td>
                    <td>
                        <?php echo $member->gw2_handle; ?>
                    </td>
                </tr>
                <?php $k = 1 - $k; // alternates $k between 0 and 1 for row class ?>
                <?php $i++; ?>
            <?php endforeach; ?>
                <tfoot>
                    <tr>
                        <td colspan="8">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
        </table>
    <?php endif; ?>
        <input type="hidden" name="option" value="com_guilds" />
        <input type="hidden" name="view" value="members" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="order" value="<?php echo $this->order ?>" />
        <input type="hidden" name="direction" value="<?php echo $this->direction; ?>" />
</form>