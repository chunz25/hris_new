Ext.define("App.components.grid.PejabatPenetap",{
	extend: "Ext.grid.Panel",
	alternateClassName: "App.griddaftarpejabatpenetap",
	alias: 'widget.griddaftarpejabatpenetap',
	initComponent:function(){
		var me = this;
		var store =	Ext.create('Ext.data.Store', {
			storeId:'store_pejabat_penetap',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,			
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/master/c_pejabatpenetap/get_master_pejabat_penetap',
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
				'nip', 'id_pejabat_penetap', 'nama_pejabat_penetap', 'nip_pejabat_penetap', 'jabatan_pejabat_penetap', 'instansi_pejabat_penetap'
			],
			listeners: {
				beforeload:function(store){
					var key = me.down('#keyword').getValue();
					store.proxy.extraParams.keyword = key;
				}
			}			
		});				
		Ext.apply(me,{						
			store: store,
			autoScroll: true,
			columns: [
				{xtype: 'rownumberer', width: 30, header: 'No'}, 
				{header: "NIP Pejabat Penetap", dataIndex: 'nip_pejabat_penetap', width: 150}, 
				{header: "Nama Pejabat Penetap", dataIndex: 'nama_pejabat_penetap', width: 200}, 
				{header: "Jabatan Penetap", dataIndex: 'jabatan_pejabat_penetap', width: 250},
				{header: "Unit kerja/Instansi", dataIndex: 'instansi_pejabat_penetap', width: 250}
			],
			dockedItems: [{
				xtype: 'pagingtoolbar',
				store: store,
				dock: 'bottom',
				displayInfo: true
			}],
			tbar: [
				{itemId: 'keyword', xtype: 'textfield', fieldLabel: 'Pencarian', name: 'keyword', enableKeyEvents: true,				
					listeners: {
						keypress: function(t, e){
							if(e.getKey() == e.ENTER){
								me.getStore().load();
							}
						}						
					}
				},
				{text: 'Cari',
					handler: function(){
						me.getStore().load();
					}
				}
			]
		});
		me.callParent([arguments]);
	}
})