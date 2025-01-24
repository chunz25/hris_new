Ext.define('App.components.field.ComboStatusPegawai', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combostatuspegawai',
	fieldLabel: '',
	name: 'statuspegawai',
	initComponent	: function(){	
		var me = this;	
		Ext.apply(me,{		
			store: Ext.create('Ext.data.SimpleStore', {
				fields: ['id', 'text'],
				data : [				
					['1', 'Kontrak'],
					['2', 'Permanen'],
					['3', 'Probation'],
					['4', 'Resign'],
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