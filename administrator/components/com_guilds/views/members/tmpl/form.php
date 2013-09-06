<form action="index.php" name="adminForm" method="post">
    <div class="col width-45">
        <fieldset class="adminform">
            <legend>Member Details</legend>
            <table class="admintable" cellspacing="1">
                <tr>
                    <td width="150" class="key">
                        User ID
                    </td>
                    <td>
                        <input type="text" 
                               name="user_id" 
                               class="inputbox" 
                               size="40" 
                               value="<?php echo $this->member->user_id; ?>" 
                               readonly="readonly"/>
                    </td>
                </tr>
                <tr>
                    <td width="150" class="key">
                        Username
                    </td>
                    <td>
                        <input type="text"
                               name="username"
                               class="inputbox"
                               size="40"
                               value="<?php echo $this->member->username; ?>"
                               disabled="disabled"/>
                    </td>
                </tr>
                <tr>
                    <td width="150" class="key">
                        Introduction Date
                    </td>
                    <td>
                        <?php echo JHTML::calendar($this->member->appdate,'appdate','appdate','%Y-%m-%d','size="40"'); ?>
                    </td>
                </tr>
                <tr>
                    <td width="150" class="key">
                        Status
                    </td>
                    <td>
                        <input type="text" 
                               name="status" 
                               class="inputbox" 
                               size="40" 
                               value="<?php echo ucfirst($this->member->status); ?>" 
                               disabled="disabled"/>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset class="adminform">
            <legend>Game Handles</legend>
                <table class="admintable" cellspacing="1">
                    <tr>
                        <td width="150" class="key">
                            STO Handle
                        </td>
                        <td>
                            <input type="text"
                                   name="sto_handle"
                                   class="inputbox"
                                   size="40"
                                   value="<?php echo $this->member->sto_handle; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td width="150" class="key">
                            TOR Handle
                        </td>
                        <td>
                            <input type="text"
                                   name="tor_handle"
                                   class="inputbox"
                                   size="40"
                                   value="<?php echo $this->member->tor_handle; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td width="150" class="key">
                            GW2 Handle
                        </td>
                        <td>
                            <input type="text"
                                   name="gw2_handle"
                                   class="inputbox"
                                   size="40"
                                   value="<?php echo $this->member->gw2_handle; ?>"/>
                        </td>
                    </tr>
                </table>
        </fieldset>
    </div>
    <div class="col width-55">
        <fieldset>
            <legend>Notes</legend>
            <textarea rows="15" cols="125" name="notes"><?php echo $this->member->notes; ?></textarea>
        </fieldset>
    </div>
    <input type="hidden" name="task" value="save"/>
    <input type="hidden" name="option" value="com_guilds"/>
    <input type="hidden" name="view" value="members"/>
</form>