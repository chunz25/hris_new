Ext.define('App.components.field.ComboStatusDinas', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combostatusdinas',
	fieldLabel: '',
	name: 'statusdinas',
	initComponent	: function() {	
		var me = this;	
		Ext.apply(me,{		
			store: Ext.create('Ext.data.SimpleStore', {
				fields: ['id', 'text'],
				data : [				
					['1', 'Aktif'],
					['0', 'Tidak Aktif'],
				]
			}),			
			triggerAction : 'all',
			editable : false,
			displayField: 'text',
			valueField: 'id',
			name: me.name,					
			value: 'L',
			typeAhead: false,
			mode: 'local',
			forceSelection: true,
			// selectOnFocus: true,			
		});		
		me.callParent([arguments]);
	},
});