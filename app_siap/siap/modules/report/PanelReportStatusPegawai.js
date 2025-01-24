Ext.define("SIAP.modules.report.PanelReportStatusPegawai", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.panelreportstatuspegawai",
	alias: 'widget.panelreportstatuspegawai',
	initComponent: function () {
		var me = this;

		var store_chart_statuspeg = Ext.create('Ext.data.Store', {
			storeId: 'statistikstatuspegawai',
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getGraphByStatusPegawai',
				actionMethods: {
					create: 'POST',
					read: 'POST',
				},
				reader: {
					type: 'json',
					root: 'data',
				}
			},
			fields: [
				{ name: 'name', mapping: 'label' },
				{ name: 'labelid', mapping: 'labelid' },
				{ name: 'data1', mapping: 'jml' }
			],
			listeners: {
				beforeload: function (store) {
				},
			}
		});

		var storereportstatuspeg = Ext.create('Ext.data.Store', {
			storeId: 'storereportstatuspeg',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getReportListStatusPegawai',
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
				'levelid', 'level', 'statuspegawaiid', 'statuspegawai', 'jeniskelamin', 'email', 'emailkantor', 'telp', 'tglmulai', 'tglselesai', 'keterangan', 'lokasi', 'tglakhirkontrak'
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
					xtype: 'panel', region: 'west', layout: 'fit', width: 400,
					items: [
						Ext.create('Ext.chart.Chart', {
							xtype: 'chart',
							animate: true,
							store: store_chart_statuspeg,
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
								tips: {
									trackMouse: true,
									width: 140,
									height: 28,
									renderer: function (storeItem, item) {
										this.setTitle(storeItem.get('data1') + ' ' + storeItem.data.name);
									}
								},
								highlight: {
									segment: {
										margin: 20
									}
								},
								listeners: {
									itemmouseup: function (obj) {
										this.chart.fireEvent('chartitemmouseup', obj);
									}
								}
							}],
							listeners: {
								chartitemmouseup: function (obj) {
									Ext.getStore('storereportstatuspeg').proxy.extraParams.statuspegawaiid = obj.storeItem.data.labelid;
									Ext.getStore('storereportstatuspeg').load();
								}

							}
						})
					]
				},
				{
					xtype: 'grid', region: 'center', layout: 'fit',
					autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
					store: storereportstatuspeg,
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
						{ header: 'Tgl Habis Kontrak', dataIndex: 'tglakhirkontrak', width: 80 },
					],
					bbar: Ext.create('Ext.toolbar.Paging', {
						displayInfo: true,
						height: 35,
						store: 'storereportstatuspeg'
					})

				},
			],
			tbar: ['->',
				{
					glyph: 'xf02f@FontAwesome', text: 'Cetak',
					handler: function () {
						var m = me.down('grid').getStore().proxy.extraParams;
						window.open(Settings.SITE_URL + "/report/cetakdokumen/statuspegawai?" + objectParametize(m));
					}
				},
			]
		});

		me.callParent([arguments]);
	}
});

