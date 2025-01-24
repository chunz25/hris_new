Ext.define("SIAP.modules.master.MasterHariLibur", {
	extend: "Ext.grid.Panel",
	alternateClassName: "SIAP.masterharilibur",
	alias: 'widget.masterharilibur',
	requires: [
		// 'SIAP.components.field.ComboGolongan'
	],
	initComponent: function () {
		var me = this;
		var storeharilibur = Ext.create('Ext.data.Store', {
			storeId: 'storeharilibur',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.MASTER_URL + '/c_harilibur/getListHariLibur',
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
				'hariliburid', 'tgl', 'keterangan', 'status', 'jenisid', 'jenis'
			],
			listeners: {
				beforeload: function (store) {
					me.fireEvent("beforeload", store);
				}
			}
		});

		Ext.apply(me, {
			layout: 'fit',
			autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true, allowDeselect: true,
			store: storeharilibur,
			columns: [
				{ header: 'No', xtype: 'rownumberer', width: 30 },
				{ header: 'Tanggal', dataIndex: 'tgl', width: 80 },
				{ header: 'Keterangan', dataIndex: 'keterangan', width: 380 },
				{ header: 'Jenis', dataIndex: 'jenis', width: 150 },
				{
					header: 'Status', dataIndex: 'status', width: 120,
					renderer: function (value, meta, record, index) {
						if (value == '1') {
							return 'Aktif';
						}
						else {
							return 'Tidak Aktif';
						}
					}
				},
			],
			tbar: ['->',
				{
					glyph: 'xf196@FontAwesome', text: 'Tambah',
					handler: function () {
						me.crud({}, '1');
					}
				},
				{
					glyph: 'xf044@FontAwesome', text: 'Ubah',
					handler: function () {
						var m = me.getSelectionModel().getSelection();
						if (m.length > 0) {
							console.log(m[0]);
							me.crud(m[0], '2');
						}
						else {
							Ext.Msg.alert('Pesan', 'Harap pilih data terlebih dahulu');
						}
					}
				},
				{
					glyph: 'xf014@FontAwesome', text: 'Hapus',
					handler: function () {
						var m = me.getSelectionModel().getSelection();
						if (m.length > 0) {
							me.hapus(m);
						}
						else {
							Ext.Msg.alert('Pesan', 'Harap pilih data terlebih dahulu');
						}
					}
				},
			],
			listeners: {
				afterrender: function () {
					Ext.get('id_submenu').dom.style.display = 'block';
				}
			}
		});
		me.callParent([arguments]);
	},
	crud: function (record, flag) {
		var me = this;
		var win = Ext.create('Ext.window.Window', {
			title: 'Master Hari Libur',
			width: 400,
			closeAction: 'destroy', modal: true, layout: 'fit', autoScroll: true, autoShow: true,
			buttons: [
				{
					text: 'Simpan',
					handler: function () {
						win.down('form').getForm().submit({
							waitTitle: 'Menyimpan...',
							waitMsg: 'Sedang menyimpan data, mohon tunggu...',
							url: Settings.MASTER_URL + '/c_harilibur/crudHariLibur',
							method: 'POST',
							success: function (form, action) {
								var obj = Ext.decode(action.response.responseText);
								win.destroy();
								me.getStore().reload();
								me.getSelectionModel().deselectAll();
							},
							failure: function (form, action) {
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
						{ xtype: 'hidden', name: 'hariliburid' },
						{ xtype: 'datefield', fieldLabel: 'Tanggal', name: 'tgl', format: 'd/m/Y' },
						{ fieldLabel: 'Keterangan', name: 'keterangan' },
						{
							xtype: 'combo',
							store: Ext.create('Ext.data.SimpleStore', {
								fields: ['id', 'text'],
								data: [
									['1', 'Hari Libur Nasional'],
									['2', 'Cuti Bersama'],
								]
							}),
							fieldLabel: 'Jenis',
							triggerAction: 'all',
							editable: false,
							displayField: 'text',
							valueField: 'id',
							name: 'jenisid',
							value: '1',
							typeAhead: false,
							mode: 'local',
							forceSelection: true,
						},
						{
							xtype: 'combo',
							store: Ext.create('Ext.data.SimpleStore', {
								fields: ['id', 'text'],
								data: [
									['2', 'Tidak Aktif'],
									['1', 'Aktif'],
								]
							}),
							fieldLabel: 'Status',
							triggerAction: 'all',
							editable: false,
							displayField: 'text',
							valueField: 'id',
							name: 'status',
							value: '1',
							typeAhead: false,
							mode: 'local',
							forceSelection: true,
						}
					]
				},
			]
		});
		if (flag == '2') {
			win.down('form').getForm().loadRecord(record);
		}
	},
	hapus: function (record) {
		var me = this;
		var params = [];
		Ext.Array.each(record, function (rec, i) {
			var temp = {};
			temp.id = rec.get('hariliburid');
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
						url: Settings.MASTER_URL + '/c_harilibur/hapus',
						method: 'POST',
						params: {
							params: Ext.encode(params)
						},
						success: function (response) {
							var obj = Ext.decode(response.responseText);
							Ext.Msg.alert('Informasi', obj.message);
							me.getStore().load();
							me.getSelectionModel().deselectAll();
						}
					});
				}
			}
		});
	}
});