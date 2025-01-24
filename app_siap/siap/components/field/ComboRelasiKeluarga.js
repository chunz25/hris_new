Ext.define('SIAP.components.field.ComboRelasiKeluarga', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.comborelasikeluarga',
	fieldLabel: '',
	name: 'relasikeluarga',
	initComponent: function () {
		var me = this;
		var storemrelasikeluarga = Ext.create('Ext.data.Store', {
			autoLoad: true,
			storeId: 'storemrelasikeluarga',
			fields: ['id', 'text'],
			proxy: {
				type: 'ajax',
				url: Settings.MASTER_URL + '/c_relasikeluarga/getRelasiKeluarga',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
		});
		Ext.apply(me, {
			store: storemrelasikeluarga,
			triggerAction: 'all',
			editable: false,
			displayField: 'text',
			valueField: 'id',
			name: me.name,
		});
		me.callParent([arguments]);
	},
});