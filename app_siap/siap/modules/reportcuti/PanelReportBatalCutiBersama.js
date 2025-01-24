Ext.define("SIAP.modules.reportcuti.PanelReportBatalCutiBersama", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.panelreportbatalcutibersama",
	alias: 'widget.panelreportbatalcutibersama',
	initComponent: function () {
		var me = this;

		var storereportbatalcutibersama = Ext.create('Ext.data.Store', {
			storeId: 'storereportbatalcutibersama',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/reportcuti/getReportListBatalCutiBersama',
				actionMethods: {
					create: 'POST',
					read: 'POST',
				},
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'count'
				}
			},
			fields: [
				'namadepan', 'nik', 'tglmulai', 'tglselesai', 'detailjeniscuti', 'status', 'lokasi', 'jabatan', 'level', 'direktorat', 'divisi', 'departemen', 'seksi', 'subseksi', 'status', 'tglpermohonan'
			],
			listeners: {
				beforeload: function (store) {
					me.fireEvent("beforeload", store);
				}
			}
		});


		Ext.apply(me, {
			layout: 'border',
			items: [
				{
					xtype: 'grid', region: 'center', layout: 'fit',
					autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
					store: storereportbatalcutibersama,
					columns: [
						{ header: 'No', xtype: 'rownumberer', width: 30 },
						{ header: 'NIK', dataIndex: 'nik', width: 80 },
						{ header: 'Nama', dataIndex: 'namadepan', width: 150 },
						{ header: 'Tgl Pengajuan', dataIndex: 'tglpermohonan', width: 84 },
						/*{header: 'Level', dataIndex: 'level', width: 120},
						{header: 'Jabatan', dataIndex: 'jabatan', width: 180},*/
						{
							header: 'Unit Kerja', align: 'left',
							columns: [
								{ header: 'Direktorat', dataIndex: 'direktorat', width: 120 },
								{ header: 'Divisi', dataIndex: 'divisi', width: 120 },
								{ header: 'Departemen', dataIndex: 'departemen', width: 120 },
								/*{header: 'Seksi', dataIndex: 'seksi', width: 120}, 
								{header: 'Sub Seksi', dataIndex: 'subseksi', width: 120},*/
							]
						},
						{ header: 'Status', dataIndex: 'status', width: 190 },
						{ header: 'Jenis Cuti', dataIndex: 'detailjeniscuti', width: 100 },
						{ header: 'Tgl Mulai', dataIndex: 'tglmulai', width: 80 },
						{ header: 'Tgl Selesai', dataIndex: 'tglselesai', width: 80 },
						{ header: 'Lokasi', dataIndex: 'lokasi', width: 120 },
					],
					bbar: Ext.create('Ext.toolbar.Paging', {
						displayInfo: true,
						height: 35,
						store: 'storereportbatalcutibersama'
					})

				},
			],
			tbar: [
				// {itemId: 'id_ketstatus_bulan', xtype: 'combobox',fieldLabel: 'Cuti Bersama ', name: 'date', labelWidth:100, style: 'margin-left:10px;',
				// 	queryMode: 'local', displayField: 'text', valueField: 'id',
				// 	store: Ext.create('Ext.data.Store', {
				// 		fields: ['id', 'text'],
				// 		data : [
				// 			{"id":"2020/08/21", "text":"21 Agustus 2020"},
				// 			{"id":"2020/10/28", "text":"28 Oktober 2020"},
				// 			{"id":"2020/10/30", "text":"30 Oktober 2020"},
				// 			{"id":"2020/12/24", "text":"24 Desember 2020"},
				// 			{"id":"2020/12/28", "text":"28 Desember 2020"},
				// 			{"id":"2020/12/29", "text":"29 Desember 2020"},
				// 			{"id":"2020/12/30", "text":"30 Desember 2020"},
				// 			{"id":"2020/12/31", "text":"31 Desember 2020"},
				// 		]												 
				// 	}),						
				// },	
				// { glyph:'xf002@FontAwesome', 
				// 	handler: function(){
				// 		var tree = Ext.ComponentQuery.query('#id_unitkerja')[0].getSelectionModel().getSelection();
				// 		treeid = null;
				// 		if(tree.length > 0){
				// 			treeid = tree[0].get('id');
				// 		}
				// 		Ext.getStore('storereportbatalcutibersama').proxy.extraParams.satkerid = treeid;
				// 		Ext.getStore('storereportbatalcutibersama').proxy.extraParams.date = Ext.ComponentQuery.query('#id_ketstatus_bulan')[0].getValue();
				// 		Ext.getStore('storereportbatalcutibersama').load();
				// 	}
				// },
				'->',
				{
					glyph: 'xf02f@FontAwesome', text: 'Cetak',
					handler: function () {
						var m = me.down('grid').getStore().proxy.extraParams;
						window.open(Settings.SITE_URL + "/reportcuti/cetakdokumen/batalcutibersama?" + objectParametize(m));
					}
				},
			]
		});

		me.callParent([arguments]);
	}
});

