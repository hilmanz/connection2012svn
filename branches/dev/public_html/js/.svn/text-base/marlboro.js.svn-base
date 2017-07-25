var tradeHaveSelected = 0;
var tradeReqSelected = 0;
var trade = new Object();
var confirmMine = 0;
var confirmYour = 0;
var sellerID	= 0;
var redeemAllow = 0;

trade.haveSelect = function (i) {
	if ( $('input[name="have_'+i+'"]').is(':checked') ){ 
		if (tradeHaveSelected == 0){
			tradeHaveSelected = i;
		}else{
			$('input[name="have_'+tradeHaveSelected+'"]').removeAttr("checked");
			tradeHaveSelected = i;
		}
		$('#confirmMineImg').attr('src','images/badge/badge'+i+'-big.png');
		$('input[name="want"]').val(i);
	}else{
		tradeHaveSelected=0;
	}
}
trade.reqSelect = function (i) {
	if ( $('input[name="req_'+i+'"]').is(':checked') ){ 
		if (tradeReqSelected == 0){
			tradeReqSelected = i;
		}else{
			$('input[name="req_'+tradeReqSelected+'"]').removeAttr("checked");
			tradeReqSelected = i;
		}
		$('#confirmYourImg').attr('src','images/badge/badge'+i+'-big.png');
		$('input[name="badge"]').val(i);
	}else{
		tradeReqSelected=0;
	}
}

showBadgePopup = function (badgeId,id) {
	if (id==null){
		id=0;
	}
	
	var d = new Date();
	var rand = d.getTime();
	$.ajax({
		type: "POST",
		url: 'index.php?page=news&act=getbadgeinfo&ajax=1&rand='+rand,
		data: {'id':badgeId},
		dataType: "json",
		success: function (data) {
			if ( data.status > 0 ){
				$('#badgeInfo').text(data.data.name);
				$('#imgSuccess').attr('src','');
				$('#imgSuccess').attr('src','images/badge/badge'+badgeId+'-super.png');
				$('#messageMenu').html('<a href="index.php?page=profile&id='+id+'">See Badges</a>');
				$("#popup").fadeToggle("slow");
			}
		}
	})
}

goUniversal = function (m){
	
	var d = new Date();
	var rand = d.getTime();
	$.ajax({
		type: "GET",
		url: 'index.php?page=code&act=universal&ajax=1&m='+m+'&rand='+rand,
		dataType: "json",
		success: function (data) {
			if ( data.status == 1 ){
				
				$('#uni-code').val(data.kode);
				$('#listBadgeRequest').fadeIn();
				
			}else{
				$('#poptitle').html("YOU ARE UNLUCKY IN<br />UNLOCKING THE BADGE TODAY");
				$('#popuniimg').attr('src','');
				$('#popuniimg').attr('src','images/badge/badge_universal.png');
				$('#unluckyBadgeUniversal').fadeIn();
			}
		}
	})
	
}

showProfilePopup = function (regid) {
	var no = regid;
	var rand = Math.floor((10-4)*Math.random()) + 5;
					$.ajax({
						type: "POST",
						url: 'index.php?page=code&act=getmopprofile&ajax=1&rand='+rand,
						data: {'regid':no},
						dataType: "json",
						success: function (data) {
							//alert(data);
							if ( data.status > 0 ){
								var profile = data.data;
								$('#profileName').text(profile.name);
								$('#profileImg').attr('src',profile.img);
								$('#profileAge').text(profile.age);
								$('#profileCity').text(profile.kota);
								$('#profileDate').text(profile.register_date);
								$('#profileDescription').html(profile.description);
								
								/* BADGE NGGA JADI DI TAMPILIN
								var num = data.data.badge.length;
								var htm='';
								for(var i=0;i<num;i++){
									htm += '<div class="badge-box">';
									htm += '<div class="badge-img">';
									htm += '<img src="images/badge/badge'+(i+1)+'.png" width="65" />';
									htm += '</div>';
									htm += '<div class="badge-count">';
									htm += '<span>'+data.data.badge[i]+'</span>';
									htm += '</div>';
									htm += '</div>';
								}
								$('#profileBadge').html(htm);
								*/
								$("#offerBox").fadeToggle("slow");
								
								
							}else{
								alert("Failed, please try again!");
							}
						},
						error: function (res, status) {
							if  (status === "error") {
								var errorMessage = $.parseJSON(res.responseText);
								alert(errorMessage.Message);
							}
						}
					});
}


