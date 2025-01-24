<div class="page-title">
	<div class="title_left">
		<h3>Notifikasi Kehadiran</h3>
	</div>
</div>
<div class="clearfix"></div>
<div class="row center-cont">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
					<table id="gridcontent" class="table table-striped jambo_table bulk_action">
						<thead>
							<tr class="headings">
								<th class="column-title">No </th>
								<th class="column-title">NIK </th>
								<th class="column-title">Nama </th>
								<th class="column-title">Jenis Notifikasi </th>
								<th class="column-title">Tgl Pengajuan </th>
							</tr>
						</thead>

						<tbody>
						</tbody>
					</table>
					<div class="pull-right">
						<ul id="gridpaging" class="pagination-sm"></ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		$.fn.load_grid_content = function(start, limit) {
			var link = '';
			if (<?= $this->session->userdata('nik'); ?> == '1003049' ||
				<?= $this->session->userdata('nik'); ?> == '16030068'
			) {
				link = '/notifikasi/getAllNotificationHR';
			} else {
				link = '/notifikasi/getAllNotification';
			}
			$.getJSON(
				SITE_URL + link,
				'start=' + start,
				function(response) {
					var html = '';
					$.each(response.data, function(index, record) {
						html += '<tr class="even pointer">';
						html += '<td class=" ">' + (index + 1) + '</td>';
						html += '<td class=" ">' + record.nik + '</td>';
						html += '<td class=" ">' + record.nama + '</td>';
						html += '<td class=" ">' + record.jenisnotif + '</td>';
						html += '<td class=" ">' + record.tglnotif + '</td>';
						html += '</tr>';
					});

					$('#gridcontent > tbody').html(html);
					$("#gridcontent tbody tr").click(function() {
						var selected = $(this).hasClass("activated");
						$("#gridcontent tr").removeClass("activated");
						if (!selected) $(this).addClass("activated");
					});
					if (response.count > 0) {
						var visiblePages = Math.ceil(response.count / limit);
						$('#gridpaging').twbsPagination({
							initiateStartPageClick: false,
							totalPages: visiblePages,
							visiblePages: 10,
							startPage: 1,
							onPageClick: function(event, page) {
								var start = (page - 1) * limit;
								$.fn.load_grid_content(start, limit);
							}
						});
					}
				}
			)
		};
		var start = 0;
		var limit = 25;

		$.fn.load_grid_content(start, limit);
	});
</script>