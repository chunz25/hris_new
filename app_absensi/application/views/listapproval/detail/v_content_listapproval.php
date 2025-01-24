<div class="page-title">
	<div class="title_left">
		<h3>Detail Approval Datang Telat / Pulang Cepat</h3>
	</div>
</div>
<div class="clearfix"></div>
<div class="row center-cont">
	<div class="col-md-12 col-sm-12 col-xs-12" style="height:15px;"></div>
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="col-md-8"></div>
			<div class="col-md-4">
				<div class="form-group">
					<div class="pull-right">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Status Form</label>
						<div class="col-md-8 col-sm-8 col-xs-12">
							<span class="badge badge-primary">
								<?= $info['status']; ?>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<form id="id_formcuti" class="form-horizontal form-label-left">
				<div class="x_panel">
					<div class="x_title">
						<h2>Data Karyawan</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<div class="col-md-4">
							<div>
								<label class="control-label col-md-3 col-sm-3 col-xs-12">NIK</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $info['nik']; ?></span>
								</div>
							</div>
							<div>
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $info['nama']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Jabatan</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $info['jabatan']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Lokasi</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $info['lokasi']; ?></span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Unit Bisnis</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $info['unit']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Direktorat</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $info['direktorat']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Divisi</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $info['divisi']; ?></span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Departemen</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $info['departemen']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Seksi</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $info['seksi']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Seksi</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $info['subseksi']; ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="x_panel">
					<div class="x_title">
						<h2>Form Datang Telat / Pulang Cepat</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<table id="gridcontent" class="table table-striped jambo_table bulk_action">
							<thead>
								<tr class="headings">
									<th class="column-title">No </th>
									<th class="column-title">Jenis Waktu </th>
									<th class="column-title">Tanggal </th>
									<th class="column-title">Jam </th>
									<th class="column-title">Keterangan </th>
									<th class="column-title"></th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 1; ?>
								<?php foreach ($daftarcuti as $r) : ?>
									<tr data-id="<?= $i; ?>" data-jenis="<?= $r['jenis']; ?>" data-tglmulai="<?= $r['tglmulai']; ?>" data-jam="<?= $r['jam']; ?>" data-keterangan="<?= $r['keterangan']; ?>">
										<td><?= $r['no']; ?></td>
										<td><?= $r['jenis']; ?></td>
										<td><?= $r['tglmulai']; ?></td>
										<td><?= $r['jam']; ?></td>
										<td><?= $r['keterangan']; ?></td>
										<td></td>
									</tr>
									<?php $i++; ?>
								<?php endforeach; ?>
							</tbody>
						</table>
						<div class="pull left">
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12">Lampiran</label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<?php if ($info['files'] != null) {  ?>
											<span class="form-control readtext"><a href="download?filename=<?= $info['files']; ?>" class="btn btn-default btn-xs" aria-label="Left Align" data-toggle="tooltip" data-placement="bottom" title="Attach Files"><i class="fa fa-paperclip" aria-hidden="true"></i></a></span>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="x_panel">
					<div class="x_content">
						<div class="col-md-12 col-xs-12">
							<div class="x_title">
								<h2>Disetujui oleh</h2>
								<div class="clearfix"></div>
							</div>
							<div class="col-md-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12">Nama</label>
									<div class="col-md-10 col-sm-10 col-xs-12">
										<span id="id_fieldatasannama" class="form-control readtext">
											<?= $info['atasannama']; ?>
										</span>
										<input id="id_fieldatasanid" type="hidden" name="atasanid" value="<?= $info['atasanid']; ?>">
										<input id="id_fieldatasanemail" type="hidden" name="atasanemail" value="<?= $info['atasanemail']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12">Jabatan</label>
									<div class="col-md-10 col-sm-10 col-xs-12">
										<span id="id_fieldatasanposisi" class="form-control readtext">
											<?= $info['atasanjabatan']; ?>
										</span>
									</div>
								</div>
							</div>
							<div class="col-md-6"></div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="row" style="padding-left:5px;padding-right:5px;">
		<div class="pull-right">
			<input type="button" class="btn btn-default" value=" Setuju " onclick=$(this).showApprove("<?= $info['pegawaiid']; ?>","<?= $info['nourut']; ?>","<?= $info['atasanid']; ?>","<?= $info["atasanemail"]; ?>","<?= $name = str_replace(' ', '_', $info["nama"]); ?>","<?= $info["nik"]; ?>","1")>
			<input type="button" class="btn btn-default" value=" Tolak " onclick=$(this).showRejected("<?= $info['pegawaiid']; ?>","<?= $info['nourut']; ?>","<?= $info['atasanid']; ?>","<?= $info["atasanemail"]; ?>","<?= $info["pengajuemail"]; ?>","<?= $name = str_replace(' ', '_', $info["nama"]); ?>","<?= $info["nik"]; ?>","2")>
			<input type="button" class="btn btn-success" value=" Kembali " onclick="window.location.href='<?= site_url(); ?>/listapprove'">
		</div>
	</div>
</div>

