Ext.define("SIAP.modules.report.PanelReportKetStatus", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.panelreportketstatus",
	alias: 'widget.panelreportketstatus',
	requires: [
		'SIAP.components.field.ComboBulan',
		'SIAP.components.field.FieldPeriode'
	],
	initComponent: function () {
		var me = this;

		var store_chart_ketstatus = Ext.create('Ext.data.Store', {
			storeId: 'statistikketstatus',
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getGraphByKetPegawai',
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

		var storereportketstatus = Ext.create('Ext.data.Store', {
			storeId: 'storereportketstatus',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getReportListKetPegawai',
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
				'ketstatus', 'pegawaiid', 'nama', 'nik', 'satkerid', 'direktorat', 'divisi', 'departemen', 'seksi', 'subseksi', 'jabatanid', 'jabatan',
				'levelid', 'level', 'statuspegawaiid', 'statuspegawai', 'tglmulai', 'tglselesai', 'tglakhirkontrak', 'lokasi'
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

					colors: ['#7D9308', '#A9A9A9']

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
							store: store_chart_ketstatus,
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
									var tree = Ext.ComponentQuery.query('#id_unitkerja')[0].getSelectionModel().getSelection();
									treeid = null;
									if (tree.length > 0) {
										treeid = tree[0].get('id');
									}
									Ext.getStore('storereportketstatus').proxy.extraParams.satkerid = treeid;
									Ext.getStore('storereportketstatus').proxy.extraParams.bulan = Ext.ComponentQuery.query('#id_ketstatus_bulan')[0].getValue();
									Ext.getStore('storereportketstatus').proxy.extraParams.tahun = Ext.ComponentQuery.query('#id_ketstatus_thn')[0].getValue();
									Ext.getStore('storereportketstatus').proxy.extraParams.ketstatus = obj.storeItem.data.labelid;
									Ext.getStore('storereportketstatus').load();
								}

							}
						})
					]
				},
				{
					xtype: 'grid', region: 'center', layout: 'fit',
					autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
					store: storereportketstatus,
					columns: [
						{ header: 'No', xtype: 'rownumberer', width: 30 },
						{ header: 'NIK', dataIndex: 'nik', width: 80 },
						{ header: 'Nama', dataIndex: 'nama', width: 150 },
						{ header: 'Level', dataIndex: 'level', width: 120 },
						{ header: 'Jabatan', dataIndex: 'jabatan', width: 180 },
						/*{header: 'Unit', align: 'left',
							columns:[
								{header: 'Direktorat', dataIndex: 'direktorat', width: 120}, 
								{header: 'Divisi', dataIndex: 'divisi', width: 120}, 
								{header: 'Departemen', dataIndex: 'departemen', width: 120}, 
								{header: 'Seksi', dataIndex: 'seksi', width: 120}, 
								{header: 'Sub Seksi', dataIndex: 'subseksi', width: 120}, 					
							]
						},*/
						{ header: 'Lokasi', dataIndex: 'lokasi', width: 120 },
						{ header: 'Status', dataIndex: 'statuspegawai', width: 120 },
						{ header: 'Tgl Masuk', dataIndex: 'tglmulai', width: 80 },
						{ header: 'Tgl Habis Kontrak', dataIndex: 'tglakhirkontrak', width: 120 },
						{ header: 'Tgl Keluar', dataIndex: 'tglselesai', width: 80 },
					],
					bbar: Ext.create('Ext.toolbar.Paging', {
						displayInfo: true,
						height: 35,
						store: 'storereportketstatus'
					})

				}
			],
			tbar: [
				{
					itemId: 'id_ketstatus_bulan', xtype: 'combobulan',
					listeners: {
						afterrender: function () {
							this.setValue('');
						}
					}
				},
				{ itemId: 'id_ketstatus_thn', xtype: 'fieldperiode', value: new Date().getFullYear() },
				{
					glyph: 'xf002@FontAwesome',
					handler: function () {
						var tree = Ext.ComponentQuery.query('#id_unitkerja')[0].getSelectionModel().getSelection();
						treeid = null;
						if (tree.length > 0) {
							treeid = tree[0].get('id');
						}
						Ext.getStore('statistikketstatus').proxy.extraParams.satkerid = treeid;
						Ext.getStore('statistikketstatus').proxy.extraParams.bulan = Ext.ComponentQuery.query('#id_ketstatus_bulan')[0].getValue();
						Ext.getStore('statistikketstatus').proxy.extraParams.tahun = Ext.ComponentQuery.query('#id_ketstatus_thn')[0].getValue();
						Ext.getStore('statistikketstatus').load();

						Ext.getStore('storereportketstatus').proxy.extraParams.satkerid = treeid;
						Ext.getStore('storereportketstatus').proxy.extraParams.bulan = Ext.ComponentQuery.query('#id_ketstatus_bulan')[0].getValue();
						Ext.getStore('storereportketstatus').proxy.extraParams.tahun = Ext.ComponentQuery.query('#id_ketstatus_thn')[0].getValue();
						Ext.getStore('storereportketstatus').proxy.extraParams.ketstatus = null;
						Ext.getStore('storereportketstatus').load();
					}
				},
				'->',
				{
					glyph: 'xf02f@FontAwesome', text: 'Cetak',
					handler: function () {
						var m = me.down('grid').getStore().proxy.extraParams;
						window.open(Settings.SITE_URL + "/report/cetakdokumen/ketstatus?" + objectParametize(m));
					}
				},
			]
		});

		me.callParent([arguments]);
	}
});