var acitivityInterval = 0;
var onActivity = false;
var css = 1;
function getLastActivity (){
	//alert("latest activity: "+last_activity_id);
	var d = new Date();
	var rand = d.getTime();
	$.ajax({
		type: "POST",
		url: 'index.php?page=news&act=getlasttrade&ajax=1&rand='+rand,
		data: {'id':last_activity_id},
		dataType: "json",
		success: function (data) {
			if ( data.status > 0 ){
				last_activity_id = data.last_id;
				
				num = data.data.length;
				if (num > 0){
						var htm = "";
						for(i=0;i<num;i++){
							htm += '<div class="row">';
							if ( css == 1){
								css = 0;
								htm +='<div class="entry2">';
							}else{
								css = 1;
								htm += '<div class="entry">';
							}
							htm += '<p>';
							htm += '<span class="icon_user">';
							if (data.data[i].img != ''){
								htm += '<img src="'+data.data[i].img+'" width="28" height="34" />';
							}
							htm += '</span>'; 
							htm +='<span class="activity">';
							htm += data.data[i].tradenews_content+' - '+data.data[i].time;
							htm +='</span>';
							htm +='</p>';
							htm += '</div></div>';
						}
						
						//$("#dataActivity").prepend(htm);
						addDataActivity(htm,num);
				}
			}else{
				//alert("Failed, please try again!");
			}
		},
		error: function (res, status) {
			/*
			if  (status === "error") {
				var errorMessage = $.parseJSON(res.responseText);
				alert(errorMessage.Message);
			}
			*/
		}
	});
}

addDataActivity = function (htm,num) {
	
	$("#dataActivity").prepend(htm);
	
	//var num = $("#dataActivity").children().size();
	
	for(i=0;i<num;i++){
		$("#dataActivity div:last").remove();
	}
	
}

/*
id:
berlin_wall
dj
yacht
art_museum
*/
trackingGames = function (id) {
	var rand = Math.floor((10-4)*Math.random()) + 5;
	$.ajax({
		type: "POST",
		url: 'index.php?page=games&act='+id+'&rand='+rand,
		data: {},
		dataType: "json",
		success: function (data) {},
		error: function (res, status) {}
	});
}

reGenerateCaptcha = function () {
	var d = new Date();
	var rand = d.getTime();
	$('#captcha').attr('src','captcha.php?rand='+rand);
}
	
