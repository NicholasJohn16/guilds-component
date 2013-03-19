<?php $member = $this->member;
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
	<li class="active"><a href="#network" data-toggle="tab">Network</a></li>
	<li><a href="#guilds" data-toggle="tab">Guilds</a></li>
	<li><a href="#notes" data-toggle="tab">Notes</a></li>
</ul>
<form class="form-horizontal">
	<div class="tab-content">
		<div class="tab-pane active" id="network">
			<div class="control-group">
				<label class="control-label" for="sto_handle">@Handle</label>
				<div class="controls"><input id="sto_handle" type="text" value="<?php echo $member->sto_handle; ?>"/></div>
			</div>
			<div class="control-group">
				<label class="control-label" for="tor_handle">TOR Forum name</label>
				<div class="controls"><input id="tor_handle" type="text" value="<?php echo $member->tor_handle; ?>"/></div>
			</div>
                    <div class="control-group">
				<label class="control-label" for="gw2_handle">Guild Wars 2 User ID</label>
				<div class="controls"><input id="gw2_handle" type="text" value="<?php echo $member->gw2_handle; ?>"/></div>
			</div>
		</div>
		<div class="tab-pane" id="guilds">
			<div class="control-group">
				<label class="control-label" for="status">Membership Status</label>
				<div class="controls">
                                    <input class="span2" id="status" type="text" readonly="readonly" value="<?php echo $member->status; ?>"/>
                                </div>
			</div>
			<div class="control-group">
				<label class="control-label" for="appdate">Introduction Date</label>
				<div class="controls">
                                    <input class="span2" id="appdate" type="text" value="<?php echo $member->appdate; ?>">
				</div>
			</div>
		</div>
		<div class="tab-pane" id="notes">
			<textarea rows="3" style="width:315px;"><?php $member->notes ?></textarea>
		</div>
	</div>
    <p class="muted"><i>Last edited by <a href="#"><?php echo $member->editor; ?></a> on <?php echo date('l, F d<\s\u\p>S</\s\u\p>, Y', strtotime($member->edit_time)); ?>.</i></p>
	<div class="form-actions" style="padding-left:200px;">
		<button class="btn btn-primary" type="submit">Submit</button>
		<button class="btn">Cancel</button>
	</div>
	<input type="hidden" name="option" value="com_guilds"/>
	<input type="hidden" name="view" value="members"/> 
</form>