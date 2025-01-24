Ext.define("App.modules.info.pengumuman.GridInfo", {
	extend: "Ext.view.View",
	alternateClassName: "App.gridinfo",
	alias: 'widget.gridinfo',
	initComponent: function(){
		var me = this;
		me.addEvents({"beforeload": true});
		var store_info = Ext.create('Ext.data.Store', {
			storeId: 'store_info',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,			
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/c_info/get_list_info',
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
				'infoid', 'title', 'text', 'tglupdate'
			],
			listeners: {
				beforeload: function(store){
					me.fireEvent("beforeload", store);
				}
			}
		});
		
		var resultTpl = Ext.create('Ext.XTemplate',
			'<tpl for=".">',
				'<div class="div-info">',
					'<span class="title"><a href="#">{title}</a></span>',
					'<p>{text}</p>',
				'</div>',
			'</tpl>'
		);
		
		Ext.apply(me, {
			layout: 'fit', bodyPadding: 10,
			autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
			store: store_info,						
			overflowY: 'auto',
            tpl: resultTpl,
            itemSelector: 'div.div-info',
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