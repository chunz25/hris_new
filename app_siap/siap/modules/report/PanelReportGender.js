Ext.define("SIAP.modules.report.PanelReportGender", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.panelreportgender",
	alias: 'widget.panelreportgender',
	initComponent: function () {
		var me = this;

		var store_chart_statuspeg = Ext.create('Ext.data.Store', {
			storeId: 'statistikgenderpegawai',
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getGraphByJenisKelamin',
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

		var storereportstatuspeg = Ext.create('Ext.data.Store', {
			storeId: 'storereportgenderpeg',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getReportListJenisKelamin',
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
				'levelid', 'level', 'statuspegawaiid', 'statuspegawai', 'jeniskelamin', 'email', 'emailkantor', 'telp', 'tglmulai', 'tglselesai', 'keterangan', 'lokasi', 'gender'
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

					colors: ['#0F5391', '#940F1D']

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
							store: store_chart_statuspeg,
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
									Ext.getStore('storereportgenderpeg').proxy.extraParams.jeniskelamin = obj.storeItem.data.labelid;
									Ext.getStore('storereportgenderpeg').load();
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
						{ header: 'Lokasi', dataIndex: 'lokasi', width: 120 },
						{ header: 'Jenis Kelamin', dataIndex: 'gender', width: 120 }
					],
					bbar: Ext.create('Ext.toolbar.Paging', {
						displayInfo: true,
						height: 35,
						store: 'storereportgenderpeg'
					})

				},
			],
			tbar: ['->',
				{
					glyph: 'xf02f@FontAwesome', text: 'Cetak',
					handler: function () {
						var m = me.down('grid').getStore().proxy.extraParams;
						window.open(Settings.SITE_URL + "/report/cetakdokumen/jeniskelamin?" + objectParametize(m));
					}
				},
			]
		});

		me.callParent([arguments]);
	}
});

