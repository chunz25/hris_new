Ext.define("SIAP.modules.master.MasterUnit", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.masterunit",
	alias: 'widget.masterunit',
	requires: [
		'SIAP.components.tree.UnitKerja',
	],
	initComponent: function () {
		var me = this;
		Ext.apply(me, {
			layout: 'fit', border: false,
			items: [Ext.create('SIAP.components.tree.UnitKerja', { onlySatker: false })],
			tbar: ['->',
				{
					glyph: 'xf196@FontAwesome', text: 'Tambah',
					handler: function () {
						var m = me.down('#id_unitkerja').getSelectionModel().getSelection();
						if (m.length > 0) {
							me.winCRUD('1', m[0]);
						}
					}
				},
				{
					glyph: 'xf044@FontAwesome', text: 'Ubah',
					handler: function () {
						var m = me.down('#id_unitkerja').getSelectionModel().getSelection();
						if (m.length > 0) {
							me.winCRUD('2', m[0]);
						}
					}
				},
				{
					glyph: 'xf014@FontAwesome', text: 'Hapus',
					handler: function () {
						var m = me.down('#id_unitkerja').getSelectionModel().getSelection();
						if (m.length > 0) {
							me.hapus(m);
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
	winCRUD: function (flag, record) {
		var me = this;
		// console.log('disini');
		var win = Ext.create('Ext.window.Window', {
			title: 'Master Unit',
			width: 400,
			closeAction: 'destroy', modal: true, layout: 'fit', autoScroll: true, autoShow: true,
			buttons: [
				{
					text: 'Simpan',
					handler: function () {
						var formp = win.down('form').getForm();
						if (formp.isValid()) {
							formp.submit({
								url: Settings.MASTER_URL + '/c_unitkerja/CRUDMasterUnitkerja',
								waitTitle: 'Menyimpan...',
								waitMsg: 'Sedang menyimpan data, mohon tunggu...',
								success: function (form, action) {
									var obj = Ext.decode(action.response.responseText);
									win.destroy();
									Ext.Msg.alert('Informasi', obj.message, function () {
										if (obj.success) {
											window.location.reload();
										}
									});
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
					defaults: { labelWidth: 100, anchor: '100%' },
					items: [
						{ xtype: 'hidden', name: 'flag', value: flag },
						{ xtype: 'hidden', name: 'id' },
						{ xtype: 'hidden', name: 'satkerid' },
						{ xtype: 'textfield', fieldLabel: 'Nama Unit', name: 'text' },
					]
				},
			]
		});
		win.down('form').getForm().loadRecord(record);
		if (flag == '1') {
			win.down('form').getForm().findField('text').setValue(null);
		}
	},
	hapus: function (record) {
		var me = this;
		var params = [];
		Ext.Array.each(record, function (rec, i) {
			var temp = {};
			temp.id = rec.get('satkerid');
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
						url: Settings.MASTER_URL + '/c_unitkerja/hapus',
						method: 'POST',
						params: {
							params: Ext.encode(params)
						},
						success: function (response) {
							var obj = Ext.decode(response.responseText);
							Ext.Msg.alert('Informasi', obj.message, function () {
								if (obj.success) {
									window.location.reload();
								}
							});
						}
					});
				}
			}
		});
	}
});