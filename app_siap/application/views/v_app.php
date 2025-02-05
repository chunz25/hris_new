<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title><?php echo $config['app_name']; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel="shortcut icon" href="<?php echo config_item('url_image'); ?>old_logo.png" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo config_item('url_logo'); ?>" />
	<link rel="stylesheet" href="<?php echo $config['url_ext']; ?>css/ext-all.css" />
	<link rel="stylesheet" href="<?php echo $config['url_css']; ?>siap/style.css" />
	<link rel="stylesheet" href="<?php echo $config['url_css']; ?>siap/icons.css" />
	<link rel="stylesheet" type="text/css"
		href="<?php echo $config['url_css']; ?>font-awesome/css/font-awesome.min.css">
	<link href="<?= config_item('url_template'); ?>siap/app.css" rel="stylesheet">

	<script type="text/javascript" src="<?php echo $config['url_ext']; ?>ext-all.js"></script>
	<script type="text/javascript" src="<?php echo $config['url_ext']; ?>helper.js"></script>

	<script type='text/javascript' src="<?php echo $config['view_siap']; ?>siap.js"></script>
	<script type='text/javascript' src="<?php echo $config['view_siap']; ?>packages/packages.js"></script>

	<script type='text/javascript' src="<?php echo $config['url_js']; ?>jquery-1.9.1.js"></script>
	<script type="text/javascript" src="<?php echo $config['url_js']; ?>moment/min/moment.min.js"></script>

	<script type="text/javascript">
		Settings = Ext.decode('<?php echo json_encode($config); ?>');
		var hak = '';
		var first_menu = 'pegawai';

		function logoutapp() {
			window.location = "<?php echo config_item('url_logout'); ?>/logout";
		}

		function myFunction() {
			document.getElementById("myDropdown").classList.toggle("show");
		}

		window.onclick = function (event) {
			if (!event.target.matches('.dropbtn')) {
				var dropdowns = document.getElementsByClassName("dropdown-content");
				var i;
				for (i = 0; i < dropdowns.length; i++) {
					var openDropdown = dropdowns[i];
					if (openDropdown.classList.contains('show')) {
						openDropdown.classList.remove('show');
					}
				}
			}
		}

		$(function () {
			$.fn.loadShortNotif = function () {
				$.getJSON(
					Settings.SITE_URL + '/notifikasi/getShortNotificationCuti',
					'',
					function (response) {
						var html = '';
						$.each(response.data, function (index, record) {
							html += '<ul class="msg-list">';
							html += '<li>';
							html += '<a onclick=$(this).cuti();>';
							html += '<span>' + record.nama + '</span>' + '<br>';
							html += '<span>' + record.jenisnotif + '</span>' + '<br>';
							html += '<span>' + record.tglnotif + '</span>' + '<br>';
							html += '</a>';
							html += '</li>';
							html += '</ul>';
						});
						html += '<ul class="msg-list">';
						html += '<li>';
						html += '<div class="text-center">';
						html += '<a onclick=$(this).cuti();><strong>Lihat Semua</strong><i class="fa fa-angle-right"></i></a>';
						html += '</div>';
						html += '<li>';
						html += '</ul>';
						$(".mypanel").html(html);
						if (response.count > 0) {
							$('#id_count_readnotif').text(response.count);
						} else {
							$('#id_count_readnotif').hide();
							$('#id_count_readnotif').text(response.count);
						}
					}
				);
			};
			$.fn.loadShortNotif();
		});

		$.fn.cuti = function () {
			window.location = Settings.SITE_URL + '#cuti';
		};

		$(document).ready(function () {
			$(document).on('click', '#link', function (e) {
				e.preventDefault();
				$.ajax({
					type: 'POST',
					url: Settings.SITE_URL + '/notifikasi/updateReadNotif',
					data: '',
					cache: false,
					dataType: 'json',
					success: function (response) {
						if (response.success) {
							$.fn.loadShortNotif();
						}
					}
				});

			});
		});

		packages();
		Ext.onReady(function () {
			Ext.QuickTips.init();

			var halpegawai = '<li><a href="#pegawai">Pegawai</a></li>';
			var halcuti = '<li><a href="#cuti">Cuti</a></li>';
			var halreportcuti = '<li><a href="#reportcuti">Report Cuti</a></li>';
			var halkehadiran = '<li><a href="#kehadiran">Kehadiran</a></li>';
			var halreport = '<li><a href="#report">Report</a></li>';
			var halmaster = '<li><a href="#master&MasterUnit">Master</a></li>';
			var halatt = '<li><a href="#attendance">Attendance</a></li>';

			var halunit = '<li><a href="#master&MasterUnit">Unit</a></li>';
			var haljabatan = '<li><a href="#master&MasterJabatan">Jabatan</a></li>';
			var hallevel = '<li><a href="#master&MasterLevel">Level</a></li>';
			var halharilibur = '<li><a href="#master&MasterHariLibur">Hari Libur</a></li>';
			var hallokasi = '<li><a href="#master&MasterLokasi">Lokasi</a></li>';

			Ext.create('Ext.container.Viewport', {
				layout: 'border',
				padding: '0 0 0 0',
				renderTo: Ext.getBody(),
				items: [{
					region: 'north',
					layout: 'anchor',
					border: false,
					bodyPadding: 0,
					items: [{
						xtype: 'panel',
						border: 0,
						layout: 'fit',
						height: 95,
						html: '<div class="header">' +
							'<div class="div_center">' +
							'<div class="logoleft"><a href="' + Settings.BASE_URL + '"><img src="' + Settings.BASE_URL + 'setting/eci/old_logo.png' + '"></a></div>' +
							'<div class="left">' +
							'<ul id="menu" class="menudropdown">' +
							halpegawai +
							halcuti +
							halreportcuti +
							halkehadiran +
							halreport +
							halmaster +
							halatt +
							'</ul>' +
							'</div>' +
							'<div class="right">' +
							'<ul class="eastmenu">' +
							'<li>' +
							'<div class="notification">' +
							'<div id="link" class="dropdown"><i onclick="myFunction()" class="fa fa-envelope-o dropbtn"><div id="myDropdown" class="dropdown-content"><div class="mypanel"></div></div></i><span id="id_count_readnotif" class="badge bg-green">0</span></div>' +
							'</div>' +
							'</li>' +
							'<li>' +
							'<div class="listuser">' +
							Settings.usergroup + '<br>' +
							Settings.nama + ' | <span class="cllogout" onclick="logoutapp()" style="cursor:pointer;">logout</span>' +
							'</div>' +
							'</li>' +
							'</ul>' +
							'</div>' +
							'</div>' +
							'<div class="div_bottom">' +
							'<ul id="id_submenu" class="submenu">' +
							halunit +
							haljabatan +
							hallevel +
							halharilibur +
							hallokasi +
							'</ul>' +
							'</div>' +
							'</div>',
					}]
				},
				{
					id: 'center',
					layout: 'fit',
					region: 'center',
					bodyPadding: 0,
					padding: 0,
					border: false,
					loader: Ext.create('Ext.Component', {
						loader: {},
						border: false,
						renderTo: Ext.getBody()
					})
				},
				{
					region: 'south',
					bodyPadding: 3,
					border: false,
					minHeight: 30,
					style: 'background-color:#ededed;text-align: right;',
					html: Settings.footer
				}
				],
				listeners: {
					afterrender: function () {
						dispatch = function (token) {
							var tokens = token.split('&');
							var m = tokens[0];
							var act = tokens[1];
							var params = tokens[2];

							if (Ext.isEmpty(m)) {
								m = first_menu;
							}

							var type = '';
							if (!Ext.isEmpty(act)) {
								type = act.toLowerCase();
								var require = 'SIAP.modules.' + m + '.' + act;
							} else {
								type = m;
								var require = 'SIAP.modules.' + type + '.App';
							}

							Ext.require(require, function () {
								Ext.getCmp('center').removeAll();
								Ext.getCmp('center').add({
									xtype: type,
									layout: 'fit',
									menu: m,
									params: params,
								});
								Ext.getCmp('center').doLayout();
							});
							Ext.getCmp('center').doLayout();
						}

						Ext.History.init(function () {
							var hashTag = document.location.hash;
							var tag = hashTag.replace("#", "");
							dispatch(tag);
						});
						Ext.History.on('change', dispatch);
					}
				}
			});
		});
	</script>

</head>

<body>
</body>

</html>