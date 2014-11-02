//document.addEventListener("deviceready", onDeviceReady, false);
var server = 'http://192.168.1.80/kadokadeh/api/';
var myDate = new Date();
var IMEI = myDate.getTime();
var permanentStorage = window.localStorage;
permanentStorage.setItem('logged_in',false);
permanentStorage.setItem('password_set',false);
permanentStorage.setItem('verified',false);

for (var i in permanentStorage){
	console.log(i+':'+permanentStorage.getItem(i));
}


// device APIs are available
//
/*
function onDeviceReady() {
	IMEI = device.uuid;
}
*/
$(document).ready(function(){
	if (permanentStorage.getItem('password_set') == 'true'){ //go to login page
		$.mobile.changePage( "#mainFourthPage");	
	}
	else if (permanentStorage.getItem('verified') == 'true'){ //go to set password
		$.mobile.changePage( "#mainSecondPage");	
	}
});

$( document ).one( "pagecreate", "#mainFirstPage", function() {
	$( "#mainFirstPage-text-1" ).on('focus',function(){
		if(!$( this ).val().length){
			$(this).parent().addClass('no-label');
			/*for iphone bug setTimeout Added*/
			setTimeout(function(){
				$( "#mainFirstPage-text-1"  ).val('09')
			},100)

		}

	})
	$( "#mainFirstPage-text-1" ).on('blur',function(){
		if($( this ).val().length < 3){
			$( this ).val('')
			$(this).parent().removeClass('no-label');
		}
	})
	$( "#mainFirstPage-text-2" ).on('focus',function(){
		if(!$( this ).val().length){
			$(this).parent().addClass('no-label');
			setTimeout(function(){
				$( "#mainFirstPage-text-2"  ).val('09')
			},100)
		}

	})
	$( "#mainFirstPage-text-2" ).on('blur',function(){
		if($( this ).val().length < 3){
			$( this ).val('')
			$(this).parent().removeClass('no-label');
		}
	})
	
	$(".numbers-only").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });				
	$( "#registerForm" ).submit(function( event ) {
		event.preventDefault();
		if(false){
			objTools.validation.addError('#mainFirstPage-text-1','لطفا فقط عدد وارد نمایید.')
//                        objTools.validation.removeError('#mainFirstPage-text-1')
		}
		else{
			$.mobile.loading( "show");
			data='referer_cell='+$('#mainFirstPage-text-1').val()+'&own_cell='+$('#mainFirstPage-text-2').val()+'&IMEI='+IMEI+'&format=json';
			$.ajax({
					dataType: "jsonp",
					url: server + 'referer',
					headers:{"X-API-KEY":"eywfHzJctuyz6Rv6TcS2aOspWbD5Vg47OBTAXgwg"},
					data: data,
					type: 'GET',
					success: function(mydata){
					if (mydata.status == -2){
						objTools.validation.addError('#mainFirstPage-text-2','شما قبلا ثبت نام کرده اید');
						return false;
					}
					if (mydata.status == -1){
						objTools.validation.addError('#mainFirstPage-text-1','معرف شما در سیستم پیدا نشد');
						return false;
					}
					permanentStorage.setItem("user_id", mydata.data.user_id);								
					permanentStorage.setItem("cell_number", $('#mainFirstPage-text-2').val());								
					
					console.log(mydata);
					$.mobile.changePage( "#mainSecondPage");
				},
				error: function( xhr, status, errorThrown ) {

				},
				complete: function( xhr, status ) {
					$.mobile.loading( "hide");
				}						  
			});	
		}

	});

});
$( document ).one( "pagecreate", "#mainSecondPage", function() {
	$('#resendCode').click(function(){
		$.mobile.loading( "show");
		var user_id = permanentStorage.getItem("user_id");
		var cell_number = permanentStorage.getItem("cell_number");
		data='user_id='+user_id+'&cell_number='+cell_number+'&format=json';
		$.ajax({
				dataType: "jsonp",
				url: server + 'setVerificationCode',
				headers:{"X-API-KEY":"eywfHzJctuyz6Rv6TcS2aOspWbD5Vg47OBTAXgwg"},
				data: data,
				type: 'GET',
				success: function(mydata){
				if (mydata.status == -1){
					objTools.validation.addError('#mainSecondPage-text-1','خطا در ارسال مجدد کد');
					return false;
				}
				console.log(mydata);
				$.mobile.changePage( "#mainSecondPage");
			},
			error: function( xhr, status, errorThrown ) {

			},
			complete: function( xhr, status ) {
				$.mobile.loading( "hide");
			}						  
		});	
	})
	$( "#confirmForm" ).submit(function( event ) {
		event.preventDefault();
		if(false){

		}
		else{
			$.mobile.loading( "show");
			var cell_number = permanentStorage.getItem("cell_number");
			data='verification_code='+$('#mainSecondPage-text-1').val()+'&cell_number='+cell_number+'&format=json';
			$.ajax({
					dataType: "jsonp",
					url: server + 'verification',
					headers:{"X-API-KEY":"eywfHzJctuyz6Rv6TcS2aOspWbD5Vg47OBTAXgwg"},
					data: data,
					type: 'GET',
					success: function(mydata){
					if (mydata.status == -2){
						objTools.validation.addError('#mainSecondPage-text-1','شماره شما قبلا تایید شده است');
						return false;
					}
					if (mydata.status == -1){
						objTools.validation.addError('#mainFirstPage-text-1','کد فعال سازی نادرست است');
						return false;
					}
					permanentStorage.setItem("verified", true);								
					$('#mainThirdPage-text-1').val(cell_number);
					console.log(mydata);
					$.mobile.changePage( "#mainThirdPage");
				},
				error: function( xhr, status, errorThrown ) {

				},
				complete: function( xhr, status ) {
					$.mobile.loading( "hide");
				}						  
			});	
		}

	});
});
$( document ).one( "pagecreate", "#mainThirdPage", function() {
	$('#resendCode').click(function(){
		$.mobile.loading( "show");
	})
	$( "#setPasswordForm" ).submit(function( event ) {
		event.preventDefault();
		if(false){

		}
		else{
			$.mobile.loading( "show");
			var cell_number = permanentStorage.getItem("cell_number");
			data='password='+$('#mainThirdPage-text-2').val()+'&cell_number='+cell_number+'&format=json';
			$.ajax({
					dataType: "jsonp",
					url: server + 'setPassword',
					headers:{"X-API-KEY":"eywfHzJctuyz6Rv6TcS2aOspWbD5Vg47OBTAXgwg"},
					data: data,
					type: 'GET',
					success: function(mydata){
					if (mydata.status == -1){
						objTools.validation.addError('#mainThirdPage-text-2','هنوز ثبت نام نشده اید');
						return false;
					}
					permanentStorage.setItem("password_set", true);								
					console.log(mydata);
					$.mobile.changePage( "#mainFourthPage");
				},
				error: function( xhr, status, errorThrown ) {

				},
				complete: function( xhr, status ) {
					$.mobile.loading( "hide");
				}						  
			});	
		}

	});
});
$( document ).one( "pagecreate", "#mainFourthPage", function() {
	$( "#loginForm" ).submit(function( event ) {
		event.preventDefault();
		if(false){

		}
		else{
			$.mobile.loading( "show");
			data='password='+$('#mainFourthPage-text-2').val()+'&cell_number='+$('#mainFourthPage-text-1').val()+'&format=json';
			console.log(data);
			console.log(server + 'login');
			$.ajax({
					dataType: "jsonp",
					url: server + 'login',
					headers:{"X-API-KEY":"eywfHzJctuyz6Rv6TcS2aOspWbD5Vg47OBTAXgwg"},
					data: data,
					type: 'GET',
					success: function(mydata){
					if (mydata.status == -1){
						objTools.validation.addError('#mainFourthPage-text-1','هنوز ثبت نام نشده اید');
						return false;
					}
					if (mydata.status == -2){
						objTools.validation.addError('#mainFourthPage-text-2','کلمه عبور نادرست است');
						return false;
					}
					if ($('#mainFourthPage-checkbox-0').is(':checked')){
						permanentStorage.setItem("logged_in", true);								
					}
					else{
						permanentStorage.setItem("logged_in", false);								
					}
					console.log(mydata);
					$.mobile.changePage( "#mainFourthPage");
				},
				error: function( xhr, status, errorThrown ) {

				},
				complete: function( xhr, status ) {
					$.mobile.loading( "hide");
				}						  
			});	
		}

	});
});
