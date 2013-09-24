<form action="index.php" name="adminForm" method="post">
    <div class="col width-45">
        <fieldset class="adminform">
            <legend>Character Details</legend>
            <table class="admintable" cellspacing="1">
                <tr>
                    <td width="150" class="key">
                        User ID
                    </td>
                    <td>
                        <input type="text"
                               class="inputbox"
                               size="40"
                               name="user_id"
                               value="<?php echo $this->character->user_id; ?>">
                    </td>
                </tr>
                <tr>
                    <td width="150" class="key">
                        Name
                    </td>
                    <td><input type="text"
                               class="inputbox"
                               size="40"
                               name="name"
                               value="<?php echo $this->character->name; ?>">
                    </td>
                </tr>
                <tr>
                    <td width="150" class="key">
                        Handle
                    </td>
                    <td>
                        
                        <input type="text"
                               class="inputbox"
                               size="40"
                               name="handle"
                               value="<?php echo $this->character->handle; ?>">
                    </td>
                </tr>
                <tr>
                    <td width="150" class="key">
                        Checked
                    </td>
                    <td>
                        <?php echo JHTML::calendar($this->character->checked,'checked','checked','%Y-%m-%d','size="40"'); ?>
                    </td>
                </tr>
                <tr>
                    <td width="150" class="key">
                        Published
                    </td>
                    <td>
                        <input <?php if($this->character->published == 1) {echo 'checked';}?> name="published" type="radio" value="1"/> Yes
                        <input <?php if($this->character->published == 0) {echo 'checked';}?> name="published" type="radio" value="0"/> No
                    </td>
                </tr>
                <tr>
                    <td width="150" class="key">
                        Unpublished Date
                    </td>
                    <td>
                        <input type="text"
                               class="inputbox"
                               size="40"
                               name="unpublished_date"
                               value="<?php echo $this->character->unpublished_date; ?>"
                               disabled="disabled">
                    </td>
                </tr>
                <tr>
                    <td width="150" class="key">
                        Invite
                    </td>
                    <td>
                        <input <?php if($this->character->invite == 1) {echo 'checked';}?> name="invite" type="radio" value="1"/> Yes
                        <input <?php if($this->character->invite == 0) {echo 'checked';}?> name="invite" type="radio" value="0"/> No
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <div class="col width-55">
        <fieldset class="adminform">
            <legend>Categories</legend>
            <table class="admintable" cellspacing="1">
                <?php foreach($this->types as $type) : ?>
                <tr>
                    <?php $type_name = $type->name.'_name';
                          $type_id = $type->name.'_id'; ?>
                    <td width="150" class="key">
                        <?php echo ucfirst($type->name); ?>
                    </td>
                    <td>
                    <select name="category[<?php echo $type->name;?>]" style="width:215px;">
                        <option value="">Select <?php echo ucfirst($type->name); ?></option>
                        <?php foreach($this->categories as $category): ?>
                            <?php $selected = ($this->character->$type_id == $category->id) ? 'selected="selected"' : ''; ?>
                            <?php if($category->type_id == $type->id): ?>
                                <option value="<?php echo $category->id ?>" <?php echo $selected; ?>>
                                    <?php echo $category->name; ?>
                                </option>
                            <?php endif; ?>
                    
                        <?php endforeach; ?>
                    </select>
                    </td>
                  </tr>  
                <?php endforeach; ?>
            </table>
        </fieldset>
    </div>
    <input type="hidden" name="option" value="com_guilds"/>
    <input type="hidden" name="view" value="characters" />
    <input type="hidden" name="task" value="save"/>
    <input type="hidden" name="id" value="<?php echo $this->character->id; ?>" />
</form>