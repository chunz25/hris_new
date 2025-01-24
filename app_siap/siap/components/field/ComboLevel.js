Ext.define('SIAP.components.field.ComboLevel', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combolevel',
	fieldLabel: '',
	name: 'level', isLoad: true,
	initComponent: function () {
		var me = this;
		var storemlevel = Ext.create('Ext.data.Store', {
			autoLoad: me.isLoad,
			storeId: 'storemlevel',
			fields: ['id', 'text', 'gol'],
			proxy: {
				type: 'ajax',
				url: Settings.MASTER_URL + '/c_level/getComboLevel',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
		});
		Ext.apply(me, {
			store: storemlevel,
			triggerAction: 'all',
			editable: false,
			displayField: 'text',
			valueField: 'id',
			name: me.name,
		});
		me.callParent([arguments]);
	},
});