<div class="page-title">
	<div class="title_left">
		<h3>History Cuti</h3>
	</div>
</div>
<div class="clearfix"></div>
<div class="row center-cont">
	<div class="row" style="padding-left:5px;padding-right:5px;">
		<div class="pull-left">
			<div class="div_filter">
				<?php if ($this->session->userdata('aksesid_eservices') != '11') : ?>
					<div class="column1">
						<select id="id_status" class="form-control" name="jeniscuti">
							<option value="">Pilih Status</option>
							<?php foreach ($vstatuscuti as $r) : ?>
								<option value="<?= $r['id']; ?>"><?= $r['name']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>
				<div class="column1">
					<div id="id_tglawal" class="input-group date" style="width:140px;margin-bottom:0px;">
						<input type="text" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
					</div>
				</div>
				<div class="column1">
					<div id="id_tglakhir" class="input-group date" style="width:140px;margin-bottom:0px;">
						<input type="text" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
					</div>
				</div>
				<div class="column1">
					<button id="id_search" type="button" class="btn btn-success" aria-label="Left Align" style="margin-top:0px;margin-bottom:0px;"><i class="fa fa-search"></i></button>
				</div>
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
								<th class="column-title">Jabatan </th>
								<th class="column-title">Tgl Pengajuan </th>
								<th class="column-title">Jenis Cuti </th>
								<th class="column-title">Tgl Mulai </th>
								<th class="column-title">Tgl Selesai </th>
								<th class="column-title">Lama </th>
								<th class="column-title">Status </th>
								<th class="column-title">Nama Pelimpahan </th>
								<th class="column-title">Nama Verifikator </th>
								<th class="column-title">Nama Approval </th>
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
	$(function() {
		var date = new Date(),
			y = date.getFullYear(),
			m = date.getMonth();
		var firstDay = new Date(y, 0, 1);
		var lastDay = new Date(y, 12, 0);
		firstDay2 = moment(firstDay).format('YYYY-MM-DD');
		lastDay2 = moment(lastDay).format('YYYY-MM-DD');
		var pegawaiid = '<?= $vpegawaiid; ?>';

		$('#id_tglawal').datepicker({
			format: 'dd/mm/yyyy',
			todayHighlight: true,
			autoclose: true,
			toggleActive: true,
			beforeShowDay: function(date) {
				var curr_date = moment(date).format("YYYY-MM-DD");
			}
		});
		$('#id_tglawal').datepicker("setDate", firstDay);

		$('#id_tglakhir').datepicker({
			format: 'dd/mm/yyyy',
			todayHighlight: true,
			autoclose: true,
			toggleActive: true,
			beforeShowDay: function(date) {
				var curr_date = moment(date).format("YYYY-MM-DD");
			}
		});
		$('#id_tglakhir').datepicker("setDate", lastDay);

		$.fn.load_grid_content = function(pegawaiid, tglmulai, tglselesai, jeniscutiid, statusid, keyword, nstatus, start, limit) {
			$.getJSON(
				SITE_URL + '/history/getListHistoryCuti',
				'pegawaiid=' + pegawaiid + '&tglmulai=' + tglmulai + '&tglselesai=' + tglselesai + '&jeniscuti=' + jeniscutiid + '&status=' + statusid + '&nstatus=' + nstatus + '&start=' + start + '&limit=' + limit + '&keyword=' + keyword,
				function(response) {
					var html = '';
					$.each(response.data, function(index, record) {
						var jenis = record.jeniscuti;
						var jeniscuti = jenis.replace(/{/g, "").replace(/}/g, "").replace(/"/g, "").replace(/,/g, ", ");

						var mulai = record.tglmulai;
						var tglmulai = mulai.replace(/{/g, "").replace(/}/g, "").replace(/"/g, "").replace(/,/g, ", ").replace(/-/g, "/");

						var selesai = record.tglselesai;
						var tglselesai = selesai.replace(/{/g, "").replace(/}/g, "").replace(/"/g, "").replace(/,/g, ", ").replace(/-/g, "/");

						var lama = record.lama;
						var lama = lama.replace(/{/g, "").replace(/}/g, "").replace(/"/g, "").replace(/,/g, ", ").replace(/-/g, "/");

						html += '<tr class="even pointer">';
						html += '<td class=" ">' + (index + 1) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.nik) ? '' : record.nik) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.nama) ? '' : record.nama) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.jabatan) ? '' : record.jabatan) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.tglpermohonan) ? '' : record.tglpermohonan) + '</td>';
						html += '<td class=" ">' + (isEmpty(jeniscuti) ? '' : jeniscuti) + '</td>';
						html += '<td class=" ">' + (isEmpty(tglmulai) ? '' : tglmulai) + '</td>';
						html += '<td class=" ">' + (isEmpty(tglselesai) ? '' : tglselesai) + '</td>';
						html += '<td class=" ">' + (isEmpty(lama) ? '' : lama) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.status) ? '' : record.status) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.pelimpahannama) ? '' : record.pelimpahannama) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.atasan1nama) ? '' : record.atasan1nama) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.atasan2nama) ? '' : record.atasan2nama) + '</td>';
						html += '<td class=" ">';
						html += '<a onclick=$(this).detailhistory("' + record.pegawaiid + '","' + record.nourut + '","' + record.periode + '","' + record.pengajuanid + '") class="btn btn-default btn-xs" aria-label="Left Align" data-toggle="tooltip" data-placement="bottom" title="Lihat Detail"><i class="glyphicon glyphicon-search" aria-hidden="true"></i></a>';
						html += '</td>';
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
								$.fn.load_grid_content(pegawaiid, tglmulai, tglselesai, jeniscutiid, statusid, keyword, nstatus, start, limit);
							}
						});
					}
				}
			)
		};

		$.fn.detailhistory = function(pegawaiid, nourut, periode, pengajuanid) {
			window.location = SITE_URL + '/history/detail?pegawaiid=' + Base64.encode(pegawaiid) + '&nourut=' + Base64.encode(nourut) + '&periode=' + Base64.encode(periode) + '&pengajuanid=' + Base64.encode(pengajuanid);
		};

		var tglmulai = moment($('#id_tglawal').datepicker('getDate')).format('DD/MM/YYYY');
		var tglselesai = moment($('#id_tglakhir').datepicker('getDate')).format('DD/MM/YYYY');
		var jeniscutiid = null;
		var statusid = null;
		var keyword = $('#id_keyword').val();
		var nstatus = $('#id_status').val();
		var start = 0;
		var limit = 25;

		$.fn.load_grid_content(pegawaiid, tglmulai, tglselesai, jeniscutiid, statusid, keyword, nstatus, start, limit);

		$('#id_search').on('click', function(e) {
			e.preventDefault();
			e.stopPropagation();

			var tglmulai = moment($('#id_tglawal').datepicker('getDate')).format('DD/MM/YYYY');
			var tglselesai = moment($('#id_tglakhir').datepicker('getDate')).format('DD/MM/YYYY');
			var keyword = $('#id_keyword').val();
			var nstatus = $('#id_status').val();
			var jeniscutiid = null;
			var statusid = null;

			$.fn.load_grid_content(pegawaiid, tglmulai, tglselesai, jeniscutiid, statusid, keyword, nstatus, start, limit);
		});

	});
</script>