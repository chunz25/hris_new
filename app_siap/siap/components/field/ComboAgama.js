Ext.define('SIAP.components.field.ComboAgama', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.comboagama',
	fieldLabel: '',
	name: 'agama', isLoad: true,
	initComponent: function () {
		var me = this;
		var storemagama = Ext.create('Ext.data.Store', {
			autoLoad: me.isLoad,
			storeId: 'storemagama',
			fields: ['id', 'text'],
			proxy: {
				type: 'ajax',
				url: Settings.MASTER_URL + '/c_agama/get_agama',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
		});
		Ext.apply(me, {
			store: storemagama,
			triggerAction: 'all',
			editable: false,
			queryMode: 'local',
			displayField: 'text',
			valueField: 'id',
			name: me.name,
		});
		me.callParent([arguments]);
	},
	reload: function () {
		var me = this;
		me.getStore().load();
	}
});