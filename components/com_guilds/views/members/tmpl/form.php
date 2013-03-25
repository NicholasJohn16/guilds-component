<?php
$document = JFactory::getDocument();
$document->addScriptDeclaration("
        $(document).ready(function() {
            $('#appdate').datepicker({
                format:'yyyy-mm-dd',
                todayBtn:'linked'
            });
        });
    ");
?>
<ul class="nav nav-tabs">
    <li class="active"><a href="#guilds" data-toggle="tab">Guilds</a></li>
    <li><a href="#network" data-toggle="tab">Network</a></li>
    <li><a href="#notes" data-toggle="tab">Notes</a></li>
</ul>
<form class="form-horizontal" action="index.php" method="post">
    <div class="tab-content">
        <div class="tab-pane active" id="guilds">
            <div class="control-group">
                <label class="control-label" for="username">Username</label>
                <div class="controls"><input name="username" id="username" readonly="readonly" type="text" value="<?php echo $this->member->username; ?>"/></div>
            </div>
            <div class="control-group">
                <label class="control-label" for="sto_handle">@Handle</label>
                <div class="controls"><input name="sto_handle" id="sto_handle" type="text" value="<?php echo $this->member->sto_handle; ?>"/></div>
            </div>
            <div class="control-group">
                <label class="control-label" for="tor_handle">TOR Forum name</label>
                <div class="controls"><input name="tor_handle" id="tor_handle" type="text" value="<?php echo $this->member->tor_handle; ?>"/></div>
            </div>
            <div class="control-group">
                <label class="control-label" for="gw2_handle">Guild Wars 2 User ID</label>
                <div class="controls"><input name="gw2_handle" id="gw2_handle" type="text" value="<?php echo $this->member->gw2_handle; ?>"/></div>
            </div>
        </div>
        <div class="tab-pane" id="network">
            <div class="control-group">
                <label class="control-label" for="status">Membership Status</label>
                <div class="controls">
                    <input class="span2" id="status" type="text" readonly="readonly" value="<?php echo $this->member->status; ?>"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="appdate">Introduction Date</label>
                <div class="controls">
                    <input class="span2" name="appdate" id="appdate" type="text" value="<?php echo $this->member->appdate; ?>">
                </div>
            </div>
        </div>
        <div class="tab-pane" id="notes">
            <textarea name="notes" rows="3" style="width:315px;"><?php echo $this->member->notes ?></textarea>
        </div>
    </div>
    <div class="form-actions" style="padding-left:200px;">
        <button class="btn btn-primary" type="submit">Submit</button>
        <a href="<?php echo JRoute::_('index.php?view=members'); ?>" class="btn">Cancel</a>
    </div>
    <input type="hidden" name="option" value="com_guilds"/>
    <input type="hidden" name="view" value="members"/>
    <input type="hidden" name="task" value="save" />
    <input type="hidden" name="id" value="<?php echo $this->member->id; ?>" />
</form>
<p class="muted pull-right"><i>Last edited by <a href="#"><?php echo $this->member->editor; ?></a> on <?php echo date('l, F d<\s\u\p>S</\s\u\p>, Y', strtotime($this->member->edit_time)); ?>.</i></p>