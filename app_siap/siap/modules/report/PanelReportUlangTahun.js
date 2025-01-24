Ext.define("SIAP.modules.report.PanelReportUlangtahun", {
	extend: "Ext.grid.Panel",
	alternateClassName: "SIAP.panelreportulangtahun",
	alias: 'widget.panelreportulangtahun',
	requires: [
		'SIAP.components.field.ComboStatusPegawai'
	],
	initComponent: function () {
		var me = this;

		var storerulangtahun = Ext.create('Ext.data.Store', {
			storeId: 'storerulangtahun',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/report/getReportUlangtahun',
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
				'pegawaiid', 'nik', 'namadepan', 'divisi', 'direktorat', 'departemen', 'seksi', 'subseksi', 'tgllahir', 'birthday', 'lokasi'
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
			store: storerulangtahun,
			columns: [
				{ header: 'No', xtype: 'rownumberer', width: 30 },
				{ header: 'NIK', dataIndex: 'nik', width: 120 },
				{ header: 'Nama', dataIndex: 'namadepan', width: 150 },
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
				{ header: 'Tgl Lahir', dataIndex: 'tgllahir', width: 80 },
				{ header: 'Lokasi', dataIndex: 'lokasi', width: 120 },

			],
			viewConfig: {
				getRowClass: function (record, rowIndex, rowParams, store) {
					if (record.get('birthday') == 1) {
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
				store: 'storerulangtahun'
			}),
			tbar: [
				{
					itemId: 'id_day', xtype: 'combobox', name: 'hari', labelWidth: 40, style: 'margin-left:10px;',
					queryMode: 'local', displayField: 'text', valueField: 'id',
					store: Ext.create('Ext.data.Store', {
						fields: ['id', 'text'],
						data: [
							{ "id": "01", "text": "1" },
							{ "id": "02", "text": "2" },
							{ "id": "03", "text": "3" },
							{ "id": "04", "text": "4" },
							{ "id": "05", "text": "5" },
							{ "id": "06", "text": "6" },
							{ "id": "07", "text": "7" },
							{ "id": "08", "text": "8" },
							{ "id": "09", "text": "9" },
							{ "id": "10", "text": "10" },
							{ "id": "11", "text": "11" },
							{ "id": "12", "text": "12" },
							{ "id": "13", "text": "13" },
							{ "id": "14", "text": "14" },
							{ "id": "15", "text": "15" },
							{ "id": "16", "text": "16" },
							{ "id": "17", "text": "17" },
							{ "id": "18", "text": "18" },
							{ "id": "19", "text": "19" },
							{ "id": "20", "text": "20" },
							{ "id": "21", "text": "21" },
							{ "id": "22", "text": "22" },
							{ "id": "23", "text": "23" },
							{ "id": "24", "text": "24" },
							{ "id": "25", "text": "25" },
							{ "id": "26", "text": "26" },
							{ "id": "27", "text": "27" },
							{ "id": "28", "text": "28" },
							{ "id": "29", "text": "29" },
							{ "id": "30", "text": "30" },
							{ "id": "31", "text": "31" },
						]
					}),
					triggerAction: 'all',
					displayField: 'text',
					valueField: 'id',
					name: me.name,
				},
				{
					itemId: 'id_month', xtype: 'combobox', name: 'month', labelWidth: 40, style: 'margin-left:10px;',
					queryMode: 'local', displayField: 'text', valueField: 'id',
					store: Ext.create('Ext.data.Store', {
						fields: ['id', 'text'],
						data: [
							{ "id": "01", "text": "Januari" },
							{ "id": "02", "text": "Februari" },
							{ "id": "03", "text": "Maret" },
							{ "id": "04", "text": "April" },
							{ "id": "05", "text": "Mei" },
							{ "id": "06", "text": "Juni" },
							{ "id": "07", "text": "Juli" },
							{ "id": "08", "text": "Agustus" },
							{ "id": "09", "text": "September" },
							{ "id": "10", "text": "Oktober" },
							{ "id": "11", "text": "November" },
							{ "id": "12", "text": "Desember" },
						]
					}),
					triggerAction: 'all',
					displayField: 'text',
					valueField: 'id',
					name: me.name,
				},
				{
					glyph: 'xf002@FontAwesome',
					handler: function () {
						var tree = Ext.ComponentQuery.query('#id_unitkerja')[0].getSelectionModel().getSelection();
						treeid = null;
						if (tree.length > 0) {
							treeid = tree[0].get('id');
						}
						Ext.getStore('storerulangtahun').proxy.extraParams.satkerid = treeid;
						Ext.getStore('storerulangtahun').proxy.extraParams.hari = Ext.ComponentQuery.query('#id_day')[0].getValue();
						Ext.getStore('storerulangtahun').proxy.extraParams.bulan = Ext.ComponentQuery.query('#id_month')[0].getValue();
						Ext.getStore('storerulangtahun').load();
					}
				},
				'->',
				{
					glyph: 'xf02f@FontAwesome', text: 'Cetak',
					handler: function () {
						var tree = Ext.ComponentQuery.query('#id_unitkerja')[0].getSelectionModel().getSelection();
						treeid = null;
						if (tree.length > 0) {
							treeid = tree[0].get('id');
						}
						Ext.getStore('storerulangtahun').proxy.extraParams.satkerid = treeid;
						Ext.getStore('storerulangtahun').proxy.extraParams.hari = Ext.ComponentQuery.query('#id_day')[0].getValue();
						Ext.getStore('storerulangtahun').proxy.extraParams.bulan = Ext.ComponentQuery.query('#id_month')[0].getValue();
						var m = Ext.getStore('storerulangtahun').proxy.extraParams;
						window.open(Settings.SITE_URL + '/report/cetakdokumen/ulangtahun?' + objectParametize(m));
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

