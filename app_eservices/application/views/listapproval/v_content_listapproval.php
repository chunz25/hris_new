<div class="page-title">
	<div class="title_left">
		<h3>Approval Cuti</h3>
	</div>
</div>
<div class="clearfix"></div>
<div class="row center-cont">
	<div class="row" style="padding-left:5px;padding-right:5px;">
		<div class="pull-left">
			<div class="div_filter">
				<div class="column1">
					<div id="id_tglawal" class="input-group date" style="width:180px;margin-bottom:0px;">
						<input type="text" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
					</div>
				</div>
				<div class="column1">
					<div id="id_tglakhir" class="input-group date" style="width:180px;margin-bottom:0px;">
						<input type="text" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
					</div>
				</div>
				<div class="column1">
					<button id="id_search" type="button" class="btn btn-success" aria-label="Left Align" style="margin-top:0px;margin-bottom:0px;"><i class="fa fa-search"></i></button>
				</div>
				<?php if ($this->session->userdata('nik') == '13121215') { ?>
					<div class="column1">
						<button id="id_approve" type="button" class="btn btn-success" aria-label="Left Align" style="margin-top:0px;margin-bottom:0px;" onclick=$(this).bulkapprove();><i class="fa fa-check"></i> Approve</button>
					</div>
				<?php } ?>
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
								<?php if ($this->session->userdata('nik') == '13121215') { ?>
									<th class="column-title"><input type="checkbox" id="checkAll" name="selectcheckall"></th>
								<?php } ?>
								<th class="column-title">No </th>
								<th class="column-title">NIK </th>
								<th class="column-title">Nama </th>
								<th class="column-title">Jabatan </th>
								<th class="column-title">Tanggal Pengajuan </th>
								<th class="column-title">Jenis Cuti </th>
								<th class="column-title">Tgl Mulai </th>
								<th class="column-title">Tgl Selesai </th>
								<th class="column-title">Status </th>
								<th class="column-title">Pelimpahan </th>
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

<div id="id_winconfirm" class="modal fade hapus-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title">Informasi</h4>
			</div>
			<div class="modal-body">
				Apakah anda akan menyetujui cuti ini ?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
				<button id="id_confirm_yes" type="button" class="btn btn-primary">Ya</button>
			</div>
		</div>
	</div>
</div>

