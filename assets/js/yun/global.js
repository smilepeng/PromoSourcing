
  window.onload=init;
 

function check_login()
{
	
		$.ajax({
		  type: "GET",
		  url:  $('#site_url').val()+"/admin_users/check_login",
		  
		  data: {			
		  },
		  dataType: "json",
		  async: true,
		  success: function(msg){
			
			$('body').ajaxComplete( function(event, request, settings) {					
					
					if( msg["logged"]==1)
					{						
						self.location=$('#site_url').val()+'/admin_users/display_my_info';
					}
					
					$('#body').unbind('ajaxComplete');
				});
			
			
		},
		
		 error: function(e) {
			alert('Error:"'+e.message);
		  }

		});
	
} 

function init() {
//	check_login();
//$('#after_login').hide();
//  $('#user_auth_username').focus();
 }
 
function isContainInvalidChar(str){
    var invalidChars=new Array(' ','"','\'', '\\');
    for(x in invalidChars){
        if (str.indexOf(invalidChars[x])!= -1)
            return true;
    }
    return false;
}

function isUnsignedInteger(s) {
    return (s.toString().search(/^[0-9]+$/) == 0);
}


function loginUser(login, password)
{
	
		$.ajax({
		  type: "GET",
		  url: $('#site_url').val(),
		  
		  data: {
			'c': 'admin_users',
			'm': 'login',
			'login': login,
			'password': password
		  },
		  dataType: "json",
		  async: true,
		  success: function(msg){
			
			$('#error_message').ajaxComplete( function(event, request, settings) {
					
					if( msg["passed"]==1)
					{
						//$(this).html('Logged in');
						$('#login_name').html(msg['login']);
						$('#role').html(msg['role']);
						$('#after_login').show();
						$('#user_auth_username').val('');
						$('#user_auth_password').val('');
						$('#form_users_login').hide();
					}
					else {
						$(this).html( 'Username and password are not matched.');
					}				
					$('#error_message').unbind('ajaxComplete');
				});
			
			
		},
		
		 error: function(e) {
			alert('Error:"'+e.message);
		  }

		});
	
} 

function login_from_login_page()
{
	var from_url = $('#from_url').val();
    var login	=$('#login_page_name').val();
    var password_val=$('#login_page_password').val();//document.forms['loginForm'].elements['password'].value;
	
    $('#user_login_error').html("");
	$('#login_error').html("");
	$('#password_error').html("");
    
    if ( login===""){
		$('#login_error').html("User ID should not be empty.");
		return false;
    }else if(login.length >20){
		$('#login_error').html("User ID's length should less than 20.");
        return false;
    }else if(login.length < 1 ){
        $('#login_error').html("User ID's length should more than 1.");
        return false;
    }else if( isContainInvalidChar(login)   ){
        $('#login_error').html("User ID contains invalid character, such as  \\, ', \", or white space.");
        return false;
    }
    
    if ( password_val===''){
		$('#password_error').html("Password should not be empty.");
		return false;
    }else if(password_val.length >20 ){
        $('#password_error').html("Password's length should less than 20.");
        return false;
    }else if(password_val.length < 1 ){
        $('#password_error').html("Password's length should more than 1.");
        return false;
    }else if(isContainInvalidChar(password_val)   ){
        $('#password_error').html("Password contains invalid character, such as \\, ', \", or white space.");
        return false;
    }

    $.ajax({
		  type: "GET",
		  url: $('#site_url').val(),
		  
		  data: {
			'c': 'admin_users',
			'm': 'login',
			'login': login,
			'password': password_val
		  },
		  dataType: "json",
		  async: true,
		  success: function(msg){
			
			$('#user_login_error').ajaxComplete( function(event, request, settings) {
					
					if( msg["passed"]==1)
					{
						if (from_url !='')
							window.location.href=from_url;
						else
							window.location.href=$('#site_url').val();
					
					}
					else {
						$(this).html( 'Login name and password are not matched.');
						return false;
					}
					
				});
			
			
		},
		
		 error: function(e) {
			alert('Error:"'+e.message);
		  }

		});
	
} 
    

