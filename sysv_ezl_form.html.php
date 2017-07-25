<form name="login_form" id="sysv_ezl_login_form" class="sysv_ezl_regform" onsubmit="return ezl_submitfunction()" style="position: relative; background-color: rgba(44,44,44,1);">
	<input id="sysv_ezl_picker" type="hidden" value="wait">
	<span class="sysv_ezl_logregsection" id="sysv_ezl_retlog"><?php echo $sysv_ezl_vals['text']['form_name']; ?></span><br>

	<span class="sysv_ezl_logregsection" id="userSection">
		<label for="user_name">Email:</label>
		<input id="username" type="text" placeholder="Email" name="user_name" class="sysv_ezl_userfield">
	</span>

	<span id="sysv_ezl_passsection" class="sysv_ezl_logregsection" style="position: absolute; opacity: 0;">
		<label for="password">Password:</label>
		<input id="password" type="password" placeholder="Password" name="password" class="sysv_ezl_passfield">
	</span>
	<input type="submit" id="sysv_ezl_subbutton" class="sysv_ezl_logbutton" style="display: none;" value="">
	<span class="sysv_ezl_logregsection sysv_ezl_loginsections" style="display: none;">
		<br>
		<label for="rememberme">Remember User?</label>
		<input type="checkbox" name="rememberme" id="rememberme" style="display:none;">
	</span>

	<span class="sysv_ezl_logregsection sysv_ezl_loginsections" style="display: none; float:right;">
		<a href="<?php echo wp_lostpassword_url( get_permalink() ); ?>" title="Lost Password">Lost Password</a>
	</span>
</form>