Ext.define("SIAP.modules.report.PanelReportUsia", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.panelreportusia",
	alias: 'widget.panelreportusia',
	initComponent: function () {
		var me = this;

		var store_chart_usia = Ext.create('Ext.data.Store', {
			storeId: 'statistikstatususia',
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getGraphByUsiaPegawai',
				actionMethods: {
					create: 'POST',
					read: 'POST',
				},
				reader: {
					type: 'json',
					root: 'data',
					// totalProperty: 'count'
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

		var storereportusia = Ext.create('Ext.data.Store', {
			storeId: 'storereportusia',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getReportListUsiaPegawai',
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
				'pegawaiid', 'namadepan', 'nik', 'satkerid', 'direktorat', 'divisi', 'departemen', 'seksi', 'subseksi', 'jabatanid', 'jabatan',
				'levelid', 'level', 'statuspegawaiid', 'statuspegawai', 'tglmulai', 'tglselesai', 'keterangan', 'lokasi', 'tgllahir', 'tahun', 'bulan'
			],
			listeners: {
				beforeload: function (store) {
					me.fireEvent("beforeload", store);
				}
			}
		});

		// changed color of pie chart
		Ext.define('Ext.chart.theme.ColumnTheme', {
			extend: 'Ext.chart.theme.Base',
			constructor: function (config) {
				this.callParent([Ext.apply({

					colors: ['#0F5391', '#940F1D', '#7D9308', '#D200FF', '#00A8FF']

				}, config)]);
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
							store: store_chart_usia,
							shadow: true,
							legend: {
								position: 'right'
							},
							insetPadding: 10,
							theme: 'ColumnTheme',
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
									Ext.getStore('storereportusia').proxy.extraParams.labelid = obj.storeItem.data.labelid;
									Ext.getStore('storereportusia').load();
								}

							}
						})
					]
				},
				{
					xtype: 'grid', region: 'center', layout: 'fit',
					autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
					store: storereportusia,
					columns: [
						{ header: 'No', xtype: 'rownumberer', width: 30 },
						{ header: 'NIK', dataIndex: 'nik', width: 80 },
						{ header: 'Nama', dataIndex: 'namadepan', width: 150 },
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
						{ header: 'Tgl Lahir', dataIndex: 'tgllahir', width: 80 },
						{
							text: 'Usia', dataIndex: 'tahun',
							renderer: function (value, p, r) {
								return (r.data['tahun'] + ' th ') + (r.data['bulan'] + ' bl');
							}
						},
					],
					bbar: Ext.create('Ext.toolbar.Paging', {
						displayInfo: true,
						height: 35,
						store: 'storereportusia'
					})

				},
			],
			tbar: ['->',
				{
					glyph: 'xf02f@FontAwesome', text: 'Cetak',
					handler: function () {
						var m = me.down('grid').getStore().proxy.extraParams;
						window.open(Settings.SITE_URL + "/report/cetakdokumen/reportusia?" + objectParametize(m));
					}
				},
			]
		});

		me.callParent([arguments]);
	}
});

