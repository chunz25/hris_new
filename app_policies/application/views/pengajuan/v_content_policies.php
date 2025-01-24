<div class="page-title">
	<div class="title_left">
		<h3>Form Input Informasi</h3>
	</div>
</div>
<div class="clearfix"></div>
<div class="row center-cont">
	<?= form_open_multipart('policies/upload', 'id="id_formkontrak"'); ?>
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
										<?php if ($r['id'] == '1'): ?>
											<?php $selected = 'selected'; ?>
										<?php endif; ?>
										<option value="<?= $r['id']; ?>" <?= $selected; ?>><?= $r['text']; ?></option>
									<?php endforeach; ?>
								</select>
								<br>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Judul Dokumen</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<textarea class="form-control" name="deskripsi" rows="5" placeholder="Judul Dokumen"
									maxlength="255"></textarea><br>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Dokumen</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select class="form-control" name="jenisform">
									<option value="1">Kebijakan dan Peraturan Perusahaan </option>
									<option value="2">Template Form </option>
								</select>
								<br>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Unit Kerja</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select id="id_jeniscuti" class="form-control" name="status">
									<option value="1">SEMUA</option>
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
										<option value="<?= $r['id']; ?>" <?= $selected; ?>><?= $r['text']; ?></option>
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
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Lokasi</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select class="form-control" name="lokasi" value="<?= $info['lokasi']; ?>">
									<option value="1">ECI HEAD OFFICE</option>
									<option value="2">STORE & DC</option>
									<option value="3">SEMUA</option>
								</select>
								<br>
							</div>
						</div>

						<div>
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Lampiran</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="file" id="file_upload" name="files" class="filestyle" data-icon="false"
									accept="application/pdf" autofocus>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row" style="padding-left:5px;padding-right:5px;">
		<div class="pull-right">
			<button type="button" id="id_ajukancuti" class="btn btn-success" aria-label="Left Align">Simpan</button>
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
					Apakah anda yakin akan mengunduh lampiran ini ?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
					<button id="id_confirm_yes" type="button" class="btn btn-primary"
						onclick="this.disabled=true;this.value='Mengirim, harap tunggu...';this.form.submit();">Ya</button>
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
				nama: { required: true },
				deskripsi: { required: true },
				status: { required: true },
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
				}
				else {
					error.insertAfter(element);
				}
			},

			messages: {
				nama: { required: "Harap mengisi nama dokumen" },
				deskripsi: { required: "Harap mengisi deskripsi dokumen" },
				status: { required: "Harap memilih jenis dokumen" },
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
							data: { jeniscutiid: selected },
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

		$.fn.simpanKontrak = function (act) {
			var formkontrak = $("#id_formkontrak");
			var formData = new FormData(formkontrak[0]);

			var data = [];
			if (formkontrak.valid()) {
				var file = $('#file_upload').val();
				if (file.length == 0) {
					new PNotify({
						title: 'Informasi',
						text: 'Anda harus melampirkan file',
						type: 'success', delay: 3000,
						styling: 'bootstrap3'
					});
					return;
				}

				formData.append('daftarkontrak', JSON.stringify(data));
				$('#id_winconfirm').modal('show');
				$('#id_confirm_yes').one('click', function () {
					$("#loading-image").hide();
					$("#id_winconfirm").modal('hide');
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