<div id="id_winconfirm" class="modal fade hapus-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
	<img id="loading-image" style="display:none;" />
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title">Informasi</h4>
			</div>
			<div class="modal-body">
				<?php
				$status = "";
				if ($info['statusid'] == "9" || $info['statusid'] == "10") {
					$status = " pembatalan ";
				} ?>
				Apakah anda akan menyetujui form absensi kehadiran ini ?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
				<button id="id_confirm_yes" type="button" class="btn btn-primary">Ya</button>
			</div>
		</div>
	</div>
</div>

<div id="id_winconfirmreject" class="modal fade hapus-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
	<img id="loading-image" style="display:none;" />
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
					<input type="hidden" name="atasanid" />
					<input type="hidden" name="atasanemail" />
					<input type="hidden" name="pengajuemail" />
					<input type="hidden" name="nama" />
					<input type="hidden" name="nik" />
					<input type="hidden" name="action" />
					<input type="hidden" name="batalalasan" />
					<div class="row">
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Alasan Ditolak</label>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<textarea id="id_alasanditolak" class="form-control" rows="3" name="batalalasan" placeholder="Alasan Ditolak"></textarea>
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

<?php $this->load->view("v_wincaripegawai.php"); ?>

<script type="text/javascript">
	$(function() {
		var availabledates = jQuery.parseJSON('<?= $vharilibur; ?>');

		$.fn.showApprove = function(pegawaiid, nourut, atasanid, atasanemail, nama, nik, action) {
			$('#id_winconfirm').modal('show');
			$('#id_confirm_yes').one('click', function() {
				$.ajax({
					type: 'POST',
					url: SITE_URL + '/listapprove/approveAbsensi',
					data: 'pegawaiid=' + pegawaiid + '&nourut=' + nourut + '&atasanid=' + atasanid + '&atasanemail=' + atasanemail + '&nama=' + nama + '&nik=' + nik + '&action=' + action,
					async: false,
					cache: false,
					dataType: 'json',
					beforeSend: function() {
						$("#loading-image").show(0).delay(15000).hide(0);
						window.location = '<?= site_url(); ?>' + '/listapprove';
					},
					success: function(response) {
						if (response.success) {
							var tglmulai = moment($('#id_tglawal').datepicker('getDate')).format('DD/MM/YYYY');
							var tglselesai = moment($('#id_tglakhir').datepicker('getDate')).format('DD/MM/YYYY');
							$("#loading-image").hide();
							$('#id_winconfirm').modal('hide');
							location.href = SITE_URL + "/listapprove/";
							$.fn.load_grid_content(tglmulai, tglselesai, start, limit);

						}
					}
				});
			});
		}

		$.fn.showRejected = function(pegawaiid, nourut, atasanid, atasanemail, pengajuemail, nama, nik, action, batalalasan) {
			var form = $('#id_form_content_reject');
			$('#id_winconfirmreject').modal('show');
			form.find('input[name=pegawaiid]').val(pegawaiid);
			form.find('input[name=nourut]').val(nourut);
			form.find('input[name=atasanid]').val(atasanid);
			form.find('input[name=atasanemail]').val(atasanemail);
			form.find('input[name=pengajuemail]').val(pengajuemail);
			form.find('input[name=nama]').val(nama);
			form.find('input[name=nik]').val(nik);
			form.find('input[name=action]').val(action);
			form.find('input[name=batalalasan]').val(batalalasan);

			$('#id_confirmreject_yes').on('click', function(e) {
				$('#id_form_content_reject').validate({
					onfocusout: false,
					rules: {
						batalalasan: {
							required: true
						},
					},
					highlight: function(element) {
						$(element).closest('.form-group').addClass('has-error');
					},
					unhighlight: function(element) {
						$(element).closest('.form-group').removeClass('has-error');
					},
					errorElement: 'span',
					errorClass: 'help-block',
					errorPlacement: function(error, element) {
						if (element.parent('.input-group').length) {
							error.insertAfter(element.parent());
						} else {
							error.insertAfter(element);
						}
					},
					messages: {
						batalalasan: {
							required: "Harap mengisi alasan tolak form kehadiran"
						},
					}
				});

				e.preventDefault();
				e.stopPropagation();

				var form = $('#id_form_content_reject');
				if (form.valid()) {
					$.ajax({
						type: 'POST',
						url: SITE_URL + '/listapprove/rejectAbsensi',
						data: form.serialize(),
						async: false,
						cache: false,
						dataType: 'json',
						beforeSend: function() {
							$("#loading-image").show(0).delay(15000).hide(0);
							window.location = '<?= site_url(); ?>' + '/listapprove';
						},
						success: function(response) {
							if (response.success) {
								var tglmulai = moment($('#id_tglawal').datepicker('getDate')).format('DD/MM/YYYY');
								var tglselesai = moment($('#id_tglakhir').datepicker('getDate')).format('DD/MM/YYYY');
								$("#loading-image").hide();
								$('#id_winconfirmreject').modal('hide');
								location.href = SITE_URL + "/listapprove/";
								$.fn.load_grid_content(tglmulai, tglselesai, start, limit);
							}
						}
					});
				}
			});
		}
	});
</script>