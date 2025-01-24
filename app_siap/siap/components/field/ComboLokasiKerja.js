Ext.define('SIAP.components.field.ComboLokasiKerja', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combolokasikerja',
	fieldLabel: '',
	name: 'lokasikerja', isLoad: true,
	initComponent: function () {
		var me = this;
		var storemlokasikerja = Ext.create('Ext.data.Store', {
			autoLoad: me.isLoad,
			storeId: 'storemlokasikerja',
			fields: ['id', 'text'],
			proxy: {
				type: 'ajax',
				url: Settings.MASTER_URL + '/c_lokasi/getLokasiKerja',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
		});
		Ext.apply(me, {
			store: storemlokasikerja,
			triggerAction: 'all',
			editable: false,
			displayField: 'text',
			valueField: 'id',
			name: me.name,
		});
		me.callParent([arguments]);
	},
});