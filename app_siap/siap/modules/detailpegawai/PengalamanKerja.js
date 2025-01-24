Ext.define("SIAP.modules.detailpegawai.PengalamanKerja", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.PengalamanKerja",
	alias: 'widget.pengalamankerja',
	requires: [
		'SIAP.modules.detailpegawai.TreeDRH',
		'SIAP.components.field.FieldPeriode'
	],
	initComponent: function () {
		var me = this;

		var storepengalamankerja = Ext.create('Ext.data.Store', {
			storeId: 'storepengalamankerja',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/pegawai/getRiwayatPengalamanKerja',
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
				'pegawaiid', 'nourut', 'perusahaan', 'alamat', 'bidangusaha', 'jabatan', 'jobdesc', 'tahunmasuk', 'tahunkeluar', 'alasankeluar', 'gaji', 'atasan'
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
					title: 'Pengalaman Kerja', xtype: 'grid', region: 'center', layout: 'fit', autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
					store: storepengalamankerja,
					columns: [
						{ header: 'No', xtype: 'rownumberer', width: 30 },
						{ header: 'Nama Perusahaan', dataIndex: 'perusahaan', width: 160 },
						{ header: 'Alamat', dataIndex: 'alamat', width: 160 },
						{ header: 'Bidang Usaha', dataIndex: 'bidangusaha', width: 120 },
						{ header: 'Jabatan', dataIndex: 'jabatan', width: 120 },
						{ header: 'Uraian Pekerjaan', dataIndex: 'jobdesc', width: 160 },
						{ header: 'Tahun Masuk', dataIndex: 'tahunmasuk', width: 80 },
						{ header: 'Tahun Keluar', dataIndex: 'tahunkeluar', width: 80 },
						{ header: 'Alasan Berhenti', dataIndex: 'alasankeluar', width: 120 },
						{ header: 'Gaji', dataIndex: 'gaji', width: 120 },
						{ header: 'Atasan', dataIndex: 'atasan', width: 120 },
					],
					bbar: Ext.create('Ext.toolbar.Paging', {
						displayInfo: true,
						height: 35,
						store: 'storepengalamankerja'
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
		var win = Ext.create('Ext.window.Window', {
			title: 'Pengalaman Kerja',
			width: 400,
			closeAction: 'destroy', modal: true, layout: 'fit', autoScroll: true, autoShow: true,
			buttons: [
				{
					text: 'Simpan',
					handler: function () {
						var formp = win.down('form').getForm();
						formp.submit({
							url: Settings.SITE_URL + '/pegawai/crudRiwayatPengalamanKerja',
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
					xtype: 'form', waitMsgTarget: true, bodyPadding: 15, layout: 'anchor', defaultType: 'textfield', region: 'center', autoScroll: true,
					defaults: {
						labelWidth: 100, anchor: '100%'
					},
					items: [
						{ xtype: 'hidden', name: 'flag', value: flag },
						{ xtype: 'hidden', name: 'pegawaiid', value: me.params },
						{ xtype: 'hidden', name: 'nourut' },
						{ fieldLabel: 'Nama Perusahaan', name: 'perusahaan' },
						{ fieldLabel: 'Alamat', name: 'alamat' },
						{ fieldLabel: 'Bidang Usaha', name: 'bidangusaha' },
						{ fieldLabel: 'Jabatan', name: 'jabatan' },
						{ fieldLabel: 'Uraian Pekerjaan', name: 'jobdesc' },
						{
							xtype: 'numberfield',
							fieldLabel: 'Tahun Masuk',
							name: 'tahunmasuk',
							minValue: 1900,
							hideTrigger: true,
							keyNavEnabled: false,
							mouseWheelEnabled: false,
							maxLength: 4,
							enforceMaxLength: true
						},
						{
							xtype: 'numberfield',
							fieldLabel: 'Tahun Keluar',
							name: 'tahunkeluar', minValue: 1900,
							hideTrigger: true,
							keyNavEnabled: false,
							mouseWheelEnabled: false,
							maxLength: 4,
							enforceMaxLength: true
						},
						{ fieldLabel: 'Alasan Berhenti', name: 'alasankeluar' },
						{ xtype: 'numberfield', name: 'gaji', fieldLabel: 'Gaji', minValue: 0, hideTrigger: true, keyNavEnabled: false, mouseWheelEnabled: false },
						{ fieldLabel: 'Atasan', name: 'atasan' },
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
						url: Settings.SITE_URL + '/pegawai/delRiwayatPengalamanKerja',
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