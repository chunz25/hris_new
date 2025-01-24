<div class="page-title">
	<div class="title_left">
		<?php if ($info['nama'] == '5') { ?>
			<h3>Template Form</h3>
		<?php } else { ?>
			<h3>Kebijakan dan Peraturan Perusahaan</h3>
		<?php } ?>

	</div>
</div>
<div class="clearfix"></div>
<div class="row center-cont">
	<?php echo form_open_multipart('policies/upload', 'id="id_formkontrak"'); ?>
	<!--untuk mendeklarasikan update-->
	<input type="hidden" name="hidden" value="update">
	<input id="id_filesold" type="hidden" name="filesold" value="<?php echo $info['files']; ?>" />
	<input type="hidden" name="satkerold" value="<?php echo $info['satkerid']; ?>" />
	<input type="hidden" name="statusold" value="<?php echo $info['status']; ?>" />
	<input type="hidden" name="lokasiold" value="<?php echo $info['lokasiid']; ?>" />
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Dokumen</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select id="id_namadokumen" class="form-control" name="nama" autofocus>
									<?php foreach ($vnamadokumen as $r): ?>
										<?php $selected = ''; ?>
										<?php if ($r['id'] == $info['nama']): ?>
											<?php $selected = 'selected'; ?>
										<?php endif; ?>
										<option value="<?php echo $r['id']; ?>" <?php echo $selected; ?>>
											<?php echo $r['text']; ?></option>
									<?php endforeach; ?>
								</select>
								<br>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Judul Dokumen</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<textarea class="form-control" name="deskripsi" rows="5" placeholder="Deskripsi Dokumen"
									maxlength="255"><?php echo $info['deskripsi']; ?></textarea><br>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Dokumen</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select class="form-control" name="jenisform" value="<?php $x = $info['jenisform']; ?>">
									<?php if ($x == '1') { ?>
										<option value="1">Kebijakan dan Peraturan Perusahaan </option>
										<option value="2">Template Form </option>
									<?php } else { ?>
										<option value="2">Template Form </option>
										<option value="1">Kebijakan dan Peraturan Perusahaan </option>
									<?php } ?>
								</select>
								<br>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Unit Kerja</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select id="id_jeniscuti" class="form-control" name="status"
									value="<?php echo $info['status']; ?>">
									<option></option>
									<option value="1">Semua</option>
									<option value="2">Divisi / Departement </option>
								</select>
								<br>
							</div>
						</div>

						<div id="id_group_divisi" class="form-group" style="display:none;">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Divisi</label>
							<div class="col-md-9 col-sm-9 col-xs-12" style="padding-left:10px;">
								<select id="id_jenisdivisi" class="form-control" name="divisi" autofocus>
									<?php foreach ($vsatker as $r): ?>
										<?php $selected = ''; ?>
										<?php if ($r['id'] == '0101'): ?>
											<?php $selected = 'selected'; ?>
										<?php endif; ?>
										<option value="<?php echo $r['id']; ?>" <?php echo $selected; ?>>
											<?php echo $r['text']; ?></option>
									<?php endforeach; ?>
								</select>
								<br>
							</div>
						</div>

						<div id="id_group_departemen" class="form-group" style="display:none;">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
							<div class="col-md-9 col-sm-9 col-xs-12" style="padding-left:10px;">
								<select id="id_departemen" class="form-control" name="departemen"></select>
								<br>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<?php if ($info['status'] == "1") { ?>
									<span class="form-control readtext">Semua</span>
								<?php } else { ?>
									<?php if ($info['divisi'] == "") { ?>
										<span class="form-control readtext"><?php echo $info['direktorat']; ?></span>
									<?php } else { ?>
										<span class="form-control readtext"><?php echo $info['direktorat']; ?> -
											<?php echo $info['divisi']; ?></span>
									<?php } ?>
								<?php } ?>
								<br>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Lokasi</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select class="form-control" name="lokasi" value="<?php echo $info['lokasi']; ?>">
									<option></option>
									<option value="1">ECI HEAD OFFICE</option>
									<option value="2">STORE & DC</option>
									<option value="3">Semua</option>
								</select>
								<br>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<span class="form-control readtext"><?php echo $info['lokasi']; ?></span>
								<br>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Lampiran</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="file" name="files" class="filestyle" data-icon="false"
									accept="application/pdf" autofocus>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<?php if ($info['files'] != null) { ?>
									<div style="margin-left:-12px;">
										<span class="form-control readtext">
											<a href="download?filename=<?php echo $info['files']; ?>"
												class="btn btn-default btn-sm" aria-label="Left Align" data-toggle="tooltip"
												data-placement="bottom" title="Attach Files">
												<i class="fa fa-paperclip" aria-hidden="true">
													<?php echo $info['files']; ?></i>
											</a>
										</span>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row" style="padding-left:5px;padding-right:5px;">
		<div class="pull-right">
			<button type="button" name="update" id="id_ajukancuti" class="btn btn-success"
				aria-label="Left Align">Ubah</button>
			<!-- <button name="update" type="submit" class="btn btn-success" aria-label="Left Align" value="<?php echo $info['id']; ?>">Ubah</button> -->
		</div>
	</div>

	<div id="id_winconfirm" class="modal fade hapus-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">×</span></button>
					<h4 class="modal-title">Informasi</h4>
				</div>
				<div class="modal-body">
					Apakah anda yakin akan mengubah lampiran ini ?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
					<button name="update" id="id_confirm_yes" type="submit" class="btn btn-success"
						aria-label="Left Align" value="<?php echo $info['id']; ?>">Ya</button>
				</div>
			</div>
		</div>
	</div>

	</form>
</div>
<script type="text/javascript">
	$(function () {

		var formkontrak = $('#id_formkontrak');
		formkontrak[0].reset();

		formkontrak.validate({
			onfocusout: false,
			rules: {
				nama: {
					required: true
				},
				deskripsi: {
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
				nama: {
					required: "Harap mengisi nama dokumen"
				},
				deskripsi: {
					required: "Harap mengisi deskripsi dokumen"
				},
			}
		});

		$('#id_jeniscuti').on('change', function (e) {
			e.preventDefault();
			e.stopPropagation();
			var selected = $(this).find("option:selected").val();
			if (selected == '2') {
				$('#id_group_divisi').show();
				$('#id_jenisdivisi').on('change', function (e) {
					e.preventDefault();
					e.stopPropagation();
					var selected = $(this).find("option:selected").val();
					if (selected != '') {
						$.ajax({
							url: SITE_URL + '/policies/getComboDept',
							type: 'POST',
							data: {
								jeniscutiid: selected
							},
							success: function (response) {
								var obj = jQuery.parseJSON(response);
								if (obj.success) {
									if (selected == '0101') {
										$('#id_group_departemen').hide();
									} else {
										$('#id_group_departemen').show();
										var html = '';
										html += '<option value="">All</option>';
										$.each(obj.data, function (index, value) {
											html += '<option value="' + value.id + '">' + value.text + '</option>';
										});
										$('#id_departemen').html(html);
									}
								}
							}
						});
					} else {
						$('#id_group_departemen').hide();
					}
				});
			} else {
				$('#id_group_divisi').hide();
				$('#id_group_departemen').hide();
			}
		});

		$.fn.simpanKontrak = function () {
			var formkontrak = $("#id_formkontrak");
			var formData = new FormData(formkontrak[0]);

			var data = [];
			if (formkontrak.valid()) {
				formData.append('daftarkontrak', JSON.stringify(data));
				$('#id_winconfirm').modal('show');
				$('#id_confirm_yes').one('click', function () {
					$("#loading-image").hide();
					$('#id_winconfirm').modal('hide');
				});
			}
		};

		$('#id_ajukancuti').on('click', function (e) {
			e.preventDefault();
			e.stopPropagation();

			$("#myText").text("mengajukan");
			$.fn.simpanKontrak('ajukan');
		});

	});
</script>
<style>
	.div-checkbox {
		display: inline-block;
		vertical-align: middle;
	}

	.div-checkbox .boxcheck {
		width: 20px;
		display: inline-block;
		vertical-align: middle;
	}

	.div-checkbox .boxlabel {
		margin-top: 1px;
		display: inline-block;
		vertical-align: middle;
	}

	.div-checkbox .boxinput {
		margin-top: 1px;
		display: inline-block;
		vertical-align: middle;
	}

	.highlight_calender {
		background-color: #d90036 !important;
		color: #fff;
	}

	#loading-image {
		display: none;
		position: fixed;
		z-index: 1000;
		top: 0;
		left: 0;
		height: 100%;
		width: 100%;
		background: rgba(255, 255, 255, .8) url('<?php echo base_url(); ?>media/asset/images/loading-image.gif') 50% 50% no-repeat;
	}
</style>