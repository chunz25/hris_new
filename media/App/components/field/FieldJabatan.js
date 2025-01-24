Ext.define('App.components.field.FieldJabatan',{
	extend: 'App.abstract.FieldButton',
	alias: 'widget.fieldjabatan',	
	requires:['App.components.window.WinJabatan'],		
	config:{
		onlyFungsional: false,
	},	
	URL_JABATAN: '',	
	initComponent: function(){	
		var me = this;
		me.addEvents({
			"itemclick"	: true,
			"pilih"		: true,
			"batal"		: true
		});		
		me.fieldLabel = me.getFieldLabel();
		me.name = me.getName();
		me.autoHeight = true;
			  
		me.callParent([arguments]);	
	},		
	getName:function(){
		if(this.name=='unknown')
			return 'KAMUSJABATAN';
		else return this.name;
	},
	
	getFieldLabel:function(){
		if(this.fieldLabel=='unknown')
			return 'Master Jabatan';
		else return this.fieldLabel;
	},	
	createWindow:function(){
		var me=this;
		return Ext.create('App.components.window.WinJabatan', {
			width: 400, height: 500,
			listeners: {
				pilih: function(records){
					me.field.setValue(records.get('text'));
					me.fieldid.setValue(records.get('id'));
					me.fireEvent('pilih',records);
				}
			}
		});
	}
});
	
