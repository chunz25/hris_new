Ext.define("App.components.grid.Pegawai",{
	extend: "Ext.grid.Panel",
	alternateClassName: "App.griddaftarpegawai",
	alias: 'widget.griddaftarpegawai',
	isSex: '',
	initComponent:function(){
		var me = this;
		var store =	Ext.create('Ext.data.Store', {
			storeId:'simpsonsStore',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,			
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/c_pegawai/get_daftar_pegawai',
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
				'no_pekerja', 'nip', 'nama', 'jeniskelamin', 'jabatan', 'golongan', 'unitkerja', 'unitkerjaid', 'noenroll'
			],
			listeners: {
				beforeload:function(store){
					var key = me.down('#cari_pegawai').getValue();
					store.proxy.extraParams.keyword = key;
					store.proxy.extraParams.sex = me.isSex;
				}
			}			
		});				
		Ext.apply(me,{						
			store: store,
			autoScroll: true,
			columns: [
				{xtype: 'rownumberer', width: 30, header: 'No'}, 
				{header: "NIP", dataIndex: 'nip', width: 150}, 
				{header: "Nama", dataIndex: 'nama', width: 200}, 
				{header: "Jabatan", dataIndex: 'jabatan', width: 250},
				{header: "Unit Kerja", dataIndex: 'unitkerja', width: 250}
			],
			dockedItems: [{
				xtype: 'pagingtoolbar',
				store: store,
				dock: 'bottom',
				displayInfo: true
			}],
			tbar: [
				{itemId: 'cari_pegawai', xtype: 'textfield', fieldLabel: 'Pencarian', name: 'keyword', enableKeyEvents: true,				
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