?>
<script>

jQuery(function(){
	var timeouttimer;
	jQuery('#username').keyup(function(evt){
		if (evt.which!=13 && evt.keyCode!=13)
		jQuery('#sysv_ezl_picker').val('wait');
		jQuery('.sysv_ezl_loginsections').css('display','none');
		jQuery('#rememberme').css('display','none');
		jQuery('.sysv_ezl_regsections').css('display','none');
		jQuery('#sysv_ezl_passsection').css('opacity','0')
		jQuery('#sysv_ezl_passsection').css('position','absolute')
		jQuery('#sysv_ezl_subbutton').css('display','none')
		jQuery('#sysv_ezl_retlog').html('<img src="<?php echo plugin_dir_url(__FILE__) . 'pacloader.gif' ?>">');
		clearTimeout(timeouttimer);
		timeouttimer=setTimeout(function(){
			sysv_ezl_checkvalsin();
		},2000);
	});
});

function ezl_submitfunction(evt){
	switch(jQuery('#sysv_ezl_picker').val()){
		case "reg":
			sysv_ezl_register();
			break;
		case "log":
			sysv_ezl_login();
			break;
	}
	return false;
};


function sysv_ezl_register(){
	var data = {
		'action' : 'sysv_login_function',
		'submittype': 'reg',
		'user_name' : jQuery('#username').val()
	};
	jQuery.ajax({
		type:"POST",
		url: "<?php echo admin_url('admin-ajax.php'); ?>",
		data: data,
		success:function(valret){
			sysv_retlog_entry = '<?php echo $sysv_ezl_global_use['text']['reg_success']; ?>';
			jQuery('#sysv_ezl_retlog').html(sysv_retlog_entry);
			jQuery(location).attr('href', '<?php echo $sysv_ezl_global_use['text']['reg_redir']; ?>');
		},
		error:function(valret){
			sysv_retlog_entry = valret;
			jQuery('#sysv_ezl_retlog').html(sysv_retlog_entry);
			jQuery(location).attr('href', '#');
		}
	});
}

function sysv_ezl_login(){
	var data = {
		'action' : 'sysv_login_function',
		'submittype': 'log',
		'user_name' : jQuery('#username').val(),
		'user_password' : jQuery('#password').val(),
		'rememberme' : jQuery('#rememberme').is(':checked')
	};
	jQuery.ajax({
		type:"POST",
		url: "<?php echo admin_url('admin-ajax.php'); ?>",
		data: data,
		success:function(valret){
		jQuery('#sysv_ezl_retlog').html('<span>' + valret + '</span>');
		jQuery(location).attr('href', '<?php echo $sysv_ezl_global_use['text']['after_login']; ?>');
		},
		error: function(errorThrown){
		sysv_retlog_entry = 'Wrong Password';
		jQuery('#sysv_ezl_retlog').html('<span>' + sysv_retlog_entry + '</span>');
		return false;
		}
	});
};

function sysv_ezl_checkvalsin(){
	var sysv_retlog_entry;
	var data = {
		'action' : 'sysv_login_function',
		'submittype': 'chk',
		'user_name' : jQuery('#username').val()
	};
	jQuery.ajax({
		type:"POST",
		url: "<?php echo admin_url('admin-ajax.php'); ?>",
		data: data,
		success:function(valret){
			if (valret==1){
				jQuery('#sysv_ezl_picker').val('log');
				sysv_retlog_entry = '<?php echo $sysv_ezl_global_use['text']['isuser']; ?>';
				jQuery('#sysv_ezl_retlog').html(sysv_retlog_entry);
				jQuery('.sysv_ezl_regsections').css('display','none');
				jQuery('.sysv_ezl_loginsections').css('display','inline');
				jQuery('#rememberme').css('display','inline');
				jQuery('#sysv_ezl_passsection').css('opacity',1);
				jQuery('#sysv_ezl_passsection').css('position','');
				jQuery('#sysv_ezl_passsection').css('display','inline');
				jQuery('#sysv_ezl_submittype').val('log');
				jQuery('#sysv_ezl_subbutton').val('<?php echo $sysv_ezl_global_use['text']['log_text']; ?>');
				jQuery('#sysv_ezl_subbutton').css('display', 'inline');
			}
			else{
				jQuery('#sysv_ezl_subbutton').val('<?php echo $sysv_ezl_global_use['text']['reg_text']; ?>');
				jQuery('#sysv_ezl_subbutton').css('display', 'inline');
				jQuery('#sysv_ezl_picker').val('reg');
				sysv_retlog_entry = '<?php echo $sysv_ezl_global_use['text']['notuser']; ?>';
				jQuery('#sysv_ezl_retlog').html(sysv_retlog_entry);
				jQuery('.sysv_ezl_loginsections').css('display','none');
				jQuery('#rememberme').css('display','none');
				jQuery('.sysv_ezl_regsections').css('display', 'inline');
				jQuery('#sysv_ezl_submittype').val('reg');
				jQuery('#sysv_ezl_passsection').css('display','none');
			}
		},
		error: function(errorThrown){
				sysv_retlog_entry = errorThrown;
				jQuery('#sysv_ezl_retlog').html(sysv_retlog_entry);
				return false;
		} 
	});
}
</script>
<?php