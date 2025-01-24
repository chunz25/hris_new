Ext.define("App.modules.info.App", {
	extend: "Ext.panel.Panel",
	alternateClassName: "App.info",
	alias: 'widget.info',
	requires: [
		'App.modules.info.pengumuman.App',
		'App.modules.info.news.App',
	],		
	initComponent: function(){
		var me = this;
		Ext.apply(me,{		
			layout: 'border', border: false,
			items: [
				{region: 'west', title: 'Info', width: 200,
					items: [
						{xtype: 'menu', floating: false, layout: 'anchor', height: 600,
							items: [
								{text: 'Pengumuman', 
									handler: function(){
										me.submenu('pengumuman',{id:'pengumuman'});
									}
								},
								{text: 'Berita', 
									handler: function(){
										me.submenu('news',{id:'news'});
									}
								},
							]
						}
					]
				},			
				{ region: 'center', xtype: 'panel', id: 'containt_center', layout: 'fit'},	
			],		
			listeners :{
				afterrender : function(){
					me.add_component_topbar();
				}
			}			
		});		
		me.callParent([arguments]);
	},
	submenu: function(xtype, self){
		var me = this;
		var mask = new Ext.LoadMask(me, {
			msg: 'Loading ...'
		});
		mask.show();		
		Ext.getCmp('containt_center').removeAll();									
		Ext.getCmp('containt_center').add({
			xtype: 'content_' + xtype,
			layout: 'fit',
		});			
		Ext.getCmp('containt_center').doLayout();		
		setTimeout(function() {
			mask.hide();
		}, 100);		
	},		
	add_component_topbar: function(){
		var me = this;
		me.submenu('pengumuman',{id:'pengumuman'});
	}		
});