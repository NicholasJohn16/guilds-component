<ul class="nav nav-tabs">
	<li class="active"><a href="#network" data-toggle="tab">Network</a></li>
	<li><a href="#forums" data-toggle="tab">Forums</a></li>
	<li><a href="#guilds" data-toggle="tab">Guilds</a></li>
	<li><a href="#store" data-toggle="tab">Store</a></li>
	<li><a href="#notes" data-toggle="tab">Notes</a></li>
</ul>
<form class="form-horizontal">
	<div class="tab-content">
		<div class="tab-pane active" id="network">
			<div class="control-group">
				<label class="control-label" for="network_join">Guild Membership</label>
				<div class="controls">
					<select id="netwtork_join" multiple="multiple" class="span5">
						<option>Yes, I'd like to join Stonewall Fleet.</option>
						<option>Yes, I'd like to join Knights of Stonewall</option>
						<option>Yes, I'd like to join Stonewall Vanguard.</option>
						<option>No, I'm already a member of another Star Trek Fleet.</option>
						<option>No, I'm already a member of another Star Wars Guild.</option>
						<option>No, I'm already a member of another Guild Wars 2 Guild.</option>
						<option>No, I'd just like to join the community.</option>
					</select>
					<p class="help-block">Hold <code style="color:#333">Ctrl</code> to select multiple options or deselect an option.</p>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="network_guilds">Guild Name(s)</label>
				<div class="controls"><input id="network_guilds" type="text"/></div>
			</div>
			<div class="control-group">
				<label class="control-label" for="network_stoforum">STO Forum name</label>
				<div class="controls"><input id="network_stoforum" type="text"/></div>
			</div>
			<div class="control-group">
				<label class="control-label" for="network_handle">@Handle</label>
				<div class="controls"><input id="network_handle" type="text"/></div>
			</div>
			<div class="control-group">
				<label class="control-label" for="network_torforum">TOR Forum name</label>
				<div class="controls"><input id="network_torforum" type="text"/></div>
			</div>
		</div>
		<div class="tab-pane" id="forums">
			<div class="control-group">
				<label class="control-label" for="forums_count">Number of Posts</label>
				<div class="controls"><input id="forum_count" type="text" readonly="readonly"/></div>
			</div>
			<div class="control-group">
				<label class="control-label" for="forums_rank">Forum Rank</label>
				<div class="controls">
					<select>
						<?php foreach($this->ranks as $rank ):?>
							<option value="<?php echo $rank->id; ?>"><?php echo $rank->title; ?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="forums_text">Personal Text</label>
				<div class="controls"><input id="forums_text" type="text"/></div>
			</div>
			<div class="control-group">
				<label class="control-label" for="forums_signature">Signature</label>
				<div class="controls">
					<textarea id="signature">
				
					</textarea>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="guilds">
			<div class="control-group">
				<label class="control-label" for="guilds_status">Membership Status</label>
				<div class="controls"><input class="span2" id="guilds_status" type="text" readonly="readonly"/></div>
			</div>
			<div class="control-group">
				<label class="control-label" for="guilds_intro">Introduction Date</label>
				<div class="controls">
					<div class="input-append">
						<input class="span2" id="guild_intro" type="text"/>
						<button class="btn" type="button" style="height:28px;margin-left:-4px;"><i class="icon-calendar"></i></button>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="store">
			<div class="control-group">
				<label class="control-label" for="store_permissions">Permissions</label>
				<div class="controls">
					<select id="store_permissions">
						<option>admin</option>
						<option>storeadmin</option>
						<option>shopper</option>
						<option>demo</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" id="example" for="store_group">Shopper Group</label>
				<div class="controls">
					<select id="store_group">
						<option value="5">Fleet Member</option>
						<option value="6">Silver Member</option>
						<option value="7">Gold Member</option>
						<option value="8">Platinum Member</option>
						<option value="9">Diamon Member</option>
						<option value="10">Corps Member</option>
					</select>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="notes">
			<textarea rows="3" style="width:315px;"></textarea>
		</div>
	</div>
	<div class="form-actions" style="padding-left:200px;">
		<button class="btn btn-primary" type="submit">Submit</button>
		<button class="btn">Cancel</button>
	</div>
	<input type="hidden" name="option" value="com_guilds"/>
	<input type="hidden" name="view" value="members"/> 
</form>