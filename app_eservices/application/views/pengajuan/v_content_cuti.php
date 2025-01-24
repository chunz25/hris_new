<div class="page-title">
	<div class="title_left">
		<h3>Pengajuan Cuti</h3>
	</div>
</div>
<input type="hidden" value="<?php foreach ($vstatuscuti as $r):
	echo $r;
endforeach; ?>">
<div class="clearfix"></div>
<div class="row center-cont">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<form id="id_formcuti" class="form-horizontal form-label-left">
				<div class="x_panel">
					<div class="x_title">
						<h2>Data Karyawan</h2>
						<div class="clearfix"></div>
						<!-- <a href="<?= site_url() . '/crott'; ?>"> Generate sisa Cuti</a> -->
						<!-- <a href="<?= site_url() . '/crotber'; ?>"> Generate cuti bersama</a> -->
					</div>
					<div class="x_content">
						<div class="col-md-4">
							<div>
								<label class="control-label col-md-3 col-sm-3 col-xs-12">NIK</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['nik']; ?></span>
								</div>
							</div>
							<div>
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['nama']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Jabatan</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['jabatan']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Lokasi</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['lokasi']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Unit Bisnis</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['unit']; ?></span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Direktorat</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['direktorat']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Divisi</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['divisi']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Departemen</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['departemen']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Seksi</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['seksi']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Seksi</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['subseksi']; ?></span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Jatah Cuti</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<input id="id_info_jatahcuti" type="text" class="form-control readtext"
										value="<?= $vsisacuti['jatahAwal']; ?>">
								</div>
							</div>
							<?php
							if (date("m") <= 3) {
								?>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Sisa Cuti
										<?= date("Y") - 1; ?></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<input id="id_info_sisacuti1" type="text" class="form-control readtext"
											value="<?= $vsisacuti['saldoLY']; ?>">
									</div>
								</div>
							<?php } else { ?>
								<input id="id_info_sisacuti1" type="hidden" class="form-control readtext" value="0">
							<?php } ?>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Sisa Cuti
									<?= date("Y"); ?></label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<input id="id_info_sisacuti2" type="text" class="form-control readtext"
										value="<?= $vsisacuti['saldoCY']; ?>">
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
									<input type="text" class="form-control" name="hp"
										value="<?= $vinfopegawai['nohp']; ?>">
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
						<div class="pull-right">
							<button type="button" id="id_tambah" class="btn btn-success"
								aria-label="Left Align">Tambah</button>
						</div>
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
							<tbody></tbody>
						</table>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Lampiran</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<input id="file_upload" type="file" name="files" class="filestyle"
										data-icon="false">
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
									<div class="input-group text">
										<input id="id_fieldpelimpahan" type="text" name="rekannik"
											class="form-control"><span class="input-group-addon"><i class="fa fa-search"
												aria-hidden="true"></i></span>
										<input id="id_fieldpelimpahanid" type="hidden" name="rekanpegawaiid">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Nama</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<input id="id_fieldpelimpahannama" type="text" class="form-control readtext">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">HP</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<input id="id_fieldpelimpahanhp" type="text" class="form-control readtext">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Jabatan</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<input id="id_fieldpelimpahanposisi" type="text" class="form-control readtext">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Divisi</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<input id="id_fieldpelimpahandivisi" type="text" class="form-control readtext">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Lokasi</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<input id="id_fieldpelimpahanlokasi" type="text" class="form-control readtext">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Unit Bisnis</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<input id="id_fieldpelimpahanunit" type="text" class="form-control readtext">
								</div>
							</div>
						</div>
					</div>
				</div>
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
									<span id="id_fieldatasan1nama"
										class="form-control readtext"><?= $vinfoatasan['verifikatornama']; ?></span>
									<input id="id_fieldatasan1id" type="hidden" name="atasan1id"
										value="<?= $vinfoatasan['verifikatorid']; ?>">
									<input id="id_fieldatasan1email" type="hidden" name="atasan1email"
										value="<?= $vinfoatasan['verifikatoremail']; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Jabatan</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<span id="id_fieldatasan1posisi"
										class="form-control readtext"><?= $vinfoatasan['verifikatorjab']; ?></span>
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
										class="form-control readtext"><?= $vinfoatasan['atasannama']; ?></span>
									<input id="id_fieldatasan2id" type="hidden" name="atasan2id"
										value="<?= $vinfoatasan['atasanid']; ?>">
									<input id="id_fieldatasan2email" type="hidden" name="atasan2email"
										value="<?= $vinfoatasan['atasanemail']; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Jabatan</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<span id="id_fieldatasan2posisi"
										class="form-control readtext"><?= $vinfoatasan['atasanjab']; ?></span>
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
			<?php if (!in_array($vinfopegawai['nik'], $vnikadmin)) {
				foreach ($vinfocuti as $r):
					if ($r > 0) {
						echo '<div class="alert alert-dismissible alert-default"><button type="button" class="close" data-dismiss="alert">&times;</button>
				  <center><span style = "font-size: 15px;"><strong>Terdapat pengajuan cuti<br>yang belum disetujui oleh Verifikator/Approval/HRD</strong></span></center>
				  </div>';
					} else { ?>
						<button type="button" id="id_ajukancuti" class="btn btn-success" aria-label="Left Align">Ajukan</button>
					<?php }
				endforeach;
			} else {
				?>
				<button type="button" id="id_ajukancuti" class="btn btn-success" aria-label="Left Align">Ajukan</button>
			<?php } ?>
		</div>
	</div>
