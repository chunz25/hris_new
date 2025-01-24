Ext.define("SIAP.modules.master.MasterLokasi", {
	extend: "Ext.grid.Panel",
	alternateClassName: "SIAP.masterlokasi",
	alias: 'widget.masterlokasi',
	requires: [
		// 'SIAP.components.field.ComboGolongan'
	],
	initComponent: function () {
		var me = this;
		var storelokasi = Ext.create('Ext.data.Store', {
			storeId: 'storelokasi',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.MASTER_URL + '/c_lokasi/getLokasiKerja',
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
				'id', 'text', 'kodelokasi'
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
			store: storelokasi,
			columns: [
				{ header: 'No', xtype: 'rownumberer', width: 30 },
				{ header: 'Lokasi', dataIndex: 'text', width: 180 },
				{ header: 'Kode Lokasi', dataIndex: 'kodelokasi', width: 80 },
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
			title: 'Master Lokasi',
			width: 400,
			closeAction: 'destroy', modal: true, layout: 'fit', autoScroll: true, autoShow: true,
			buttons: [
				{
					text: 'Simpan',
					handler: function () {
						win.down('form').getForm().submit({
							waitTitle: 'Menyimpan...',
							waitMsg: 'Sedang menyimpan data, mohon tunggu...',
							url: Settings.MASTER_URL + '/c_lokasi/crudLokasi',
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
						{ xtype: 'hidden', name: 'id' },
						{ fieldLabel: 'Lokasi', name: 'text' },
						{ fieldLabel: 'Kode Lokasi', name: 'kodelokasi' },
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
			temp.id = rec.get('id');
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
						url: Settings.MASTER_URL + '/c_lokasi/hapus',
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