Ext.define("SIAP.modules.absensi.GridDetailPrice2",{
	extend: "Ext.grid.Panel",
	alternateClassName: "SIAP.griddetailprice2",
	alias: 'widget.griddetailprice2',	
	isDefaultSelectAll: false,
	params : {},
	isSave: true,
	initComponent: function(){
		var me = this;
		me.addEvents({"beforeload": true});
		var storeprice = Ext.create('Ext.data.Store', {
			storeId: 'storeprice',
			autoLoad: false,
			pageSize: Settings.PAGESIZE,			
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/absensi/getListPegawai',
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
				'id','pegawaiid','nik','lokasi','nama','jabatan','tgl','tglupdate','level','fingerid','direktorat', 'divisi', 'departemen', 'seksi', 'subseksi'
			],
			groupField : 'id',
			sortInfo:{field: 'Ordering', direction: "DESC"},			
			// remoteGroup:true,
			// remoteSort: true,						
			listeners: {
				beforeload: function(store){
					me.fireEvent("beforeload", store);
				},
				load :function(str, records, a, b){
					Ext.each(records, function(rec,i){
						if(rec.data.HAS == 1){
							cb.select(rec,true);
						}
						else cb.deselect(rec,true);
					});
				}								
			}
		});
		
		var cb = {};
		cb = Ext.create('Ext.selection.CheckboxModel',{
			checkOnly: false,
		});
		Ext.apply(me, {
			layout: 'fit',
			autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
			selModel: cb,
			store: storeprice,			
			columns: [
				// {header: 'Pilih Semua',xtype: 'rownumberer', renderer: Ext.renderer.formatNominal},
				{header: 'No', xtype: 'rownumberer', width: 30}, 
				{header: 'NIK', dataIndex: 'nik', width: 80}, 
				{header: 'Nama', dataIndex: 'nama', width: 150},
				{header: 'Level', dataIndex: 'level', width: 120},
				{header: 'Jabatan', dataIndex: 'jabatan', width: 120},
				{header: 'Unit', align: 'left',
					columns:[
						{header: 'Direktorat', dataIndex: 'direktorat', width: 120}, 
						{header: 'Divisi', dataIndex: 'divisi', width: 120}, 
						{header: 'Departemen', dataIndex: 'departemen', width: 120}, 
						{header: 'Seksi', dataIndex: 'seksi', width: 120}, 
						{header: 'Sub Seksi', dataIndex: 'subseksi', width: 120}, 					
					]
				},
				{header: 'Lokasi', dataIndex: 'lokasi', width: 120},
				
			],
			listeners: {
				afterlayout: function(g, e){
					if(me.isDefaultSelectAll){
						g.getSelectionModel().selectAll();
					}
				}			
			},
			bbar: Ext.create('Ext.toolbar.Paging',{
				displayInfo: true,
				height : 35,
				store: 'storeprice'
			})		
		});

		me.callParent([arguments]);
	}

});