<div class="nav_menu">
	<nav>
		<div class="nav toggle">
			<a id="menu_toggle"><i class="fa fa-bars"></i></a>
		</div>
		<ul class="nav navbar-nav navbar-right">
			<li class="">
				<a href="javascript:;" class="user-profile">
					<?php echo $this->session->userdata('nik'); ?> | <?php echo $this->session->userdata('nama'); ?>
				</a>
			</li>

			<li role="presentation" class="dropdown">
				<a id="id_btnnotif" href="javascript;" class="dropdown-toggle info-number" data-toggle="dropdown"
					aria-expanded="false">
					<i class="fa fa-envelope-o"></i>
					<span id="id_count_readnotif" class="badge bg-green">0</span>
				</a>
				<ul id="id_panelnotifikasi" class="dropdown-menu list-unstyled msg_list" role="menu"></ul>
			</li>
		</ul>
	</nav>
</div>
<script type="text/javascript">
	$(function () {
		$.fn.loadShortNotif = function () {
			var link = '';
			if (<?php echo $this->session->userdata('nik'); ?> == '1003049' ||
				<?php echo $this->session->userdata('nik'); ?> == '16030068'
			) {
				link = '/notifikasi/getShortNotificationHR';
			} else {
				link = '/notifikasi/getShortNotification';
			}
			$.getJSON(
				SITE_URL + link,
				'',
				function (response) {
					var html = '';
					$.each(response.data, function (index, record) {
						html += '<li>';
						if (record.jenisnotif == 'Pengajuan Cuti' || record.jenisnotif == 'Pengajuan Batal Cuti') {
							html += '<a onclick=$(this).detailApp();>';
						}
						else {
							html += '<a onclick=$(this).detailHist();>';
						}
						html += '<span>' + record.nama + '</span>';
						html += '<span class="message">' + record.jenisnotif + '</span>';
						html += '<span class="time_notif">' + record.tglnotif + '</span>';
						html += '</a>';
						html += '</li>';
					});

					html += '<li>';
					html += '<div class="text-center">';
					html += '<a onclick=$(this).lihatsemua();><strong>Lihat Semua</strong><i class="fa fa-angle-right"></i></a>';
					html += '</div>';
					html += '</li>';

					$('#id_panelnotifikasi').html(html);

					if (response.count > 0) {
						$('#id_count_readnotif').text(response.count);
					}
					else {
						$('#id_count_readnotif').hide();
						$('#id_count_readnotif').text(response.count);
					}
				}
			);
		};
		$.fn.loadShortNotif();
		$.fn.detailApp = function () {
			window.location = SITE_URL + '/listapprove/';
		};
		$.fn.detailHist = function () {
			window.location = SITE_URL + '/history/';
		};
		$.fn.lihatsemua = function () {
			window.location = SITE_URL + '/notifikasi/';
		};
		$('#id_btnnotif').on('click', function (e) {
			e.preventDefault();
			$.ajax({
				type: 'POST',
				url: SITE_URL + '/notifikasi/updateReadNotif',
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
</script>