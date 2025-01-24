Ext.define('SIAP.components.field.ComboStatusNikah', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combostatusnikah',
	fieldLabel: '',
	name: 'statusnikah', isLoad: true,
	initComponent: function () {
		var me = this;
		var storemstatusnikah = Ext.create('Ext.data.Store', {
			autoLoad: me.isLoad,
			storeId: 'storemstatusnikah',
			fields: ['id', 'text'],
			proxy: {
				type: 'ajax',
				url: Settings.MASTER_URL + '/c_statusnikah/getStatusNikah',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
			listeners: {
				load: function (s, records, successful, eOpts) {
					this.recordDef = Ext.data.Model([
						{ id: '9999', text: 'Tambah Data' }
					]);
					storemstatusnikah.add(this.recordDef);
				}
			}
		});
		Ext.apply(me, {
			store: storemstatusnikah,
			triggerAction: 'all',
			editable: false,
			displayField: 'text',
			valueField: 'id',
			name: me.name,
			listeners: {
				select: function (combo, records, eOpts) {
					if (records[0].get('id') == '9999') {
						me.winCRUDMaster();
					} else {
						var statid = records[0].get('id');
						var statfield = combo.up('form').getForm().findField('statusnikahid');
						statfield.setValue(statid);
					}
				}
			}
		});
		me.callParent([arguments]);
	},
	winCRUDMaster: function () {
		var me = this;
		me.setValue(null);
		var win = Ext.create('Ext.window.Window', {
			title: 'Add Data',
			width: 400,
			closeAction: 'destroy', modal: true, layout: 'fit', autoScroll: true, autoShow: true,
			buttons: [
				{
					text: 'Simpan',
					handler: function () {
						var form = win.down('form').getForm();
						form.submit({
							url: Settings.MASTER_URL + '/c_statusnikah/crudStatusNikah',
							waitTitle: 'Menyimpan...',
							waitMsg: 'Sedang menyimpan data, mohon tunggu...',
							success: function (f, action) {
								var obj = Ext.decode(action.response.responseText);
								if (obj.success) {
									me.setValue(form.findField('text').getValue());
									win.destroy();
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
						{ xtype: 'hidden', name: 'flag' },
						{ fieldLabel: 'Text', name: 'text' },
					]
				},
			]
		});


	}
});