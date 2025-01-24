Ext.define('SIAP.components.field.ComboGolDarah', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combogoldarah',
	fieldLabel: '',
	name: 'goldarah',
	initComponent: function () {
		var me = this;
		var storemgoldarah = Ext.create('Ext.data.Store', {
			autoLoad: true,
			storeId: 'storemgoldarah',
			fields: ['id', 'text', 'rhesus', 'desc'],
			proxy: {
				type: 'ajax',
				url: Settings.MASTER_URL + '/c_goldarah/get_goldarah',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
		});
		Ext.apply(me, {
			store: storemgoldarah,
			triggerAction: 'all',
			editable: false,
			displayField: 'desc',
			valueField: 'id',
			name: me.name,
		});
		me.callParent([arguments]);
	},
});