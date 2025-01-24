Ext.define('App.components.field.FieldSatker',{
	extend: 'App.abstract.FieldButton',
	alias: 'widget.fieldsatker',	
	requires:[
		'App.components.window.UnitKerja'
	],		
	config:{},	
	URL_SATKER: '',	
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
			return 'SATKER';
		else return this.name;
	},
	
	getFieldLabel:function(){
		if(this.fieldLabel=='unknown')
			return 'Master Unit';
		else return this.fieldLabel;
	},	
	createWindow:function(){
		var me=this;
		return Ext.create('App.components.window.UnitKerja', {
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
	
