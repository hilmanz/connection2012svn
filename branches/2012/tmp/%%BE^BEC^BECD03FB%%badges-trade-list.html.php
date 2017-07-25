<?php /* Smarty version 2.6.13, created on 2012-06-08 14:28:47
         compiled from marlboro/badges-trade-list.html */ ?>
<link rel="stylesheet" type="text/css" href="css/skins/connection/skin3.css" />
<div id="badgesPage">
    <div id="content">
    	<div class="post postBadgesList">
        	<h1>YOUR TRADE REQUEST<br />MATCHes these people!</h1>
			<h2>click on a person to trade your badge!</h2>
            	<div id="listTrader">
                    <ul id="first-carousel" class="listTrader jcarousel-skin-tango">
                        
						<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
						<li>
                        <div class="boxTrader">
                            <div class="thumbTrader">
                                <a href="#popupProfile" class="popProfile"><img src="img/photo.jpg" /></a>
                            </div><!-- end .thumbTrader -->
                            <div class="boxName">
                                <span class="nameTrader"><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['name']; ?>
</span>
                                <a class="tradeMyBadge" href="#popupTradeMyBadge" onClick="sendTraderData(<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['auction_id']; ?>
,<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['register_id']; ?>
,<?php echo $this->_tpl_vars['want']; ?>
,<?php echo $this->_tpl_vars['badge']; ?>
,'<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['name']; ?>
')" ></a>
                            </div><!-- end .boxName -->
                        </div><!-- end .boxTrader -->
						
                                                </li>
						<?php endfor; endif; ?>
                    </ul>
                </div><!-- end #listTrader -->
            <div class="imgBadgesList"></div>
        </div><!-- end .post -->
    </div><!-- end #content -->
</div><!-- end #badgesPage -->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "marlboro/popup_tradeMyBadge.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "marlboro/profile.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo '
<script>
var auctionid = \'\';
var registerid =\'\';
var iWantThisBadge =\'\';
var thisMyBadge =\'\';
var name =\'\';

function sendTraderData(auction_id,register_id,iWantBadge,myBadge,name){
auctionid = auction_id;
registerid = register_id;
iWantThisBadge = iWantBadge;
thisMyBadge = myBadge;
name = name;
$("#chosenTrader").html(name);

}


function submitTrade(){
alert(iWantThisBadge);
$.post("index.php?page=badges&act=confirmtraderequest", { mine:thisMyBadge , your:iWantThisBadge, sellerId:auctionid  },
			   function(data) {
				
					$("#finishMessageSub").html(data.message);
					if(data.status>0) { 
					$("#finishTradeMessage").show(); 
					var popupCloser  =  setInterval(function(){ 
					window.location="index.php?page=badges&act=tradeList&want="+iWantThisBadge+"&badge="+thisMyBadge
					}, 5000);
					}
					 
				 
		});

}
</script>

'; ?>