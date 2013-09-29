<?php 
    $fleets = $this->fleets;
    $holdings = $this->holdings;
    $tracks = $this->tracks;
    $levels = $this->levels;
    $xp = $this->xp;
    $numerals = $this->numerals;
?>
<?php 
    $document = JFactory::getDocument();
    $document->addStyleDeclaration('
        .bar {
            text-align:center !important;
            padding:2px 0;
        }
    ');
?>
<div class="container-fluid">
    <div class="row-fluid">
        <h1>Fleet Holdings</h1>
    </div>
    <div class="row-fluid">
        <p class="span12">
            In Star Trek Online, fleets can work together to build large fleet
            structures called Holdings.  These Holdings come in large sizes like
            a starbase or smaller like a Dilithium mine.  Stonewall Fleet has 
            been steadily working on our Holdings since their introduction into
            the game.  With different levels of activity, each of our fleet's 
            holdings are at different levels of completion.  Click on the tabs 
            along the top to select one of our in-game fleets and a tab on the 
            left to select a holding to view its current level of completion.
        </p>
    </div>
    <div class="row-fluid">
        <h2>Holdings</h2>
    </div>
    <div class="row-fluid">
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <?php $first = true; ?>
            <?php foreach($fleets as $fleet_code => $fleet_name): ?>
                <?php if(!is_array($fleet_name)): ?>
                    <li <?php if($first) echo 'class="active" style="margin-left:125px;"'; ?>>
                        <a href="#<?php echo $fleet_code;?>" data-toggle="tab">
                            <?php echo $fleet_name; ?>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            Other
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <?php foreach($fleet_name as $other_code => $other_name): ?>
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
            <?php foreach($fleets as $fleet_code => $fleet_name): ?>
                <?php if(!is_array($fleet_name)): ?>
                    <div class="tab-pane <?php if($first) echo 'active' ?>" id="<?php echo $fleet_code;?>">
                        <div class="tabbable tabs-left">
                            <ul class="nav nav-tabs" style="width:125px;height:320px;">
                                <?php $first = true; ?>
                                <?php foreach($holdings[$fleet_code] as $holding_code => $holding_name): ?>
                                    <li <?php if($first) echo 'class="active"';?>>
                                        <a href="#<?php echo $fleet_code.'-'.$holding_code; ?>" data-toggle="tab">
                                            <?php echo $holding_name; ?>
                                        </a>
                                    </li>
                                    <?php $first = false; ?>
                                <?php endforeach; ?>
                            </ul>
                            <div class="tab-content">
                                <?php $first = true; ?>
                                <?php foreach($holdings[$fleet_code] as $holding_code => $holding_name):?>
                                    <div class="tab-pane <?php if($first) echo 'active'; ?>" id="<?php echo $fleet_code.'-'.$holding_code; ?>">
                                        <div class="row-fluid">
                                            
                                            <div class="">
                                                <?php $first = true; ?>
                                                <?php foreach($tracks[$holding_code] as $track_name => $track): ?>
                                                    <h2 <?php if(!$first) echo 'style="display:inline;margin-left:50px;"'?>>
                                                        <?php echo ucfirst($track_name); ?>
                                                    </h2>
                                                    <div class="progress" <?php if(!$first) echo 'style="margin-left:50px;"'?>>
                                                        <?php
                                                            $running = 0;
                                                            $total = array_sum($levels[$track_name]);
                                                            
                                                            for($i = 0;$i < $track['tiers'];$i++,$running = $running + $current_level):
                                                                // a couple aliases to clean up the code
                                                                $current_level = $levels[$track_name][$i];
                                                                $current_xp = $xp[$fleet_code][$track_name];
                                                                // the width of the bar if its complete
                                                                $full = $current_level/$total * 100;
                                                                // the percentage that's complete so far
                                                                $percent = $current_xp / ($current_level + $running);
                                                                
                                                                if($percent > 1) {
                                                                    // if it's greater than 1, keep it full
                                                                    $width = $full;
                                                                } else {
                                                                    // if it's less than 1, multiple
                                                                    $width = $full * $percent;
                                                                }
                                                                // if we have reached this tier
                                                                if($current_xp <= $running ) {
                                                                    // just break so nothing shows up
                                                                    break;
                                                                }
                                                        ?>
                                                            <div class="bar <?php echo $track['color'];?>" style="width:<?php echo $width.'%'; ?>;">
                                                                <?php echo $numerals[$i]; ?>
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
    <div class="row-fluid">
        <h2>Projects</h2>
    </div>
    <div class="row-fluid">
        <p class="span12">
            Holdings are expanded by completing projects through the Fleet UI.  Each project
            requires different items including Fleet Marks, Dilithium, common
            Duty Officers, Commodities and Data Samples which are deposited by Fleet members.  As we complete projects,
            we earn XP for that projects category, such as Military XP or Diplomacy XP.
            Once we earn enough XP in a particular category to complete a Tier, an upgrade project is unlocked
            and we're able to upgrade that part of the Holding, such as Industrial
            Replicators or Development Facilities.  These upgrade projects also
            provide holding XP, such as Starbase XP or Mine XP.  As we gain more 
            holding XP, we unlock upgrade projects for each holding and can upgrade
            it's tier.
        </p>
    </div>
    <div class="row-fluid">
        <h2>Fleet Credit and Fleet Stores</h2>
    </div>
    <div class="row-fluid">
        <p class="span12">
            Depositing items into projects converts that item in an appropriate
            amount of Fleet Credits.  Fleet Credits can then be used to purchase
            items from Fleet Stores.  Fleet Items include Fleet ships, weapons,
            shields, warp cores, engines and much more.  A different selection of
            items is available at each Holding.  The items available depend on the 
            Tier of the holding and it's categories.  If you're currently in a 
            fleet that does not have access to items that you wish to requisition, 
            check the chart above and find a fleet that has them unlocked.  
            When you're in-game, request to be invited to that fleet's starbase.  
            Once on that map, you'll be able to purchase from that fleet's stores.
        </p>
    </div>
    <div class="row-fluid">
        <h2>Hillbane Memorial Embassy</h2>
    </div>
    <div class="row-fluid">
        <p class="span12">
            In 2012, Hillbane, our Chief of Klingon Affairs passed away.  He was
            one of our most dedicated members, serving as a Fleet Captain for 
            several months before joining the Admirals.  Hillbane loved our 
            community and was dedicated and passionate about the House of Nagh 
            reD.  After his passing, the House of Nagh reD Embassy was renamed 
            in his honor.
        </p>
    </div>
</div>