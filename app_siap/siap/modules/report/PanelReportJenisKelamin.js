Ext.define("SIAP.modules.report.PanelReportJenisKelamin", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.panelreportjeniskelamin",
	alias: 'widget.panelreportjeniskelamin',
	initComponent: function(){
		var me = this;
		
		var store_chart_jeniskelamin = Ext.create('Ext.data.Store', {
			storeId: 'statistikjeniskelamin',
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getGraphByJenisKelamin',
				actionMethods: {
					create : 'POST',
					read   : 'POST',
				},								
				reader: {
					type: 'json',
					root: 'data',
					// totalProperty: 'count'
				}
			},
			fields: [
				{name: 'name', mapping: 'label'}, 
				{name: 'data1', mapping: 'jml'}
			],
			listeners: {
				beforeload: function(store){
				},
			}
		});
						
		var storereportjeniskelamin = Ext.create('Ext.data.Store', {
			storeId: 'storereportjeniskelamin',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,			
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getReportListJenisKelamin',
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
				'pegawaiid', 'nama', 'nik', 'satkerid', 'direktorat', 'divisi', 'departemen', 'seksi', 'subseksi', 'jabatanid', 'jabatan', 
				'levelid', 'level', 'statuspegawaiid', 'statuspegawai', 'jeniskelamin', 'email', 'emailkantor', 'telp', 'tglmulai', 'tglselesai', 'keterangan'
			],
			listeners: {
				beforeload: function(store){
					me.fireEvent("beforeload", store);
				}
			}
		});
		
		
		Ext.apply(me, {
			layout: 'border',
			items: [
				{xtype: 'panel', region: 'west', layout: 'fit', width: 400,
					items: [					
						Ext.create('Ext.chart.Chart', {
							xtype: 'chart',
							animate: true,
							store: store_chart_jeniskelamin,
							shadow: true,
							legend: {
								position: 'right'
							},
							insetPadding: 10,
							theme: 'Base:gradients',
							style: 'border:1px solid #000;',
							series: [{
								type: 'pie',
								field: 'data1',
								showInLegend: true,
								// donut: 35,
								tips: {
									trackMouse: true,
									width: 140,
									height: 28,
									renderer: function(storeItem, item) {
										this.setTitle(storeItem.get('data1'));
									}
								},
								highlight: {
									segment: {
										margin: 20
									}
								},
								listeners: {
									itemmouseup: function(obj){
										this.chart.fireEvent('chartitemmouseup', obj);
									}   
								}						
							}],
							listeners: {
								 chartitemmouseup: function(obj){
									console.log('chartitemmouseup', obj)
								}
								
							}
						})							
					]
				},
				{xtype: 'grid', region: 'center', layout: 'fit',
					autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
					store: storereportjeniskelamin,
					columns: [
						{header: 'No', xtype: 'rownumberer', width: 30}, 
						{header: 'NIK', dataIndex: 'nik', width: 80}, 
						{header: 'Nama', dataIndex: 'nama', width: 150}, 
						{header: 'Jabatan', dataIndex: 'jabatan', width: 180},
						{header: 'Level', dataIndex: 'level', width: 120},
						{header: 'Title', dataIndex: 'level', width: 120},
						{header: 'Unit', align: 'left',
							columns:[
								{header: 'Direktorat', dataIndex: 'direktorat', width: 120}, 
								{header: 'Divisi', dataIndex: 'divisi', width: 120}, 
								{header: 'Departemen', dataIndex: 'departemen', width: 120}, 
								{header: 'Seksi', dataIndex: 'seksi', width: 120}, 
								{header: 'Sub Seksi', dataIndex: 'subseksi', width: 120}, 					
							]
						},
						{header: 'Tanggal Masuk', dataIndex: 'tglmasuk', width: 120},
						{header: 'Tanggal Keluar', dataIndex: 'tglkeluar', width: 120},
						{header: 'Alasan Keluar', dataIndex: 'alasankeluar', width: 120},
						{header: 'Status Pegawai', dataIndex: 'statuspegawai', width: 120},
						{header: 'Jenis Kelamin', dataIndex: 'jeniskelamin', width: 120},
						{header: 'Email', dataIndex: 'email', width: 120},
						{header: 'Telp', dataIndex: 'telp', width: 120},
					],
					bbar: Ext.create('Ext.toolbar.Paging',{
						displayInfo: true,
						height : 35,
						store: 'storereportjeniskelamin'
					})
					
				},
			],
			tbar: ['->',
				{text: 'Cetak'}
			]
		});

		me.callParent([arguments]);
	}
});

