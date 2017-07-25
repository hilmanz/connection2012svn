<?php /* Smarty version 2.6.13, created on 2012-06-20 17:48:09
         compiled from marlboro/badges-trade.html */ ?>
<div id="tradePage">
    <div id="content">
      <a class="post postTrades" href="#postTrades"></a>
      <div class="post postTrade" id="postTrades" style="display:none;">
        	<form>
        	<table id="listBadges" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top">
                	<div class="listBadges" id="badgeWillTrade">
                        <div class="titleBar">
                            <h1>MY BADGES</h1>
                        </div>
						<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['have']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['max'] = (int)10;
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
                        <div class="boxBadges"  >
                            <div class="theBadges"><img src="img/badge/badge<?php echo $this->_tpl_vars['have'][$this->_sections['i']['index']]['id']; ?>
.png" /></div>
                            <div class="count"><?php echo $this->_tpl_vars['have'][$this->_sections['i']['index']]['total']; ?>
</div>
                            <input type="radio" class="styled" name="badgeWillTrade" badge_id="<?php echo $this->_tpl_vars['have'][$this->_sections['i']['index']]['id']; ?>
"  />
                        </div>
						<?php endfor; endif; ?>
                      
                    </div><!-- end .listBadges -->
                </td>
                <td><input type="button" class="tradeNow" onclick="tradeThis()" /></td>
                <td valign="top">
                	<div class="listBadges BadgeNeed" id="badgeTrade">
                        <div class="titleBar">
                            <h1>WHAT I NEED?</h1>
                        </div>
						<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['have']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['max'] = (int)10;
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
                        <div class="boxBadges" >
                            <div class="theBadges"><img src="img/badge/badge<?php echo $this->_tpl_vars['have'][$this->_sections['i']['index']]['id']; ?>
.png" /></div>
                            <input type="radio" class="styled" name="badgeTrade" badge_id="<?php echo $this->_tpl_vars['have'][$this->_sections['i']['index']]['id']; ?>
" />
                        </div>
						<?php endfor; endif; ?>
                       
                    </div><!-- end .listBadges -->
                </td>
              </tr>
            </table>
			
            </form>
        </div><!-- end .post -->
    </div><!-- end #content -->
</div><!-- end #activity -->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "marlboro/popup_confirmTrade.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "marlboro/popup_finishTrade.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo '
<script>
var myBadge =\'\' ;
var iWantBadge =\'\';
function tradeThis(){

$(\'#badgeWillTrade .boxBadges :radio\').each(function(){
	if($(this).attr(\'checked\')){
	myBadge = $(this).attr(\'badge_id\');
	$(\'#leftBadge\').attr(\'src\',\'img/badge/big/badge\'+myBadge+\'.png\');	
	
	}

});

$(\'#badgeTrade .boxBadges :radio\').each(function(){
	if($(this).attr(\'checked\')){
	iWantBadge = $(this).attr(\'badge_id\');
	$(\'#rightBadge\').attr(\'src\',\'img/badge/big/badge\'+iWantBadge+\'.png\');	

	}

});
}


function postTrade(){
$("#finishTradeMessage").hide(); 
$.post("index.php?page=badges&act=submittrade", { have:myBadge , req:iWantBadge },
			   function(data) {
				
					$("#finishMessageSub").html(data.message);
					if(data.status>0) { 
					$("#finishTradeMessage").show(); 
					$("#tradeListUrl").attr(\'href\',\'index.php?page=badges&act=tradeList&badge=\'+iWantBadge+\'&want=\'+myBadge);
					var popupCloser  =  setInterval(function(){ 
					window.location="index.php?page=badges&act=trade&id="+auctionid
					}, 5000);
					}
					 
				 
		});

}

</script>
'; ?>