<div id="id_winconfirmreject" class="modal fade hapus-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title">Informasi</h4>
			</div>
			<div class="modal-body">
				<form id="id_form_content_reject" class="form-horizontal form-label-left" action="./" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="pegawaiid" />
					<input type="hidden" name="nourut" />
					<input type="hidden" name="useraction" />
					<div class="row">
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Alasan Ditolak</label>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<textarea id="id_alasanditolak" class="form-control" rows="3" name="alasan" placeholder="Alasan Ditolak ..."></textarea>
							</div>
						</div>
					</div>
				</form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
				<button id="id_confirmreject_yes" type="button" class="btn btn-primary">Ya</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		var start = 0;
		var limit = 25;
		var usergroupid = '<?= $this->session->userdata('aksesid_eservices'); ?>';

		$.fn.load_grid_content = function(tglmulai, tglselesai, start, limit) {
			$.getJSON(
				SITE_URL + '/listapprove/getListApprovalCuti',
				'tglmulai=' + tglmulai + '&tglselesai=' + tglselesai + '&start=' + start + '&limit=' + limit,
				function(response) {
					var html = '';
					$.each(response.data, function(index, record) {
						var jenis = record.jeniscuti;
						var jeniscuti = jenis.replace(/{/g, "").replace(/}/g, "").replace(/"/g, "").replace(/,/g, ", ");

						var mulai = record.tglmulai;
						var tglmulai = mulai.replace(/{/g, "").replace(/}/g, "").replace(/"/g, "").replace(/,/g, ", ").replace(/-/g, "/");

						var selesai = record.tglselesai;
						var tglselesai = selesai.replace(/{/g, "").replace(/}/g, "").replace(/"/g, "").replace(/,/g, ", ").replace(/-/g, "/");

						html += '<tr class="even pointer">';
						<?php if ($this->session->userdata('nik') == '13121215') { ?>
							html += '<td class=" "><input type="checkbox" name="selectcheck[]" value = "' + record.pengajuanid + '" ></td>';
						<?php } ?>
						html += '<td class=" ">' + (index + 1) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.nik) ? '' : record.nik) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.nama) ? '' : record.nama) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.jabatan) ? '' : record.jabatan) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.tglpermohonan) ? '' : record.tglpermohonan) + '</td>';
						html += '<td class=" ">' + (isEmpty(jeniscuti) ? '' : jeniscuti) + '</td>';
						html += '<td class=" ">' + (isEmpty(tglmulai) ? '' : tglmulai) + '</td>';
						html += '<td class=" ">' + (isEmpty(tglselesai) ? '' : tglselesai) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.status) ? '' : record.status) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.pelimpahannama) ? '' : record.pelimpahannama) + '</td>';
						html += '<td class=" ">';
						html += '<a onclick=$(this).detailapprove("' + record.pegawaiid + '","' + record.nourut + '","' + record.periode + '","' + record.pengajuanid + '") class="btn btn-default btn-xs" aria-label="Left Align" data-toggle="tooltip" data-placement="bottom" title="Lihat Detail"><i class="glyphicon glyphicon-search" aria-hidden="true"></i></a>';
						html += '</td>';
						html += '</tr>';
					});

					$('#gridcontent > tbody').html(html);

					if (response.count > 0) {
						var visiblePages = Math.ceil(response.count / limit);
						$('#gridpaging').twbsPagination({
							initiateStartPageClick: false,
							totalPages: visiblePages,
							visiblePages: 10,
							startPage: 1,
							onPageClick: function(event, page) {
								var start = (page - 1) * limit;
								$.fn.load_grid_content(tglmulai, tglselesai, start, limit);
							}
						});
					}
				}
			);
		};

		$("#checkAll").click(function() {
			$('input:checkbox').not(this).prop('checked', this.checked);
		});

		$.fn.bulkapprove = function(vals) {
			var checkboxes = document.getElementsByName('selectcheck[]');
			var vals = "";
			for (var i = 0, n = checkboxes.length; i < n; i++) {
				if (checkboxes[i].checked) {
					vals += "," + checkboxes[i].value;
				}
			}
			if (vals == '') {
				alert('Pilih Minimal 1 Row!')
			} else {
				vals = vals.substring(1);
				window.location = SITE_URL + '/listapprove/approveCutibulk?vals=' + vals;
			};
		};

		var date = new Date(),
			y = date.getFullYear(),
			m = date.getMonth();
		var firstDay = new Date(y, m, 1);
		var lastDay = new Date(y, m + 1, 0);
		firstDay2 = moment(firstDay).format('YYYY-MM-DD');
		lastDay2 = moment(lastDay).format('YYYY-MM-DD');

		$.fn.detailapprove = function(pegawaiid, nourut, periode, pengajuanid) {
			window.location = SITE_URL + '/listapprove/detail?pegawaiid=' + Base64.encode(pegawaiid) + '&nourut=' + Base64.encode(nourut) + '&periode=' + Base64.encode(periode) + '&pengajuanid=' + Base64.encode(pengajuanid);
		};

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

		var tglmulai = moment($('#id_tglawal').datepicker('getDate')).format('DD/MM/YYYY');
		var tglselesai = moment($('#id_tglakhir').datepicker('getDate')).format('DD/MM/YYYY');

		$.fn.load_grid_content(tglmulai, tglselesai, start, limit);

		$('#id_search').on('click', function(e) {
			e.preventDefault();
			e.stopPropagation();

			var tglmulai = moment($('#id_tglawal').datepicker('getDate')).format('DD/MM/YYYY');
			var tglselesai = moment($('#id_tglakhir').datepicker('getDate')).format('DD/MM/YYYY');
			$.fn.load_grid_content(tglmulai, tglselesai, start, limit);
		});
	});
</script>