Ext.define("SIAP.modules.detailpegawai.MutasiPromosi", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.MutasiPromosi",
	alias: 'widget.mutasipromosi',
	requires: [
		'SIAP.modules.detailpegawai.TreeDRH',
		'SIAP.components.field.FieldJabatan',
		'SIAP.components.field.FieldSatker',
		'SIAP.components.field.ComboStatusPegawai',
		'SIAP.components.field.ComboLevel',
		'SIAP.components.field.ComboLokasiKerja'
	],
	initComponent: function () {
		var me = this;

		var storemutasipromosi = Ext.create('Ext.data.Store', {
			storeId: 'storemutasipromosi',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/pegawai/getMutasiPromosi',
				actionMethods: {
					create: 'POST',
					read: 'POST',
				},
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'count'
				}
			},
			fields: [
				'pegawaiid',
				'nourut',
				'mutasipromosi',
				'unitkerja1',
				'direktorat1',
				'divisi1',
				'departemen1',
				'seksi1',
				'subseksi1',
				'unitkerja2',
				'direktorat2',
				'divisi2',
				'departemen2',
				'seksi2',
				'subseksi2',
				'jabatan1',
				'jabatan2',
				'level1',
				'level2',
				'golongan1',
				'golongan2',
				'lokasi1',
				'lokasi2',
				'tglmulai',
				'tglakhir',
				'keterangan'
			],
			listeners: {
				beforeload: function (store) {
					store.proxy.extraParams.pegawaiid = me.params;
				}
			}
		});

		Ext.apply(me, {
			layout: 'border',
			items: [
				{
					region: 'west', title: 'Daftar Riwayat Hidup', collapsible: true, collapsed: false, layout: 'fit', border: false, split: true,
					resizable: { dynamic: true },
					items: [
						{ xtype: 'treedrh', params: me.params }
					]
				},
				{
					title: 'Mutasi & Promosi', xtype: 'grid', region: 'center', layout: 'fit', autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
					store: storemutasipromosi,
					columns: [
						{ header: 'No', xtype: 'rownumberer', width: 30 },
						{ header: 'Mutasi / Promosi', dataIndex: 'mutasipromosi', width: 120 },
						{
							header: 'Informasi Awal', align: 'left',
							columns: [
								{ header: 'Level', dataIndex: 'level1', width: 100 },
								{ header: 'Jabatan', dataIndex: 'jabatan1', width: 100 },
								{ header: 'Direktorat', dataIndex: 'direktorat1', width: 100 },
								{ header: 'Divisi', dataIndex: 'divisi1', width: 100 },
								{ header: 'Department', dataIndex: 'departemen1', width: 100 },
								{ header: 'Seksi', dataIndex: 'seksi1', width: 100 },
								{ header: 'Sub Seksi', dataIndex: 'subseksi1', width: 100 },
								{ header: 'Lokasi', dataIndex: 'lokasi1', width: 100 },
							]
						},
						{
							header: 'Pengajuan Perubahan Informasi', align: 'left',
							columns: [
								{ header: 'Level', dataIndex: 'level2', width: 100 },
								{ header: 'Jabatan', dataIndex: 'jabatan2', width: 100 },
								{ header: 'Direktorat', dataIndex: 'direktorat2', width: 100 },
								{ header: 'Divisi', dataIndex: 'divisi2', width: 100 },
								{ header: 'Department', dataIndex: 'departemen2', width: 100 },
								{ header: 'Seksi', dataIndex: 'seksi2', width: 100 },
								{ header: 'Sub Seksi', dataIndex: 'subseksi2', width: 100 },
								{ header: 'Lokasi', dataIndex: 'lokasi2', width: 100 },
							]
						},
						{ header: 'Tgl Efektif', dataIndex: 'tglmulai', width: 120 },
						{ header: 'Tgl Akhir', dataIndex: 'tglakhir', width: 120 },
						{ header: 'Keterangan', dataIndex: 'keterangan', width: 120 },
					],
					bbar: Ext.create('Ext.toolbar.Paging', {
						displayInfo: true,
						height: 35,
						store: 'storemutasipromosi'
					}),
					tbar: [
						{
							text: 'Kembali', glyph: 'xf060@FontAwesome',
							handler: function () {
								Ext.History.add('#pegawai');
							}
						},
						'->',
						{
							text: 'Tambah', glyph: 'xf196@FontAwesome',
							handler: function () {
								me.wincrud('1', {});
							}
						},
						// {
						// 	text: 'Ubah', glyph: 'xf044@FontAwesome',
						// 	handler: function () {
						// 		var m = me.down('grid').getSelectionModel().getSelection();
						// 		if (m.length > 0) {
						// 			me.wincrud('2', m[0]);
						// 		}
						// 		else {
						// 			Ext.Msg.alert('Pesan', 'Harap pilih data terlebih dahulu');
						// 		}
						// 	}
						// },
						{
							text: 'Hapus', glyph: 'xf014@FontAwesome',
							handler: function () {
								var m = me.down('grid').getSelectionModel().getSelection();
								if (m.length > 0) {
									me.winDelete(m);
								}
								else {
									Ext.Msg.alert('Pesan', 'Harap pilih data terlebih dahulu');
								}
							}
						},
					]
				}
			]
		});
		me.callParent([arguments]);
	},
	wincrud: function (flag, records) {
		var me = this;
		var storeNik = Ext.create('Ext.data.Store', {
			storeId: 'storeNik',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/pegawai/getNik',
				actionMethods: {
					create: 'POST',
					read: 'POST',
				},
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'count'
				}
			},
			fields: [
				'nik'
			],
			listeners: {
				beforeload: function (store) {
					store.proxy.extraParams.pegawaiid = me.params;
				},
				load: function (store, records, successful) {
					if (successful && records.length > 0) {
						var firstItem = store.getAt(0); // Get the first record
						var nikValue = firstItem.get('nik'); // Get the 'nik' field value
						win.down('form').getForm().findField('nik').setValue(nikValue);
					} else {
						console.log('No records found or failed to load data.');
					}
				}
			}
		});
		var win = Ext.create('Ext.window.Window', {
			title: 'Mutasi & Promosi',
			width: 400,
			closeAction: 'destroy', modal: true, layout: 'fit', autoScroll: false, autoShow: true,
			buttons: [
				{
					text: 'Simpan',
					handler: function () {
						var formp = win.down('form').getForm();
						formp.submit({
							url: Settings.SITE_URL + '/pegawai/crudRiwayatMutasiPromosi',
							waitTitle: 'Menyimpan...',
							waitMsg: 'Sedang menyimpan data, mohon tunggu...',
							success: function (form, action) {
								var obj = Ext.decode(action.response.responseText);
								if (obj.success) {
									win.destroy();
									me.down('grid').getSelectionModel().deselectAll();
									me.down('grid').getStore().reload();
								}
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
					xtype: 'form',
					waitMsgTarget: true,
					bodyPadding: 15,
					layout: 'anchor',
					defaultType: 'textfield',
					region: 'center',
					autoScroll: true,
					defaults: { labelWidth: 100, anchor: '100%' },
					items: [
						{ xtype: 'hidden', name: 'flag', value: flag },
						{ xtype: 'hidden', name: 'pegawaiid', value: me.params },
						{ xtype: 'hidden', name: 'nourut' },
						{ xtype: 'hidden', name: 'levelid2' },
						{ xtype: 'hidden', name: 'jabatanid2' },
						{ xtype: 'hidden', name: 'satkerid2' },
						{ xtype: 'hidden', name: 'lokasiid2' },
						{
							xtype: 'combobox',
							fieldLabel: 'Mutasi/Promosi',
							name: 'mutasipromosi',
							anchor: '100%',
							queryMode: 'local',
							displayField: 'text',
							valueField: 'id',
							editable: false,
							store: Ext.create('Ext.data.Store', {
								fields: ['id', 'text'],
								data: [
									{ "id": "1", "text": "Mutasi" },
									{ "id": "2", "text": "Promosi" },
								]
							}),
						},
						{
							xtype: 'textfield',
							fieldLabel: 'NIK',
							name: 'nik',
							readOnly: true,
							anchor: '100%',
							listeners: {
								afterrender: function (field) {
									let isRequestInProgress = false; // Flag to track request status

									setInterval(function () {
										var nikValue = field.getValue();
										if (nikValue && !isRequestInProgress) {
											isRequestInProgress = true; // Set the flag to true

											Ext.Ajax.request({
												url: Settings.SITE_URL + '/pegawai/getMutasiPromosiByNik',
												method: 'POST',
												params: {
													nik: nikValue,
												},
												success: function (response) {
													var obj = Ext.decode(response.responseText);
													if (obj.success) {
														// Populate the form fields with the response data
														win.down('form').getForm().findField('jabatan').setValue(obj.data[0].jabatan);
														win.down('form').getForm().findField('jabatanid1').setValue(obj.data[0].jabatanid);
														win.down('form').getForm().findField('level').setValue(obj.data[0].level);
														win.down('form').getForm().findField('levelid1').setValue(obj.data[0].levelid);
														win.down('form').getForm().findField('gol').setValue(obj.data[0].gol);
														win.down('form').getForm().findField('golongan1').setValue(obj.data[0].gol);
														win.down('form').getForm().findField('direktorat1').setValue(obj.data[0].direktorat);
														win.down('form').getForm().findField('satkerid1').setValue(obj.data[0].satkerid);
														win.down('form').getForm().findField('lokasi').setValue(obj.data[0].lokasi);
														win.down('form').getForm().findField('lokasiid1').setValue(obj.data[0].lokasikerja);
													}
												},
												failure: function (response) {
													// Handle failure case
													Ext.Msg.alert('Error', 'Failed to retrieve data. Please try again.');
												},
												// Always reset the flag after the request completes
												callback: function () {
													isRequestInProgress = true; // Reset the flag
												}
											});
										}
									}, 100); // Refresh every 1000 milliseconds (1 second)
								}
							}
						},
						{ xtype: 'label', text: 'Informasi Awal', style: { 'font-weight': 'bold' }, hidden: flag === '2' },
						{ xtype: 'hidden', name: 'jabatanid1' },
						{ xtype: 'hidden', name: 'golongan1' },
						{ xtype: 'hidden', name: 'levelid1' },
						{ xtype: 'hidden', name: 'satkerid1' },
						{ xtype: 'hidden', name: 'lokasiid1' },
						{ xtype: 'displayfield', fieldLabel: 'Jabatan', name: 'jabatan', anchor: '95%', hidden: flag === '2' },
						{ xtype: 'displayfield', fieldLabel: 'Level', name: 'level', anchor: '95%', hidden: flag === '2' },
						{ xtype: 'displayfield', fieldLabel: 'Golongan', name: 'gol', anchor: '95%', hidden: flag === '2' },
						{ xtype: 'displayfield', fieldLabel: 'Direktorat', name: 'direktorat1', anchor: '95%', hidden: flag === '2' },
						{ xtype: 'displayfield', fieldLabel: 'Lokasi Kerja', name: 'lokasi', anchor: '95%', hidden: flag === '2' },
						{ xtype: 'label', text: 'Pengajuan Perubahan Informasi', style: { 'font-weight': 'bold', }, hidden: flag === '2' },
						{
							xtype: 'fieldjabatan', fieldLabel: 'Jabatan', name: 'jabatan2',
							listeners: {
								pilih: function (p, rec) {
									win.down('form').getForm().findField('jabatanid2').setValue(rec.get('id'));
								}
							}
						},
						{
							xtype: 'combolevel', fieldLabel: 'Level', name: 'level2',
							listeners: {
								select: function (combo, rec, opt) {
									win.down('form').getForm().findField('golongan2').setValue(rec[0].data.gol);
									win.down('form').getForm().findField('levelid2').setValue(rec[0].data.id);
								}
							}
						},
						{ fieldLabel: 'Golongan', name: 'golongan2', readOnly: true },
						{
							xtype: 'fieldsatker', fieldLabel: 'Direktorat', name: 'direktorat2',
							listeners: {
								pilih: function (p, rec) {
									win.down('form').getForm().findField('satkerid2').setValue(rec.get('id'));
								}
							}
						},
						{
							xtype: 'combolokasikerja', fieldLabel: 'Lokasi Kerja', name: 'lokasi2',
							listeners: {
								select: function (combo, rec, opt) {
									win.down('form').getForm().findField('lokasiid2').setValue(rec[0].data.id);
								}
							}
						},
						{ xtype: 'datefield', fieldLabel: 'Tgl Efektif', format: 'd/m/Y', name: 'tglmulai', editable: false },
						{ xtype: 'datefield', fieldLabel: 'Tgl Akhir', format: 'd/m/Y', name: 'tglakhir', editable: false },
						{ xtype: 'textfield', fieldLabel: 'Keterangan', name: 'keterangan' },
					]
				},
			]
		});

		if (flag == '2') {
			win.down('form').getForm().loadRecord(records);
		}
	},
	winDelete: function (record) {
		var me = this;
		var params = [];
		Ext.Array.each(record, function (rec, i) {
			var temp = {};
			temp.pegawaiid = rec.get('pegawaiid');
			temp.nourut = rec.get('nourut');
			params.push(temp);
		});
		Ext.Msg.show({
			title: 'Konfirmasi',
			msg: 'Apakah anda yakin akan menghapus data ?',
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn: function (btn) {
				if (btn == 'yes') {
					Ext.Ajax.request({
						url: Settings.SITE_URL + '/pegawai/delRiwayatMutasiPromosi',
						method: 'POST',
						params: {
							params: Ext.encode(params)
						},
						success: function (response) {
							var obj = Ext.decode(response.responseText);
							if (obj.success) {
								Ext.Msg.alert('Informasi', obj.message);
								me.down('grid').getSelectionModel().deselectAll();
								me.down('grid').getStore().reload();
							}
						}
					});
				}
			}
		});
	}
});