Ext.define("SIAP.modules.report.PanelReportLevel", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.panelreportlevel",
	alias: 'widget.panelreportlevel',
	initComponent: function () {
		var me = this;

		var store_chart_level = Ext.create('Ext.data.Store', {
			storeId: 'statistiklevel',
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getGraphByLevel',
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
				{ name: 'name', mapping: 'level' },
				{ name: 'levelid', mapping: 'levelid' },
				{ name: 'data1', mapping: 'jml' },
			],
			listeners: {
				beforeload: function (store) {
				},
			}
		});

		var chart_level = Ext.create('Ext.chart.Chart', {
			itemId: 'chartlevel',
			animate: {
				easing: 'ease',
				duration: 350
			},
			shadow: false,
			width: 1140, height: 500,
			autoScroll: true,
			store: store_chart_level,
			axes: [{
				type: 'Numeric',
				position: 'left',
				fields: ['data1'],
				title: 'Total',
				grid: false,
				minimum: 0,
			}, {
				type: 'Category',
				position: 'bottom',
				fields: ['name'],
				title: 'Level',
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
					'text-anchor': 'middle'
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
					Ext.getStore('storereportlevel').proxy.extraParams.levelid = obj.storeItem.data.levelid;
					Ext.getStore('storereportlevel').load();
				},
				load: function (me, selection) {
					me.refresh();
				}
			}

		});

		var storereportlevel = Ext.create('Ext.data.Store', {
			storeId: 'storereportlevel',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getReportListLevel',
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
				'levelid', 'level', 'gol', 'lokasi', 'statuspegawai'
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
					items: chart_level
				},
				{
					xtype: 'grid', region: 'south', title: 'Data Karyawan', height: 250, collapsed: true, collapsible: true,
					autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
					store: storereportlevel,
					columns: [
						{ header: 'No', xtype: 'rownumberer', width: 30 },
						{ header: 'NIK', dataIndex: 'nik', width: 80 },
						{ header: 'Nama', dataIndex: 'nama', width: 150 },
						{ header: 'Grade', dataIndex: 'gol', width: 60 },
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
						{ header: 'Status', dataIndex: 'statuspegawai', width: 120 }
					],
					bbar: Ext.create('Ext.toolbar.Paging', {
						displayInfo: true,
						height: 35,
						store: 'storereportlevel'
					})

				}
			],
			tbar: ['->',
				{
					glyph: 'xf02f@FontAwesome', text: 'Cetak',
					handler: function () {
						var m = me.down('grid').getStore().proxy.extraParams;
						window.open(Settings.SITE_URL + "/report/cetakdokumen/bylevel?" + objectParametize(m));
					}
				},
			]
		});

		me.callParent([arguments]);


	}
});

