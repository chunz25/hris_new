Ext.define("SIAP.modules.report.PanelReportDivisi", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.panelreportdivisi",
	alias: 'widget.panelreportdivisi',
	initComponent: function () {
		var me = this;

		var store_chart_divisi = Ext.create('Ext.data.Store', {
			storeId: 'statistikdivisi',
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getGraphByDivisi',
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
				{ name: 'name', mapping: 'satker' },
				{ name: 'satkerid', mapping: 'satkerid' },
				{ name: 'data1', mapping: 'jml' },
			],
			listeners: {
				beforeload: function (store) {
				},
			}
		});

		var chart_divisi = Ext.create('Ext.chart.Chart', {
			itemId: 'chartdivisi',
			id: 'idchartdivisi',
			width: 1140, height: 500,
			autoScroll: true,
			// resizable:true,
			animate: {
				easing: 'ease',
				duration: 350
			},
			shadow: false,
			store: store_chart_divisi,
			axes: [{
				type: 'Numeric',
				position: 'left',
				fields: ['data1'],
				title: 'Total',
				grid: false,
				minimum: 0,
				// maximum: 1000
			}, {
				type: 'Category',
				position: 'bottom',
				fields: ['name'],
				title: 'Unit',
				label: {
					rotate: {
						degrees: 270
					}
				}
			}],
			series: [{
				type: 'column',
				axis: 'left',
				gutter: 10,
				highlight: true,
				xField: 'name',
				yField: ['data1'],
				label: {
					display: 'outside',
					field: 'data1',
					color: '#333',
					// renderer: Ext.util.Format.numberRenderer('0'),
					'text-anchor': 'middle',
				},
				tips: {
					trackMouse: true,
					width: 74,
					height: 38,
					renderer: function (storeItem, item) {
						this.setTitle(storeItem.get('name'));
						this.update(storeItem.get('data1'));
					}
				},
				style: {
					fill: '#4f81bd'
				},
				listeners: {
					itemmouseup: function (obj) {
						this.chart.fireEvent('chartitemmouseup', obj);
					}
				}
			}],
			listeners: {
				chartitemmouseup: function (obj) {
					Ext.getStore('storereportdivisi').proxy.extraParams.satkerid = obj.storeItem.data.satkerid;
					Ext.getStore('storereportdivisi').load();
				},
				load: function (me, selection) {
					me.refresh();
				}
			}

		});

		var storereportdivisi = Ext.create('Ext.data.Store', {
			storeId: 'storereportdivisi',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getReportListDivisi',
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
				'levelid', 'level', 'statuspegawaiid', 'statuspegawai', 'jeniskelamin', 'email', 'emailkantor', 'telp', 'tglmulai', 'tglselesai', 'lokasi', 'keterangan'
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
					xtype: 'panel', region: 'center', title: 'Graph', autoScroll: true,
					// resizable:true,
					items: chart_divisi
				},
				{
					xtype: 'grid', region: 'south', title: 'Data Karyawan', height: 250, collapsed: true, collapsible: true,
					autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
					store: storereportdivisi,
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
						{ header: 'Tgl Masuk', dataIndex: 'tglmulai', width: 80 },
					],
					bbar: Ext.create('Ext.toolbar.Paging', {
						displayInfo: true,
						height: 35,
						store: 'storereportdivisi'
					})

				},
			],
			tbar: ['->',
				{
					glyph: 'xf02f@FontAwesome', text: 'Cetak',
					handler: function () {
						var m = me.down('grid').getStore().proxy.extraParams;
						window.open(Settings.SITE_URL + "/report/cetakdokumen/bydivisi?" + objectParametize(m));
					}
				}
			]
		});

		me.callParent([arguments]);


	}
});