</div>

<div id="id_win_content" class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">x</span></button>
				<h4 class="modal-title" id="myModalLabel2">Form Cuti</h4>
			</div>
			<div class="modal-body">
				<form id="id_formcontent" class="form-horizontal form-label-left" action="./" method="POST"
					enctype="multipart/form-data">
					<div class="row">
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-10">Jenis Cuti</label>
							<div class="col-md-10 col-sm-10 col-xs-12" style="padding-left:10px;">
								<select id="id_jeniscuti" class="form-control" name="jeniscuti">
									<?php foreach ($vjeniscuti as $r): ?>
										<?php $selected = ''; ?>
										<?php if ($r['id'] == '1'): ?>
											<?php $selected = 'selected'; ?>
										<?php endif; ?>
										<option value="<?= $r['id']; ?>" <?= $selected; ?>><?= $r['text']; ?></option>
									<?php endforeach; ?>
								</select>
								<input id="id_detailjeniscutiid" type="hidden" name="detailjeniscutiid" />
							</div>
						</div>
						<div id="id_group_detailjeniscuti" class="form-group" style="display:none;">
							<label class="control-label col-md-2 col-sm-2 col-xs-10">Alasan Cuti</label>
							<div class="col-md-10 col-sm-10 col-xs-12" style="padding-left:10px;">
								<select id="id_detailjeniscuti" class="form-control" name="detailjeniscuti"></select>
							</div>
						</div>
						<div id="id_group_jatahcutikhusus" class="form-group" style="display:none;">
							<label class="control-label col-md-2 col-sm-2 col-xs-10">Jatah Cuti Khusus</label>
							<div class="col-md-10 col-sm-10 col-xs-12"
								style="padding-left:20px;padding-top:10px;padding-bottom:10px;">
								<span id="id_spanjatahcutikhusus">0</span>
							</div>
						</div>
						<div id="id_kerja" class="form-group" style="display:none;">
							<label class="control-label col-md-2 col-sm-1 col-xs-12">Tgl Kerja</label>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<div id="id_tglkerja" class="input-group date">
									<input type="text" name="tglkerja" class="form-control"><span
										class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group" id="id_tglcuti">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Tgl Awal</label>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<div id="id_tglawal" class="input-group date">
									<input type="text" name="tglawal" class="form-control" autocomplete="off" onkeypress="return false"><span
										class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
								</div>
							</div>
							<label class="control-label col-md-2 col-sm-1 col-xs-12">Tgl Akhir</label>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<div id="id_tglakhir" class="input-group date" autocomplete="off" onkeypress="return false">
									<input type="text" name="tglakhir" class="form-control"><span
										class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group" style="display:none;" id="id_tglperdin">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Tgl Awal</label>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<div id="id_perdinawal" class="input-group date">
									<input type="text" name="perdinawal" class="form-control" autocomplete="off" onkeypress="return false"><span
										class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
								</div>
							</div>
							<label class="control-label col-md-2 col-sm-1 col-xs-12">Tgl Akhir</label>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<div id="id_perdinakhir" class="input-group date">
									<input type="text" name="perdinawal" class="form-control" autocomplete="off" onkeypress="return false"><span
										class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12"
								style="padding-left:0px;padding-right:5px;">Lama Cuti</label>
							<div class="col-md-3 col-sm-3 col-xs-12">
								<input type="text" id="id_lamacuti" name="lamacuti" class="form-control readtext"
									value="0">
							</div>
							<label class="col-md-7 col-sm-7 col-xs-12" style="margin-top:7px;margin-bottom:5px;">Hari
								Kerja</label>
							<input type="hidden" name="satuan" value="HARI KERJA" />
						</div>
						<div class="form-group" id="id_group_sisacuti">
							<label class="control-label col-md-2 col-sm-2 col-xs-12"
								style="padding-left:0px;padding-right:5px;">Sisa Cuti</label>
							<div class="col-md-3 col-sm-3 col-xs-12">
								<input type="text" id="id_sisacuti" name="sisacuti" class="form-control readtext"
									value="0">
							</div>
						</div>
						<div id="id_group_keterangan" class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Alasan</label>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<textarea id="id_keterangan" name="alasancuti" class="form-control" rows="2"></textarea>
							</div>
						</div>
					</div>
				</form>
				<div class="alert alert-success" id="id_popup_alert" role="alert" style="display:none;">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
							aria-hidden="true">x</span></button>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button id="id_addcuti" type="button" class="btn btn-primary">Save</button>
			</div>
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
				Apakah anda yakin akan <span id="myText"></span> cuti ini ?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
				<button id="id_confirm_yes" type="button" class="btn btn-primary"
					onclick="this.disabled=true;this.value='Mengirim, harap tunggu...';this.form.submit();">Ya</button>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view("v_wincaripegawai.php"); ?>

