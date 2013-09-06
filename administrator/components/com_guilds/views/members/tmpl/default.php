<form name="adminForm" action="index.php">
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
        No Characters found.  Revise search criteria.
    <?php else: ?>
        <table class="adminlist">
            <thead>
                <tr>
                    <th width="25px">
                        <input type="checkbox" name="toggle" onclick="checkAll(<?php echo count($this->members); ?>);">
                    </th>
                    <th>
                        <?php echo JHTML::_('grid.sort','ID','a.user_id',$this->order,$this->direction); ?>
                    </th>
                    <th>
                        Username
                    </th>
                    <th>
                        Intro Date
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        STO Handle
                    </th>
                    <th>
                        TOR Handle
                    </th>
                    <th>
                        GW2 Handle
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
</form>