/*!
 * jQuery JavaScript 
 * MARLBORO CONNECTIONS
 * ACIT JAZZ 2012
 */
/*------------DATEPICKER------------*/
$(function() {
	$( ".from" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 3,
		onSelect: function( selectedDate ) {
			$( ".to" ).datepicker( "option", "minDate", selectedDate );
		}
	});
	$( ".to" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 3,
		onSelect: function( selectedDate ) {
			$( ".from" ).datepicker( "option", "maxDate", selectedDate );
		}
	});
});
/*------------SCROLL UP------------*/	
$(function() {
	$('a#backTop').click(
		function (e) {
			$('html, body').animate({scrollTop: '0px'}, 800);
		}
	);
});