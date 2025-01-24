Ext.define('App.components.field.ComboAlasan', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.comboalasan',
	fieldLabel: '',
	name: 'alasan',
	isAll: true,
	initComponent	: function() {	
		var me = this;	
		var s = [];
		if(me.isAll){
			s = ['0', 'Semua'];
		}
		Ext.apply(me,{		
			store: Ext.create('Ext.data.SimpleStore', {
				fields: ['id', 'text'],
				data : [				
					s,
					['1', 'Datang Terlambat'],
					['2', 'Pulang Lebih Awal'],					
					['3', 'Datang Terlambat dan Pulang Awal']
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