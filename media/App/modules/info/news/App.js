Ext.define("App.modules.info.news.App", {
	extend: "Ext.panel.Panel",
	alternateClassName: "App.infonews",
	alias: 'widget.content_news',
	requires : [
		'App.modules.info.news.GridNews'
	],
	initComponent: function() {
		var me = this;
		Ext.apply(me, {
			layout: 'fit', border: false,
			items: [
				{itemId: 'gridnews', xtype: 'gridnews', region: 'center', frame: true, border: false}
			],
			tbar: ['Berita']
		});
		me.callParent([arguments]);
	}	
});