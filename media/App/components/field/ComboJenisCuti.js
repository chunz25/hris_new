Ext.define('App.components.field.ComboJenisCuti', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combojeniscuti',
	fieldLabel: '',
	name: 'grade',
	initComponent	: function() {	
		var me = this;	
		var store_jenis_cuti = Ext.create('Ext.data.Store', {
			autoLoad : true,
			storeId: 'store_jenis_cuti',
			fields: ['id_jenis_cuti', 'deskripsi_jenis_cuti'],
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/master/c_cuti/get_combo_cuti',
				reader: {
					type: 'json',
					root:'data'
				}
			},
		});		
		Ext.apply(me,{		
			store: store_jenis_cuti,
			triggerAction : 'all',
			editable : false,
			displayField: 'deskripsi_jenis_cuti',
			valueField: 'id_jenis_cuti',
			name: me.name,		
		});		
		me.callParent([arguments]);
	},
});