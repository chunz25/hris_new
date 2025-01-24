Ext.define("App.components.window.PejabatPenetap",{
	extend: "Ext.window.Window",
	alternateClassName: "App.pejabatpenetap" ,
	alias: 'widget.windaftarpejabatpenetap',
	title: 'Daftar Pejabat Penetap',	
	requires: [
		'App.components.grid.PejabatPenetap'
	],
	initComponent:function(){
		var me = this;
		me.addEvents({"pilih": true});		
		Ext.apply(me,{						
			width: 600, height: 400, closeAction: 'destroy', modal: true, autoShow: true,
			layout: 'fit',	
			items: [
				{xtype: 'griddaftarpejabatpenetap', layout: 'fit',
					listeners: {
						celldblclick: function(p, td, cellIndex, record, tr, rowIndex, e, opt){
							me.fireEvent("pilih", me, record);
							me.destroy();							
						}
					},
				}
			],
			buttons: [
				{text: 'Pilih',
					handler: function(){
						var m = me.down('griddaftarpejabatpenetap').getSelectionModel().getSelection();
						if(m.length > 0){							
							me.fireEvent("pilih", me, m[0]);
							me.destroy();							
						}						
					}
				},
				{text: 'Batal',
					handler: function(){
						me.destroy();
					}
				},
			]
		});
		me.callParent([arguments]);
	}
})