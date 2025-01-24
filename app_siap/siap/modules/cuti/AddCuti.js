Ext.define("SIAP.modules.cuti.AddCuti", {
	extend: "Ext.form.Panel",
	alternateClassName: "SIAP.addcuti",
	alias: 'widget.addcuti',
	requires: [
		'SIAP.components.field.FieldPegawai',
		'SIAP.components.window.WinAllPegawai',
	],
	initComponent: function () {
		var me = this;

		Ext.apply(me, {
			layout: 'border',
			items: [
				{
					itemId: 'formcuti', xtype: 'form', region: 'center', autoScroll: true, bodyPadding: 10,
					listeners: {
						afterrender: function (p) {

						}
					},
					items: [
						{
							xtype: 'fieldset', title: 'Data Karyawan', collapsible: true,
							items: [
								{
									layout: 'column', baseCls: 'x-plain', border: false,
									items: [
										{
											xtype: 'panel',
											columnWidth: .5,
											bodyPadding: 10,
											layout: 'form',
											defaultType: 'displayfield',
											baseCls: 'x-plain',
											border: false,
											defaults: {
												labelWidth: 170,
											},
											items: [
												{ id: 'id_pegawaiid', xtype: 'hidden', name: 'pegawaiid' },
												{ xtype: 'textfield', fieldLabel: 'Tahun', name: 'tahun', value: moment().year() },
												{
													xtype: 'searchfield', fieldLabel: 'NIK', name: 'nik', anchor: '95%',
													listeners: {
														'enter': function (val, e) {
															Ext.Ajax.request({
																url: Settings.SITE_URL + '/cuti/getPegawaiByNIK',
																method: 'POST',
																params: {
																	nik: val,
																	tahun: me.down('#formcuti').getForm().findField('tahun').getValue()
																},
																success: function (response) {
																	var obj = Ext.decode(response.responseText);
																	if (obj.success) {
																		console.log(obj);
																		me.down('#formcuti').getForm().findField('pegawaiid').setValue(obj.data.pegawaiid);
																		me.down('#formcuti').getForm().findField('nama').setValue(obj.data.nama);
																		me.down('#formcuti').getForm().findField('jabatan').setValue(obj.data.jabatan);
																		me.down('#formcuti').getForm().findField('direktorat').setValue(obj.data.direktorat);
																		me.down('#formcuti').getForm().findField('divisi').setValue(obj.data.divisi);
																		me.down('#formcuti').getForm().findField('department').setValue(obj.data.departemen);
																		me.down('#formcuti').getForm().findField('seksi').setValue(obj.data.seksi);
																		me.down('#formcuti').getForm().findField('subseksi').setValue(obj.data.subseksi);
																		me.down('#formcuti').getForm().findField('lokasi').setValue(obj.data.lokasi);
																		me.down('#formcuti').getForm().findField('unitbisnis').setValue('ECI');
																		me.down('#formcuti').getForm().findField('sisacutithnini').setValue(obj.data.sisacutithnini);
																		me.down('#formcuti').getForm().findField('sisacutithnlalu').setValue(obj.data.sisacutithnlalu);
																		me.down('#formcuti').getForm().findField('atasanid').setValue(obj.data.verifikatorid);
																		me.down('#formcuti').getForm().findField('atasannama').setValue(obj.data.verifikatornama);
																		me.down('#formcuti').getForm().findField('atasanjabatan').setValue(obj.data.verifikatorjabatan);
																		me.down('#formcuti').getForm().findField('atasan2id').setValue(obj.data.approvalid);
																		me.down('#formcuti').getForm().findField('atasan2nama').setValue(obj.data.approvalnama);
																		me.down('#formcuti').getForm().findField('atasan2jabatan').setValue(obj.data.approvaljabatan);
																	}
																}
															});
														}
													}
												},
												{ xtype: 'displayfield', fieldLabel: 'Nama', name: 'nama', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Jabatan', name: 'jabatan', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Direktorat', name: 'direktorat', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Divisi', name: 'divisi', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Department', name: 'department', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Seksi', name: 'seksi', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Sub Seksi', name: 'subseksi', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Lokasi', name: 'lokasi', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Unit Bisnis', name: 'unitbisnis', anchor: '95%' },
												{ id: 'id_sisacutithnini', xtype: 'hidden', name: 'sisacutithnini' },
												{ id: 'id_sisacutithnlalu', xtype: 'hidden', name: 'sisacutithnlalu' },
											]
										}
									]
								}
							]
						},
						{
							xtype: 'fieldset', title: 'Kontak Selama Ketidakhadiran', collapsible: true,
							items: [
								{ xtype: 'textfield', fieldLabel: 'HP', name: 'hp', anchor: '30%' },
								{ xtype: 'filefield', fieldLabel: 'Lampiran', name: 'files', anchor: '30%' },
							]
						},
						{
							xtype: 'fieldset', title: 'Pengajuan Cuti', collapsible: true, style: 'padding: 5px 10px 5px 10px',
							items: [
								{ xtype: 'datefield', fieldLabel: 'Tgl Permohonan', format: 'd-m-Y', name: 'tglpermohonan' },
								{
									xtype: 'grid',
									store: Ext.create('Ext.data.Store', {
										storeId: 'storeDetailPengajuanID',
										model: 'storeDetailPengajuan',
										fields: [
											'jeniscutiid', 'jeniscuti', 'detailjeniscutiid', 'detailjeniscuti',
											'tglmulai', 'tglselesai', 'lama', 'satuan', 'sisacuti', 'alasancuti'
										],
									}),
									columns: [
										{ text: 'No', xtype: 'rownumberer', width: 30 },
										{ text: 'Jenis Cuti', dataIndex: 'jeniscuti' },
										{ text: 'Alasan', dataIndex: 'alasancuti', flex: 1 },
										{ text: 'Tanggal Awal', dataIndex: 'tglmulai' },
										{ text: 'Tanggal Akhir', dataIndex: 'tglselesai' },
										{ text: 'Lama Cuti', dataIndex: 'lama' },
										{ text: 'Sisa Cuti', dataIndex: 'sisacuti' }
									],
									tbar: ['->',
										{
											glyph: 'xf196@FontAwesome',
											handler: function () {
												me.winAddCuti();
											}
										}
									]
								}
							]
						},
						{
							xtype: 'fieldset', title: 'Pelimpahan Tanggung Jawab', collapsible: true,
							items: [
								{
									layout: 'column', baseCls: 'x-plain', border: false,
									items: [
										{
											xtype: 'panel',
											columnWidth: .5,
											bodyPadding: 10,
											layout: 'form',
											defaultType: 'displayfield',
											baseCls: 'x-plain',
											border: false,
											defaults: {
												labelWidth: 170,
											},
											items: [
												{ xtype: 'hidden', name: 'pelimpahanid' },
												{
													xtype: 'searchfield', fieldLabel: 'NIK', name: 'pelimpahannik', anchor: '95%',
													listeners: {
														search: function () {
															Ext.create('SIAP.components.window.WinAllPegawai', {
																width: 600, height: 400, bodyPadding: 5,
																listeners: {
																	pilih: function (records) {
																		console.log(records);
																		me.down('#formcuti').getForm().findField('pelimpahanid').setValue(records.data.pegawaiid);
																		me.down('#formcuti').getForm().findField('pelimpahannik').setValue(records.data.nik);
																		me.down('#formcuti').getForm().findField('pelimpahannama').setValue(records.data.nama);
																		me.down('#formcuti').getForm().findField('pelimpahanhp').setValue(records.data.hp);
																		me.down('#formcuti').getForm().findField('pelimpahanjabatan').setValue(records.data.jabatan);
																		me.down('#formcuti').getForm().findField('pelimpahansatker').setValue(records.data.divisi);
																		me.down('#formcuti').getForm().findField('pelimpahanlokasi').setValue(records.data.lokasi);
																		me.down('#formcuti').getForm().findField('pelimpahanunitbisnis').setValue('ECI');
																	}
																}
															});
														}
													}
												},
												{ xtype: 'displayfield', fieldLabel: 'Nama', name: 'pelimpahannama', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'HP', name: 'pelimpahanhp', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Jabatan', name: 'pelimpahanjabatan', anchor: '95%' },
											]
										},
										{
											xtype: 'panel',
											columnWidth: .5,
											bodyPadding: 10,
											layout: 'form',
											defaultType: 'displayfield',
											baseCls: 'x-plain',
											border: false,
											defaults: {
												labelWidth: 170,
											},
											items: [
												{ xtype: 'displayfield', fieldLabel: 'Divisi', name: 'pelimpahansatker', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Lokasi', name: 'pelimpahanlokasi', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Unit Bisnis', name: 'pelimpahanunitbisnis', anchor: '95%' },
											]
										}
									]
								}
							]
						},
						{
							xtype: 'panel', layout: 'column', border: false,
							items: [
								{
									xtype: 'fieldset', columnWidth: 0.5, title: 'Diperiksa oleh', collapsible: true, defaultType: 'displayfield', style: 'margin-right:5px;',
									defaults: { anchor: '100%' }, layout: 'anchor',
									items: [
										{ xtype: 'hidden', name: 'atasanid' },
										{ xtype: 'searchfield', fieldLabel: 'Nama', name: 'atasannama' },
										{ fieldLabel: 'Jabatan', name: 'atasanjabatan' }
									]
								},
								{
									xtype: 'fieldset', columnWidth: 0.5, title: 'Disetujui oleh', collapsible: true, defaultType: 'displayfield', style: 'margin-left:5px;',
									defaults: { anchor: '100%' }, layout: 'anchor',
									items: [
										{ xtype: 'hidden', name: 'atasan2id' },
										{ xtype: 'searchfield', fieldLabel: 'Nama', name: 'atasan2nama' },
										{ fieldLabel: 'Jabatan', name: 'atasan2jabatan' }
									]
								}
							]
						},
					]
				}
			],
			tbar: [
				{
					text: 'Kembali', glyph: 'xf060@FontAwesome',
					handler: function () {
						Ext.History.add('#cuti');
					}
				}, '->',
				{
					text: 'Simpan',
					handler: function () {
						me.onSaveCuti();
					}
				}
			]
		});
		me.callParent([arguments]);
	},
	winAddCuti: function () {
		var me = this;
		var win = Ext.create('Ext.window.Window', {
			title: 'Form Cuti',
			width: 450,
			closeAction: 'destroy', modal: true, layout: 'fit', autoScroll: true, autoShow: true,
			buttons: [
				{
					text: 'Simpan',
					handler: function () {
						var form = Ext.getCmp('id_form_add').getForm();
						var jeniscutiid = Ext.getCmp('id_jeniscuti').getValue();
						var jeniscuti = Ext.getCmp('id_jeniscuti').getRawValue();
						var detailjeniscutiid = Ext.getCmp('id_detailjeniscuti').getValue();
						var detailjeniscuti = Ext.getCmp('id_detailjeniscuti').getRawValue();
						var tglmulai = Ext.Date.format(Ext.getCmp('id_tglmulai').getValue(), 'd-m-Y');
						var tglselesai = Ext.Date.format(Ext.getCmp('id_tglselesai').getValue(), 'd-m-Y');
						var lamacuti = Ext.getCmp('id_lamacuti').getValue();
						var sisacuti = Ext.getCmp('id_sisacuti').getValue();
						var alasan = Ext.getCmp('id_alasan').getValue();

						if (jeniscutiid == '1') {
							Ext.getStore('storeDetailPengajuanID').add({
								jeniscutiid: jeniscutiid,
								jeniscuti: jeniscuti,
								detailjeniscutiid: '1',
								detailjeniscuti: null,
								tglmulai: tglmulai,
								tglselesai: tglselesai,
								lama: lamacuti,
								satuan: 'HARI KERJA',
								sisacuti: sisacuti,
								alasancuti: alasan
							});
						}
						else if (jeniscutiid == '2') {
							Ext.getStore('storeDetailPengajuanID').add({
								jeniscutiid: jeniscutiid,
								jeniscuti: jeniscuti,
								detailjeniscutiid: '2',
								detailjeniscuti: null,
								tglmulai: tglmulai,
								tglselesai: tglselesai,
								lama: lamacuti,
								satuan: 'HARI KERJA',
								sisacuti: null,
								alasancuti: alasan
							});
						}
						else if (jeniscutiid == '3') {
							Ext.getStore('storeDetailPengajuanID').add({
								jeniscutiid: jeniscutiid,
								jeniscuti: jeniscuti,
								detailjeniscutiid: detailjeniscutiid,
								detailjeniscuti: detailjeniscuti,
								tglmulai: tglmulai,
								tglselesai: tglselesai,
								lama: lamacuti,
								satuan: 'HARI KERJA',
								sisacuti: null,
								alasancuti: detailjeniscuti
							});
						}
						else if (jeniscutiid == '4') {
							Ext.getStore('storeDetailPengajuanID').add({
								jeniscutiid: jeniscutiid,
								jeniscuti: jeniscuti,
								detailjeniscutiid: '4',
								detailjeniscuti: null,
								tglmulai: tglmulai,
								tglselesai: tglselesai,
								lama: lamacuti,
								satuan: 'HARI KERJA',
								sisacuti: null,
								alasancuti: alasan
							});
						}
						else if (jeniscutiid == '5') {
							Ext.getStore('storeDetailPengajuanID').add({
								jeniscutiid: jeniscutiid,
								jeniscuti: jeniscuti,
								detailjeniscutiid: '5',
								detailjeniscuti: null,
								tglmulai: tglmulai,
								tglselesai: tglselesai,
								lama: lamacuti,
								satuan: 'HARI KERJA',
								sisacuti: null,
								alasancuti: alasan
							});
						}

						win.destroy();
					}
				},
				{
					text: 'Batal',
					handler: function () {
						win.destroy();
					}
				},
			],
			items: [
				{
					id: 'id_form_add', xtype: 'form', waitMsgTarget: true, bodyPadding: 15, layout: 'anchor', defaultType: 'textfield', region: 'center', autoScroll: true,
					defaults: { labelWidth: 100, anchor: '100%' },
					items: [
						{
							id: 'id_jeniscuti', xtype: 'combobox', fieldLabel: 'Jenis Cuti', name: 'jeniscuti',
							store: Ext.create('Ext.data.Store', {
								storeId: 'storeJenisCuti',
								autoLoad: false,
								fields: ['id', 'text', 'gol'],
								proxy: {
									type: 'ajax',
									url: Settings.MASTER_URL + '/c_jeniscuti/getJenisCuti',
									reader: {
										type: 'json',
										root: 'data'
									}
								}
							}),
							triggerAction: 'all',
							editable: false,
							displayField: 'text',
							valueField: 'id',
							listeners: {
								select: function (combo, records, e) {
									if (combo.getValue() == '1') {
										Ext.getCmp('id_detailjeniscuti').hide();
										Ext.getCmp('id_jatahcutikhusus').hide();
										Ext.getCmp('id_sisacuti').show();
										Ext.getCmp('id_alasan').show();
									}
									else if (combo.getValue() == '2') {
										Ext.getCmp('id_detailjeniscuti').hide();
										Ext.getCmp('id_jatahcutikhusus').hide();
										Ext.getCmp('id_sisacuti').hide();
										Ext.getCmp('id_alasan').show();
									}
									else if (combo.getValue() == '3') {
										Ext.getCmp('id_detailjeniscuti').show();
										Ext.getCmp('id_jatahcutikhusus').show();
										Ext.getCmp('id_sisacuti').hide();
										Ext.getCmp('id_alasan').hide();

										Ext.getStore('storeDetailJenisCuti').proxy.extraParams.jeniscutiid = records[0].get('id');
										Ext.getStore('storeDetailJenisCuti').load();
									}
									else {
										Ext.getCmp('id_detailjeniscuti').hide();
										Ext.getCmp('id_jatahcutikhusus').hide();
										Ext.getCmp('id_sisacuti').hide();
										Ext.getCmp('id_alasan').show();
									}
								}
							}
						},
						{
							id: 'id_detailjeniscuti', xtype: 'combobox', fieldLabel: 'Alasan Cuti', name: 'detailjeniscuti', hidden: true,
							store: Ext.create('Ext.data.Store', {
								storeId: 'storeDetailJenisCuti',
								autoLoad: false,
								fields: ['id', 'text', 'jatahcuti', 'satuan'],
								proxy: {
									type: 'ajax',
									url: Settings.MASTER_URL + '/c_jeniscuti/getDetailJenisCuti',
									reader: {
										type: 'json',
										root: 'data'
									}
								}
							}),
							triggerAction: 'all',
							editable: false,
							displayField: 'text',
							valueField: 'id',
							listeners: {
								select: function (combo, records, e) {
									Ext.getCmp('id_jatahcutikhusus').setValue(records[0].data.jatahcuti);
								}
							}
						},
						{
							xtype: 'fieldcontainer', fieldLabel: 'Tgl', layout: 'hbox',
							items: [
								{
									id: 'id_tglmulai', xtype: 'datefield', format: 'd-m-Y', name: 'tglmulai',
									listeners: {
										change: function (d, newValue, oldValue, eOpts) {
											me.countLengthCuti();
										}
									}
								},
								{ xtype: 'splitter' },
								{
									id: 'id_tglselesai', xtype: 'datefield', format: 'd-m-Y', name: 'tglselesai',
									listeners: {
										change: function (d, newValue, oldValue, eOpts) {
											me.countLengthCuti();
										}
									}
								}
							]
						},
						{ id: 'id_jatahcutikhusus', xtype: 'textfield', fieldLabel: 'Jatah Cuti Khusus', name: 'jatahcutikhusus', hidden: true },
						{ id: 'id_lamacuti', xtype: 'textfield', fieldLabel: 'Lama', name: 'lama' },
						{ id: 'id_sisacuti', xtype: 'textfield', fieldLabel: 'Sisa Cuti', name: 'sisacuti' },
						{ id: 'id_alasan', xtype: 'textareafield', fieldLabel: 'Alasan', name: 'alasan', grow: true },
					]
				},
			]
		});
	},
	countLengthCuti: function () {
		var me = this;
		var pegawaiid = Ext.getCmp('id_pegawaiid').getValue();
		var tglmulai = Ext.Date.format(Ext.getCmp('id_tglmulai').getValue(), 'd-m-Y');
		var tglselesai = Ext.Date.format(Ext.getCmp('id_tglselesai').getValue(), 'd-m-Y');
		var sisacutithnini = Ext.getCmp('id_sisacutithnini').getValue();

		if (!Ext.isEmpty(tglmulai) && !Ext.isEmpty(tglselesai)) {
			Ext.Ajax.request({
				url: Settings.SITE_URL + '/cuti/countLengthCuti',
				method: 'POST',
				params: {
					pegawaiid: pegawaiid,
					tglmulai: tglmulai,
					tglselesai: tglselesai,
				},
				success: function (response) {
					var obj = Ext.decode(response.responseText);
					if (obj.success) {
						var lamacuti = obj.data;
						var sisacuti = parseInt(sisacutithnini) - parseInt(lamacuti);
						Ext.getCmp('id_lamacuti').setValue(lamacuti);
						Ext.getCmp('id_sisacuti').setValue(sisacuti);
					}
				}
			});
		}
	},
	onSaveCuti: function () {
		var me = this;
		var formp = me.down('#formcuti').getForm();

		var paramstemp = [];
		Ext.getStore('storeDetailPengajuanID').each(function (recs, index) {
			paramstemp.push(recs.data);
		});

		formp.submit({
			url: Settings.SITE_URL + '/cuti/crudCuti',
			waitTitle: 'Menyimpan...',
			waitMsg: 'Sedang menyimpan data, mohon tunggu...',
			params: {
				detailallcuti: Ext.encode(paramstemp)
			},
			success: function (form, action) {
				var obj = Ext.decode(action.response.responseText);
				console.log(obj);
			},
			failure: function (form, action) {
				switch (action.failureType) {
					case Ext.form.action.Action.CLIENT_INVALID:
						Ext.Msg.alert('Failure', 'Harap isi semua data');
						break;
					case Ext.form.action.Action.CONNECT_FAILURE:
						Ext.Msg.alert('Failure', 'Terjadi kesalahan');
						break;
					case Ext.form.action.Action.SERVER_INVALID:
						Ext.Msg.alert('Failure', action.result.msg);
				}
			}
		});
	}
});