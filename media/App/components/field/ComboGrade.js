Ext.define('App.components.field.ComboGrade', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combograde',
	fieldLabel: '',
	name: 'grade',
	initComponent	: function() {	
		var me = this;	
		var store_grade = Ext.create('Ext.data.Store', {
			autoLoad : true,
			storeId: 'gradeStore',
			fields: ['kode_kelas'],
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/master/c_jabatan/get_grade_jabatan',
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
			displayField: 'kode_kelas',
			valueField: 'kode_kelas',
			name: me.name,		
		});		
		me.callParent([arguments]);
	},
});