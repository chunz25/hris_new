Ext.define('SIAP.components.field.ComboPendidikan', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combopendidikan',
	fieldLabel: '',
	name: 'tingkatpendidikan',
	initComponent: function () {
		var me = this;
		var storempendidikan = Ext.create('Ext.data.Store', {
			autoLoad: true,
			storeId: 'storempendidikan',
			fields: ['id', 'text'],
			proxy: {
				type: 'ajax',
				url: Settings.MASTER_URL + '/c_pendidikan/getDataPendidikan',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
		});
		Ext.apply(me, {
			store: storempendidikan,
			triggerAction: 'all',
			editable: false,
			displayField: 'text',
			valueField: 'id',
			name: me.name,
		});
		me.callParent([arguments]);
	},
});