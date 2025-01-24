Ext.define("SIAP.modules.report.PanelReportActingAs", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.panelreportactingas",
	alias: 'widget.panelreportactingas',
	initComponent: function () {
		var me = this;

		var storereportactingas = Ext.create('Ext.data.Store', {
			storeId: 'storereportactingas',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getReportActingAs',
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
				'id', 'month',
				{ name: 'jml1', type: 'int' },
				{ name: 'jml2', type: 'int' },
				{ name: 'jml3', type: 'int' },
				{ name: 'jml4', type: 'int' },
				{ name: 'status1', type: 'int' }
			],
			listeners: {
				beforeload: function (store) {
					me.fireEvent("beforeload", store);
				}
			}
		});

		var storereportactingasbysatker = Ext.create('Ext.data.Store', {
			storeId: 'storereportactingasbysatker',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getReportActingAsBySatker',
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
				'pegawaiid', 'nik', 'namadepan', 'actingas',
				'direktorat1', 'divisi1', 'departemen1', 'seksi1', 'subseksi1', 'level1', 'jabatan1', 'lokasi1', 'satker1',
				'direktorat2', 'divisi2', 'departemen2', 'seksi2', 'subseksi2', 'level2', 'jabatan2', 'lokasi2', 'satker2',
				'tglmulai', 'tglakhir', 'keterangan'
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
					store: storereportactingas,
					columns: [
						{ header: 'No', xtype: 'rownumberer', width: 30 },
						{ header: 'Bulan', dataIndex: 'month', width: 120 },
						{ header: 'Acting As Ke - 1', dataIndex: 'jml1', width: 120 },
						{ header: 'Acting As Ke - 2', dataIndex: 'jml2', width: 120 },
						{ header: 'Acting As Ke - 3', dataIndex: 'jml3', width: 120 },
						{ header: 'Acting As Ke - 4', dataIndex: 'jml4', width: 120 },
					],
				},
				{
					xtype: 'grid', region: 'south', title: 'Data Karyawan', height: 250, collapsed: true, collapsible: true,
					autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
					store: storereportactingasbysatker,
					columns: [
						{ header: 'No', xtype: 'rownumberer', width: 30 },
						{ header: 'NIK', dataIndex: 'nik', width: 80 },
						{ header: 'Nama', dataIndex: 'namadepan', width: 150 },
						{ header: 'Acting As Ke-', dataIndex: 'actingas', width: 100 },
						{
							header: 'Informasi Awal', align: 'left',
							columns: [
								{ header: 'Level', dataIndex: 'level1', width: 120 },
								{ header: 'Jabatan', dataIndex: 'jabatan1', width: 120 },
								{ header: 'Direktorat', dataIndex: 'direktorat1', width: 120 },
								{ header: 'Divisi', dataIndex: 'divisi1', width: 120 },
								{ header: 'Departemen', dataIndex: 'departemen1', width: 120 },
								{ header: 'Seksi', dataIndex: 'seksi1', width: 120 },
								{ header: 'Sub Seksi', dataIndex: 'subseksi1', width: 120 },
								{ header: 'Lokasi', dataIndex: 'lokasi1', width: 120 },
							]
						},
						{
							header: 'Pengajuan Perubahan Informasi', align: 'left',
							columns: [
								{ header: 'Level', dataIndex: 'level2', width: 120 },
								{ header: 'Jabatan', dataIndex: 'jabatan2', width: 120 },
								{ header: 'Direktorat', dataIndex: 'direktorat2', width: 120 },
								{ header: 'Divisi', dataIndex: 'divisi2', width: 120 },
								{ header: 'Departemen', dataIndex: 'departemen2', width: 120 },
								{ header: 'Seksi', dataIndex: 'seksi2', width: 120 },
								{ header: 'Sub Seksi', dataIndex: 'subseksi2', width: 120 },
								{ header: 'Lokasi', dataIndex: 'lokasi2', width: 120 },
							]
						},
						{ header: 'Tgl Efektif', dataIndex: 'tglmulai', width: 100 },
						{ header: 'Tgl Akhir', dataIndex: 'tglakhir', width: 100 },
						{ header: 'keterangan', dataIndex: 'keterangan', width: 120 },
					],
					bbar: Ext.create('Ext.toolbar.Paging', {
						displayInfo: true,
						height: 35,
						store: 'storereportactingasbysatker'
					})

				},
			],
			tbar: ['->',
				{
					glyph: 'xf02f@FontAwesome', text: 'Cetak',
					handler: function () {
						var tree = Ext.ComponentQuery.query('#id_unitkerja')[0].getSelectionModel().getSelection();
						treeid = null;
						if (tree.length > 0) {
							treeid = tree[0].get('id');
						}
						Ext.getStore('storereportactingas').proxy.extraParams.satkerid = treeid;
						var m = Ext.getStore('storereportactingas').proxy.extraParams;
						window.open(Settings.SITE_URL + "/report/cetakdokumen/actingas?" + objectParametize(m));
					}
				}
			]
		});

		me.callParent([arguments]);
	}
});

