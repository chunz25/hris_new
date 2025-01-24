<div class="page-title">
	<div class="title_left">
		<h3>Detail History Cuti</h3>
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
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Sisa Cuti <?= date("Y") - 1; ?></label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vsisacuti['saldoLY']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Sisa Cuti <?= date("Y"); ?></label>
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
								<?php foreach ($daftarcuti as $r) : ?>
									<tr data-id="<?= $i; ?>" data-jeniscutiid="<?= $r['jeniscutiid']; ?>" data-detailjeniscutiid="<?= $r['detailjeniscutiid']; ?>" data-tglawal="<?= $r['tglmulai']; ?>" data-tglakhir="<?= $r['tglselesai']; ?>" data-sisacuti="<?= $r['sisacuti']; ?>" data-lamacuti="<?= $r['lama']; ?>" data-keterangan="<?= $r['alasancuti']; ?>">
										<td><?= $r['no']; ?></td>
										<td><?= $r['jeniscuti']; ?></td>
										<td><?= $r['alasancuti']; ?></td>
										<td><?= $r['tglmulai']; ?></td>
										<td><?= $r['tglselesai']; ?></td>
										<td><?= $r['lama']; ?></td>
										<td><?= $r['sisacuti']; ?></td>
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
				<?php if ($info['verifikasinotes'] != '' || $info['verifikasinotes'] != null) { ?>
					<div class="x_panel">
						<div class="x_title">
							<h2>Alasan Pengajuan Ditolak</h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12">Alasan</label>
									<div class="col-md-10 col-sm-10 col-xs-12">
										<span class="form-control readtext"><?= $info['verifikasinotes']; ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- VIEW VERIF & APPROVAL 04/10/2022 Created by CHUNZ  -->
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
									<span id="id_fieldatasan1nama" class="form-control readtext"><?= $vinfoatasan['verifikatornama']; ?></span>
									<input id="id_fieldatasan1id" type="hidden" name="atasan1id" value="<?= $vinfoatasan['verifikatorid']; ?>">
									<input id="id_fieldatasan1email" type="hidden" name="atasan1email" value="<?= $vinfoatasan['verifikatoremail']; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Jabatan</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<span id="id_fieldatasan1posisi" class="form-control readtext"><?= $vinfoatasan['verifikatorjab']; ?></span>
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
									<span id="id_fieldatasan2nama" class="form-control readtext"><?= $vinfoatasan['atasannama']; ?></span>
									<input id="id_fieldatasan2id" type="hidden" name="atasan2id" value="<?= $vinfoatasan['atasanid']; ?>">
									<input id="id_fieldatasan2email" type="hidden" name="atasan2email" value="<?= $vinfoatasan['atasanemail']; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Jabatan</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<span id="id_fieldatasan2posisi" class="form-control readtext"><?= $vinfoatasan['atasanjab']; ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- VIEW VERIF & APPROVAL 04/10/2022 Created by CHUNZ  -->

			</form>
		</div>
	</div>
	<div class="row" style="padding-left:5px;padding-right:5px;">
		<div class="pull-right">
			<input type="button" class="btn btn-success" value=" Kembali " onclick=$(this).detailhistory("<?= $info['pegawaiid'] ?>")>
			<?php if ($info['pegawaiid'] == $infopegawai['pegawaiid']) {
				if ($info['statusid'] == '2') { ?>
					<input type="button" class="btn btn-default" value=" Cancel Cuti " onclick=$(this).delete("<?= $info['pegawaiid']; ?>","<?= $info['pengajuanid']; ?>","<?= $vinfoatasan['verifikatorid']; ?>","<?= $vinfoatasan['atasanid']; ?>","<?= $vinfoatasan['verifikatoremail']; ?>","<?= $vinfoatasan['atasanemail']; ?>")>
				<?php } else if ($info['statusid'] == '7' || $info['statusid'] == '15') { ?>
					<?php foreach ($daftarcuti as $r) :
						if ($r['batalcuti'] == null) { ?>
							<input type="button" class="btn btn-default" value=" Batal Cuti " onclick=$(this).showPengajuanBatalCuti("<?= $info['nourut']; ?>","<?= $vinfoatasan['atasanid']; ?>","<?= $info['pengajuanid']; ?>","<?= $vinfoatasan['verifikatorid']; ?>","<?= $vinfoatasan['atasanid']; ?>","<?= $vinfoatasan['verifikatoremail']; ?>","<?= $vinfoatasan['atasanemail']; ?>")>
					<?php }
					endforeach; ?>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_batal_cuti" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
				<h3 class="modal-title" id="myModalLabel"> Informasi </h3>
			</div>
			<form id="id_form_content_batalcuti" class="form-horizontal form-label-left" action="./" method="POST" enctype="multipart/form-data">
				<div class="modal-body">
					<input type="hidden" name="nourut" />
					<input type="hidden" name="atasanid" />
					<input type="hidden" name="pengajuanid" />
					<input type="hidden" name="verifikatorid" />
					<input type="hidden" name="approvalid" />
					<input type="hidden" name="verifikatoremail" />
					<input type="hidden" name="approvallemail" />
					<div class="form-group">
						<label class="control-label col-xs-3">Alasan Batal Cuti</label>
						<div class="col-xs-8">
							<textarea id="id_alasanditolak" class="form-control" rows="3" name="alasan" placeholder="Alasan Batal Cuti"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Tidak</button>
					<button id="id_confirmreject_yes" class="btn btn-primary">Ya</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="id_reject_winconfirm" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title">Konfirmasi</h4>
			</div>
			<div class="modal-body" style="text-align: justify;">
				Apakah anda yakin akan mengajukan pembatalan Cuti ? Jika <b style="color:Tomato;">Ya</b>, maka jika ditanggal tersebut anda tidak masuk bekerja di kantor, secara otomatis akan dimasukkan ke <b style="color:Tomato;">Cuti Tanpa Upah</b> dan <b style="color:Tomato;">Tidak Bisa Diganti</b> ke tanggal cuti bersama lainnya.
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
				<button id="id_reject_confirm_yes" type="button" class="btn btn-primary">Ya</button>
			</div>
		</div>
	</div>
</div>

<div id="id_winconfirm" class="modal fade hapus-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title">Konfirmasi</h4>
			</div>
			<div class="modal-body">
				Apakah anda akan menghapus cuti ini ?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
				<button id="id_confirm_yes" type="button" class="btn btn-primary">Ya</button>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view("v_wincaripegawai.php"); ?>

<script type="text/javascript">
	$(function() {

		$.fn.showPengajuanBatalCuti = function(nourut, atasanid, pengajuanid, verifikatorid, approvalid, verifikatoremail, approvallemail, alasan) {
			var form = $('#id_form_content_batalcuti');
			$('#modal_batal_cuti').modal('show');
			form.find('input[name=nourut]').val(nourut);
			form.find('input[name=atasanid]').val(atasanid);
			form.find('input[name=pengajuanid]').val(pengajuanid);
			form.find('input[name=verifikatorid]').val(verifikatorid);
			form.find('input[name=approvalid]').val(approvalid);
			form.find('input[name=verifikatoremail]').val(verifikatoremail);
			form.find('input[name=approvallemail]').val(approvallemail);
			form.find('input[name=alasan]').val(alasan);

			$('#id_confirmreject_yes').on('click', function(e) {
				$('#id_form_content_batalcuti').validate({
					onfocusout: false,
					rules: {
						alasan: {
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
						alasan: {
							required: "Harap mengisi alasan batal cuti"
						},
					}
				});

				e.preventDefault();
				e.stopPropagation();

				var form = $('#id_form_content_batalcuti');
				if (form.valid()) {
					$('#modal_batal_cuti').modal('hide');
					$('#modal_batal_cuti').on('hidden.bs.modal', function() {
						$('#id_reject_winconfirm').modal('show');
						$('#id_reject_confirm_yes').one('click', function() {
							$.ajax({
								type: 'POST',
								url: SITE_URL + '/history/batalCuti',
								data: form.serialize(),
								async: false,
								cache: false,
								dataType: 'json',
								beforeSend: function() {
									$("#loading-image").show(0).delay(15000).hide(0);
									window.location = '<?= site_url(); ?>' + '/history';
								},
								success: function(response) {
									if (response.success) {
										var tglmulai = moment($('#id_tglawal').datepicker('getDate')).format('DD/MM/YYYY');
										var tglselesai = moment($('#id_tglakhir').datepicker('getDate')).format('DD/MM/YYYY');
										$("#loading-image").hide();
										$('#id_reject_winconfirm').modal('hide');
										location.href = SITE_URL + "/history/";
										$.fn.load_grid_content(tglmulai, tglselesai, start, limit);
									}
								}
							});
						});
					});
				}
			});
		}

		$.fn.delete = function(pegawaiid, pengajuanid, verifikatorid, approvalid, verifikatoremail, approvallemail) {
			var tglmulai = moment($('#id_tglawal').datepicker('getDate')).format('DD/MM/YYYY');
			var tglselesai = moment($('#id_tglakhir').datepicker('getDate')).format('DD/MM/YYYY');

			$('#id_winconfirm').modal('show');
			$('#id_confirm_yes').one('click', function() {
				$.ajax({
					type: 'POST',
					url: SITE_URL + '/history/deleteCuti',
					data: 'pegawaiid=' + pegawaiid + '&pengajuanid=' + pengajuanid + '&verifikatorid=' + verifikatorid + '&approvalid=' + approvalid + '&verifikatoremail=' + verifikatoremail + '&approvallemail=' + approvallemail,
					async: false,
					cache: false,
					dataType: 'json',
					success: function(response) {
						if (response.success) {
							$('#id_winconfirm').modal('hide');
							location.href = SITE_URL + "/history/";
							$.fn.load_grid_content(tglmulai, tglselesai, start, limit);
						}
					}
				});
			});
		};

		$.fn.detailhistory = function(pegawaiid) {
			window.location = SITE_URL + '/history/detaillistcuti?pegawaiid=' + Base64.encode(pegawaiid);
		};
	});
</script>