function logout( site_url)
{
	
	$.ajax({
		type: "GET",
		url: site_url,//"http://localhost/RACCA/index.php",
		  
		data: {
			'c': 'accounts',
			'm': 'logout'
		},
		dataType: "json",
		async: true,
		success: function(msg){
			
			$('#error_message').ajaxComplete( function(event, request, settings) {
					
					if( msg["loggedout"]==1)
					{
						/*
						$('#login_name').html('');
						$('#role').html('');
						$('#after_login').hide();
						$('#form_users_login').show();
						*/
						
						$('#error_message').unbind('ajaxComplete');
						location.reload();
					}
					
				
				});
		},
		
		 error: function(e) {
			alert('Error:"'+e.message);
		  }

		});
	
} 
//Script of login form
function validate_login(){

    var login	=$('#user_auth_username').val();
    var password_val=$('#user_auth_password').val();//document.forms['loginForm'].elements['password'].value;
	
    $('#error_message').html("");
    //document.getElementById("errorMessage").innerHTML="";
    var pass=true;
    var errorMessage="";
    
    if ( login===""){
		errorMessage+="User ID should not be empty.<p/>";
		pass= false;
    }else if(login.length >20){
		errorMessage+="User ID's length should less than 20.<p/>";
        pass= false;
    }else if(login.length < 1 ){
        errorMessage+="User ID's length should more than 1.<p/>";
        pass= false;
    }else if( isContainInvalidChar(login)   ){
        errorMessage+="User ID contains invalid character, such as  \\, ', \", or white space.<p/>";
        pass= false;
    }
    
    if ( password_val===''){
		errorMessage+="Password should not be empty.<p/>";
		pass= false;
    }else if(password_val.length >20 ){
        errorMessage+="Password's length should less than 20.<p/>";
        pass= false;
    }else if(password_val.length < 1 ){
        errorMessage+="Password's length should more than 1.<p/>";
        pass= false;
    }else if(isContainInvalidChar(password_val)   ){
        errorMessage+="Password contains invalid character, such as \\, ', \", or white space.<p/>";
        pass= false;
    }

    $("#error_message").html(errorMessage);
    
    if (pass !=false){
		var str="login=" + login+"&password="+password_val;
		
		loginUser(login, password_val);
		//$('#error_message').html('logged in');
    }

}
 $("#user_auth_username").keyup(function (e) {
     if(e.keyCode == 13) {
       validate_login();
     }

   });
   
 $("#user_auth_password").keyup(function (e) {
     if(e.keyCode == 13) {
       validate_login();
     }

   });

   
	
/* START Popup positioning operations. */
function centerPopup(popupItem) {
    var windowWidth = document.documentElement.clientWidth;  
    var windowHeight = document.documentElement.clientHeight;  
    var popupHeight = popupItem.height();  
    var popupWidth = popupItem.width();  

    popupItem.css({  
	"position" : "fixed",
	"top"      : windowHeight/2 - popupHeight/2,  
	"left"     : windowWidth/2 - popupWidth/2  
    });
}

function positionPopup(popupItem, top, topPadding, left, leftPadding) {
    var windowWidth = document.documentElement.clientWidth;
    var windowHeight = document.documentElement.clientHeight;
    var verticalScroll = $(window).scrollTop();
    var horizontalScroll = $(window).scrollLeft();
    var popupWidth = popupItem.width();    
    var popupHeight = popupItem.height();
    
    var topPlacement = top + topPadding;
    var leftPlacement = left + leftPadding;
    if(top + popupHeight >= verticalScroll + windowHeight) {
	/* If popup will be too low to view, then place it as high as necessary to make it viewable */
	if(top + topPadding - popupHeight - verticalScroll >= 0) {
	    topPlacement = top - topPadding - popupHeight;
	}
	else {
	    topPlacement = verticalScroll;
	}
    }

    if(left + leftPadding + popupWidth >= horizontalScroll + windowWidth) {
	/* If popup will be too far right to view, then place it as far left as necessary to make it viewable */
	if(left + leftPadding - popupWidth - horizontalScroll >= 0) {
	    leftPlacement = left - leftPadding - popupWidth;
	}
	else {
	    leftPlacement = horizontalScroll;
	}
    }

    popupItem.css({
	        "position" : "absolute",
		"top"      : topPlacement,
		"left"     : leftPlacement
		});
}
/* END Popup positioning operations. */


 