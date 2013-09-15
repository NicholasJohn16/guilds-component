<form name="adminForm" action="index.php" method="post">
    <fieldset class="adminform">
        <legend>Category Details</legend>
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
                           value="<?php echo $this->category->name; ?>">
                </td>
            </tr>
            <tr>
                <td width="150" class="key">
                    Order
                </td>
                <td>
                    <?php echo JHTML::_('list.specificordering',$this->category,$this->category->id,$this->sql); ?>
                </td>
            </tr>
            <tr>
                <td width="150" class="key">
                    Type
                </td>
                <td>
                    <select name="type" >
                        <option value="">Select Type</option>
                        <?php foreach($this->types as $type): ?>
                            <?php $selected = ($type->id == $this->category->type_id) ? 'selected="selected"' : ''; ?>
                            <option value="<?php echo $type->id; ?>" <?php echo $selected; ?>><?php echo $type->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="150" class="key">
                    Parent
                </td>
                <td>
                    <select name="parent" size="<?php echo count($this->categories) + 1 ;?>" >
                        <option value="0">Top</option>
                        <?php foreach($this->categories as $category): ?>
                            <?php $disabled = ($this->category->id == $category->id) ? 'disabled="disabled"' : ''; ?>
                            <?php $checked = ($this->category->parent == $category->id) ? 'selected="selected"' : ''; ?>
                            <option <?php echo $disabled.' '.$checked; ?> value="<?php echo $category->id; ?>"><?php echo $category->treename; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                    <td width="150" class="key">
                        Published
                    </td>
                    <td>
                        <input <?php if($this->category->published == 1) {echo 'checked';}?> name="published" type="radio" value="1"/> Yes
                        <input <?php if($this->category->published == 0) {echo 'checked';}?> name="published" type="radio" value="0"/> No
                    </td>
                </tr>
        </table>
    </fieldset>
    <input type="hidden" name="option" value="com_guilds"/>
    <input type="hidden" name="view" value="categories"/>
    <input type="hidden" name="task" value="save"/>
    <input type="hidden" name="id" value="<?php echo $this->category->id; ?>"/>
</form>