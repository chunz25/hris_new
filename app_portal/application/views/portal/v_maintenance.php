<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="shortcut icon" href="<?php echo config_item('url_image'); ?>old_logo.png" />
	<title>Maintenance | <?php echo config_item('instansi_long_name'); ?></title>

	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/build/css/custom.min.css" rel="stylesheet">

	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/nprogress/nprogress.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/animate.css/animate.min.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>portal/maintenance.css" rel="stylesheet">

	<script src="<?php echo config_item('url_template'); ?>gentelella/vendors/jquery/dist/jquery.min.js"></script>
	<script src="<?php echo config_item('url_template'); ?>gentelella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>

</head>

<body class="login">




	<img src="<?php echo config_item('url_image'); ?>image1.png" class="center">
	<marquee behavior="scroll" direction="left" scrollamount="13">
		<h3 style="text-align: center" class="center">Website Under Maintenance, More Info Please Contact HR </h3>
	</marquee>




</body>
<script type="text/javascript">
	$(document).ready(function() {
		$('#id_username').bind('keyup', function(event) {
			if (event.keyCode == 13) {
				document.getElementById('id_password').focus();
			}
		});
		$('#id_password').bind('keyup', function(event) {
			if (event.keyCode == 13) {
				do_login();
			}
		});
	});

	function do_login() {
		$('#id_pesan').removeClass();
		$('#id_pesan').addClass('pesan-wait');
		$('#id_pesan').html("Sedang Verifikasi Username dan Password ...");


		$.post('<?php echo site_url("login/check_login"); ?>', $("#form_login").serialize(), function(data, status) {
			if (status == 'success') {
				var obj = jQuery.parseJSON(data);
				if (obj.success == true) {
					$('#id_pesan').addClass('pesan-success');
					$('#id_pesan').html("Login Sukses, Loading Aplikasi...");
					document.location.href = "<?php echo config_item('url_portal'); ?>";
				} else {
					$('#id_pesan').addClass('pesan-failure');
					$('#id_pesan').html("Username dan Password Tidak Sesuai.");
				}
			} else {
				$('#id_pesan').addClass('pesan-failure');
				$('#id_pesan').html("Maaf, ada kesalahan dalam pengiriman data.");
			}
		});
	}
</script>

</html>