Ext.define("App.modules.info.pengumuman.App", {
	extend: "Ext.panel.Panel",
	alternateClassName: "App.infopengumuman",
	alias: 'widget.content_pengumuman',
	requires : [
		'App.modules.info.pengumuman.GridInfo'
	],
	initComponent: function() {
		var me = this;
		Ext.apply(me, {
			layout: 'fit', border: false,
			items: [
				{itemId: 'gridInfo', xtype: 'gridinfo', region: 'center', frame: true, border: false}
			],
			tbar: ['Pengumuman']
		});
		me.callParent([arguments]);
	}	
});