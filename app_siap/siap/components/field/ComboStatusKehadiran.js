Ext.define('SIAP.components.field.ComboStatusKehadiran', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combostatuskehadiran',
	fieldLabel: '',
	name: 'status',
	initComponent: function () {
		var me = this;
		var storemstatuskehadiran = Ext.create('Ext.data.Store', {
			autoLoad: true,
			storeId: 'storemstatuskehadiran',
			fields: ['id', 'text'],
			proxy: {
				type: 'ajax',
				url: Settings.MASTER_URL + '/c_statuskehadiran/getStatusKehadiran',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
		});
		Ext.apply(me, {
			store: storemstatuskehadiran,
			triggerAction: 'all',
			editable: true,
			displayField: 'text',
			valueField: 'id',
			name: me.name,
		});
		me.callParent([arguments]);
	},
});