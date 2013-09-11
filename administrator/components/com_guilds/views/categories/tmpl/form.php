<?php dump($this,'Form'); ?>
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
                    <input type="text"
                           class="inputbox"
                           size="40"
                           name="ordering"
                           value="<?php echo $this->category->ordering; ?>">
                </td>
            </tr>
            <tr>
                <td width="150" class="key">
                    Type
                </td>
                <td>
                    <input type="text"
                           class="inputbox"
                           size="40"
                           name=""
                           value="<?php echo $this->category->type; ?>">
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