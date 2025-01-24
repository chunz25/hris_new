Ext.define('App.components.field.ComboJenisKelamin', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combojeniskelamin',
	fieldLabel: '',
	name: 'jeniskelamin',
	initComponent	: function() {	
		var me = this;	
		Ext.apply(me,{		
			store: Ext.create('Ext.data.SimpleStore', {
				fields: ['id', 'text'],
				data : [				
					['L', 'Laki-laki'],
					['P', 'Perempuan'],
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