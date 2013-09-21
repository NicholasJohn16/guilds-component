<?php
/*
 * @package		Character Manager
 * @subpackage	Components
 * @link			http://www.nicholasjohn16.com
 * @license		GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');
?>
<div id="cpanel" style="width:55%;">
    <div class="icon">
        <a href="index.php?option=com_guilds&view=members">

            <span style="font-weight:bold">Members</span>
        </a>
    </div>
    <div class="icon">
        <a href="index.php?option=com_guilds&view=characters">

            <span style="font-weight:bold">Characters</span>
        </a>
    </div>
    <div class="icon">
        <a href="index.php?option=com_guilds&view=categories">

            <span style="font-weight:bold">Categories</span>
        </a>
    </div>
    <div class="icon">
        <a href="index.php?option=com_guilds&view=types">

            <span style="font-weight:bold">Types</span>
        </a>
    </div>
</div>
<form name="adminForm" action="index.php" method="post">
    <input type="hidden" name="option" value="com_guilds"/>
    <input type="hidden" name="task" value=""/>
</form>