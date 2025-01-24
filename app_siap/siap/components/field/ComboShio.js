Ext.define('SIAP.components.field.ComboShio', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.comboshio',
	fieldLabel: '',
	name: 'shio',
	initComponent: function () {
		var me = this;
		var storemshio = Ext.create('Ext.data.Store', {
			autoLoad: true,
			storeId: 'storemshio',
			fields: ['id', 'text', 'unsur', 'desc'],
			proxy: {
				type: 'ajax',
				url: Settings.MASTER_URL + '/c_shio/get_shio',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
		});
		Ext.apply(me, {
			store: storemshio,
			triggerAction: 'all',
			editable: false,
			displayField: 'desc',
			valueField: 'id',
			name: me.name,
		});
		me.callParent([arguments]);
	},
});