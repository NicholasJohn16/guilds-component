<form action="index.php" name="adminForm" method="post">
    <fieldset class="adminform">
        <legend>Type Details</legend>
        <table class="admintable" cellspacing="1">
            <tr>
                <td width="150" class="key">
                    Name
                </td>
                <td>
                    <input type="text"
                           class="inputbox"
                           size="40"
                           name="name"
                           value="<?php echo $this->type->name;?>"/>
                </td>
            </tr>
            <tr>
                <td width="150" class="key">
                    Ordering
                </td>
                <td>
                    <?php echo JHTML::_('list.specificordering',$this->type,$this->type->id,$this->sql); ?>
                </td>
            </tr>
            <tr>
                <td width="150" class="key">
                    Published
                </td>
                <td>
                    <input type="radio" name="published" <?php if($this->type->published == 1) {echo 'checked';} ?> value="1"/> Yes
                    <input type="radio" name="published" <?php if($this->type->published == 0) {echo 'checked';} ?> value="0"/> No
                </td>
            </tr>
        </table>
    </fieldset>
    <input type="hidden" name="option" value="com_guilds"/>
    <input type="hidden" name="view" value="types"/>
    <input type="hidden" name="task" value="save" />
    <input type="hidden" name="id" value="<?php echo $this->type->id; ?>" />
</form>