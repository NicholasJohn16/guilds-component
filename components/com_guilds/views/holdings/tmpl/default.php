<?php 
    $fleets = $this->fleets;
    $holdings = $this->holdings;
    $tiers = $this->tiers;
?>
<div class="container-fluid">
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <?php $first = true; ?>
            <?php foreach($fleets as $code => $fleet): ?>
                <?php if(!is_array($fleet)): ?>
                    <li <?php if($first) echo 'class="active" style="margin-left:125px;"'; ?>>
                        <a href="#<?php echo $code;?>" data-toggle="tab">
                            <?php echo $fleet; ?>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            Other
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <?php foreach($fleet as $other_code => $other_name): ?>
                                <li>
                                    <a href="#<?php echo $other_code; ?>" data-toggle="tab">
                                        <?php echo $other_name; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php $first = false; ?>
            <?php endforeach;?>
        </ul>
        <div class="tab-content">
            <?php $first = true; ?>
            <?php foreach($fleets as $code => $name): ?>
                <?php if(!is_array($name)): ?>
                    <div class="tab-pane <?php if($first) echo 'active' ?>" id="<?php echo $code;?>">
                        <div class="tabbable tabs-left">
                            <ul class="nav nav-tabs" style="width:125px;">
                                <?php $first = true; ?>
                                <?php foreach($holdings[$code] as $short => $holding): ?>
                                    <li <?php if($first) echo 'class="active"';?>>
                                        <a href="#<?php echo $code.'-'.$short; ?>" data-toggle="tab">
                                            <?php echo $holding; ?>
                                        </a>
                                    </li>
                                    <?php $first = false; ?>
                                <?php endforeach; ?>
                            </ul>
                            <div class="tab-content">
                                <?php $first = true; ?>
                                <?php foreach($holdings[$code] as $short => $holding):?>
                                    <div class="tab-pane <?php if($first) echo 'active'; ?>" id="<?php echo $code.'-'.$short; ?>">
                                        <div class="row-fluid">
                                            <div class="span6">
                                                <img src="http://placehold.it/295x295">
                                            </div>
                                            <div class="span6">
                                                <?php $first = true; ?>
                                                <?php foreach($tiers[$short] as $tier_name => $tier_count): ?>
                                                    <h2 <?php if(!$first) echo 'style="display:inline;margin-left:50px;"'?>>
                                                        <?php echo ucfirst($tier_name); ?>
                                                    </h2>
                                                    <div class="row-fluid">
                                                        <?php for($i = 0;$i < $tier_count;$i++): ?>
                                                        <div class="span2" <?php if(!$first && $i == 0) echo 'style="margin-left:50px;"'; ?>>
                                                            <div class="progress">
                                                                <div class="bar" style="width:100%;"></div>
                                                            </div>
                                                        </div>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <?php $first = false; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php $first = false; ?>
                                <?php endforeach; ?>
                                
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php $first = false; ?>
            <?php endforeach; ?>
            <div class="tab-pane" id="cc">
                <div class="alert alert-block alert-info">
                    <h4>Crafting Corp</h4>
                    <p>This fleet is not open to general membership.  
                        Instead, it's utilized as storage for the 
                        Fleet's crafting materials.  These items are used by the
                        Crafting Corp to craft items for the general membership. If 
                        you're interested in joining the Crafting Corp 
                        and volunteering your services, please contact 
                        the Chief of Fleet Resources.            
                    </p>
                </div>
                <div class="alert alert-info">
                    <p>
                        The holdings in this fleet are not currently being developed.
                    </p>
                </div>        
            </div>
            <div class="tab-pane" id="swv">
                <div class="alert alert-block alert-info">
                    <h4>Stonewall Vault</h4>
                    <p>
                        This fleet is not open to general membership.  Instead, it's
                        utilized as storage for items donated to fleet as prizes for 
                        future events.  Currently, only Admirals and some Fleet Captains
                        have access to this fleet.
                    </p>
                </div>
                <div class="alert alert-info">
                    <p>
                        The holdings in this fleet are not currently being developed.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>