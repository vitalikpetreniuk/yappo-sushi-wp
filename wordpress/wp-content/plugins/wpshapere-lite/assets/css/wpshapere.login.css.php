<style type="text/css">
<?php
if(!empty($this->aof_options['login_external_bg_url']) && filter_var($this->aof_options['login_external_bg_url'], FILTER_VALIDATE_URL)) {
  $login_bg_img = esc_url( $this->aof_options['login_external_bg_url']);
}
else {
  $login_bg_img = (is_numeric($this->aof_options['login_bg_img'])) ? $this->get_wps_image_url($this->aof_options['login_bg_img']) : $this->aof_options['login_bg_img'];
}
if(!empty($this->aof_options['login_external_logo_url']) && filter_var($this->aof_options['login_external_logo_url'], FILTER_VALIDATE_URL)) {
  $login_logo = esc_url( $this->aof_options['login_external_logo_url']);
}
else {
  $login_logo = (is_numeric($this->aof_options['admin_login_logo'])) ? $this->get_wps_image_url($this->aof_options['admin_login_logo']) : $this->aof_options['admin_login_logo'];
}

?>
body, html { height: auto; }
body.login{background-color:<?php echo $this->aof_options['login_bg_color'] . ' !important;'; if(!empty($login_bg_img)) echo ' background-image: url(' . $login_bg_img  . ');'; if($this->aof_options['login_bg_img_repeat'] == 1) echo 'background-repeat: repeat'; else echo 'background-repeat: no-repeat'; ?>; background-position: center center; <?php if($this->aof_options['login_bg_img_scale']) echo 'background-size: 100% auto;'; ?> background-attachment: fixed; margin:0; padding:1px; top: 0; right: 0; bottom: 0; left: 0; }
html, body.login:after { display: block; clear: both; }
body.login-action-register { position: relative }
body.login-action-login, body.login-action-lostpassword { position: fixed }
.login h1 {padding: 40px 0}
.login h1 a {
<?php if(!empty($login_logo)) { ?>
width: 100%;
text-indent: -9999px;
background: url(<?php echo esc_url($login_logo); ?>) no-repeat !important;
background-size: contain !important;
background-position: center center !important;
<?php } ?>
margin: 0 auto 0;}
div#login { background: #fff; margin-top: 150px; padding: 0 0 18px 0 }
body.interim-login div#login {width: 95% !important; height: auto }
.login label, .login form, .login form p { color: #5a5a5a !important }
.login a { text-decoration: underline; color: #2b2a2f !important }
.login a:focus, .login a:hover { color: <?php echo $this->aof_options['form_link_hover_color']; ?> !important; }
.login form { margin-top: 0;border:none;background: #fff !important; -webkit-box-shadow: none; -moz-box-shadow: none; box-shadow: none;border-bottom: 1px solid #f9f9f9; padding: 0px 55px 30px !important; }
form#loginform .button-primary, form#registerform .button-primary, .button-primary { background:<?php echo $this->aof_options['login_button_color']; ?> !important; color: <?php echo $this->aof_options['login_button_text_color']; ?> !important; text-shadow: none!important;}
form#loginform .button-primary.focus,form#loginform .button-primary.hover,form#loginform .button-primary:focus,form#loginform .button-primary:hover, .wp-core-ui .button-primary:hover, form#registerform .button-primary.focus, form#registerform .button-primary.hover,form#registerform .button-primary:focus,form#registerform .button-primary:hover { background: <?php echo $this->aof_options['login_button_hover_color']; ?> !important; color: <?php echo $this->aof_options['login_button_hover_text_color']; ?> !important;}

input[type=checkbox], input[type=color], input[type=date], input[type=email], input[type=number], input[type=password], input[type=radio], input[type=search], input[type=tel], input[type=text], input[type=url], select, textarea {color:#72787d!important;}
::-webkit-input-placeholder { /* Chrome/Opera/Safari */
  color:#b5b8bb!important
}
::-moz-placeholder { /* Firefox 19+ */
  color:#b5b8bb!important
}
:-ms-input-placeholder { /* IE 10+ */
  color:#b5b8bb!important
}
:-moz-placeholder { /* Firefox 18- */
  color:#b5b8bb!important
}
.login form input.input {background: #fff url(<?php echo WPSHAPERE_LITE_DIR_URI; ?>assets/images/login-sprite.png) no-repeat; padding: 9px 0 9px 32px !important; font-size: 16px !important; line-height: 1; outline: none !important; border: none !important }
input#user_login { background-position:7px -6px !important; }
input#user_pass, input#user_email, input#pass1, input#pass2 { background-position:7px -56px !important; }
.login form #wp-submit { width: 100%; height: 35px }
p.forgetmenot { margin-bottom: 16px !important; }
.login #pass-strength-result {margin: 12px 0 16px !important }
p.indicator-hint { clear:both }
div.updated, .login #login_error, .login .message { border-left: 4px solid <?php echo $this->aof_options['msgbox_border_color']; ?>; background-color: <?php echo $this->aof_options['msg_box_color']; ?>; color: <?php echo $this->aof_options['msgbox_text_color']; ?>; }

.login_footer_content { padding: 20px 0; text-align:center }
<?php if($this->aof_options['hide_backtoblog'] == 1) echo '#backtoblog { display:none !important; }';
if($this->aof_options['hide_remember'] == 1) echo 'p.forgetmenot { display:none !important; }';
if($this->aof_options['design_type'] == 1) {
?>
.login .message, .button-primary,.wp-core-ui .button-primary, input[type=text], input[type=email], input[type=password], .button-primary:hover {
	-webkit-box-shadow: none !important;
	-moz-box-shadow: none !important;
	box-shadow: none !important;
}
.button-primary,.wp-core-ui .button-primary,.button-primary:hover {
	border: none !important;
}

<?php }
else {
?>
.button-primary, form#loginform .button-primary, form#registerform .button-primary, .button-primary {border-color:<?php echo $this->aof_options['login_button_border_color']; ?> !important;}
form#loginform .button-primary.focus,form#loginform .button-primary.hover,form#loginform .button-primary:focus,form#loginform .button-primary:hover, form#registerform .button-primary.focus,
.button-primary:hover,form#registerform .button-primary.hover,form#registerform .button-primary:focus,form#registerform .button-primary:hover{border-color:<?php echo $this->aof_options['login_button_hover_border_color']; ?> !important;}
<?php
}
 //end of design_type
?>

.login form input.input {
  border-bottom: 1px solid #f3f3f3!important;
}

div#login{
  -webkit-box-shadow: 5px 9px 19px 7px rgba(0,0,0,0.09);
-moz-box-shadow: 5px 9px 19px 7px rgba(0,0,0,0.09);
box-shadow: 5px 9px 19px 7px rgba(0,0,0,0.09);
}

.login form #wp-submit {
  margin-top: 37px;
  height: 60px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  border-radius: 30px;
  -webkit-box-shadow: 8px 8px 20px 4px rgba(0, 0, 0, 0.21) !important;
-moz-box-shadow: 8px 8px 20px 4px rgba(0, 0, 0, 0.21) !important;
box-shadow: 8px 8px 20px 4px rgba(0, 0, 0, 0.21) !important;
}

#nav, #backtoblog {text-align: center;}

<?php
echo $this->aof_options['login_custom_css']; ?>

@media only screen and (min-width: 780px) {
	div#login {
		width: 500px !important;
	}
}
@media screen and (max-width: 780px){
  div#login {
    width: 90% !important;
  }
}
@media screen and (max-width: 800px){
	body.login {
		background-size: auto;
	}
	body.login-action-login, body.login-action-lostpassword {
		position: relative;
	}
}
</style>
