/*!
 * jQuery JavaScript 
 * MARLBORO CONNECTIONS
 * ACIT JAZZ 2012
 */
/*------------CAROUSEL------------*/
jQuery(document).ready(function() {
								
					
	/*------------VIDEO PLAYER------------*/				
	$("#marlboroPlayer").jPlayer({
		ready: function () {
			$(this).jPlayer("setMedia", {
				m4v: "http://www.loocker.com/video/mc2.mp4",
				ogv: "http://www.loocker.com/video/mc2.ogv",
				webmv: "http://www.loocker.com/video/mc2.webm",
				poster: "img/videocover.jpg"
			});
		},
		play: function() { // To avoid both jPlayers playing together.
			$(this).jPlayer("pauseOthers");
		},
		swfPath: "js",
		supplied: "webmv, ogv, m4v",
		cssSelectorAncestor: "#marlboroVideo",
		size: { width: "392px", height: "245px"}
	});
	
	jQuery("a.jp-video-play-icon").click(function(){
		jQuery("#popupFinishBid").fadeIn();
		jQuery("#marlboroVideos div.jp-video ul.jp-controls").fadeIn();
	});	
					
	/*------------jcarousel------------*/				
								
	jQuery('.prize').jcarousel({
		scroll: 1
	});
	jQuery('.listnews').jcarousel({
		vertical: true,
		scroll: 3
	});
	jQuery('.listactivity').jcarousel({
		vertical: true,
		scroll: 4
	});
	jQuery('.listTrader').jcarousel({
		vertical: true,
		scroll: 2
	});
	/*------------POP UP------------*/	
	jQuery("a.seeDetail,a.changePhoto,a.thumbGame,a.tradeMyBadge,a.popProfile,a.bidNow,a.bidNows").click(function(){
		var targetID = jQuery(this).attr('href');
		jQuery(targetID).fadeIn();
		jQuery("#bgPopup").fadeIn();
	});
	jQuery("a.closePopup").click(function(){
		jQuery(".popupContainer").fadeOut();
		jQuery("#bgPopup").fadeOut();
	});
	/*------------POP UP TRADE------------*/
	jQuery(".tradeNow").click(function(){
		var targetID = jQuery(this).attr('href');
		jQuery("#popupConfirmTrade").fadeIn();
		jQuery("#bgPopup").fadeIn();
	});	
	jQuery(".btnConfirmRequest,.finish").click(function(){
		var targetID = jQuery(this).attr('href');
		jQuery("#popupConfirmTrade").fadeOut();
		jQuery("#popupFinishTrade").fadeIn();
		jQuery("#bgPopup").fadeIn();
	});	
	/*------------POP UP FINISH BID------------*/
	jQuery(".btnPlaceBid").click(function(){
		jQuery("#popupFinishBid").fadeIn();
		jQuery(".popupBid").fadeOut();
		jQuery("#bgPopup").fadeIn();
	});	
	/*------------Badge Auction------------*/
	jQuery(".postBadgeAuctions,.postTrades").click(function(){
		jQuery("#postBadgeAuction,#postTrades").fadeIn();
		jQuery(".postBadgeAuctions,.postTrades").fadeOut();
	});	
	var url = document.location.href;
	if (url.indexOf('index.php?page=badges&act=auction#postBadgeAuction') >= 0) {
	  $('#postBadgeAuction').show();
	} 

});

/*------------SCROLL UP------------*/	
$(function() {
	$('a.changePhoto,a.seeDetail,a.thumbGame').click(
		function (e) {
			$('html, body').animate({scrollTop: '0px'}, 800);
		}
	);
});
/*------------ROTATE------------*/
$(function() {
	$('.popupContainer').rotate('-3deg');
	$('#game1').rotate('-8deg')
	$('#game2').rotate('-3deg');
	$('#game3').rotate('-5deg');
	$('#game4').rotate('10deg');
	$('#game5').rotate('-3deg');
	$('#game6').rotate('10deg');
	$('#game7').rotate('2deg');
	$('#game8').rotate('-2deg');
	$('.yellowTapes').rotate('5deg');
	$('#placeBid,#popupFinishBid,.popupGame').rotate('0deg');
});
/*------------TOOLTIP------------*/
 $(document).ready(function() {
	
    $(".tip_trigger").hover(function(){
        tip = $(this).find('.tip');
        tip.fadeIn(); //Show tooltip
    }, function() {
        tip.fadeOut(); //Hide tooltip
    })
});
/*------------SCROLL BAR------------*/
$(function()
{
	//$('.scrollbar').jScrollPane();
});

/*------------FORM VALIDATION------------*/
function validate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}
