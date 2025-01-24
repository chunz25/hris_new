<div class="page-title">
	<div class="title_left">
		<h3>History Datang Telat / Pulang Cepat</h3>
	</div>
</div>
<div class="clearfix"></div>
<div class="row center-cont">
	<div class="row" style="padding-left:5px;padding-right:5px;">
		<div class="pull-left">
			<div class="div_filter">
				<?php if ($this->session->userdata('akses_absensi') != 'pegawai'): ?>
					<div class="column1">
						<input id="id_keyword" type="text" class="form-control" style="width:200px;"
							placeholder="NIK atau Nama">
					</div>
					<div class="column1">
						<button id="id_search" type="button" class="btn btn-success" aria-label="Left Align"
							style="margin-top:0px;margin-bottom:0px;"><i class="fa fa-search"></i></button>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="pull-right">
		</div>
	</div>
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
								<th class="column-title">Divisi </th>
								<th class="column-title">Jabatan </th>
								<th class="column-title"></th>
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
	$(function () {
		let pegawaiid = '<?= $vpegawaiid; ?>';

		$.fn.load_grid_content = function (pegawaiid, keyword, start, limit) {
			$.getJSON(
				SITE_URL + '/history/getListPegawai', 'pegawaiid=' + pegawaiid + '&keyword=' + keyword + '&start=' + start + '&limit=' + limit,
				function (response) {
					var html = '';
					$.each(response.data, function (index, record) {
						html += '<tr class="even pointer">';
						html += '<td class=" ">' + (index + 1) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.nik) ? '' : record.nik) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.nama) ? '' : record.nama) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.divisi) ? '' : record.divisi) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.jabatan) ? '' : record.jabatan) + '</td>';
						html += '<td class=" ">';
						html += '<a onclick=$(this).detailhistory("' + record.pegawaiid + '") class="btn btn-default btn-xs" aria-label="Left Align" data-toggle="tooltip" data-placement="bottom" title="Lihat Detail"><i class="glyphicon glyphicon-search" aria-hidden="true"></i></a>';
						html += '</td>';
						html += '</tr>';
					});

					$('#gridcontent > tbody').html(html);
					$("#gridcontent tbody tr").click(function () {
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
							onPageClick: function (event, page) {
								var start = (page - 1) * limit;
								$.fn.load_grid_content(pegawaiid, keyword, start, limit);
							}
						});
					}
				}
			)
		};

		$.fn.showAlasan = function (pegawaiid, nourut) {
			$('#div_alasan' + pegawaiid + nourut).collapse('toggle');
		}

		$.fn.detailhistory = function (pegawaiid) {
			window.location = SITE_URL + '/history/detaillistkehadiran?pegawaiid=' + Base64.encode(pegawaiid);
		};

		var keyword = $('#id_keyword').val();
		var start = 0;
		var limit = 25;

		$.fn.load_grid_content(pegawaiid, keyword, start, limit);

		$('#id_search').on('click', function (e) {
			e.preventDefault();
			e.stopPropagation();

			var keyword = $('#id_keyword').val();

			$.fn.load_grid_content(pegawaiid, keyword, start, limit);
		});
	});
</script>