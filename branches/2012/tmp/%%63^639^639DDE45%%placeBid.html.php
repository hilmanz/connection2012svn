<?php /* Smarty version 2.6.13, created on 2012-06-12 13:14:45
         compiled from marlboro/placeBid.html */ ?>
<div id="placeBid" class="popupContainer popupBid">
        	<div id="popupContent">
            	<div class="selotip"></div>
                <a class="closePopup" href="#">&nbsp;</a>
  
                    <div class="poupContent">
                        <h1>place bid for your "connection exclusive hard suitcase"</h1>
                        <h2>YOUR BADGES</h2>
                        <div id="badgeListBid1" class="badgeListBid">
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
                            <div class="badgeBox">
                                <a class="badgesIcon" href="#"><img src="img/badge/badge<?php echo $this->_tpl_vars['have'][$this->_sections['i']['index']]['id']; ?>
.png" /> </a>
                                <input type="text" class="badgesCount" maxlength="2" value="<?php echo $this->_tpl_vars['have'][$this->_sections['i']['index']]['total']; ?>
" disabled="disabled" />
                            </div><!-- end .badgeBox -->
						<?php endfor; endif; ?>
							</div><!-- end #badgeList -->
                        <h2>YOUR BID</h2>
                        <div id="badgeListBid2" class="badgeListBid">
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
								<div class="badgeBox">
									<a class="badgesIcon" href="#"><img src="img/badge/badge<?php echo $this->_tpl_vars['have'][$this->_sections['i']['index']]['id']; ?>
.png" /> </a>
									<input type="text" class="badgesCount badgebid" badge_id="<?php echo $this->_tpl_vars['have'][$this->_sections['i']['index']]['id']; ?>
" id="bidvalue_<?php echo $this->_tpl_vars['have'][$this->_sections['i']['index']]['id']; ?>
" value="0" maxlength="2" name="bidvalue_<?php echo $this->_tpl_vars['have'][$this->_sections['i']['index']]['id']; ?>
"  onkeypress='validate(event)'/>
								</div><!-- end .badgeBox -->
                       		<?php endfor; endif; ?>
                        </div><!-- end #badgeList -->
                    </div><!-- end .poupContent -->
                    <h2 class="currentBid">CURRENT HIGHEST BID : 68</h2>
                    <h2 class="yourBid">YOUR BID : 54</h2>
                    <!--<input type="submit" value="PLACE BID" class="btnPlaceBid" />-->
					<input type="hidden" id="auction_id" value="<?php echo $this->_tpl_vars['auction']->id; ?>
">
					<input type="hidden" id="user_id" value="<?php echo $this->_tpl_vars['user']['id']; ?>
">
                    <a href="#popupFinishBid" class="btnPlaceBid"  onClick="placingBid()"  >PLACE BID</a>
               
            </div><!-- end #poupContent -->
</div><!-- end .popupContainer -->
<div id="bgPopup"></div>
<?php echo '
<script>

var days = $(".day").attr("days");
var hours = $(".hour").attr("hours");
var minutes = $(".minute").attr("minutes");
var seconds = $(".second").attr("seconds");

var remainingTimes  =  setInterval(function(){ 
		
	if(seconds >1 && seconds<=60) seconds--;
		else {
		seconds = 60;
			if(minutes > 1 && minutes<=60)minutes--;
			else {
				minutes= 60;
				if(hours>1 && hours<=12) hours--;
				else { hours=12; days--;	}
			}
		}
		
		
		
		$(".day").html(days+"D");
		$(".hour").html(hours+"H");
		$(".minute").html(minutes+"M");
		$(".second").html(seconds+"S");

		}, 100);

	
 function placingBid(){

		$("#placingbidmessage").hide();
		var arrBid = [];
		var auctionid = $(\'#auction_id\').val();
		var user_id = $(\'#user_id\').val();
		$(".badgebid").each(function (index, val) {
			var badgeid = $(this).attr(\'badge_id\');
			var bidValue = $("#bidvalue_"+badgeid).val();
			arrBid.push(badgeid+"-"+bidValue);
			});

		//send array to  post bid
		$.post("index.php?page=badges&act=submit_auction", { bid: "\'"+arrBid+"\'" , user_id:user_id ,auction_id:auctionid},
			   function(data) {
				
					$("#popupfinishbidmessage").html(data.message);
					if(data.status>0) { 
					$("#placingbidmessage").show(); 
					var popupCloser  =  setInterval(function(){ 
					window.location="index.php?page=badges&act=auction_bid&id="+auctionid
					}, 2000);
					}
					
				 
		});
		
		
	}

</script>
'; ?>