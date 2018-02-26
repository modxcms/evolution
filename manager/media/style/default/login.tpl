<!DOCTYPE html>
<html>
<head>
	<title>[(site_name)] (Evolution CMS Manager Login)</title>
	<meta http-equiv="content-type" content="text/html; charset=[+modx_charset+]" />
	<meta name="robots" content="noindex, nofollow" />
	<meta name="viewport" content="width=device-width">
	<link rel="icon" type="image/ico" href="[+favicon+]" />
	<link rel="stylesheet" type="text/css" href="media/style/[(manager_theme)]/style.css" />
	<style>
		html, body { min-height: 100%; height: 100%; }
		.page { height: 100%; padding-top: 7%; }
		.loginbox { width: 90%; max-width: 25rem; margin: 0 auto; }
		.copyrights { position: absolute; left: 0; right: 0; bottom: 0; padding: .5rem 1rem; font-size: .675rem; color: #aaa; text-align: right }
		.copyrights a { color: #777 }
		#submitButton { float: right; }
		#FMP-email_label { color: #818a91 }
		#FMP-email { margin-bottom: 1rem }
		#FMP-email_button { float: right; }
		/* mainloader */
		#mainloader { position: absolute; z-index: 50000; top: 0; left: 0; width: 100%; height: 100%; text-align: center; vertical-align: middle; padding: 15% 0 0 0; background-color: rgba(255, 255, 255, 0.64); opacity: 0; visibility: hidden; -webkit-transition-duration: 0.3s; transition-duration: 0.3s }
		#mainloader.show { opacity: 0.75; visibility: visible; -webkit-transition-duration: 0.1s; transition-duration: 0.1s }
		#mainloader::before { content: ""; display: block; position: absolute; z-index: 1; left: 50%; top: 30%; width: 120px; height: 120px; margin: -60px 0 0 -60px; border-radius: 50%; animation: rotate 2s linear infinite; box-shadow: 5px 5px 0 0 rgb(234, 132, 82), 14px -7px 0 0 rgba(111, 163, 219, 0.7), -7px 11px 0 0 rgba(112, 193, 92, 0.74), -11px -7px 0 0 rgba(147, 205, 99, 0.78); }
		@keyframes rotate {
			to { transform: rotate(360deg) }
			}
	</style>
</head>
<body class="[+manager_theme_style+]">
<div class="page">
	<div class="tab-page loginbox">
		<form method="post" name="loginfrm" id="loginfrm" class="container container-body" action="processors/login.processor.php">
			[+OnManagerLoginFormPrerender+]
			<div class="form-group text-center">
				<a class="logo" href="../" title="[(site_name)]">
					<img src="media/style/[(manager_theme)]/images/misc/login-logo.png" alt="[(site_name)]" id="logo" />
				</a>
			</div>
			<div class="form-group">
				<label for="username" class="text-muted">[+username+]</label>
				<input type="text" class="form-control" name="username" id="username" tabindex="1" value="[+uid+]" />
			</div>
			<div class="form-group">
				<label for="password" class="text-muted">[+password+]</label>
				<input type="password" class="form-control" name="password" id="password" tabindex="2" value="" />
			</div>
			<div class="clearfix">
				<div class="caption">[+login_captcha_message+]</div>
				<p>[+captcha_image+]</p>
				[+captcha_input+]
			</div>
			<div class="form-group">
				<label for="rememberme" class="text-muted">
					<input type="checkbox" id="rememberme" name="rememberme" value="1" class="checkbox" [+remember_me+] /> [+remember_username+]</label>
				<button type="submit" name="submitButton" class="btn btn-success float-xs-right" id="submitButton">[+login_button+]</button>
			</div>
			[+OnManagerLoginFormRender+]
		</form>
	</div>
	<div class="copyrights">
		<p class="loginLicense"></p>
		<div class="gpl">&copy; 2005-2018 by the <a href="http://evo.im/" target="_blank">EVO</a>. <strong>EVO</strong>&trade; is licensed under the GPL.</div>
	</div>
</div>
<div id="mainloader"></div>
<script type="text/javascript">
	/* <![CDATA[ */
	if(window.frames.length) {
		window.location = self.document.location;
	}
	var form = document.loginfrm;
	if(form.username.value !== '') {
		form.password.focus()
	} else {
		form.username.focus()
	}
	form.onsubmit = function(e) {
		document.getElementById('mainloader').classList.add('show');
		var xhr = new XMLHttpRequest();
		xhr.open('POST', 'processors/login.processor.php', true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
		xhr.onload = function() {
			if(this.readyState === 4) {
				var header = this.response.substr(0, 9);
				if(header.toLowerCase() === 'location:') {
					window.location = this.response.substr(10);
				} else {
					var cimg = document.getElementById('captcha_image');
					if(cimg) cimg.src = 'includes/veriword.php?rand=' + Math.random();
					document.getElementById('mainloader').classList.remove('show');
					alert(this.response);
				}
			}
		};
		xhr.send('ajax=1&username=' + encodeURIComponent(form.username.value) + '&password=' + encodeURIComponent(form.password.value) + (form.captcha_code ? '&captcha_code=' + encodeURIComponent(form.captcha_code.value) : '') + '&rememberme=' + form.rememberme.value);
		e.preventDefault();
	}
	/* ]]> */
</script>
</body>
</html>