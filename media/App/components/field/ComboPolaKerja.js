Ext.define('App.components.field.ComboPolaKerja', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combopolakerja',
	fieldLabel: '',
	name: 'polakerja',
	initComponent	: function() {	
		var me = this;	
		var store_grade = Ext.create('Ext.data.Store', {
			autoLoad : true,
			storeId: 'gradeStore',
			fields: ['polakerja_id','polakerja_nama'],
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/master/c_polakerja/get_combo_polakerja',
				reader: {
					type: 'json',
					root:'data'
				}
			},
		});		
		Ext.apply(me,{		
			store: store_grade,
			triggerAction : 'all',
			editable : false,
			displayField: 'polakerja_nama',
			valueField: 'polakerja_id',
			name: me.name,		
		});		
		me.callParent([arguments]);
	},
});