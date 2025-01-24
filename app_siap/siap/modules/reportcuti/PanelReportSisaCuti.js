Ext.define("SIAP.modules.reportcuti.PanelReportSisaCuti", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.panelreportsisacuti",
	alias: 'widget.panelreportsisacuti',
	initComponent: function () {
		var me = this;

		var storereportsisacuti = Ext.create('Ext.data.Store', {
			storeId: 'storereportsisacuti',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/reportcuti/getReportListStatusPegawai',
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
				'levelid', 'level', 'statuspegawaiid', 'statuspegawai', 'lokasi', 'jatahcuti', 'sisacutithnini'
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
					store: storereportsisacuti,
					columns: [
						{ header: 'No', xtype: 'rownumberer', width: 30 },
						{ header: 'NIK', dataIndex: 'nik', width: 80 },
						{ header: 'Nama', dataIndex: 'nama', width: 150 },
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
						{ header: 'Jatah Cuti', dataIndex: 'jatahcuti', align: 'center', width: 62 },
						{
							header: 'Sisa Cuti', dataIndex: 'sisacutithnini', width: 60, align: 'center',
							renderer: function (value, meta) {
								if (parseInt(value) < 0) {
									meta.style = "background-color:red;color:white;";
								}
								return value;
							}
						},
						{ header: 'Lokasi', dataIndex: 'lokasi', width: 120 },
						{
							header: '', width: 30,
							renderer: function (value, meta, record, index) {
								return '<a onclick=detailhistory("' + record.data.pegawaiid + '")  style="cursor:pointer;"><span class="black-icon-brand"><i class="fa fa-external-link" aria-hidden="true"></i></span></a>';
							},
						},
					],
					bbar: Ext.create('Ext.toolbar.Paging', {
						displayInfo: true,
						height: 35,
						store: 'storereportsisacuti'
					})

				},
			],
			tbar: [
				{
					glyph: 'xf002@FontAwesome', text: 'Cari Pegawai',
					handler: function () {
						me.winSearchPegawai();
					}
				},
				'->',
				{
					glyph: 'xf02f@FontAwesome', text: 'Cetak',
					handler: function () {
						var m = me.down('grid').getStore().proxy.extraParams;
						window.open(Settings.SITE_URL + "/reportcuti/cetakdokumen/sisacuti?" + objectParametize(m));
					}
				},
			]
		});

		me.callParent([arguments]);
	},
	winSearchPegawai: function () {
		var me = this;

		var win = Ext.create('Ext.window.Window', {
			title: 'Cari Pegawai',
			width: 400,
			closeAction: 'destroy', modal: true, layout: 'fit', autoScroll: true, autoShow: true,
			buttons: [
				{
					text: 'Cari',
					handler: function () {
						var form = win.down('form').getForm();
						var nama = form.findField('nama').getValue();

						me.down('grid').getStore().proxy.extraParams.nama = nama;
						me.down('grid').getStore().loadPage(1);
						win.destroy();
					}
				},
				{
					text: 'Batal',
					handler: function () {
						win.destroy();
					}
				},
			],
			items: [
				{
					xtype: 'form', waitMsgTarget: true, bodyPadding: 15, layout: 'anchor', defaultType: 'textfield', region: 'center', autoScroll: true,
					defaults: {
						labelWidth: 100, anchor: '100%'
					},
					items: [
						{ fieldLabel: 'Nama', name: 'nama' },
					]
				},
			]
		});

	}
});

function detailhistory(pegawaiid) {
	Ext.History.add('#reportcuti&reportdetailcuti&' + Base64.encode(pegawaiid));
}


