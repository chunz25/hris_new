<div class="page-title">
	<div class="title_left">
		<h3>Template Form</h3>
	</div>
</div>
<div class="clearfix"></div>
<div class="row center-cont">
	<div class="row" style="padding-left:5px;padding-right:5px;">
		<div class="pull-left">
			<input type="hidden" name="newsatker"
				value="<?php echo substr($this->session->userdata('satkerid'), 0, 6); ?>" />
			<div class="div_filter">
				<div class="column1">
					<input id="id_keyword" type="text" class="form-control" style="width:200px;"
						placeholder="Judul Dokumen">
				</div>
				<div class="column1">
					<button id="id_search" type="button" class="btn btn-success" aria-label="Left Align"
						style="margin-top:0px;margin-bottom:0px;"><i class="fa fa-search"></i></button>
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
								<th class="column-title">Nama Dokumen </th>
								<th class="column-title">Judul Dokumen </th>
								<th class="column-title">Tanggal </th>
								<th class="column-title">Unit Kerja</th>
								<th class="column-title" id="id_direktorat" style="display:none;">Direktorat</th>
								<th class="column-title" id="id_divisi" style="display:none;">Divisi</th>
								<th class="column-title">Lokasi</th>
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
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">×</span></button>
				<h4 class="modal-title">Konfirmasi</h4>
			</div>
			<div class="modal-body">
				Apakah anda yakin untuk menghapus dokumen ini ?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
				<button id="id_confirm_yes" type="button" class="btn btn-primary">Ya</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function () {
		var date = new Date(), y = date.getFullYear(), m = date.getMonth();
		var firstDay = new Date(y, m / m - 1, 1);
		var lastDay = new Date(y, m / m + 11, 0);
		firstDay2 = moment(firstDay).format('YYYY-MM-DD');
		lastDay2 = moment(lastDay).format('YYYY-MM-DD');
		var pegawaiid = '<?php echo $vpegawaiid; ?>';
		var aksesid = '<?php echo $this->session->userdata('aksesid_policies'); ?>';
		var lokasikerja = '<?php echo $infopegawai['lokasiid']; ?>';

		$('#id_tglawal').datepicker({
			format: 'dd/mm/yyyy',
			todayHighlight: true,
			autoclose: true,
			toggleActive: true,
			beforeShowDay: function (date) {
				var curr_date = moment(date).format("YYYY-MM-DD");
			}
		});
		$('#id_tglawal').datepicker("setDate", firstDay);

		$('#id_tglakhir').datepicker({
			format: 'dd/mm/yyyy',
			todayHighlight: true,
			autoclose: true,
			toggleActive: true,
			beforeShowDay: function (date) {
				var curr_date = moment(date).format("YYYY-MM-DD");
			}
		});
		$('#id_tglakhir').datepicker("setDate", lastDay);

		$.fn.load_grid_content = function (keyword, newsatker, lokasikerja, start, limit) {
			$.getJSON(
				SITE_URL + '/template/getListPolicies', 'keyword=' + keyword + '&newsatker=' + newsatker + '&lokasikerja=' + lokasikerja + '&start=' + start + '&limit=' + limit,
				function (response) {
					var html = '';
					$.each(response.data, function (index, record) {
						var jenis = '';
						var direktorat = '';
						var divisi = '';
						if (record.status == '1') { var jenis = 'Semua'; } else { var jenis = 'Divisi / Departement'; }
						// get jenis dokumen
						if (record.status == '1') {
							var direktorat = '';
							var divisi = '';
						} else {
							var direktorat = record.direktorat;
							var divisi = record.divisi;
						}
						// jenis dokumen
						var jenisform = '';
						if (record.jenisform == '1') {
							var jenisform = 'Peratuan Perusahaan';
						} else {
							var jenisform = 'Template Form';
						}
						// get lokasi 
						var lokasi = '';
						if (record.lokasi != null) {
							if (record.lokasi == '1') {
								var lokasi = 'ECI HEAD OFFICE';
							} else if (record.lokasi == '2') {
								var lokasi = 'STORE & DC';
							} else {
								var lokasi = '';
							}
						}
						html += '<tr class="even pointer">';
						html += '<td class=" ">' + (index + 1) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.nama) ? '' : record.nama) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.deskripsi) ? '' : record.deskripsi) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.date) ? '' : record.date) + '</td>';
						html += '<td class=" ">' + (isEmpty(jenis) ? '' : jenis) + '</td>';
						if (record.namaid == '3') {
							html += '<td class=" " >' + (isEmpty(direktorat) ? '' : direktorat) + '</td>';
							html += '<td class=" " >' + (isEmpty(divisi) ? '' : divisi) + '</td>';
						}
						html += '<td class=" ">' + (isEmpty(lokasi) ? '' : lokasi) + '</td>';
						html += '<td class=" ">';

						if (aksesid == '5') {
							html += '<a onclick=$(this).lihatdokumen("' + record.files + '") class="btn btn-default btn-xs" aria-label="Left Align" data-toggle="tooltip" data-placement="bottom" title="Lihat Dokumen"><i class="glyphicon glyphicon-search" aria-hidden="true"></i></a>';
							html += '<a onclick=$(this).edit("' + record.id + '") class="btn btn-success btn-xs" aria-label="Left Align" data-toggle="tooltip" data-placement="bottom" title="Ubah Dokumen"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
							html += '<a onclick=$(this).delete("' + record.id + '") class="btn btn-danger btn-xs" aria-label="Left Align" data-toggle="tooltip" data-placement="bottom" title="Hapus Dokumen"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';
						}
						else {
							html += '<a onclick=$(this).lihatdokumen("' + record.files + '") class="btn btn-default btn-xs" aria-label="Left Align" data-toggle="tooltip" data-placement="bottom" title="Lihat Dokumen"><i class="glyphicon glyphicon-search" aria-hidden="true"></i></a>';
						}
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

		$.fn.lihatdokumen = function (files) {
			window.location = SITE_URL + '/template/detaildokumen?files=' + Base64.encode(files);
		};

		$.fn.edit = function (id) {
			window.location = SITE_URL + '/template/edit?id=' + Base64.encode(id);
		};

		$.fn.delete = function (id) {
			var keyword = $('#id_keyword').val();
			var nstatus = $('#id_status').val();
			var start = 0;
			var limit = 25;

			$('#id_winconfirm').modal('show');
			$('#id_confirm_yes').one('click', function () {
				$.ajax({
					type: 'POST',
					url: SITE_URL + '/template/deleteDraft',
					data: 'id=' + id,
					async: false,
					cache: false,
					dataType: 'json',
					success: function (response) {
						if (response.success) {
							$('#id_winconfirm').modal('hide');
							window.location = '<?php echo site_url(); ?>' + '/template';
							//$.fn.load_grid_content(id, keyword, jenisid, start, limit);
						}
					}
				});
			});
		};

		var keyword = $('#id_keyword').val();
		var jenisid = $('#id_jenisid').val();
		var satker = '<?php echo substr($this->session->userdata('satkerid'), 0, 6); ?>';
		var newsatker = '';
		if (typeof jenisid === "undefined") {
			var newsatker = satker;
		} else {
			var newsatker = jenisid;
		}
		var start = 0;
		var limit = 25;

		$.fn.load_grid_content(keyword, newsatker, lokasikerja, start, limit);

		$('#id_search').on('click', function (e) {
			e.preventDefault();
			e.stopPropagation();

			var keyword = $('#id_keyword').val();
			var jenisid = $('#id_jenisid').val();

			$.fn.load_grid_content(keyword, jenisid, lokasikerja, start, limit);
		});
	});
</script>