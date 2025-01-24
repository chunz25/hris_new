Ext.define("SIAP.modules.report.PanelReportRemindContract", {
	extend: "Ext.grid.Panel",
	alternateClassName: "SIAP.panelreportremindcontract",
	alias: 'widget.panelreportremindcontract',
	requires: [
		'SIAP.components.field.ComboStatusPegawai'
	],
	initComponent: function () {
		var me = this;

		var storeremindcontract = Ext.create('Ext.data.Store', {
			storeId: 'storeremindcontract',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getReportEndOfContract',
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
				'pegawaiid', 'nik', 'nama', 'level', 'jabatan', 'lokasi', 'tglmulai', 'tglakhirkontrak', 'tglpermanent', 'monthexp', 'statuspegawaiid', 'statuspegawai'
			],
			listeners: {
				beforeload: function (store) {
					me.fireEvent("beforeload", store);
				}
			}
		});


		Ext.apply(me, {
			region: 'center', layout: 'fit',
			autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true,
			store: storeremindcontract,
			columns: [
				{ header: 'No', xtype: 'rownumberer', width: 30 },
				{ header: 'NIK', dataIndex: 'nik', width: 120 },
				{ header: 'Nama', dataIndex: 'nama', width: 150 },
				{ header: 'Level', dataIndex: 'level', width: 120 },
				{ header: 'Jabatan', dataIndex: 'jabatan', width: 120 },
				{ header: 'Lokasi', dataIndex: 'lokasi', width: 120 },
				{ header: 'Status', dataIndex: 'statuspegawai', width: 120 },
				{ header: 'Tgl Masuk', dataIndex: 'tglmulai', width: 120 },
				{ header: 'Tgl Habis Kontrak', dataIndex: 'tglakhirkontrak', width: 120 },
			],
			viewConfig: {
				getRowClass: function (record, rowIndex, rowParams, store) {
					if (record.get('monthexp') < 0) {
						return 'red_grid_rows';
					}
				}
			},
			bbar: ['->',
				'<ul style="width:200px;float:left;list-style:none;margin-top:10px;">' +
				'<li>' +
				'<div style="float:left;">' +
				'<a style="cursor:pointer;" onclick=\"filterflag(2)\"><div style="width:16px;height:16px;background-color:#ff9b9b;float:left;"></div></a>' +
				'<div style="float:left;padding-left:5px;padding-right:5px;font-weight:bold;font-size:11px;">Melewati 3 bulan</div>' +
				'</div>' +
				'</li>' +
				'</ul>'
			],
			bbar: Ext.create('Ext.toolbar.Paging', {
				displayInfo: true,
				height: 35,
				store: 'storeremindcontract'
			}),
			tbar: [
				'->',
				{
					glyph: 'xf044@FontAwesome', text: 'Ubah',
					handler: function () {
						var m = me.getSelectionModel().getSelection();
						if (m.length > 0) {
							console.log(m[0]);
							me.crud(m[0], '1');
						}
						else {
							Ext.Msg.alert('Pesan', 'Harap pilih data terlebih dahulu');
						}
					}
				},
				{
					glyph: 'xf02f@FontAwesome', text: 'Cetak',
					handler: function () {
						var tree = Ext.ComponentQuery.query('#id_unitkerja')[0].getSelectionModel().getSelection();
						treeid = null;
						if (tree.length > 0) {
							treeid = tree[0].get('id');
						}
						Ext.getStore('storeremindcontract').proxy.extraParams.satkerid = treeid;
						var m = Ext.getStore('storeremindcontract').proxy.extraParams;
						window.open(Settings.SITE_URL + "/report/cetakdokumen/endofcontract?" + objectParametize(m));
					}
				},
			]
		});

		me.callParent([arguments]);
	},
	crud: function (record, flag) {
		var me = this;
		var win = Ext.create('Ext.window.Window', {
			title: 'Tgl Habis Kontrak',
			width: 400,
			closeAction: 'destroy', modal: true, layout: 'fit', autoScroll: true, autoShow: true,
			buttons: [
				{
					text: 'Simpan',
					handler: function () {
						win.down('form').getForm().submit({
							waitTitle: 'Menyimpan...',
							waitMsg: 'Sedang menyimpan data, mohon tunggu...',
							url: Settings.MASTER_URL + '/c_statuspegawai/crudEndOfContract',
							method: 'POST',
							success: function (form, action) {
								var obj = Ext.decode(action.response.responseText);
								win.destroy();
								me.getStore().reload();
								me.getSelectionModel().deselectAll();
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
						{ xtype: 'hidden', name: 'flag', value: flag },
						{ xtype: 'hidden', name: 'pegawaiid' },
						{
							xtype: 'combostatuspegawai', fieldLabel: 'Status Pegawai', name: 'statuspegawaiid',
							listeners: {
								select: function (combo, rec, opt) {
									win.down('form').getForm().findField('statuspegawaiid').setValue(rec[0].data.id);
								}
							}
						},
						{ xtype: 'datefield', fieldLabel: 'Tgl Habis Kontrak', format: 'd/m/Y', name: 'tglakhirkontrak' },
						{ xtype: 'datefield', fieldLabel: 'Tgl Permanent', format: 'd/m/Y', name: 'tglpermanent' },
					]
				},
			]
		});
		if (flag == '1') {
			win.down('form').getForm().loadRecord(record);
		}
	},
});

