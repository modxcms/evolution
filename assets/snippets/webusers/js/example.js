$(document).ready(function() { 
    var options = { 
		url:		'taconite', 
		target:     '#messages',
		success:    taconiteFollowUp
    };

	//var service = '';
	var errMsg = '';
	
	$('<fieldset id="wlpeUserButtons">'+
		'<button type="submit" id="wlpeLogoutButton" name="service" value="logout" style="margin: 0 2px;">Log Out</button>'+
		'<button type="submit" id="wlpeProfileButton" name="service" value="profile" style="margin: 0 2px;">Profile</button>'+
	'</fieldset>').insertAfter('#wlpeLoginButtons').hide();
	
	$('<fieldset id="wlpeResetButtons">'+
		'<button type="submit" id="wlpeResetButton" name="service" value="resetpassword" style="margin: 0 2px;">Send Password</button>'+
		'<button type="submit" id="wlpeResetCancelButton" name="service" value="cancel" style="margin: 0 2px;">Cancel</button>'+
	'</fieldset>').insertAfter('#wlpeUserButtons').hide();
	
	$('<fieldset id="wlpeRegisterButtons">'+
		'<button type="submit" id="wlpeRegisterButton" name="service" value="register" style="margin: 0 2px;">Register</button>'+
		'<button type="submit" id="wlpeRegisterCancelButton" name="service" value="cancel" style="margin: 0 2px;">Cancel</button>'+
	'</fieldset>').insertAfter('#wlpeUserButtons').hide();
	
	$('<div id="wlpeWelcome"></div>').appendTo('#wlpeLoginFieldset').hide();
	
	if ($('#wlpeWelcome').html() !== '')
	{
		$('#wlpeUserButtons').show();
	}
			
	$('#wlpeLoginForm').append('<input type="hidden" id="service" name="service" value="login" />');
	
	$('#content').prepend('<div id="messages">' + errMsg + '</div>');
	$('#messages').hide();
	
	$('#wlpeLoginForm button').click(function(){
		service = $(this).val();
		$('#messages').html(errMsg);
		$('#service').val(service);
		$('#messages').animate({opacity: 'hide', height: 'hide'}, 'normal');
	});
 
	$('#wlpeLoginForm').submit(function() {
	    $(this).ajaxSubmit(options);
	    return false; 
	}); 
});

var service = 'login';

function taconiteFollowUp(responseText){
	if (service == 'login')
	{
		if (responseText == '')
		{
			$('#wlpeUserButtons').show();
			$('#wlpeLoginButtons').hide();
			$('#wlpeResetButtons').hide();
			$('#wlpeRegisterButtons').hide();
			
			$('#wlpeLoginFieldset').animate({opacity: 'hide', height: 'hide'}, 'normal', function(){
				$('#messages').slideUp('fast');
			});
			$.get('taconite/login.xml', function(){
				$('#wlpeLoginFieldset').animate({opacity: 'show', height: 'show'}, 'normal');
			});
		}
		else
		{
			$('#messages').slideDown('normal', function(){
				$('#messages').animate({opacity: 1.0}, 4000, function(){
					$('#messages').animate({opacity: 'hide', height: 'hide'}, 'slow');
				});
			});
		}
	}
	else if (service == 'registernew')
	{
		$('#wlpeUserButtons').hide();
		$('#wlpeResetButtons').hide();
		$('#wlpeLoginButtons').hide();
		$('#wlpeRegisterButtons').show();
		
		$('#wlpeLoginFieldset').animate({opacity: 'hide', height: 'hide'}, 'normal', function(){
			$('#messages').slideUp('fast');
		});
		$.get('taconite/register.xml', function(){
			$('#wlpeLoginFieldset').animate({opacity: 'show', height: 'show'}, 'normal');
		});
	}
	else if (service == 'register' || service == 'resetpassword')
	{
		$('#wlpeUserButtons').hide();
		$('#wlpeResetButtons').hide();
		$('#wlpeRegisterButtons').hide();
		$('#wlpeLoginButtons').show();

		$('#wlpeLoginFieldset').animate({opacity: 'hide', height: 'hide'}, 'normal', function(){
			//$('#messages').slideUp('fast');
		});
		$.get('taconite/logout.xml', function(){
			$('#wlpeLoginFieldset').animate({opacity: 'show', height: 'show'}, 'normal');
		});
		
		$('#messages').slideDown('normal', function(){
			$('#messages').animate({opacity: 1.0}, 6000, function(){
				$('#messages').animate({opacity: 'hide', height: 'hide'}, 'slow');
			});
		});
	}
	else if (service == 'forgot')
	{
		$('#wlpeUserButtons').hide();
		$('#wlpeResetButtons').show();
		$('#wlpeLoginButtons').hide();
		$('#wlpeRegisterButtons').hide();
		
		$('#wlpeLoginFieldset').animate({opacity: 'hide', height: 'hide'}, 'normal', function(){
			$('#messages').slideUp('fast');
		});
		$.get('taconite/forgot.xml', function(){
			$('#wlpeLoginFieldset').animate({opacity: 'show', height: 'show'}, 'normal');
		});
	}
	else if (service == 'profile')
	{
		location.href = 'profile.html';
	}
	else
	{
		if (responseText == '')
		{
			$('#wlpeUserButtons').hide();
			$('#wlpeResetButtons').hide();
			$('#wlpeRegisterButtons').hide();
			$('#wlpeLoginButtons').show();

			$('#wlpeLoginFieldset').animate({opacity: 'hide', height: 'hide'}, 'normal', function(){
				$('#messages').slideUp('fast');
			});
			$.get('taconite/logout.xml', function(){
				$('#wlpeLoginFieldset').animate({opacity: 'show', height: 'show'}, 'normal');
			});
		}
		else
		{
			$('#messages').slideDown('normal', function(){
				$('#messages').animate({opacity: 1.0}, 4000, function(){
					$('#messages').animate({opacity: 'hide', height: 'hide'}, 'slow');
				});
			});
		}
	}
}