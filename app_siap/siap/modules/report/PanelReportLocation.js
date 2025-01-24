Ext.define("SIAP.modules.report.PanelReportLocation", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.panelreportlocation",
	alias: 'widget.panelreportlocation',
	initComponent: function () {
		var me = this;
		var storereportlocationbyid = Ext.create('Ext.data.Store', {
			storeId: 'storereportlocationbyid',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getReportListLocationByID',
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
				'lokasiid', 'lokasi', 'kodelokasi', 'jml'
			],
			listeners: {
				beforeload: function (store) {
					me.fireEvent("beforeload", store);
				}
			}
		});

		var storereportlocation = Ext.create('Ext.data.Store', {
			storeId: 'storereportlocation',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getReportListLocation',
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
				'pegawaiid', 'nama', 'nik', 'satkerid', 'direktorat', 'divisi', 'departemen', 'seksi', 'subseksi', 'jabatanid', 'jabatan',
				'levelid', 'level', 'lokasiid', 'lokasi', 'statuspegawai', 'tglmulai', 'tglakhirkontrak'
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
					store: storereportlocationbyid,
					columns: [
						{ header: 'No', xtype: 'rownumberer', width: 30 },
						{ header: 'Lokasi', dataIndex: 'lokasi', width: 160 },
						{ header: 'Kode Lokasi', dataIndex: 'kodelokasi', width: 80, align: 'center' },
						{ header: 'Jumlah Karyawan', dataIndex: 'jml', width: 160, align: 'center' },
					],
				},
				{
					xtype: 'grid', region: 'south', title: 'Data Karyawan', height: 250, collapsed: true, collapsible: true,
					autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
					store: storereportlocation,
					columns: [
						{ header: 'No', xtype: 'rownumberer', width: 30 },
						{ header: 'NIK', dataIndex: 'nik', width: 80 },
						{ header: 'Nama', dataIndex: 'nama', width: 150 },
						{ header: 'Level', dataIndex: 'level', width: 120 },
						{ header: 'Jabatan', dataIndex: 'jabatan', width: 180 },
						{
							header: 'Unit', align: 'left',
							columns: [
								{ header: 'Direktorat', dataIndex: 'direktorat', width: 120 },
								{ header: 'Divisi', dataIndex: 'divisi', width: 120 },
								{ header: 'Departemen', dataIndex: 'departemen', width: 120 },
								{ header: 'Seksi', dataIndex: 'seksi', width: 120 },
								{ header: 'Sub Seksi', dataIndex: 'subseksi', width: 120 },
							]
						},
						{ header: 'Lokasi', dataIndex: 'lokasi', width: 120 },
						{ header: 'Status', dataIndex: 'statuspegawai', width: 80 },
						{ header: 'Tgl Masuk', dataIndex: 'tglmulai', width: 80 },
						{ header: 'Tgl Habis Kontrak', dataIndex: 'tglakhirkontrak', width: 80 }
					],
					bbar: Ext.create('Ext.toolbar.Paging', {
						displayInfo: true,
						height: 35,
						store: 'storereportlocation'
					})

				},
			],
			tbar: ['->',
				{
					glyph: 'xf02f@FontAwesome', text: 'Cetak',
					handler: function () {
						var m = me.down('grid').getStore().proxy.extraParams;
						window.open(Settings.SITE_URL + "/report/cetakdokumen/bylocation?" + objectParametize(m));
					}
				}
			]
		});

		me.callParent([arguments]);


	}
});

