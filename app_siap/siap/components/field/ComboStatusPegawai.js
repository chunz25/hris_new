Ext.define('SIAP.components.field.ComboStatusPegawai', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combostatuspegawai',
	fieldLabel: '',
	name: 'statuspegawai',
	initComponent: function () {
		var me = this;
		var storemstatuspegawai = Ext.create('Ext.data.Store', {
			autoLoad: true,
			storeId: 'storemstatuspegawai',
			fields: ['id', 'text'],
			proxy: {
				type: 'ajax',
				url: Settings.MASTER_URL + '/c_statuspegawai/get_statuspegawai',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
		});
		Ext.apply(me, {
			store: storemstatuspegawai,
			triggerAction: 'all',
			editable: false,
			displayField: 'text',
			valueField: 'id',
			name: me.name,
		});
		me.callParent([arguments]);
	},
});