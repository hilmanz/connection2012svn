/*!
 * jQuery JavaScript 
 * MARLBORO CONNECTIONS
 * ACIT JAZZ 2012
 */
/*------------CAROUSEL------------*/
jQuery(document).ready(function() {
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
	jQuery("a.seeDetail,a.changePhoto,a.thumbGame,a.tradeMyBadge,a.popProfile,a.bidNow").click(function(){
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
	$('#game5').rotate('3deg');
	$('#game6').rotate('10deg');
	$('#game7').rotate('2deg');
	$('#game8').rotate('-2deg');
	$('#popupGame').rotate('0');
	$('.yellowTapes').rotate('5deg');
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
	$('.scrollbar').jScrollPane();
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
