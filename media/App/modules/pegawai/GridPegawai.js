Ext.define("App.modules.pegawai.GridPegawai", {
	extend: "Ext.grid.Panel",
	alternateClassName: "App.gridpegawai",
	alias: 'widget.gridpegawai',
	initComponent: function(){
		var me = this;
		me.addEvents({"beforeload": true});
		var store_pegawai = Ext.create('Ext.data.Store', {
			storeId: 'store_pegawai',
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
				'pegawaiid', 'nik', 'nama', 'gelar', 'tempatlahir', 'tgllahir', 'jeniskelamin', 'alamat', 'telp', 'voip', 'hp', 'extension',
				'email', 'statuspegawaiid', 'statuspegawai', 'satkerid', 'satker', 'jabatanid', 'jabatan', 'foto', 'fileext', 'tglmasuk'
			],
			listeners: {
				beforeload: function(store){
					me.fireEvent("beforeload", store);
				}
			}
		});
		Ext.apply(me, {
			layout: 'fit',
			autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
			store: store_pegawai,			
			columns: [
				{header: 'No', xtype: 'rownumberer', width: 30}, 
				{header: 'NIK', dataIndex: 'nik', width: 160}, 
				{header: 'Nama', dataIndex: 'nama', width: 250}, 
				{hidden: true, header: 'Status Pegawai', dataIndex: 'statuspegawai', width: 120}, 
				{header: 'Jabatan', dataIndex: 'jabatan', width: 250},
				{header: 'Departemen', dataIndex: 'satker', width: 250}, 
				{header: 'Tgl Join', dataIndex: 'tglmasuk', width: 120}, 
				{header: 'Email', dataIndex: 'email', width: 200},
				{header: 'HP', dataIndex: 'hp', width: 120},
				{header: 'VOIP', dataIndex: 'voip', width: 120},
				{header: 'Extension HO', dataIndex: 'extension', width: 120},
			],
			bbar: Ext.create('Ext.toolbar.Paging',{
				displayInfo: true,
				height : 35,
				store: 'store_pegawai'
			})
		});

		me.callParent([arguments]);
	}

});