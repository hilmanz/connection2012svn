<?php /* Smarty version 2.6.13, created on 2012-06-12 13:57:39
         compiled from marlboro/badges-auction.html */ ?>

<div id="badgeAuction">
    <div id="content">
    	<a class="post postBadgeAuctions" href="#postBadgeAuction"></a>
    	<div class="post postBadgeAuctionChoose" id="postBadgeAuction" style="display:none;">
        	<div class="badgeAuctionList">
				<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['have']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['max'] = (int)6;
$this->_sections['i']['show'] = true;
if ($this->_sections['i']['max'] < 0)
    $this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = min(ceil(($this->_sections['i']['step'] > 0 ? $this->_sections['i']['loop'] - $this->_sections['i']['start'] : $this->_sections['i']['start']+1)/abs($this->_sections['i']['step'])), $this->_sections['i']['max']);
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
                <div class="boxBadges">
                    <div class="theBadges"><a href="index.php?page=badges&act=auction_bid&id=<?php echo $this->_tpl_vars['have'][$this->_sections['i']['index']]->type_auction; ?>
"><img src="img/<?php echo $this->_tpl_vars['have'][$this->_sections['i']['index']]->img; ?>
" style="width:55px" /></a></div>
                </div>
               <?php endfor; endif; ?>
            </div>
            <div class="entry">
            	<p>Expand your Connections with the latest gadget and other exclusive merchandise!  We'll have a new item everyday, so make sure you have  enough badges to be the top bidder!</p>
            </div>
            <div id="yesterdayWinner" class="smallBox">
            	<div class="yesterdayWinner">
                    <h1>yesterday's  WINNER</h1>
                    <div class="theBadges"><img src="img/<?php echo $this->_tpl_vars['lastestWinner']['auction']->img; ?>
" /></div>
                    <h2>WON</h2>
                    <div class="boxTrader">
                        <div class="thumbTrader">
                            <a href="#popupProfile" class="popProfile"><img src="img/photo.jpg" /></a>
                        </div><!-- end .thumbTrader -->
                        <div class="boxName">
                            <span class="nameTrader"><?php echo $this->_tpl_vars['lastestWinner']['user']->name; ?>
</span>
                        </div><!-- end .boxName -->
                    </div><!-- end .boxTrader -->
                </div><!-- end .yesterdayWinner -->
            </div><!-- end #yesterdayWinner -->
 		</div><!-- end .post -->
    </div><!-- end #content -->
</div><!-- end #activity -->