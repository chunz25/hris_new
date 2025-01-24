Ext.define('App.components.field.ComboSemester', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combosemester',
	fieldLabel: '',
	name: 'semester',
	initComponent	: function() {	
		var me = this;	
		Ext.apply(me,{		
			store: Ext.create('Ext.data.SimpleStore', {
				fields: ['id', 'text'],
				data : [				
					['1', 'Semester I'],
					['2', 'Semester II'],
				]
			}),			
			triggerAction : 'all',
			editable : false,
			displayField: 'text',
			valueField: 'id',
			name: me.name,		
		});		
		me.callParent([arguments]);
	},
});