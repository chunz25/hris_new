<div class="page-title">
	<div class="title_left">
		<h3>Datang Telat / Pulang Cepat</h3>
	</div>
</div>
<div class="clearfix"></div>
<div class="row center-cont">
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
									<span class="form-control readtext"><?= $vinfopegawai['jabatanname']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Lokasi</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['lokasiname']; ?></span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Unit Bisnis</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['unitkerja']; ?></span>
								</div>
							</div>
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
						</div>
						<div class="col-md-4">
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
					</div>
				</div>
				<div class="x_panel">
					<div class="x_content">
						<div class="pull-right">
							<button type="button" id="id_tambah" class="btn btn-success"
								aria-label="Left Align">Tambah</button>
						</div>
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
										<span id="id_fieldatasan2nama" class="form-control readtext">
											<?= $vinfoatasan['atasannama']; ?>
										</span>
										<input id="id_fieldatasan2id" type="hidden" name="atasanid"
											value="<?= $vinfoatasan['atasanid']; ?>">
										<input id="id_fieldatasan2email" type="hidden" name="atasanemail"
											value="<?= $vinfoatasan['atasanemail']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12">Jabatan</label>
									<div class="col-md-10 col-sm-10 col-xs-12">
										<span id="id_fieldatasan2posisi" class="form-control readtext">
											<?= $vinfoatasan['atasanjab']; ?>
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
			<button type="button" id="id_ajukancuti" class="btn btn-success" aria-label="Left Align">Ajukan</button>
		</div>
	</div>
</div>

<!-- modalPengajuan -->
<div id="id_win_content" class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel2">Form Datang Telat / Pulang Cepat</h4>
			</div>
			<div class="modal-body">
				<form id="id_formcontent" class="form-horizontal form-label-left" action="./" method="POST"
					enctype="multipart/form-data">
					<div class="row">
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Jenis Form</label>
							<div class="col-md-10 col-sm-10 col-xs-12" style="padding-left:10px;">
								<select id="id_jeniscuti" class="form-control" name="jeniscuti">
									<option value="1">Terlambat Datang</option>
									<option value="2">Pulang Cepat</option>
								</select>
							</div>
						</div>

						<div class="form-group" id="id_group_jam_telat" style="display:none;">
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Tanggal</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div id="id_tglawal" class="input-group date">
										<input type="text" name="tglawal" class="form-control" readonly="true"><span
											class="input-group-addon"><i
												class="glyphicon glyphicon-calendar"></i></span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Jam</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div id="id_tglawal_jam" class="input-group date">
										<input id="setTimeExample1" name="jam" type="text"
											class="time ui-timepicker-input form-control" autocomplete="off"><span
											class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group" id="id_group_jam_cepat" style="display:none;">
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Tanggal</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div id="id_tglakhir" class="input-group date">
										<input type="text" name="tglawal" class="form-control" readonly="true"><span
											class="input-group-addon"><i
												class="glyphicon glyphicon-calendar"></i></span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Jam</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div id="id_tglakhir_jam" class="input-group date">
										<input id="setTimeExample" name="jam" type="text"
											class="time ui-timepicker-input form-control" autocomplete="off"><span
											class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
									</div>
								</div>
							</div>
						</div>
						<div id="id_group_keterangan" class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Keterangan</label>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<textarea id="id_keterangan" name="keterangan" class="form-control" rows="3"></textarea>
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
<!-- /modalPengajuan -->

<!-- modalSetuju -->
<div id="id_winconfirm" class="modal fade hapus-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
	<img id="loading-image" style="display:none;" />
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">x</span></button>
				<h4 class="modal-title">Informasi</h4>
			</div>
			<div class="modal-body">
				Apakah anda akan mengajukan form absensi kehadiran ini ?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
				<button id="id_confirm_yes" type="button" class="btn btn-primary"
					onclick="this.disabled=true;this.value='Mengirim, harap tunggu...';this.form.submit();">Ya</button>
			</div>
		</div>
	</div>
</div>
<!-- /modalSetuju -->

<?php $this->load->view("v_wincaripegawai.php"); ?>

<!-- Include jQuery -->

