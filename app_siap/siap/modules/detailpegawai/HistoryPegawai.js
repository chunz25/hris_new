Ext.define("SIAP.modules.detailpegawai.HistoryPegawai", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.HistoryPegawai",
	alias: 'widget.historypegawai',
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

		var storehistorypegawai = Ext.create('Ext.data.Store', {
			storeId: 'storehistorypegawai',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/pegawai/getRiwayatJabatan',
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
				'pegawaiid', 'nourut', 'statuspegawaiid', 'statuspegawai', 'levelid', 'level', 'gol', 'jabatanid', 'jabatan', 'satkerid', 'direktorat', 'divisi', 'departemen', 'seksi', 'subseksi',
				'masakerjath', 'masakerjabl', 'tglmulai', 'tglselesai', 'tglakhirkontrak', 'tglpermanent', 'keterangan', 'lokasiid', 'lokasi', 'no'
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
					title: 'History Status Karyawan', xtype: 'grid', region: 'center', layout: 'fit', autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
					store: storehistorypegawai,
					columns: [
						{ header: 'No', xtype: 'rownumberer', width: 30 },
						{ header: 'Status Pegawai', dataIndex: 'statuspegawai', width: 160 },
						{ header: 'Jabatan', dataIndex: 'jabatan', width: 250 },
						{ header: 'Level', dataIndex: 'level', width: 80 },
						{ header: 'Gol', dataIndex: 'gol', width: 80 },
						{
							header: 'Unit', align: 'left',
							columns: [
								{ header: 'Direktorat', dataIndex: 'direktorat', width: 120 },
								{ header: 'Divisi', dataIndex: 'divisi', width: 120 },
								{ header: 'Departemen', dataIndex: 'departemen', width: 120 },
								{ header: 'Seksi', dataIndex: 'seksi', width: 120 },
								{ header: 'Sub Seksi', dataIndex: 'subseksi', width: 120 },
							]
						},
						{
							header: 'Masa Kerja', dataIndex: 'masakerjath', width: 100,
							renderer: function (value, meta, record, rowIndex, colIndex, stor) {
								if (Ext.isEmpty(record.get('masakerjath'))) {
									return ' - ' + record.get('masakerjabl') + ' bl';
								}
								else if (Ext.isEmpty(record.get('masakerjabl'))) {
									return record.get('masakerjath') + ' th ' + ' - ';
								}
								else if (Ext.isEmpty(record.get('masakerjath')) && Ext.isEmpty(record.get('masakerjabl'))) {
									return '-';
								}
								else {
									return record.get('masakerjath') + ' th ' + record.get('masakerjabl') + ' bl';
								}
							}
						},
						{ header: 'Tgl Mulai', dataIndex: 'tglmulai', width: 120 },
						{ header: 'Tgl Akhir Kontrak', dataIndex: 'tglakhirkontrak', width: 120 },
						{ header: 'Tgl Permanent', dataIndex: 'tglpermanent', width: 120 },
						{ header: 'Tgl Akhir', dataIndex: 'tglselesai', width: 120 },
						{ header: 'Keterangan', dataIndex: 'keterangan', width: 120 },
						{ header: 'Lokasi Kerja', dataIndex: 'lokasi', width: 120 },
					],
					bbar: Ext.create('Ext.toolbar.Paging', {
						displayInfo: true,
						height: 35,
						store: 'storehistorypegawai'
					}),
					tbar: [
						{ text: 'Kembali', glyph: 'xf060@FontAwesome' },
						'->',
						// {text: 'Tambah', glyph:'xf196@FontAwesome',
						// 	handler: function(){
						// 		me.wincrud('1', {});
						// 	}
						// },
						{
							text: 'Ubah', glyph: 'xf044@FontAwesome',
							handler: function () {
								var m = me.down('grid').getSelectionModel().getSelection();
								if (m.length > 0) {
									me.wincrud('2', m[0]);
								}
								else {
									Ext.Msg.alert('Pesan', 'Harap pilih data terlebih dahulu');
								}
							}
						},
						// {text: 'Hapus', glyph:'xf014@FontAwesome',
						// 	handler: function(){
						// 		var m = me.down('grid').getSelectionModel().getSelection();
						// 		if(m.length > 0){
						// 			me.winDelete(m);
						// 		}
						// 		else{
						// 			Ext.Msg.alert('Pesan', 'Harap pilih data terlebih dahulu');
						// 		}																
						// 	}						
						// },
					]
				}
			]
		});
		me.callParent([arguments]);
	},
	wincrud: function (flag, records) {
		var me = this;
		var win = Ext.create('Ext.window.Window', {
			title: 'History Status Karyawan',
			width: 550,
			closeAction: 'destroy', modal: true, layout: 'fit', autoShow: true , autoScroll: false,
			buttons: [
				{
					text: 'Simpan',
					handler: function () {
						var formp = win.down('form').getForm();
						formp.submit({
							url: Settings.SITE_URL + '/pegawai/crudRiwayatJabatan',
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
					xtype: 'form', waitMsgTarget: true, bodyPadding: 10, layout: 'anchor', defaultType: 'textfield', region: 'center', autoScroll: true,
					defaults: { labelWidth: 100, anchor: '100%' },
					items: [
						{ xtype: 'hidden', name: 'flag', value: flag },
						{ xtype: 'hidden', name: 'pegawaiid', value: me.params },
						{ xtype: 'hidden', name: 'nourut' },
						{ xtype: 'hidden', name: 'statuspegawaiid' },
						{ xtype: 'hidden', name: 'lokasiid' },
						{
							xtype: 'combostatuspegawai', fieldLabel: 'Status Pegawai', name: 'statuspegawai',
							listeners: {
								select: function (combo, rec, opt) {
									win.down('form').getForm().findField('statuspegawaiid').setValue(rec[0].data.id);
								}
							}
						},
						{ fieldLabel: 'Jabatan', name: 'jabatan', readOnly: true },
						{
							xtype: 'combolevel', fieldLabel: 'Level', name: 'level', readOnly: true,
							listeners: {
								select: function (combo, rec, opt) {
									win.down('form').getForm().findField('gol').setValue(rec[0].data.gol);
									win.down('form').getForm().findField('levelid').setValue(rec[0].data.id);
								}
							}
						},
						{ fieldLabel: 'Golongan', name: 'gol', readOnly: true },
						{ fieldLabel: 'Direktorat', name: 'direktorat', readOnly: true },
						{ fieldLabel: 'Divisi', name: 'divisi', readOnly: true },
						{ fieldLabel: 'Department', name: 'departemen', readOnly: true },
						{ fieldLabel: 'Seksi', name: 'seksi', readOnly: true },
						{ fieldLabel: 'Sub Seksi', name: 'subseksi', readOnly: true },
						{
							xtype: 'combolokasikerja', fieldLabel: 'Lokasi Kerja', name: 'lokasi',
							listeners: {
								select: function (combo, rec, opt) {
									win.down('form').getForm().findField('lokasiid').setValue(rec[0].data.id);
								}
							}
						},
						{
							xtype: 'fieldcontainer', fieldLabel: 'Masa Kerja', layout: 'hbox',
							items: [
								{ xtype: 'textfield', flex: 1, name: 'masakerjath' },
								{ xtype: 'splitter' },
								{ xtype: 'text', text: 'th' },
								{ xtype: 'splitter' },
								{ xtype: 'textfield', flex: 1, name: 'masakerjabl' },
								{ xtype: 'splitter' },
								{ xtype: 'text', text: 'bl' }
							]
						},
						{ xtype: 'datefield', fieldLabel: 'Tgl Mulai', format: 'd/m/Y', name: 'tglmulai' },
						{ xtype: 'datefield', fieldLabel: 'Tgl Akhir Kontrak', format: 'd/m/Y', name: 'tglakhirkontrak' },
						{ xtype: 'datefield', fieldLabel: 'Tgl Permanent', format: 'd/m/Y', name: 'tglpermanent' },
						{ xtype: 'datefield', fieldLabel: 'Tgl Akhir', format: 'd/m/Y', name: 'tglselesai' },
						{ fieldLabel: 'Keterangan', name: 'keterangan' },
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
						url: Settings.SITE_URL + '/pegawai/delRiwayatJabatan',
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