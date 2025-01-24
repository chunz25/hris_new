Ext.define("App.modules.info.news.GridNews", {
	extend: "Ext.view.View",
	alternateClassName: "App.gridnews",
	alias: 'widget.gridnews',
	initComponent: function(){
		var me = this;
		me.addEvents({"beforeload": true});
		var store_news = Ext.create('Ext.data.Store', {
			storeId: 'store_news',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,			
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/c_info/get_list_news',
				actionMethods: {
					create : 'POST',
					read   : 'POST',
				},								
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'count'
				}
			},
			fields: [
				'beritaid', 'title', 'text', 'status', 'tglupdate', 'gambar'
			],
			listeners: {
				beforeload: function(store){
					me.fireEvent("beforeload", store);
				}
			}
		});
		var resultTpl = Ext.create('Ext.XTemplate',
			'<tpl for=".">',
				'<div class="div-news">',
					'<span class="title"><a href="#">{title}</a></span>',
					'<p>{text}</p>',
				'</div>',
			'</tpl>'
		);
		Ext.apply(me, {
			layout: 'fit', bodyPadding: 10,
			autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
			store: store_news,						
			overflowY: 'auto',
            tpl: resultTpl,
            itemSelector: 'div.div-news',
            emptyText: '<div class="x-grid-empty">No Matching Threads</div>',						
			bbar: Ext.create('Ext.toolbar.Paging',{
				displayInfo: true,
				height : 35,
				store: 'store_info'
			})
		});

		me.callParent([arguments]);
	}

});