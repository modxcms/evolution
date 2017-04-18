<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="[(lang_code)]" xml:lang="[(lang_code)]">
  <head>
    <title>[(site_name)] (MODX CMF Manager Login)</title>
    <meta http-equiv="content-type" content="text/html; charset=[+modx_charset+]" />
    <meta name="robots" content="noindex, nofollow" />
    <meta name="viewport" content="width=device-width">
    <style type="text/css">
      body {
        font-family: Arial, HelveticaNeue, "Helvetica Neue", Helvetica, sans-serif;
      }
      html:lang(ja) body {
        font-family: Arial,"Helvetica Neue",Helvetica,Meiryo,"Hiragino Kaku Gothic Pro",sans-serif;
      }
      input {
        font-family:inherit;
      }
      #login {
        background-color: #F2F2F2;
        margin: 7% 0 0;
      }
      #mx_loginbox {
        width: 309px;
        margin: 0 auto;
      }
      .sectionBody {
        border: 1px solid #E6E6E6;
        background: #FEFEFE;
        padding: 25px 0 0 20px;
        overflow: hidden;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
      }
      .logo {
        display: block;
        text-align: center;

      }
      .logo img{
        border: 0 none;
        margin: 0 0 17px;
      }
      .sectionBody label {
        color: #666666;
        display: block;
        font-size: 14px;
        margin: 0 0 5px;
      }
      .sectionBody input[type="text"],
      .sectionBody input[type="password"],
      #FMP-email
      {
        width: 261px;
        height: 33px;
        border: 1px solid #E5E5E5;
        text-indent: 5px;
        margin: 0 0 15px;
        font-size: 18px;
        box-shadow: 0 0 5px rgba(188, 188, 188, 0.15);
        border-radius: 3px;
      }
      .sectionBody input[type="text"]:focus,
      .sectionBody input[type="password"]:focus,
      #FMP-email:focus
      {
        border: 1px solid #3697CD;
        box-shadow: 0 0 5px rgba(222, 203, 165, 0.3);
      }
      #rememberme {
        float: left;
        margin: 4px 5px 0 1px;
      }
      .sectionBody .remtext {
        color: #999999;
        display: block;
        float: left;
        font-size: 13px;
        margin: 0;
        margin-top: 4px;
      } 
      #submitButton,
      #FMP-email_button {
        display: block;
        float: right;
        border: 0;
        width: 100px;
        height: 36px;
        cursor: pointer;
        /*text-indent: -9999px;*/
        color:#fff;
        font-size: 14px;
        font-weight: 100;
        margin-top: -7px;
        margin-right:22px;
        margin-bottom:10px;
        background-color: #32AB9A;
        border-radius: 3px;
        -webkit-appearance: none;
      }
      #submitButton:hover,
      #FMP-email_button:hover {
        background-color: #35baa8;
      }
      #submitButton:active,
      #FMP-email_button:active {
        background-color: #32AB9A;
      }
      #onManagerLoginFormRender {
        margin-top: 15px;
        clear: both;
      }
      #FMP-email_label {
        color: #666666;
        font-size: 13px;
        margin: 0 0 7px;
      }
      #FMP-email_button {
        margin-bottom: 20px;
      }
      .loginLicense {
        width: 309px;
        margin: 0 auto;
        display: block;
      }
      .loginLicense a {
        color: #999999;
        display: block;
        font-size: 13px;
        margin: 13px 0 0 21px;
        text-decoration: underline;
      }
      #ForgotManagerPassword-show_form {
        color: #999999;
        display: block;
        font-size: 13px;
        margin: 0 0 20px;
        text-align: left;
      }
      .error {
        font-size: 13px;
        color: #f00;
      }
      .gpl {
        position: absolute;
        bottom: 0;
        right: 0;
        color: #B2B2B2;
        margin: 0.5em auto;
        font-size: 80%;
      }
      .gpl a, .loginLicense a {
        color: #B2B2B2;
      }
      .caption {font-size: 11px; color: #666; padding-right: 25px;}
      .clear {clear: both;}
      .form-footer {padding-bottom: 10px;}
    </style>

    <script src="media/script/mootools/mootools.js" type="text/javascript"></script>

    <script type="text/javascript">
      /* <![CDATA[ */
      if (top.frames.length!=0) {
        top.location=self.document.location;
      }

      window.addEvent('domready', function() {
        $('submitButton').addEvent('click', function(e) {
          e = new Event(e).stop();
          params = 'ajax=1&' + $('loginfrm').toQueryString();
          url = 'processors/login.processor.php';
          new Ajax(url,
                   {
            method: 'post',
            postBody: params,
            onComplete:ajaxReturn
          }
                  ).request();
          $$('input').setProperty('readonly', 'readonly');
        });  

        // Initial focus
        if ($('username').value != '') {
          $('password').focus();
        } else {
          $('username').focus();
        }

      });

      function ajaxReturn(response) {
        var header = response.substr(0,9);
        if (header.toLowerCase()=='location:') top.location = response.substr(10);
        else {
          var cimg = $('captcha_image');
          if (cimg) {
            cimg.src = 'includes/veriword.php?rand=' + Math.random();
          }
          $$('input').removeProperty('readonly');
          alert(response);
        }
      }
      /* ]]> */
    </script>
  </head>
  <body id="login">
    <div id="mx_loginbox">
      <form method="post" name="loginfrm" id="loginfrm" action="processors/login.processor.php">
        <!-- anything to output before the login box via a plugin? -->
        [+OnManagerLoginFormPrerender+]
        <div class="sectionHeader">
          <a class="logo" href="../" title="[(site_name)]">
            <img src="media/style/[(manager_theme)]/images/misc/login-logo.png" alt="[(site_name)]" id="logo" />
          </a>
        </div>
        <div class="sectionBody">

          <!--<p class="loginMessage">[+login_message+]</p>-->

          <label for="username">[+username+]</label>
          <input type="text" class="text" name="username" id="username" tabindex="1" value="[+uid+]" />

          <label for="password">[+password+]</label>
          <input type="password" class="text" name="password" id="password" tabindex="2" value="" />

          <p class="caption">[+login_captcha_message+]</p>

          <p>[+captcha_image+]</p>
          [+captcha_input+]

          <div class="clear"></div>

          <div class="form-footer">
            <input type="checkbox" id="rememberme" name="rememberme" tabindex="4" value="1" class="checkbox" [+remember_me+] />
            <label for="rememberme" style="cursor:pointer" class="remtext">[+remember_username+]</label>
            <input type="submit" class="login" id="submitButton" value="[+login_button+]" />
            <!-- anything to output before the login box via a plugin ... like the forgot password link? -->
            <div class="clear"></div>
          </div>

          [+OnManagerLoginFormRender+]
        </div>
      </form>
    </div>
    <!-- close #mx_loginbox -->

    <!-- convert this to a language include -->
    <p class="loginLicense" >

    </p>
    <div class="gpl">&copy; 2005-2016 by the <a href="http://modx.com/" target="_blank">MODX</a>. <strong>MODX</strong>&trade; is licensed under the GPL.</div>
  </body>
</html>
