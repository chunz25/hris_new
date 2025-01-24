Ext.define("SIAP.modules.kehadiran.App", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.kehadiran",
	alias: 'widget.kehadiran',
	requires: [
		'SIAP.components.tree.UnitKerja',
		'SIAP.modules.kehadiran.GridKehadiran',
		'SIAP.components.field.ComboStatusKehadiran',
		'SIAP.components.progressbar.WinProgress'
	],
	initComponent: function () {
		var me = this;
		Ext.apply(me, {
			layout: 'border',
			items: [
				{
					region: 'west', title: 'Daftar Unit Kerja', collapsible: true, collapsed: false, layout: 'fit', border: false,
					resizable: { dynamic: true },
					items: [
						{
							xtype: 'unitkerja', width: 200, border: false,
							listeners: {
								itemclick: function (a, b, c) {
									var tglmulai = Ext.Date.format(me.down('#id_tglmulai').getValue(), 'd/m/Y');
									var tglselesai = Ext.Date.format(me.down('#id_tglselesai').getValue(), 'd/m/Y');

									me.down('#id_gridkehadiran').getStore().proxy.extraParams.tglmulai = tglmulai;
									me.down('#id_gridkehadiran').getStore().proxy.extraParams.tglselesai = tglselesai;
									me.down('#id_gridkehadiran').getStore().proxy.extraParams.satkerid = b.get('id');
									me.down('#id_gridkehadiran').getStore().loadPage(1);
								},
							},
						}
					]
				},
				{
					itemId: 'id_gridkehadiran', xtype: 'gridkehadiran', region: 'center', frame: true,
					listeners: {
						beforeload: function (store) {
							var satkerid = '';
							var lokasiid = me.down('#id_unitkerja').getStore().proxy.extraParams.lokasiid;
							var tglmulai = me.down('#id_tglmulai');
							tglmulai = Ext.isEmpty(tglmulai.getValue()) ? '' : Ext.Date.format(tglmulai.getValue(), 'Y-m-d');
							var tglselesai = me.down('#id_tglselesai');
							tglselesai = Ext.isEmpty(tglselesai.getValue()) ? '' : Ext.Date.format(tglselesai.getValue(), 'Y-m-d');
							var statusid = me.down('#id_statuscuti').getValue();

							store.proxy.extraParams.lokasiid = lokasiid;
							store.proxy.extraParams.tglmulai = tglmulai;
							store.proxy.extraParams.tglselesai = tglselesai;
							store.proxy.extraParams.statusid = statusid;
						}
					},
					tbar: [
						{ itemId: 'id_tglmulai', xtype: 'datefield', fieldLabel: 'Periode', format: 'd/m/Y', name: 'tglmulai', value: (moment().startOf("month").format('DD/MM/YYYY')), emptyText: 'Tgl Awal', labelWidth: 40, style: 'margin-left:10px;' },
						{ itemId: 'id_tglselesai', xtype: 'datefield', fieldLabel: 's/d', format: 'd/m/Y', name: 'tglselesai', value: (moment().endOf("month").format('DD/MM/YYYY')), emptyText: 'Tgl Akhir', labelWidth: 20, style: 'margin-left:5px;' },
						'-',
						{ itemId: 'id_statuscuti', xtype: 'combostatuskehadiran', emptyText: 'Status Kehadiran' },
						{
							glyph: 'xf002@FontAwesome',
							handler: function () {
								me.down('#id_gridkehadiran').getStore().load();
							}
						},
						'->',
						{
							glyph: 'xf02f@FontAwesome', text: 'Cetak',
							handler: function () {
								var m = me.down('#id_gridkehadiran').getStore().proxy.extraParams;
								window.open(Settings.SITE_URL + "/kehadiran/cetakdokumen?" + objectParametize(m));
							}
						}
					]
				}
			],
			listeners: {
				afterrender: function () {
					Ext.get("id_submenu").dom.style.display = "none";
				},
			},
		});
		me.callParent([arguments]);
	},
	winUploadKehadiran: function () {
		var me = this;
		var win = Ext.create('Ext.window.Window', {
			title: 'Import Data',
			width: 430,
			closeAction: 'destroy', modal: true, layout: 'fit', autoScroll: true, autoShow: true,
			buttons: [
				{
					text: 'Simpan',
					handler: function () {
						win.down('form').getForm().submit({
							waitTitle: 'Menyimpan...',
							waitMsg: 'Sedang menyimpan data, mohon tunggu...',
							url: Settings.SITE_URL + '/kehadiran/get_import_file',
							method: 'POST',
							success: function (form, action) {
								var obj = Ext.decode(action.response.responseText);
								var winprogress = Ext.create('SIAP.components.progressbar.WinProgress', {
									title: 'Proses Import Data',
									URL: Settings.SITE_URL + '/kehadiran/prosesImportData',
								});
								var extraParams = {};
								winprogress.proses_exec(obj.data, extraParams);
								winprogress.on('finished', function (p) {
									Ext.Msg.alert('Pesan', 'Data berhasil diproses');
									me.down('grid').getStore().load();
								});
							},
							failure: function (form, action) {
							}
						});
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
						{ xtype: 'filefield', name: 'dokumen', fieldLabel: 'Dokumen', allowBlank: true, buttonText: 'Pilih Dokumen ...' },
					]
				},
			]
		});
	}
});