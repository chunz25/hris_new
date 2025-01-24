<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="<?= config_item('url_image'); ?>old_logo.png" />
	<title>Login | <?= config_item('instansi_long_name'); ?></title>
	<link href="<?= config_item('url_template'); ?>gentelella/vendors/bootstrap/dist/css/bootstrap.min.css"
		rel="stylesheet">
	<link href="<?= config_item('url_template'); ?>gentelella/vendors/font-awesome/css/font-awesome.min.css"
		rel="stylesheet">
	<link href="<?= config_item('url_template'); ?>gentelella/build/css/custom.min.css" rel="stylesheet">
	<link href="<?= config_item('url_template'); ?>gentelella/vendors/nprogress/nprogress.css" rel="stylesheet">
	<link
		href="<?= config_item('url_template'); ?>gentelella/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css"
		rel="stylesheet">
	<link href="<?= config_item('url_template'); ?>gentelella/vendors/animate.css/animate.min.css" rel="stylesheet">
	<link href="<?= config_item('url_template'); ?>login/login.css" rel="stylesheet">
	<script src="<?= config_item('url_template'); ?>gentelella/vendors/jquery/dist/jquery.min.js"></script>
	<script src="<?= config_item('url_template'); ?>gentelella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
</head>

<body class="login">
	<div class="north-layout">
		<div class="col-md-2 col-md-push-10 col-sm-6 col-xs-12 left-panel">
			<img src="<?= config_item('url_image'); ?>old_logo.png" width="115">
		</div>
		<div class="col-md-10 col-md-pull-2 col-sm-6 col-xs-12 right-panel">
			<span style="font-size:30px;color:#0E7BBE;">
				<?= config_item('app_long_name'); ?>
			</span>
		</div>
	</div>
	<div class="center-layout">
		<div class="col-md-8 col-sm-6 hidden-xs left-panel">
			<div class="panel-logo">
				<img src="<?= config_item('url_image'); ?>electronic_city.png" width="35%" height="35%">
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12 right-panel">
			<form id="form_login" data-parsley-validate class="form-login">
				<label for="fullname">Username :</label>
				<input type="text" id="id_username" class="form-control" name="username" autocomplete="off" />
				<br>
				<label for="email">Password :</label>
				<input type="password" id="id_password" name="password" class="form-control" autocomplete="off">
				<span id="id_pesan" class="pesan"></span>
				<br>
				<button type="button" class="btn btn-primary" onclick="do_login();">Login</button>
			</form>
		</div>
	</div>
	<div class="south-layout">
		<p><span class="span-footer"><?= config_item('footer'); ?></span></p>
	</div>
</body>

<script type="text/javascript">
	$(document).ready(function () {
		$('#id_username, #id_password').on('keyup', function (event) {
			if (event.keyCode === 13) {
				if (this.id === 'id_username') {
					$('#id_password').focus();
				} else {
					do_login();
				}
			}
		});
	});

	function do_login() {
		$('#id_pesan')
			.removeClass()
			.addClass('pesan-wait')
			.html("Sedang Verifikasi Username dan Password ...");

		$.post('<?= site_url("login/checkLogin"); ?>', $("#form_login").serialize())
			.done(function (data) {
				var obj = $.parseJSON(data);

				if (obj.success) {
					$('#id_pesan')
						.addClass('pesan-success')
						.html("Login Sukses, Loading Aplikasi...");
					document.location.href = "<?= config_item('url_portal'); ?>";
				} else if (obj.payload === "masalah") {
					alert('Mohon hubungi HRD, Approval anda kosong!');
					window.location.reload();
				} else {
					$('#id_pesan')
						.addClass('pesan-failure')
						.html("Username dan Password Tidak Sesuai.");
				}
			})
			.fail(function (jqXHR, textStatus, errorThrown) {
				// Handle cases where the server could not be reached or a client-side error occurred
				console.error('Login Error:', textStatus, errorThrown); // Log error in console for debugging

				// Provide user feedback
				$('#id_pesan')
					.removeClass('pesan-wait')
					.addClass('pesan-failure')
					.html("Maaf, ada kesalahan dalam pengiriman data. Silakan coba lagi.");
			})
			.always(function () {
				// You can add any cleanup or reset actions here if needed
			});
	}

</script>

</html>