<script type="text/javascript">
	$(function () {
		var availabledates = jQuery.parseJSON('<?= $vharilibur; ?>');
		var formcuti = $('#id_formcuti');
		formcuti[0].reset();

		formcuti.validate({
			onfocusout: false,
			rules: {
				hp: {
					required: true
				},
				rekannik: {
					required: true
				},
			},
			highlight: function (element) {
				$(element).closest('.form-group').addClass('has-error');
			},
			unhighlight: function (element) {
				$(element).closest('.form-group').removeClass('has-error');
			},
			errorElement: 'span',
			errorClass: 'help-block',
			errorPlacement: function (error, element) {
				if (element.parent('.input-group').length) {
					error.insertAfter(element.parent());
				} else {
					error.insertAfter(element);
				}
			},

			messages: {
				hp: {
					required: "Harap mengisi nomor HP"
				},
				rekannik: {
					required: "Harap mengisi pelimpahan tanggung jawab"
				},
			}
		});


		$.fn.addPengajuanCuti = function () {
			$('#id_formcontent').validate({
				onfocusout: false,
				rules: {
					tglawal: {
						required: true
					},
					tglakhir: {
						required: true
					},
					jeniscuti: {
						required: true
					},
					alasancuti: {
						required: true
					},
					detailjeniscuti: {
						required: true
					},
				},
				highlight: function (element) {
					$(element).closest('.form-group').addClass('has-error');
				},
				unhighlight: function (element) {
					$(element).closest('.form-group').removeClass('has-error');
				},
				errorElement: 'span',
				errorClass: 'help-block',
				errorPlacement: function (error, element) {
					if (element.parent('.input-group').length) {
						error.insertAfter(element.parent());
					} else {
						error.insertAfter(element);
					}
				},

				messages: {
					tglawal: {
						required: "Harap mengisi tanggal awal cuti"
					},
					tglakhir: {
						required: "Harap mengisi tanggal akhir cuti"
					},
					jeniscuti: {
						required: "Harap mengisi jenis cuti"
					},
					alasancuti: {
						required: "Harap mengisi alasan cuti"
					},
					detailjeniscuti: {
						required: "Harap mengisi alasan cuti"
					},
				}
			});


			$.fn.detailJenisCuti = function (jeniscutiid) {
				if (jeniscutiid != '0') {
					$.ajax({
						url: SITE_URL + '/pengajuan/getDetailJenisCuti',
						type: 'POST',
						data: {
							jeniscutiid: jeniscutiid
						},
						success: function (response) {
							var obj = jQuery.parseJSON(response);
							if (obj.success) {
								$('#id_detailjeniscutiid').val(obj.data[0].id);
							}
						}
					});
				}
			};

			$('#id_jeniscuti').on('change', function (e) {
				e.preventDefault();
				e.stopPropagation();

				var selected = $(this).find("option:selected").val();
				if (selected == '1') {
					$('#id_group_sisacuti').show();
					$('#id_group_detailjeniscuti').hide();
					$('#id_group_keterangan').show();
					$('#id_group_jatahcutikhusus').hide();
					$('#id_kerja').hide();
					$('#id_tglperdin').hide();
					$('#id_tglcuti').show();
					$.fn.detailJenisCuti(selected);
					$('#id_spanjatahcutikhusus').text(0);
				} else if (selected == '3') {
					$('#id_group_sisacuti').hide();
					$('#id_group_detailjeniscuti').show();
					$('#id_group_keterangan').hide();
					$('#id_kerja').hide();
					$('#id_tglperdin').hide();
					$('#id_tglcuti').show();
					$('#id_group_jatahcutikhusus').show();
					$('#id_spanjatahcutikhusus').text(0);
					$.ajax({
						url: SITE_URL + '/pengajuan/getDetailJenisCuti',
						type: 'POST',
						data: {
							jeniscutiid: selected
						},
						success: function (response) {
							var obj = jQuery.parseJSON(response);
							if (obj.success) {
								var html = '';
								html += '<option value="">Pilih Alasan</option>';
								$.each(obj.data, function (index, value) {
									html += '<option value="' + value.id + '" data-jatahcuti="' + value.jatahcuti + '">' + value.text + '</option>';
								});
								$('#id_detailjeniscuti').html(html);
							}
						}
					});
				} else if (selected == '7' || selected == '8') {
					if (selected == '7') {
						$('#id_group_sisacuti').hide();
						$('#id_group_detailjeniscuti').hide();
						$('#id_group_keterangan').show();
						$('#id_group_jatahcutikhusus').hide();
						$('#id_kerja').show();
						$('#id_tglperdin').show();
						$('#id_tglcuti').hide();
						$('#id_spanjatahcutikhusus').text(0);
						$.fn.detailJenisCuti(selected);
					} else {
						$('#id_group_sisacuti').hide();
						$('#id_group_detailjeniscuti').hide();
						$('#id_group_keterangan').show();
						$('#id_group_jatahcutikhusus').hide();
						$('#id_kerja').hide();
						$('#id_tglperdin').show();
						$('#id_tglcuti').hide();
						$('#id_spanjatahcutikhusus').text(0);
						$.fn.detailJenisCuti(selected);
					}
				} else {
					$('#id_group_sisacuti').hide();
					$('#id_group_detailjeniscuti').hide();
					$('#id_group_keterangan').show();
					$('#id_group_jatahcutikhusus').hide();
					$('#id_tglperdin').hide();
					$('#id_tglcuti').show();
					$('#id_kerja').hide();
					$('#id_spanjatahcutikhusus').text(0);
					$.fn.detailJenisCuti(selected);
				}

			});

			$('#id_detailjeniscuti').on('change', function (e) {
				e.preventDefault();
				e.stopPropagation();

				var selected = $(this).find("option:selected");
				$('#id_detailjeniscutiid').val(selected.val());
				$('#id_spanjatahcutikhusus').text(selected.attr('data-jatahcuti'));
			});

			$('#id_tglawal').datepicker({
				format: 'dd/mm/yyyy',
				todayHighlight: true,
				autoclose: true,
				toggleActive: true,
				beforeShowDay: function (date) {
					var nik = '<?= $vinfopegawai['nik']; ?>'
					var satker = '<?= $vinfopegawai['satkerid']; ?>'
					var curr_date = moment(date).format("YYYY-MM-DD");
					var numDays = moment(date).isoWeekday();
					var checked = $.inArray(curr_date, availabledates);

					if (checked == -1 && (numDays < 6)) {
						return true;
					} else {
						return false;
					}
				}
			}).on('changeDate', function (event) {
				var tglakhir = $("#id_tglakhir").datepicker('getDate');
				$.fn.hitungLamaCuti(event.date, tglakhir);
			});

			$('#id_tglakhir').datepicker({
				format: 'dd/mm/yyyy',
				todayHighlight: true,
				autoclose: true,
				toggleActive: true,
				beforeShowDay: function (date) {
					var nik = '<?= $vinfopegawai['nik']; ?>'
					var satker = '<?= $vinfopegawai['satkerid']; ?>'
					var curr_date = moment(date).format("YYYY-MM-DD");
					var numDays = moment(date).isoWeekday();
					var checked = $.inArray(curr_date, availabledates);

					if (checked == -1 && (numDays < 6)) {
						return true;
					} else {
						return false;
					}
				}
			}).on('changeDate', function (event) {
				var tglawal = $("#id_tglawal").datepicker('getDate');
				$.fn.hitungLamaCuti(tglawal, event.date);
			});

			$('#id_perdinawal').datepicker({
				format: 'dd/mm/yyyy',
				todayHighlight: true,
				autoclose: true,
				toggleActive: true,
				beforeShowDay: function (date) {
					var nik = '<?= $vinfopegawai['nik']; ?>'
					var satker = '<?= $vinfopegawai['satkerid']; ?>'
					var curr_date = moment(date).format("YYYY-MM-DD");
					var numDays = moment(date).isoWeekday();
					var checked = $.inArray(curr_date);

					if (checked == -1 && (numDays < 8)) {
						return true;
					} else {
						return false;
					}
				}
			}).on('changeDate', function (event) {
				var tglakhir = $("#id_perdinakhir").datepicker('getDate');
				$.fn.hitungLamaCuti(event.date, tglakhir);
			});

			$('#id_perdinakhir').datepicker({
				format: 'dd/mm/yyyy',
				todayHighlight: true,
				autoclose: true,
				toggleActive: true,
				beforeShowDay: function (date) {
					var nik = '<?= $vinfopegawai['nik']; ?>'
					var satker = '<?= $vinfopegawai['satkerid']; ?>'
					var curr_date = moment(date).format("YYYY-MM-DD");
					var numDays = moment(date).isoWeekday();
					var checked = $.inArray(curr_date);

					if (checked == -1 && (numDays < 8)) {
						return true;
					} else {
						return false;
					}
				}
			}).on('changeDate', function (event) {
				var tglawal = $("#id_perdinawal").datepicker('getDate');
				$.fn.hitungLamaCuti(tglawal, event.date);
			});

			$('#id_tglkerja').datepicker({
				format: 'dd/mm/yyyy',
				todayHighlight: true,
				autoclose: true,
				toggleActive: true,
				beforeShowDay: function (date) {
					var curr_date = moment(date).format("YYYY-MM-DD");
					var numDays = moment(date).isoWeekday();
					var checked = $.inArray(curr_date);

					if (checked == -1 && (numDays < 8)) {
						return true;
					} else {
						return false;
					}
				}
			}).on('changeDate', function (event) {
				var tglkerja = $("#id_tglkerja").datepicker('getDate');
			});

			$('#id_addcuti').on('click', function (e) {
				e.preventDefault();
				e.stopPropagation();

				var form = $('#id_formcontent');

				// Pergi Haji <Konfirmasi HRD Nik>
				var nik_cutihaji = '<?= $vinfopegawai['nik']; ?>';

				if (form.valid()) {
					var jeniscutiid = $('#id_jeniscuti option:selected').val();
					var jeniscutitext = $('#id_jeniscuti option:selected').text();
					var keterangan = $('#id_keterangan').val();
					var tglkerja = moment($("#id_tglkerja").datepicker('getDate')).format("DD/MM/YYYY");
					var lamacuti = parseInt($('#id_lamacuti').val());
					var sisacuti = '';
					var detailjeniscuti = $('#id_detailjeniscuti option:selected');
					var detailjeniscutiid = $('#id_detailjeniscutiid').val();
					var sisacutitahunini = parseInt($('#id_info_sisacuti1').val()) + parseInt($('#id_info_sisacuti2').val());
					var tglawal = '';
					var tglakhir = '';
					var jcuti = ['7', '8']
					if (jcuti.includes(jeniscutiid)) {
						tglawal = moment($("#id_perdinawal").datepicker('getDate')).format("DD/MM/YYYY");
						tglakhir = moment($("#id_perdinakhir").datepicker('getDate')).format("DD/MM/YYYY");
					} else {
						tglawal = moment($("#id_tglawal").datepicker('getDate')).format("DD/MM/YYYY");
						tglakhir = moment($("#id_tglakhir").datepicker('getDate')).format("DD/MM/YYYY");
					}

					var gridRows = $('#gridcontent > tbody > tr');
					var id = 1;
					if (gridRows.length > 0) {
						id = $('#gridcontent > tbody > tr')[gridRows.length - 1].getAttribute('data-id');
						id = parseInt(id) + 1;
					}

					// cek rentang tgl cuti apakah sudah diajukan atau belum
					$.when($.ajax({
						url: SITE_URL + '/pengajuan/cekPengajuanCuti',
						type: 'POST',
						data: {
							act: 'add',
							tglawal: tglawal,
							tglakhir: tglakhir,
							nourut: ''
						}
					}))
						.then(function (data, textStatus, jqXHR) {
							var obj = jQuery.parseJSON(data);
							if (obj.data > 0) {
								$('#id_popup_alert').html('Anda sudah pernah mengajukan direntang tanggal yang sama');
								$('#id_popup_alert').fadeIn();
								return;
							}

							// cuti tahunan
							if (jeniscutiid == '1') {
								var tglawal = moment($("#id_tglawal").datepicker('getDate')).format("DD/MM/YYYY");
								var tglakhir = moment($("#id_tglakhir").datepicker('getDate')).format("DD/MM/YYYY");
								if (lamacuti > sisacutitahunini) {
									$('#id_popup_alert').html('Maaf anda mengajukan cuti lebih dari sisa cuti tahun ini');
									$('#id_popup_alert').fadeIn();
									return;
								}
								sisacuti = $('#id_sisacuti').val();
								if (sisacuti < 0) {
									$('#id_popup_alert').html('Maaf anda mengajukan cuti lebih dari sisa cuti tahun ini');
									$('#id_popup_alert').fadeIn();
									return;
								}
							}

							// cuti Sakit
							if (jeniscutiid == '4') {
								var tglawal = moment($("#id_tglawal").datepicker('getDate')).format("DD/MM/YYYY");
								var tglakhir = moment($("#id_tglakhir").datepicker('getDate')).format("DD/MM/YYYY");
							}

							// cuti tanpa upah
							if (jeniscutiid == '2') {
								var tglawal = moment($("#id_tglawal").datepicker('getDate')).format("DD/MM/YYYY");
								var tglakhir = moment($("#id_tglakhir").datepicker('getDate')).format("DD/MM/YYYY");
								if (sisacutitahunini > 0) {
									$('#id_popup_alert').html('Maaf Anda masih mempunyai hak cuti tahunan');
									$('#id_popup_alert').fadeIn();
									return;
								}
							}

							// cuti khusus
							if (jeniscutiid == '3') {
								tglawal = moment($("#id_tglawal").datepicker('getDate')).format("DD/MM/YYYY");
								tglakhir = moment($("#id_tglakhir").datepicker('getDate')).format("DD/MM/YYYY");
								jatahcuti = detailjeniscuti.attr('data-jatahcuti');
								keterangan = detailjeniscuti.text();
								// Jika cuti khusus batasi dengan jatah cuti yang dipilih
								if (lamacuti > jatahcuti) {
									$('#id_popup_alert').html('Maaf pengajuan cuti anda melebihi jatah cuti yang ditentukan');
									$('#id_popup_alert').fadeIn();
									return;
								}
							}

							// cuti RO
							if (jeniscutiid == '7') {
								var tglcuti = moment($("#id_perdinawal").datepicker('getDate')).format("YYYYMMDD");
								var logkerja = moment($("#id_tglkerja").datepicker('getDate')).format("YYYYMMDD");
								var ketkerja = moment($("#id_tglkerja").datepicker('getDate')).format("DD-MM-YYYY");
								var kerja = $("#id_tglkerja").datepicker('getDate');
								var milidate = kerja.setMonth(kerja.getMonth() + 1);
								var date2 = new Date(milidate);
								var batasdate = moment(date2).format("YYYYMMDD");
								keterangan = "Pengganti Tugas Tanggal " + ketkerja + ", " + $('#id_keterangan').val();
								var tglawal = moment($("#id_perdinawal").datepicker('getDate')).format("DD/MM/YYYY");
								var tglakhir = moment($("#id_perdinakhir").datepicker('getDate')).format("DD/MM/YYYY");
								jatahcuti = 1;
								if (lamacuti > jatahcuti) {
									$('#id_popup_alert').html('Maaf pengajuan cuti anda melebihi jatah cuti yang ditentukan');
									$('#id_popup_alert').fadeIn();
									return;
								}
								if (logkerja >= tglcuti) {
									$('#id_popup_alert').html('Anda tidak bisa mengajukan RO sebelum tgl kerja');
									$('#id_popup_alert').fadeIn();
									return;
								}
								if (tglcuti > batasdate) {
									$('#id_popup_alert').html('Anda tidak bisa mengajukan RO setelah lewat 30 hari dari tgl kerja');
									$('#id_popup_alert').fadeIn();
									return;
								}
							}

							// cuti Perdin
							if (jeniscutiid == '8') {
								tglawal = moment($("#id_perdinawal").datepicker('getDate')).format("DD/MM/YYYY");
								tglakhir = moment($("#id_perdinakhir").datepicker('getDate')).format("DD/MM/YYYY");
								jatahcuti = detailjeniscuti.attr('data-jatahcuti');
								if (lamacuti > jatahcuti) {
									$('#id_popup_alert').html('Maaf pengajuan cuti anda melebihi jatah cuti yang ditentukan');
									$('#id_popup_alert').fadeIn();
									return;
								}
							}

							// cuti lainnya
							if (jeniscutiid == '5') {
								jatahcuti = 40; //40 Hari
								jatahcuti = detailjeniscuti.attr('data-jatahcuti');
								keterangan = detailjeniscuti.text();

								// Pergi Haji <Konfirmasi HRD Nik>
								if (nik_cutihaji == '706058') {
									if (lamacuti > jatahcuti) {
										$('#id_popup_alert').html('Maaf pengajuan cuti anda melebihi jatah cuti yang ditentukan');
										$('#id_popup_alert').fadeIn();
										return;
									}
								} else {
									$('#id_popup_alert').html('Maaf Cuti Lainnya Hanya Untuk Ibadah Haji, Harap Konfirmasi Kepada HRD Terlebih Dahulu.');
									$('#id_popup_alert').fadeIn();
									return;
								}
							}

							$('#gridcontent > tbody').append('<tr data-id="' + id + '" data-jeniscutiid="' + jeniscutiid + '" data-detailjeniscutiid="' + detailjeniscutiid + '" data-tglawal="' + tglawal + '" data-tglakhir="' + tglakhir + '" data-sisacuti="' + sisacuti + '" data-lamacuti="' + lamacuti + '" data-keterangan="' + keterangan + '"><td>' + id + '</td><td>' + jeniscutitext + '</td><td>' + keterangan + '</td><td>' + tglawal + '</td><td>' + tglakhir + '</td><td>' + lamacuti + '</td><td>' + sisacuti + '</td><td><a onclick=$(this).delCuti("' + id + '") class="btn btn-danger btn-xs" aria-label="Left Align" data-toggle="tooltip" data-placement="bottom" title="Hapus"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td></tr>');
							$('#id_win_content').modal('hide');
						});
				}
			});

		};

		$.fn.delCuti = function (id) {
			$('#gridcontent tr[data-id=' + id + ']').remove();
		};

		$.fn.hitungLamaCuti = function (tglawal, tglakhir) {
			if (tglawal != null && tglakhir != null) {
				var nik = '<?= $vinfopegawai['nik']; ?>'
				var satker = '<?= $vinfopegawai['satkerid']; ?>'
				var jeniscutiid = $('#id_jeniscuti option:selected').val();
				if (moment(tglawal) > moment(tglakhir)) {
					$('#id_tglakhir').datepicker("setDate", null);
					return;
				}
				$.ajax({
					url: SITE_URL + '/pengajuan/hitungLamaCuti',
					type: 'POST',
					data: {
						tglawal: moment(tglawal).format("YYYY-MM-DD"),
						tglakhir: moment(tglakhir).format("YYYY-MM-DD"),
					},
					success: function (response) {
						var obj = jQuery.parseJSON(response);
						if (obj.success) {
							$('#id_lamacuti').val(obj.data);

							if (jeniscutiid == '1') {
								$.fn.hitungSisaCuti(obj.data);
							}
						}
					}
				});

			}
		};

		$.fn.hitungSisaCuti = function (lamacuti) {
			var hakcuti = $('#id_info_jatahcuti').val();
			var sisacuti2 = parseInt($('#id_info_sisacuti1').val()) + parseInt($('#id_info_sisacuti2').val());
			var gridRows = $('#gridcontent > tbody > tr');
			var totsisacuti = 0;

			console.log(parseInt($('#id_info_sisacuti1').val()));

			totsisacuti = parseInt(sisacuti2) - parseInt(lamacuti);
			if (gridRows.length > 0) {
				var total = 0;
				$('#gridcontent > tbody').find('tr').each(function () {
					var jeniscutiid = $(this).data('jeniscutiid');
					if (jeniscutiid == '1') {
						total++;
					}
				});
				if (total > 0) {
					var lastSisaCuti = gridRows[total - 1].getAttribute('data-sisacuti');
					totsisacuti = parseInt(lastSisaCuti) - parseInt(lamacuti);
				}
			}

			$('#id_sisacuti').val(totsisacuti);
		};

		$.fn.simpanCuti = function (act) {
			var formcuti = $("#id_formcuti");
			var formData = new FormData(formcuti[0]);

			var data = [];
			if (formcuti.valid()) {
				var countExclTahunan = 0;
				var mandatoryFiles = ['3', '4', '5'];

				$('#gridcontent > tbody').find('tr').each(function () {
					var temp = {};
					var jeniscutiid = String($(this).data('jeniscutiid'));
					var keterangan = $(this).data('keterangan');
					if (jeniscutiid == '3') {
						keterangan = null;
					}
					temp = {
						jeniscutiid: jeniscutiid,
						detailjeniscutiid: $(this).data('detailjeniscutiid'),
						tglawal: $(this).data('tglawal'),
						tglakhir: $(this).data('tglakhir'),
						lamacuti: $(this).data('lamacuti'),
						sisacuti: $(this).data('sisacuti'),
						keterangan: keterangan,
					};
					if ($.inArray(jeniscutiid, mandatoryFiles) != -1) countExclTahunan++;

					data.push(temp);
				});

				if (data.length == 0) {
					return;
				}

				if (countExclTahunan > 0) {
					var file = $('#file_upload').val();
					if (file.length == 0) {
						new PNotify({
							title: 'Informasi',
							text: 'Anda harus melampirkan file',
							type: 'success',
							delay: 3000,
							styling: 'bootstrap3'
						});
						return;
					}
				}

				formData.append('daftarcuti', JSON.stringify(data));
				formData.append('act', act);
				$('#id_winconfirm').modal('show');
				$('#id_confirm_yes').one('click', function () {
					$.ajax({
						url: SITE_URL + '/pengajuan/simpancuti',
						type: 'POST',
						data: formData,
						async: false,
						cache: false,
						contentType: false,
						enctype: 'multipart/form-data',
						processData: false,
						beforeSend: function () {
							$("#loading-image").show(0).delay(15000).hide(0);
							window.location = '<?= site_url(); ?>' + '/history';
						},
						success: function (response) {
							var obj = jQuery.parseJSON(response);
							if (obj.success) {
								$("#loading-image").hide();
								$('#id_winconfirm').modal('hide');

								window.location = '<?= site_url(); ?>' + '/history';
							}
						}
					});
				});
			}
		};

		$('#id_fieldpelimpahan').on('focus', function (e) {
			e.preventDefault();
			e.stopPropagation();

			$('#id_win_caripegawai').modal('show');
			$('#id_win_caripegawai').on('selected', function (event, record) {
				$('#id_fieldpelimpahanid').val(record.pegawaiid);
				$('#id_fieldpelimpahan').val(record.nik);
				$('#id_fieldpelimpahannama').val(record.nama);
				$('#id_fieldpelimpahanposisi').val(record.jabatan);
				$('#id_fieldpelimpahandivisi').val(record.unitkerja);
				$('#id_fieldpelimpahanlokasi').val(record.lokasi);
				$('#id_fieldpelimpahanhp').val(record.hp);
				$('#id_fieldpelimpahanunit').val(record.unit);
			});

		});

		$.fn.addPengajuanCuti();
		$('#id_tambah').on('click', function (e) {
			e.preventDefault();
			e.stopPropagation();

			$('#id_win_content').modal('show');
			$('#id_win_content').on('shown.bs.modal', function () {
				$('#id_formcontent')[0].reset();
				$('#id_jeniscuti').val('0');
				$('#id_group_sisacuti').hide();
				$('#id_popup_alert').hide();
				$('#id_popup_alert').html('');
				$('#id_group_detailjeniscuti').hide();
				$('#id_group_jatahcutikhusus').hide();
				$('#id_spanjatahcutikhusus').text(0);
				$('#id_tglawal').datepicker('setDate', null);
				$('#id_tglakhir').datepicker('setDate', null);
			});

		});

		$('#id_ajukancuti').on('click', function (e) {
			e.preventDefault();
			e.stopPropagation();

			$("#myText").text("mengajukan");
			$.fn.simpanCuti('ajukan');
		});


	});
</script>