$(document).ready(function(){

	$("#send-universal-badge").submit(function(){
		
		var kode = $('#uni-code').val();
		var badge = $('.badge-cek :checked').val();
		//alert($('.badge-cek :checked').val());
		
		var d = new Date();
		var rand = d.getTime();
		$.ajax({
			type: "GET",
			url: 'index.php?page=code&act=saveuniversal&ajax=1&kode='+kode+'&badge='+badge+'&rand='+rand,
			dataType: "json",
			success: function (data) {
				if ( data.status == 1 ){
					$('#listBadgeRequest').hide();
					$('#popunititle').html("Congratulations<br />you have unlocked a badge");
					$('#popuniimg').attr('src','');
					$('#popuniimg').attr('src','images/badge/badge'+badge+'-big.png');
					$('#unluckyBadgeUniversal').fadeIn();
				}else{
					$('#listBadgeRequest').hide();
					$('#poptitle').html("YOU ARE UNLUCKY IN<br />UNLOCKING THE BADGE TODAY");
					$('#popuniimg').attr('src','');
					$('#popuniimg').attr('src','images/badge/badge_universal.png');
					$('#unluckyBadgeUniversal').fadeIn();
				}
			}
		})
		
		return false;
	});

	//toolTip
	(function($){
		$.fn.tipTip = function(options) {
			var defaults = { 
				activation: "hover",
				keepAlive: false,
				maxWidth: "200px",
				edgeOffset: 3,
				defaultPosition: "bottom",
				delay: 400,
				fadeIn: 200,
				fadeOut: 200,
				attribute: "title",
				content: false, // HTML or String to fill TipTIp with
				enter: function(){},
				exit: function(){}
			};
			var opts = $.extend(defaults, options);
			
			// Setup tip tip elements and render them to the DOM
			if ($("#tiptip_holder").length <= 0){
				var tiptip_holder = $('<div id="tiptip_holder" style="max-width:'+ opts.maxWidth +';"></div>');
				var tiptip_content = $('<div id="tiptip_content"></div>');
				var tiptip_arrow = $('<div id="tiptip_arrow"></div>');
				$("body").append(tiptip_holder.html(tiptip_content).prepend(tiptip_arrow.html('<div id="tiptip_arrow_inner"></div>')));
			} else {
				var tiptip_holder = $("#tiptip_holder");
				var tiptip_content = $("#tiptip_content");
				var tiptip_arrow = $("#tiptip_arrow");
			}
			
			return this.each(function(){
				var org_elem = $(this);
				if (opts.content){
					var org_title = opts.content;
				} else {
					var org_title = org_elem.attr(opts.attribute);
				}
				if (org_title != ""){
					if (!opts.content){
						org_elem.removeAttr(opts.attribute); //remove original Attribute
					}
					var timeout = false;
					
					if (opts.activation == "hover"){
						org_elem.hover(function(){
							active_tiptip();
						}, function(){
							if (!opts.keepAlive){
								deactive_tiptip();
							}
						});
						if (opts.keepAlive){
							tiptip_holder.hover(function(){}, function(){
								deactive_tiptip();
							});
						}
					} else if (opts.activation == "focus"){
						org_elem.focus(function(){
							active_tiptip();
						}).blur(function(){
							deactive_tiptip();
						});
					} else if (opts.activation == "click"){
						org_elem.click(function(){
							active_tiptip();
							return false;
						}).hover(function(){},function(){
							if (!opts.keepAlive){
								deactive_tiptip();
							}
						});
						if (opts.keepAlive){
							tiptip_holder.hover(function(){}, function(){
								deactive_tiptip();
							});
						}
					}
				
					function active_tiptip(){
						opts.enter.call(this);
						tiptip_content.html(org_title);
						tiptip_holder.hide().removeAttr("class").css("margin","0");
						tiptip_arrow.removeAttr("style");
						
						var top = parseInt(org_elem.offset()['top']);
						var left = parseInt(org_elem.offset()['left']);
						var org_width = parseInt(org_elem.outerWidth());
						var org_height = parseInt(org_elem.outerHeight());
						var tip_w = tiptip_holder.outerWidth();
						var tip_h = tiptip_holder.outerHeight();
						var w_compare = Math.round((org_width - tip_w) / 2);
						var h_compare = Math.round((org_height - tip_h) / 2);
						var marg_left = Math.round(left + w_compare);
						var marg_top = Math.round(top + org_height + opts.edgeOffset);
						var t_class = "";
						var arrow_top = "";
						var arrow_left = Math.round(tip_w - 12) / 2;

						if (opts.defaultPosition == "bottom"){
							t_class = "_bottom";
						} else if (opts.defaultPosition == "top"){ 
							t_class = "_top";
						} else if (opts.defaultPosition == "left"){
							t_class = "_left";
						} else if (opts.defaultPosition == "right"){
							t_class = "_right";
						}
						
						var right_compare = (w_compare + left) < parseInt($(window).scrollLeft());
						var left_compare = (tip_w + left) > parseInt($(window).width());
						
						if ((right_compare && w_compare < 0) || (t_class == "_right" && !left_compare) || (t_class == "_left" && left < (tip_w + opts.edgeOffset + 5))){
							t_class = "_right";
							arrow_top = Math.round(tip_h - 13) / 2;
							arrow_left = -12;
							marg_left = Math.round(left + org_width + opts.edgeOffset);
							marg_top = Math.round(top + h_compare);
						} else if ((left_compare && w_compare < 0) || (t_class == "_left" && !right_compare)){
							t_class = "_left";
							arrow_top = Math.round(tip_h - 13) / 2;
							arrow_left =  Math.round(tip_w);
							marg_left = Math.round(left - (tip_w + opts.edgeOffset + 5));
							marg_top = Math.round(top + h_compare);
						}

						var top_compare = (top + org_height + opts.edgeOffset + tip_h + 8) > parseInt($(window).height() + $(window).scrollTop());
						var bottom_compare = ((top + org_height) - (opts.edgeOffset + tip_h + 8)) < 0;
						
						if (top_compare || (t_class == "_bottom" && top_compare) || (t_class == "_top" && !bottom_compare)){
							if (t_class == "_top" || t_class == "_bottom"){
								t_class = "_top";
							} else {
								t_class = t_class+"_top";
							}
							arrow_top = tip_h;
							marg_top = Math.round(top - (tip_h + 5 + opts.edgeOffset));
						} else if (bottom_compare | (t_class == "_top" && bottom_compare) || (t_class == "_bottom" && !top_compare)){
							if (t_class == "_top" || t_class == "_bottom"){
								t_class = "_bottom";
							} else {
								t_class = t_class+"_bottom";
							}
							arrow_top = -12;						
							marg_top = Math.round(top + org_height + opts.edgeOffset);
						}
					
						if (t_class == "_right_top" || t_class == "_left_top"){
							marg_top = marg_top + 5;
						} else if (t_class == "_right_bottom" || t_class == "_left_bottom"){		
							marg_top = marg_top - 5;
						}
						if (t_class == "_left_top" || t_class == "_left_bottom"){	
							marg_left = marg_left + 5;
						}
						tiptip_arrow.css({"margin-left": arrow_left+"px", "margin-top": arrow_top+"px"});
						tiptip_holder.css({"margin-left": marg_left+"px", "margin-top": marg_top+"px"}).attr("class","tip"+t_class);
						
						if  (timeout){ clearTimeout(timeout); }
						timeout = setTimeout(function(){ tiptip_holder.stop(true,true).fadeIn(opts.fadeIn); }, opts.delay);	
					}
					
					function deactive_tiptip(){
						opts.exit.call(this);
						if  (timeout){ clearTimeout(timeout); }
						tiptip_holder.fadeOut(opts.fadeOut);
					}
				}				
			});
		}
	})(jQuery);
	
	$('#inputCodeSubmit').click(function(){
		var code = $('input[name="code"]').val();
		var captcha = $('input[name="captcha"]').val();
		
		if (code == ''){
			//alert('please insert code!');
			$('#err').html('<p>Please insert code!</p>');
			$('#err').fadeIn('slow');
			return false;
		}
		if (captcha == ''){
			//alert('please insert security code!');
			$('#err').html('<p>Please insert security code!</p>');
			$('#err').fadeIn('slow');
			return false;
		}
		
		var d = new Date();
		var rand = d.getTime();
		$.ajax({
				type: "POST",
				url: 'index.php?page=code&act=submit&ajax=1&rand='+rand,
				data: {'code':code,'captcha':captcha},
				dataType: "json",
				beforeSend: function( xhr ) {
					$('#err').html('<p>Please wait...</p>');
					$('#err').fadeIn('slow');
				},
				success: function (data) {
					if (data){
						if ( data.status == 1 ){
							$('#err').fadeOut('slow');
							$('#msgSuccess').text(data.data.badge.name);
							$('#imgSuccess').attr('src','images/badge/badge'+data.data.badge.id+'-super.png');
							$("#popup").fadeToggle("slow");
							reGenerateCaptcha();
						}else if (data.status == 444){
							$('#err').fadeOut('slow',function(){
								$('#err').html('<p>Invalid code</p>');
								$('#err').fadeIn('slow');
								reGenerateCaptcha();
							});
						}else if (data.status == 666){
							$('#err').fadeOut('slow',function(){
								$('#err').html('<p>Please wait 1 minutes to input code again!</p>');
								$('#err').fadeIn('slow');
								reGenerateCaptcha();
							});
						/*
						}else if (data.status == 2){
							$('#err').fadeOut('slow',function(){
								$('#err').html('<p>This code is no longer valid</p>');
								$('#err').fadeIn('slow');
								reGenerateCaptcha();
							});
						}else if (data.status == 3){
							$('#err').fadeOut('slow',function(){
								$('#err').html('<p>This code is no longer available</p>');
								$('#err').fadeIn('slow');
								reGenerateCaptcha();
							});
						*/
						}else if (data.status == 9){
							//Universal Badge
							$('#universal-code').val(code);
							//$("#popupUniversal").fadeIn();
							$("#listBadgeRequest").fadeIn();
						}else{
							//alert("Submit code/security code failed, please try again!");
							$('#err').fadeOut('slow',function(){
								$('#err').html('<p>'+data.message+'</p>');
								$('#err').fadeIn('slow');
								reGenerateCaptcha();
							});
						}
					}else{
						$('#err').fadeOut('slow',function(){
							$('#err').html('<p>Error processing</p>');
							$('#err').fadeIn('slow');
							reGenerateCaptcha();
						});
					}
				},
				error: function (res, status) {
					if  (status === "error") {
						var errorMessage = $.parseJSON(res.responseText);
						alert(errorMessage.Message);
					}
					reGenerateCaptcha();
				}
			});
		
		return false;
	});
	
	$('#formRequestTrade').submit(function(){
		//alert(tradeHaveSelected+' - '+tradeReqSelected);
		if ( tradeHaveSelected != 0 && tradeReqSelected != 0){
			$(".requestBadge").fadeOut("slow");
			$("#badgeRequest").fadeIn("slow");
		}else{
			alert("Choose badge please!");
		}
		return false;
	});
	
	
	$('#formRequestTradeConfirm').submit(function(){
		
		if ( tradeHaveSelected == 0 ){
			alert('Select your badge please');
			return false
		}
		if ( tradeReqSelected == 0 ){
			alert('Select your require badge please');
			return false
		}
		
		var rand = Math.floor((10-4)*Math.random()) + 5;
		$.ajax({
				type: "POST",
				url: 'index.php?page=code&act=submittrade&ajax=1&rand='+rand,
				data: {'have':tradeHaveSelected,'req':tradeReqSelected},
				dataType: "json",
				success: function (data) {
					if ( data.status > 0 ){
						$("#badgeRequest").fadeOut("slow");
						$("#badgeSubmitted").fadeIn("slow");
					}else{
						alert(data.message);
					}
				},
				error: function (res, status) {
					if  (status === "error") {
						var errorMessage = $.parseJSON(res.responseText);
						alert(errorMessage.Message);
					}
				}
			});
		return false;
	});
	
	$('#formRequestTradeConfirm2').submit(function(){
		
		//alert(confirmMine+' - '+confirmYour);
		if ( sellerId != 0 && confirmMine != 0 && confirmYour != 0)
		{
			var rand = Math.floor((10-4)*Math.random()) + 5;
			$.ajax({
					type: "POST",
					url: 'index.php?page=code&act=confirmtraderequest&ajax=1&rand='+rand,
					data: {'mine':confirmMine,'your':confirmYour,'sellerId':sellerId},
					dataType: "json",
					success: function (data) {
						if ( data.status > 0 ){
							$("#badgeRequestConfirm").fadeOut("slow");
							$("#badgeRequestSuccess").fadeIn("slow");
							$('#msgSuccess').text(data.msg);
							$('#badgeres').attr('src','images/badge/badge'+data.badge+'-super.png');
						}else{
							alert(data.msg);
						}
					},
					error: function (res, status) {
						if  (status === "error") {
							var errorMessage = $.parseJSON(res.responseText);
							alert(errorMessage.Message);
						}
					}
				});
		}else{
			alert('Badge Trade Failed, please try again!');
		}
		return false;
		
	});
	

	$("#btnClosePopupProfile").click(function(){
		$("#offerBox").fadeOut("slow");
	});
	

	if ( $(".toolTip").length > 0){
		$(function(){
			$(".toolTip").tipTip({defaultPosition:'top'});
		});
	}
	
	$("#thumb-b1").click(function(){
		$("#bgGame").fadeIn("slow");
		$("#berlin1").fadeIn("slow");
	});
	$(".btn-close").click(function(){
		$("#bgGame").fadeOut("slow");
		$("#berlin1").fadeOut("slow");
	});
	
	$('#popupClose').click(function(){
		$('#popupError').fadeOut('slow');
	});
	$('#formRedeemConfirm').submit(function(){
		var prize = $('input[name="prize"]').val();
		var street = $('input[name="street"]').val();
		var complex = $('input[name="complex"]').val();
		var province = $('input[name="province"]').val();
		var city = $('select.city option:selected').val();
		var phone = $('input[name="phone"]').val();
		var mobile = $('input[name="mobile"]').val();
		var agree = $('input[name="agree"]').is(':checked');
		
		var type = $('input[name="type"]').val();
		var size = $('input[name="size"]').val();
		
		//alert(prize+' - '+street+' - '+complex+' - '+province+' - '+city+' - '+phone+' - '+mobile);
		
		if (prize == '' || street == '' || complex == '' || province == '' || city == '' || phone == '' || mobile == ''){
			$('#popupError p').html("Complete The Form Please");
			$('#popupError').fadeIn('slow');
			return false;
		}
		
		if (redeemAllow < 1){
			$('#popupError p').html("You don't have enough badges for this merchandise");
			$('#popupError').fadeIn('slow');
			return false;
		}
		
		
		if (!agree){
			$('#popupError p').html("Are you agree with terms and conditions?");
			$('#popupError').fadeIn('slow');
			return false;
		}
		
		var rand = Math.floor((100000-4)*Math.random()) + 5;
		$.ajax({
				type: "POST",
				url: 'index.php?page=code&act=prizesubmit&ajax=1&type='+type+'&size='+size+'&rand='+rand,
				data: {'prize':prize,'street':street,'complex':complex,'province':province,'city':city,'phone':phone,'mobile':mobile,'agree':agree},
				dataType: "json",
				success: function (data) {
					if ( data.status==0 ){
						$('#popupError p').html(data.message);
						$('#popupError').fadeIn('slow');
						return false;
					}else if (data.status == 1){
						$('#popupError p').html(data.message);
						$('#popupError').fadeIn('slow');
						window.location.href = data.url;
						return false;
					}
				},
				error: function (res, status) {
					if  (status === "error") {
						var errorMessage = $.parseJSON(res.responseText);
						alert(errorMessage.Message);
					}
				}
			});
		return false;
	});
	
	
	$('#formTshirt').submit(function(){
		var type = $('input[name="type"]:checked').val();
		//var id = $('input[name="id"]').val();
		//var prize = $('input[name="prize"]').val();
		//var size = $('select.size option:selected').val();
		
		//alert(size+' - '+type+' - '+id);
		//return false;
		
		if (size == '' || type == '' || !type ){
			$('#popupError p').html("Complete The Form Please");
			$('#popupError').fadeIn('slow');
			return false;
		}
		
		return true;
		
		/*
		var rand = Math.floor((100000-4)*Math.random()) + 5;
		$.ajax({
				type: "POST",
				url: 'index.php?page=code&act=tshirtsubmit&ajax=1&rand='+rand,
				data: {'size':size,'type':type,'id':id,'prize':prize},
				dataType: "json",
				success: function (data) {
					if ( data.status==0 ){
						$('#popupError p').html(data.message);
						$('#popupError').fadeIn('slow');
						return false;
					}else if (data.status == 1){
						$('#popupError p').html(data.message);
						$('#popupError').fadeIn('slow');
						window.location.href = data.url;
						return false;
					}
				},
				error: function (res, status) {
					if  (status === "error") {
						var errorMessage = $.parseJSON(res.responseText);
						alert(errorMessage.Message);
					}
				}
			});
		return false;
		*/
	});
	
	/* ACTIVITY REFRESH
	*/
	if (onActivity){
		acitivityInterval = setInterval( "getLastActivity()", 10000);
	}else{
		if (acitivityInterval){
			clearInterval(acitivityInterval);
		}
	}

});