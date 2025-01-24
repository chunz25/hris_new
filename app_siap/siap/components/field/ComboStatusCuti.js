Ext.define('SIAP.components.field.ComboStatusCuti', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combostatuscuti',
	fieldLabel: '',
	name: 'status',
	initComponent: function () {
		var me = this;
		var storemstatuscuti = Ext.create('Ext.data.Store', {
			autoLoad: true,
			storeId: 'storemstatuscuti',
			fields: ['id', 'text'],
			proxy: {
				type: 'ajax',
				url: Settings.MASTER_URL + '/c_statuscuti/getStatusCuti',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
		});
		Ext.apply(me, {
			store: storemstatuscuti,
			triggerAction: 'all',
			editable: true,
			displayField: 'text',
			valueField: 'id',
			name: me.name,
		});
		me.callParent([arguments]);
	},
});