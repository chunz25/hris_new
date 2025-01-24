<div class="page-title">
	<div class="title_left">
		<h3>Detail Approval Cuti</h3>
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
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Status Cuti</label>
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
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Unit Bisnis</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $info['unit']; ?></span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
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
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Jatah Cuti</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vsisacuti['jatahAwal']; ?></span>
								</div>
							</div>
							<?php
							if (date("m") <= 3) {
								?>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Sisa Cuti
										<?= date("Y") - 1; ?></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<span class="form-control readtext"><?= $vsisacuti['saldoLY']; ?></span>
									</div>
								</div>
							<?php } else { ?>
								<input id="id_info_sisacuti1" type="hidden" class="form-control readtext" value="0">
							<?php } ?>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Sisa Cuti
									<?= date("Y"); ?></label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vsisacuti['saldoCY']; ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="x_panel">
					<div class="x_title">
						<h2>Kontak Selama Ketidakhadiran</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">HP</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<span class="form-control readtext"><?= $info['hp']; ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="x_panel">
					<div class="x_title">
						<h2>Pengajuan Cuti</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<table id="gridcontent" class="table table-striped jambo_table bulk_action">
							<thead>
								<tr class="headings">
									<th class="column-title">No </th>
									<th class="column-title">Jenis Cuti </th>
									<th class="column-title">Alasan </th>
									<th class="column-title">Tanggal Awal </th>
									<th class="column-title">Tanggal Akhir </th>
									<th class="column-title">Lama Cuti </th>
									<th class="column-title">Sisa Cuti </th>
									<th class="column-title"></th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 1; ?>
								<?php foreach ($daftarcuti as $r): ?>
									<?php if (($info['statusid'] == 9 || $info['statusid'] == 10)) { ?>
										<tr data-id="<?= $i; ?>" data-jeniscutiid="<?= $r['jeniscutiid']; ?>"
											data-detailjeniscutiid="<?= $r['detailjeniscutiid']; ?>"
											data-tglawal="<?= $r['tglmulai']; ?>" data-tglakhir="<?= $r['tglselesai']; ?>"
											data-sisacuti="<?= $r['sisacuti']; ?>" data-lamacuti="<?= $r['lama']; ?>"
											data-keterangan="<?= $r['alasancuti']; ?>">
										<?php } else if ($info['statusid'] == 9 || $info['statusid'] == 10) { ?>
											<tr style="display: none;" data-id="<?= $i; ?>"
												data-jeniscutiid="<?= $r['jeniscutiid']; ?>"
												data-detailjeniscutiid="<?= $r['detailjeniscutiid']; ?>"
												data-tglawal="<?= $r['tglmulai']; ?>" data-tglakhir="<?= $r['tglselesai']; ?>"
												data-sisacuti="<?= $r['sisacuti']; ?>" data-lamacuti="<?= $r['lama']; ?>"
												data-keterangan="<?= $r['alasancuti']; ?>">
										<?php } else { ?>
											<tr data-id="<?= $i; ?>" data-jeniscutiid="<?= $r['jeniscutiid']; ?>"
												data-detailjeniscutiid="<?= $r['detailjeniscutiid']; ?>"
												data-tglawal="<?= $r['tglmulai']; ?>" data-tglakhir="<?= $r['tglselesai']; ?>"
												data-sisacuti="<?= $r['sisacuti']; ?>" data-lamacuti="<?= $r['lama']; ?>"
												data-keterangan="<?= $r['alasancuti']; ?>">
										<?php } ?>
										<td><?= $r['no']; ?></td>
										<td><?= $r['jeniscuti']; ?></td>
										<td><?= $r['alasancuti']; ?></td>
										<td><?= $r['tglmulai']; ?></td>
										<td><?= $r['tglselesai']; ?></td>
										<td><?= $r['lama']; ?></td>
										<td><?= $r['sisacuti']; ?></td>
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
										<?php if ($info['files'] != null) { ?>
											<span class="form-control readtext"><a
													href="download?filename=<?= $info['files']; ?>"
													class="btn btn-default btn-xs" aria-label="Left Align"
													data-toggle="tooltip" data-placement="bottom" title="Attach Files"><i
														class="fa fa-paperclip" aria-hidden="true"></i></a></span>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="x_panel">
					<div class="x_title">
						<h2>Pelimpahan tanggung jawab selama ketidakhadiran</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">NIK</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<span class="form-control readtext"><?= $info['pelimpahannik']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Nama</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<span class="form-control readtext"><?= $info['pelimpahannama']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">HP</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<span class="form-control readtext"><?= $info['pelimpahanhp']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Jabatan</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<span class="form-control readtext"><?= $info['pelimpahanjabatan']; ?></span>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Divisi</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<span class="form-control readtext"><?= $info['pelimpahansatker']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Lokasi</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<span class="form-control readtext"><?= $info['pelimpahanlokasi']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Unit Bisnis</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<span class="form-control readtext"><?= $info['pelimpahanunit']; ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="x_panel">
					<div class="x_content">
						<div class="col-md-6">
							<div class="x_title">
								<h2>Diperiksa oleh</h2>
								<div class="clearfix"></div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Nama</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<span id="id_fieldatasan1nama"
										class="form-control readtext"><?= $info['atasannama']; ?></span>
									<input id="id_fieldatasan1id" type="hidden" name="atasan1id"
										value="<?= $info['atasanid']; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Jabatan</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<span id="id_fieldatasan1posisi"
										class="form-control readtext"><?= $info['atasanjabatan']; ?></span>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="x_title">
								<h2>Disetujui oleh</h2>
								<div class="clearfix"></div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Nama</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<span id="id_fieldatasan2nama"
										class="form-control readtext"><?= $info['atasan2nama']; ?></span>
									<input id="id_fieldatasan2id" type="hidden" name="atasan2id"
										value="<?= $info['atasan2id']; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Jabatan</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<span id="id_fieldatasan2posisi"
										class="form-control readtext"><?= $info['atasan2jabatan']; ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="row" style="padding-left:5px;padding-right:5px;">
		<div class="pull-right">
			<!-- verifikator -->
			<?php if ($info['atasanid'] == $infopegawai['pegawaiid']) {
				$useraction = "12";
				$action = "";
				if ($info['statusid'] == "2") { //pengajuan baru
					$action = "1";
				} elseif ($info['statusid'] == "9") { //pengajuan pembatalan
					$action = "2";
				} ?>

				<?php $lamahari = 0;
				foreach ($daftarcuti as $r):
					if ($r['jeniscutiid'] == 1) {
						$lamahari = $lamahari + $r['lama'];
					}
				endforeach;
				$sisacuti = $vsisacuti['saldoLY'] + $vsisacuti['saldoCY'];
				$id = $r['jeniscutiid'];
				if (($sisacuti < $lamahari) && ($id == '1') && $info['statusid'] == "2") {
					echo '<div class="alert alert-dismissible alert-default">
					  <button type="button" class="close" data-dismiss="alert">&times;</button>
					  <center><strong>Saldo cuti<br>tidak cukup</strong></center>
					  </div>';
				} else { ?>
					<input type="button" class="btn btn-default" value=" Setuju "
						onclick=$(this).showApprove("<?= $info['pegawaiid']; ?>","<?= $info['nourut']; ?>","<?= $useraction ?>","<?= $action ?>","<?= $info['atasanid']; ?>","<?= $info["atasanemail"]; ?>","<?= $name = str_replace(' ', '_', $info["nama"]); ?>","<?= $info["email"]; ?>","<?= $info["nik"]; ?>")>
				<?php } ?>
				<input type="button" class="btn btn-default" value=" Tolak "
					onclick=$(this).showRejected("<?= $info['pegawaiid']; ?>","<?= $info['nourut']; ?>","<?= $useraction ?>","<?= $action ?>","<?= $info['atasanid']; ?>","<?= $info["atasanemail"]; ?>","<?= $name = str_replace(' ', '_', $info["nama"]); ?>","<?= $info["email"]; ?>","<?= $info["nik"]; ?>")>
			<?php } ?>

			<!-- approval -->
			<?php if ($info['atasan2id'] == $infopegawai['pegawaiid']) {
				$useraction = "13";
				$action = "";
				if ($info['statusid'] == "2" || $info['statusid'] == "3") { //disetujui verifikator
					$action = "1";
				} elseif ($info['statusid'] == "9" || $info['statusid'] == "10") { //pengajuan disetujui verifikator
					$action = "2";
				} ?>
				<?php $lamahari = 0;
				foreach ($daftarcuti as $r):
					if ($r['jeniscutiid'] == 1) {
						$lamahari = $lamahari + $r['lama'];
					}
				endforeach;
				$sisacuti = $vsisacuti['saldoLY'] + $vsisacuti['saldoCY'];
				$id = $r['jeniscutiid'];
				if (($sisacuti < $lamahari) && ($id == '1') && ($info['statusid'] == "2" || $info['statusid'] == "3")) {
					echo '<div class="alert alert-dismissible alert-default">
					  <button type="button" class="close" data-dismiss="alert">&times;</button>
					  <center><strong>Saldo cuti<br>tidak cukup</strong></center>
					  </div>';
				} else { ?>
					<input type="button" class="btn btn-default" value=" Setuju "
						onclick=$(this).showApprove("<?= $info['pegawaiid']; ?>","<?= $info['nourut']; ?>","<?= $useraction ?>","<?= $action ?>","<?= $info['atasan2id']; ?>","<?= $info["atasan2email"]; ?>","<?= $name = str_replace(' ', '_', $info["nama"]); ?>","<?= $info["email"]; ?>","<?= $info["nik"]; ?>")>
				<?php } ?>
				<input type="button" class="btn btn-default" value=" Tolak "
					onclick=$(this).showRejected("<?= $info['pegawaiid']; ?>","<?= $info['nourut']; ?>","<?= $useraction ?>","<?= $action ?>","<?= $info['atasan2id']; ?>","<?= $info["atasan2email"]; ?>","<?= $name = str_replace(' ', '_', $info["nama"]); ?>","<?= $info["email"]; ?>","<?= $info["nik"]; ?>")>
			<?php } ?>
		</div>
	</div>
</div>

<div id="id_winconfirm" class="modal fade hapus-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
	<img id="loading-image" style="display:none;" />
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">×</span></button>
				<h4 class="modal-title">Informasi</h4>
			</div>
			<div class="modal-body">
				<?php
				$status = "";
				if ($info['statusid'] == "9" || $info['statusid'] == "10") {
					$status = " pembatalan ";
				} ?>
				Apakah anda akan menyetujui<?= $status; ?> cuti ini ?
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
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">x</span></button>
				<h4 class="modal-title">Informasi</h4>
			</div>
			<div class="modal-body">
				<form id="id_form_content_reject" class="form-horizontal form-label-left" action="./" method="POST"
					enctype="multipart/form-data">
					<input type="hidden" name="pegawaiid" />
					<input type="hidden" name="nourut" />
					<input type="hidden" name="useraction" />
					<input type="hidden" name="action" />
					<input type="hidden" name="atasan2id" />
					<input type="hidden" name="atasan2email" />
					<input type="hidden" name="nama" />
					<input type="hidden" name="email" />
					<input type="hidden" name="nik" />
					<input type="hidden" name="batalalasan" />
					<div class="row">
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Alasan Ditolak</label>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<textarea id="id_alasanditolak" class="form-control" rows="3" name="alasan"
									placeholder="Alasan Ditolak ..."></textarea>
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
	$(function () {
		$.fn.showApprove = function (pegawaiid, nourut, useraction, action, atasan2id, atasan2email, nama, email, nik) {
			$('#id_winconfirm').modal('show');
			$('#id_confirm_yes').one('click', function () {
				$.ajax({
					type: 'POST',
					url: SITE_URL + '/listapprove/approveCuti',
					data: 'pegawaiid=' + pegawaiid + '&nourut=' + nourut + '&useraction=' + useraction + '&action=' + action + '&atasan2id=' + atasan2id + '&atasan2email=' + atasan2email + '&nama=' + nama + '&email=' + email + '&nik=' + nik,
					async: false,
					cache: false,
					dataType: 'json',
					beforeSend: function () {
						$("#loading-image").show(0).delay(15000).hide(0);
						window.location = '<?= site_url(); ?>' + '/listapprove';
					},
					success: function (response) {
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

		$.fn.showRejected = function (pegawaiid, nourut, useraction, action, atasan2id, atasan2email, nama, email, nik, alasan, batalalasan) {
			var form = $('#id_form_content_reject');
			$('#id_winconfirmreject').modal('show');
			form.find('input[name=pegawaiid]').val(pegawaiid);
			form.find('input[name=nourut]').val(nourut);
			form.find('input[name=useraction]').val(useraction);
			form.find('input[name=action]').val(action);
			form.find('input[name=atasan2id]').val(atasan2id);
			form.find('input[name=atasan2email]').val(atasan2email);
			form.find('input[name=nama]').val(nama);
			form.find('input[name=email]').val(email);
			form.find('input[name=nik]').val(nik);
			form.find('input[name=alasan]').val(alasan);
			form.find('input[name=batalalasan]').val(batalalasan);

			$('#id_confirmreject_yes').one('click', function () {
				$.ajax({
					type: 'POST',
					url: SITE_URL + '/listapprove/rejectCuti',
					data: form.serialize(),
					async: false,
					cache: false,
					dataType: 'json',
					beforeSend: function () {
						$("#loading-image").show(0).delay(15000).hide(0);
						window.location = '<?= site_url(); ?>' + '/listapprove';
					},
					success: function (response) {
						if (response.success) {
							var tglmulai = moment($('#id_tglawal').datepicker('getDate')).format('DD/MM/YYYY');
							var tglselesai = moment($('#id_tglakhir').datepicker('getDate')).format('DD/MM/YYYY');
							$("#loading-image").hide();
							$('#id_winconfirmreject').modal('hide');
							window.location = '<?= site_url(); ?>' + '/listapprove';
							$.fn.load_grid_content(tglmulai, tglselesai, start, limit);
						}
					}
				});
			});
		}
	});
</script>