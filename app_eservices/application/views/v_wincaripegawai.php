<div id="id_win_caripegawai" class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel2">Cari Pegawai</h4>
			</div>
			<div class="modal-body" style="padding:20px;">
				<div class="row">
					<div class="pull-left">
						<div class="input-group text">
							<input id="id_keywordcaripegawai" type="text" class="form-control"><span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="table-responsive" style="height:250px;">
						<table id="id_gridsearchpeg" class="table table-striped jambo_table bulk_action">
							<thead>
								<tr>
									<th style="width:30px" data-field="no">No</th>
									<th style="width:80px" data-field="nip">NIK</th>
									<th style="width:120px" data-field="nama">Nama</th>
									<th style="width:120px" data-field="jabatan">Jabatan</th>
									<th style="width:120px" data-field="jabatan">Divisi</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div class="pull-right">
						<ul id="paging_grid" class="pagination-sm"></ul>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button id="id_btnpilihpegawai" type="button" class="btn btn-primary">Pilih</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		var keyword = '';
		var start = 0;
		var limit = 25;

		$('#id_win_caripegawai').on('show.bs.modal', function(e) {
			load_caripegawai(keyword, start, limit);

			$('#id_btnpilihpegawai').on('click', function(e) {
				e.stopImmediatePropagation();

				var grid = $('#id_gridsearchpeg').find('.activated');
				if (grid.length > 0) {
					var res_selected = {
						pegawaiid: grid.attr('data-pegawaiid'),
						nik: grid.attr('data-nik'),
						nama: grid.attr('data-nama'),
						jabatan: grid.attr('data-jabatan'),
						unitkerjaid: grid.attr('data-satkerid'),
						unitkerja: grid.attr('data-satker'),
						lokasi: grid.attr('data-lokasi'),
						hp: grid.attr('data-hp'),
						unit: grid.attr('data-unit'),
					};

					$("#id_win_caripegawai").trigger("selected", res_selected);
					$('#id_win_caripegawai').modal('hide').data('bs.modal', null);
				}
			});
		});
		$('#id_win_caripegawai').on('hidden.bs.modal', function(e) {
			$('#id_keywordcaripegawai').val('');
			$("#id_gridsearchpeg tr").removeClass("activated");
			$("#id_win_caripegawai").unbind("selected");
		});

		$('#id_keywordcaripegawai').keypress(function(e) {
			if (e.which == 13) {
				load_caripegawai($(this).val(), start, limit);
			}
		});

		function load_caripegawai(keyword, start, limit) {
			$.getJSON(
				SITE_URL + '/pengajuan/getListRekan',
				'keyword=' + keyword + '&start=' + start + '&limit=' + limit,
				function(response) {
					if (response.success) {
						var html = '';
						$.each(response.data, function(index, record) {
							html += '<tr data-pegawaiid="' + record.pegawaiid + '" data-nik="' + record.nik + '" data-nama="' + record.nama + '" data-jabatan="' + record.jabatan + '" data-satkerid="' + record.satkerdisp + '" data-satker="' + record.divisi + '" data-lokasi="' + record.lokasi + '" data-hp="' + record.hp + '" data-unit="' + record.unit + '">';
							html += '<td>' + (index + 1) + '</td>';
							html += '<td>' + record.nik + '</td>';
							html += '<td>' + record.nama + '</td>';
							html += '<td>' + record.jabatan + '</td>';
							html += '<td>' + record.divisi + '</td>';
							html += '</tr>';
						});
						$('#id_gridsearchpeg > tbody').html(html);
						$("#id_gridsearchpeg tbody tr").click(function() {
							var selected = $(this).hasClass("activated");
							$("#id_gridsearchpeg tr").removeClass("activated");
							if (!selected) $(this).addClass("activated");
						});
					}
				}
			);
		}
	});
</script>
<style>
	#id_gridsearchpeg .activated {
		background: rgba(38, 185, 154, .16);
	}
</style>