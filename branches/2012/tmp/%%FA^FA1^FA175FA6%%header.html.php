<?php /* Smarty version 2.6.13, created on 2012-06-20 18:51:45
         compiled from marlboro/header.html */ ?>
<div id="header">
<div id="logo">
    <a class="logo" href="index.php">&nbsp;</a>
</div>
<div id="navigation">
    <ul class="nav">
        <li><a <?php if ($this->_tpl_vars['page'] == 'staticsabout' || $this->_tpl_vars['page'] == 'staticsprizes' || $this->_tpl_vars['page'] == 'staticshowtoplay' || $this->_tpl_vars['page'] == 'staticstos'): ?>class="current"<?php endif; ?> href="index.php?page=statics&act=about">About Connections</a>
            <ul>
                <li><a <?php if ($this->_tpl_vars['page'] == 'staticsprizes'): ?>class="current"<?php endif; ?> href="index.php?page=statics&act=prizes">Prizes</a></li>
                <li><a <?php if ($this->_tpl_vars['page'] == 'staticshowtoplay'): ?>class="current"<?php endif; ?> href="index.php?page=statics&act=howtoplay">How To Play</a></li>
                <li><a <?php if ($this->_tpl_vars['page'] == 'staticstos'): ?>class="current"<?php endif; ?> href="index.php?page=statics&act=tos">Terms &amp; Conditions</a></li>
            </ul>
        </li>
        <li><a <?php if ($this->_tpl_vars['page'] == 'updateclues' || $this->_tpl_vars['page'] == 'updatecluesnews' || $this->_tpl_vars['page'] == 'updatecluesactivity'): ?>class="current"<?php endif; ?> href="index.php?page=updateclues" >Update&nbsp;Clues</a>
            <ul>
                <li><a <?php if ($this->_tpl_vars['page'] == 'updatecluesnews'): ?>class="current"<?php endif; ?> href="index.php?page=updateclues&act=news">Clues &amp; Hot News</a></li>
                <li><a <?php if ($this->_tpl_vars['page'] == 'updatecluesactivity'): ?>class="current"<?php endif; ?> href="index.php?page=updateclues&act=activity">Connections Activity</a></li>
            </ul>
        </li>
        <li><a <?php if ($this->_tpl_vars['page'] == 'badges' || $this->_tpl_vars['page'] == 'badgestrade' || $this->_tpl_vars['page'] == 'badgesauction' || $this->_tpl_vars['page'] == 'badgesauction_bid' || $this->_tpl_vars['page'] == 'redeem'): ?>class="current"<?php endif; ?> href="index.php?page=badges">Badges</a>
            <ul>
                <li><a <?php if ($this->_tpl_vars['page'] == 'badgestrade'): ?>class="current"<?php endif; ?> href="index.php?page=badges&act=trade">Badge Trade</a></li>
                <li><a <?php if ($this->_tpl_vars['page'] == 'badgesauction' || $this->_tpl_vars['page'] == 'badgesauction_bid'): ?>class="current"<?php endif; ?> href="index.php?page=badges&act=auction">Badge Auction</a></li>
                <li><a <?php if ($this->_tpl_vars['page'] == 'redeem'): ?>class="current"<?php endif; ?> href="index.php?page=redeem">Redeem Prizes</a></li>
            </ul>
        </li>
        <li><a <?php if ($this->_tpl_vars['page'] == 'game'): ?>class="current"<?php endif; ?> href="index.php?page=game">Games</a></li>
        <li class="last"><a <?php if ($this->_tpl_vars['page'] == 'inputCode'): ?>class="current"<?php endif; ?>  href="index.php?page=inputCode">Input Codes</a></li>
    </ul>
</div>
</div><!-- end #header -->

<div id="container">
<div id="subnavigation">
    <a  href="index.php?page=myprofile">My Profile</a>
    <a  href="index.php?page=myprofile&act=messages">Messages</a>
    <a  href="index.php?page=referfriend" >Refer a Friend</a>
    <a href="logout.php">Sign Out</a>
</div>