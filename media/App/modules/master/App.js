Ext.define("App.modules.master.App", {
	extend: "Ext.panel.Panel",
	alternateClassName: "App.master",
	alias: 'widget.master',
	requires: [
		'App.modules.master.satker.App',		
		'App.modules.master.jabatan.App',
		'App.modules.master.user.App'
	],		
	initComponent: function(){
		var me = this;
		Ext.apply(me,{		
			layout: 'border', border: false,
			dockedItems: [
				{ xtype: 'toolbar', id: 'master_toolbar_right', dock: 'top'}
			],			
			items: [
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
		var submenu = [
			{text: 'Unit Kerja', handler: function(){me.submenu('satker',this)}},
			{text: 'Jabatan', handler: function(){me.submenu('jabatan',this)}},
			{text: 'User', handler: function(){me.submenu('user',this)}},
		];
		Ext.getCmp('master_toolbar_right').add(submenu);
		me.submenu('satker',{id:'satker'});
		// me.submenu('user',{id:'user'});
	}	
	
});