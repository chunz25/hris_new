Ext.define("SIAP.modules.absensi.PanelSchedule", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.panelschedule",
	alias: 'widget.panelschedule',
	requires: [
		'SIAP.components.field.ComboLokasiKerja'
	],
	initComponent: function(){
		var me = this;
						
		var storeschedule = Ext.create('Ext.data.Store', {
			storeId: 'storeschedule',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,			
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/schedule/getListSchedule',
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
				'id','nik','lokasi','namadepan','jabatan','tgl','tglupdate','tgl1','tgl2','tgl3','tgl4','tgl5','tgl6','tgl7','tgl8','tgl9','tgl10',
				'tgl11','tgl12','tgl13','tgl14','tgl15','tgl16','tgl17','tgl18','tgl19','tgl20',
				'tgl21','tgl22','tgl23','tgl24','tgl25','tgl26','tgl27','tgl28','tgl29','tgl30','tgl31','a','i','sk','ct','ro','off','p1','s1','m1'
			],
			listeners: {
				beforeload: function(store){
					var tglmulai = Ext.Date.format(me.down('#id_tglmulai').getValue(),'d/m/Y');
					var tglselesai = Ext.Date.format(me.down('#id_tglselesai').getValue(),'d/m/Y');
										
					Ext.getStore('storeschedule').proxy.extraParams.tglmulai = tglmulai;
					Ext.getStore('storeschedule').proxy.extraParams.tglselesai = tglselesai;
					me.fireEvent("beforeload", store);
				}
			}
		});
		
		Ext.apply(me, {
			layout: 'border',
			items: [
				{xtype: 'grid', region: 'center', layout: 'fit',
					autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
					store: storeschedule,
					columns: [
						{header: 'No', xtype: 'rownumberer', width: 30}, 
						{header: 'Store', dataIndex: 'lokasi', width: 100},
						{header: 'NIK', dataIndex: 'nik', width: 65},
						{header: 'Nama', dataIndex: 'namadepan', width: 120},
						{header: 'Jabatan', dataIndex: 'jabatan', width: 75},
						{header: 'Tgl', dataIndex: 'tgl', width: 70}, 

						{header: 'Tgl Update', dataIndex: 'tgl', width: 70, align:'center',
						renderer: function(value,meta,record,index) {												
								if(record.data.tglupdate != null ) {
									return record.data.tglupdate;
								} else {
									return '-';
								}											
							}
						}, 
						{header: 'Tanggal', align: 'left',
							columns:[
								{header: '1', dataIndex: 'tgl1', width: 35, align:'center'},
								{header: '2', dataIndex: 'tgl2', width: 35, align:'center'},
								{header: '3', dataIndex: 'tgl3', width: 35, align:'center'},
								{header: '4', dataIndex: 'tgl4', width: 35, align:'center'},
								{header: '5', dataIndex: 'tgl5', width: 35, align:'center'},
								{header: '6', dataIndex: 'tgl6', width: 35, align:'center'},
								{header: '7', dataIndex: 'tgl7', width: 35, align:'center'},
								{header: '8', dataIndex: 'tgl8', width: 35, align:'center'},
								{header: '9', dataIndex: 'tgl9', width: 35, align:'center'},
								{header: '10', dataIndex: 'tgl10', width: 35, align:'center'},

								{header: '11', dataIndex: 'tgl11', width: 35, align:'center'},
								{header: '12', dataIndex: 'tgl12', width: 35, align:'center'},
								{header: '13', dataIndex: 'tgl13', width: 35, align:'center'},
								{header: '14', dataIndex: 'tgl14', width: 35, align:'center'},
								{header: '15', dataIndex: 'tgl15', width: 35, align:'center'},
								{header: '16', dataIndex: 'tgl16', width: 35, align:'center'},
								{header: '17', dataIndex: 'tgl17', width: 35, align:'center'},
								{header: '18', dataIndex: 'tgl18', width: 35, align:'center'},
								{header: '19', dataIndex: 'tgl19', width: 35, align:'center'},
								{header: '20', dataIndex: 'tgl20', width: 35, align:'center'},

								{header: '21', dataIndex: 'tgl21', width: 35, align:'center'},
								{header: '22', dataIndex: 'tgl22', width: 35, align:'center'},
								{header: '23', dataIndex: 'tgl23', width: 35, align:'center'},
								{header: '24', dataIndex: 'tgl24', width: 35, align:'center'},
								{header: '25', dataIndex: 'tgl25', width: 35, align:'center'},
								{header: '26', dataIndex: 'tgl26', width: 35, align:'center'},
								{header: '27', dataIndex: 'tgl27', width: 35, align:'center'},
								{header: '28', dataIndex: 'tgl28', width: 35, align:'center'},
								{header: '29', dataIndex: 'tgl29', width: 35, align:'center'},
								{header: '30', dataIndex: 'tgl30', width: 35, align:'center'},
								{header: '31', dataIndex: 'tgl31', width: 35, align:'center'},
							]
						},
						{header: 'Informasi', align: 'left',
							columns:[
								{header: 'A', dataIndex: 'a', width: 35}, 
								{header: 'I', dataIndex: 'i', width: 35}, 
								{header: 'SK', dataIndex: 'sk', width: 35}, 
								{header: 'CT', dataIndex: 'ct', width: 35},
								{header: 'RO', dataIndex: 'ro', width: 35},
								{header: 'OFF', dataIndex: 'off', width: 35},
								{header: 'P1', dataIndex: 'p1', width: 35},
								{header: 'M1', dataIndex: 'm1', width: 35},	
								{header: 'S1', dataIndex: 's1', width: 35},
							]
						},
					],
					bbar: Ext.create('Ext.toolbar.Paging',{
						displayInfo: true,
						height : 35,
						store: 'storeschedule'
					})
				},
			],
			tbar: [
				{itemId: 'id_ketstatus_lokasi', xtype: 'combolokasikerja', emptyText: 'lokasi'},
				{itemId: 'id_tglmulai', xtype: 'datefield', fieldLabel: 'Periode', format: 'd/m/Y', name: 'tglmulai', value: (moment().startOf("month").format('DD/MM/YYYY')), emptyText: 'Tgl Awal', labelWidth:40, style: 'margin-left:10px;'},
				{itemId: 'id_tglselesai', xtype: 'datefield', fieldLabel: 's/d', format: 'd/m/Y', name: 'tglselesai', value: (moment().endOf("month").format('DD/MM/YYYY')), emptyText: 'Tgl Akhir', labelWidth:20, style: 'margin-left:5px;'}, 
				'-',
				{glyph:'xf002@FontAwesome',
					handler: function() {
						var tglmulai = Ext.Date.format(me.down('#id_tglmulai').getValue(),'d/m/Y');
						var tglselesai = Ext.Date.format(me.down('#id_tglselesai').getValue(),'d/m/Y');

						Ext.getStore('storeschedule').proxy.extraParams.lokasi = Ext.ComponentQuery.query('#id_ketstatus_lokasi')[0].getValue();
						Ext.getStore('storeschedule').proxy.extraParams.tglmulai = tglmulai;
						Ext.getStore('storeschedule').proxy.extraParams.tglselesai = tglselesai;
						Ext.getStore('storeschedule').load();
					}
				},
				'->',					
				{ glyph:'xf196@FontAwesome', text: 'Upload File',
					handler: function(){
						me.win_import();
					}
				},
			]
		});

		me.callParent([arguments]);
	},
	win_import: function(){
		var me = this;
		var win = Ext.create('Ext.window.Window',{
			title: 'Import Data', 
			width: 430,
			closeAction: 'destroy', modal:true, layout: 'fit', autoScroll: true, autoShow: true,
			buttons: [
				{text: 'Simpan', 
					handler: function(){
						win.down('form').getForm().submit({
							waitTitle: 'Menyimpan...', 
							waitMsg: 'Sedang menyimpan data, mohon tunggu...',
							url: Settings.SITE_URL + '/schedule/get_import_file',
							method: 'POST',
							success: function(form, action){
								var obj = Ext.decode(action.response.responseText);
								
								var winprogress = Ext.create('SIAP.components.progressbar.WinProgress',{
									title: 'Proses Import Data',
									URL: Settings.SITE_URL + '/schedule/proses_import_file',
								});
								var extraParams = {};
								winprogress.proses_exec(obj.data, extraParams);		
								winprogress.on('finished', function(p){
									me.down('grid').getStore().load();
								});																															
							},
							failure: function(form, action) {
							}																				
						});																		
					}
				},
				{text: 'Batal', 
					handler: function(){
						win.destroy();
					}
				},
			],
			items: [
				{ xtype: 'form', waitMsgTarget:true, bodyPadding: 15, layout: 'anchor', defaultType: 'textfield', region: 'center', autoScroll: true,
					defaults: {
						labelWidth: 100, anchor: '100%'
					},					
					items: [
						{ xtype: 'filefield', name: 'dokumen', fieldLabel: 'Dokumen', allowBlank: false, buttonText: 'Pilih File ...'}, 
						/*{ xtype: 'box', fieldLabel: 'label',
						  autoEl: {tag: 'a', href: Settings.SITE_URL + "/c_targetvisitor/cetak/template", children: [{tag: 'div', html: 'Download Template'}]},
						  style: 'cursor:pointer;'
						}*/						
					]
				},
			]
		});				
	}	

});