<script type="text/javascript">
	$(function () {
		var availabledates = jQuery.parseJSON('<?= $vharilibur; ?>');
		var formcuti = $('#id_formcuti');
		var nik = jQuery.parseJSON('<?= $vopendate; ?>');
		var idpeg = `<?= $vinfopegawai['nik']; ?>`;
		formcuti[0].reset();

		$.fn.addPengajuanCuti = function () {
			$('#id_formcontent').validate({
				onfocusout: false,
				rules: {
					jeniscuti: {
						required: true
					},
					tglawal: {
						required: true
					},
					jam: {
						required: true
					},
					keterangan: {
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
					jeniscuti: {
						required: "Harap mengisi jenis form kehadiran"
					},
					tglawal: {
						required: "Harap mengisi tanggal pada form kehadiran"
					},
					jam: {
						required: "Harap mengisi waktu pada form kehadiran"
					},
					keterangan: {
						required: "Harap mengisi keterangan pada form kehadiran"
					},
				}
			});

			$('.timepicker').timepicker({
				timeFormat: 'HH:mm',
				interval: 1,
				defaultTime: 'now',
				dropdown: true,
				scrollbar: true,
				zindex: 9999999
			});

			$('#setTimeExample1').timepicker({
				timeFormat: 'HH:mm',
				defaultTime: 'now',
				interval: 1,
				dropdown: true,
				scrollbar: true,
				zindex: 9999999
			});

			$('#setTimeExample').timepicker({
				timeFormat: 'HH:mm',
				defaultTime: 'now',
				dropdown: true,
				interval: 1,
				scrollbar: true,
				zindex: 9999999
			});


			$('#id_tglawal').datepicker({
				format: 'dd/mm/yyyy',
				todayHighlight: true,
				autoclose: true,
				toggleActive: true,
				beforeShowDay: function (date) {
					var mindate = moment().add(-1, 'd').format("YYYY-MM-DD");
					var curr_date = moment(date).format("YYYY-MM-DD");
					var numDays = moment(date).isoWeekday();
					var checked = $.inArray(curr_date, availabledates);
					if (nik.includes(idpeg)) {
						return true;
					} else {
						if ((curr_date > mindate) && (checked == -1 && (numDays < 8))) {
							return true;
						} else {
							return false;
						}
					}
				}
			}).on('changeDate', function (event) {
				var tglakhir = $("#id_tglakhir").datepicker('getDate');
			});

			$('#id_tglakhir').datepicker({
				format: 'dd/mm/yyyy',
				todayHighlight: true,
				autoclose: true,
				toggleActive: true,
				beforeShowDay: function (date) {
					var curr_date = moment(date).format("YYYY-MM-DD");
					var numDays = moment(date).isoWeekday();
					var checked = $.inArray(curr_date, availabledates);
					if (checked == -1 && (numDays < 8)) {
						return true;
					} else {
						return false;
					}
				}
			}).on('changeDate', function (event) {
				var tglawal = $("#id_tglawal").datepicker('getDate');
			});


			$('#id_jeniscuti').on('change', function (e) {
				e.preventDefault();
				e.stopPropagation();

				var selected = $(this).find("option:selected").val();
				if (selected == '1') {
					$('#id_group_jam_telat').show();
					$('#id_group_jam_cepat').hide();
				} else if (selected == '2') {
					$('#id_group_jam_telat').hide();
					$('#id_group_jam_cepat').show();
				} else {
					$('#id_group_jam_telat').hide();
					$('#id_group_jam_cepat').hide();
				}

			});

			$('#id_addcuti').on('click', function (e) {
				e.preventDefault();
				e.stopPropagation();

				var form = $('#id_formcontent');

				if (form.valid()) {
					var jeniscutiid = $('#id_jeniscuti option:selected').val();
					var jeniscutitext = $('#id_jeniscuti option:selected').text();
					var tglawal = '';
					if ($("#id_tglawal").find("input").val() == '') {
						var tglawal = $("#id_tglakhir").find("input").val();
					} else {
						var tglawal = $("#id_tglawal").find("input").val();
					}

					var jamawal = '';
					if (jeniscutiid == '1') {
						var jamawal = $("#id_tglawal_jam").find("input").val();
					} else {
						var jamawal = $("#id_tglakhir_jam").find("input").val();
					}

					var keterangan = $('#id_keterangan').val();
					var gridRows = $('#gridcontent > tbody > tr');
					var id = 1;
					if (gridRows.length > 0) {
						id = $('#gridcontent > tbody > tr')[gridRows.length - 1].getAttribute('data-id');
						id = parseInt(id) + 1;
					}

					$.when($.ajax({
						url: SITE_URL + '/pengajuan/cekPengajuanKehadiran',
						type: 'POST',
						data: {
							act: 'add',
							jenisid: jeniscutiid,
							tglawal: tglawal,
							nourut: ''
						}
					}))
						.then(function (data, textStatus, jqXHR) {
							var obj = jQuery.parseJSON(data);
							var nowdate = new Date();
							var currdate = moment(nowdate).format("DD/MM/YYYY");
							if (obj.data > 0) {
								$('#id_popup_alert').html('Anda sudah pernah mengajukan direntang tanggal yang sama');
								$('#id_popup_alert').fadeIn();
								return;

							} if (tglawal < currdate && !nik.includes(idpeg)) {
								$('#id_popup_alert').html('Tidak boleh mengajukan Back Date !');
								$('#id_popup_alert').fadeIn();
								return;
							}
							$('#gridcontent > tbody').append('<tr data-id="' + id + '" data-jeniscutiid="' + jeniscutiid + '" data-tglawal="' + tglawal + '" data-jamawal="' + jamawal + '" data-keterangan="' + keterangan + '"><td>' + id + '</td><td>' + jeniscutitext + '</td><td>' + tglawal + '</td><td>' + jamawal + '</td><td>' + keterangan + '</td><td><a onclick=$(this).delCuti("' + id + '") class="btn btn-danger btn-xs" aria-label="Left Align" data-toggle="tooltip" data-placement="bottom" title="Hapus"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td></tr>');
							$('#id_win_content').modal('hide');
						});
				}
			});

		};

		$.fn.delCuti = function (id) {
			$('#gridcontent tr[data-id=' + id + ']').remove();
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
					temp = {
						jeniscutiid: jeniscutiid,
						tglawal: $(this).data('tglawal'),
						jamawal: $(this).data('jamawal'),
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
						url: SITE_URL + '/pengajuan/simpanabsensi',
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
								$("#id_confirm_yes").attr("disabled", true);
								window.location = '<?= site_url(); ?>' + '/history';
							}
						}
					});
				});
			}
		};

		$.fn.addPengajuanCuti();
		$('#id_tambah').on('click', function (e) {
			e.preventDefault();
			e.stopPropagation();

			$('#id_win_content').modal('show');
			$('#id_win_content').on('shown.bs.modal', function () {
				$('#id_jeniscuti').val('0');
				$('#id_group_sisacuti').hide();
				$('#id_group_jam_telat').hide();
				$('#id_group_jam_cepat').hide();
				$('#id_group_porsicuti').hide();
				$('#id_group_cutisethari').hide();
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
			$.fn.simpanCuti('ajukan');
		});

	});
</script>