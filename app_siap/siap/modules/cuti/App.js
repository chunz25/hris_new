Ext.define("SIAP.modules.cuti.App", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.cuti",
	alias: 'widget.cuti',
	requires: [
		'SIAP.components.tree.UnitKerja',
		'SIAP.modules.cuti.GridCuti',
		'SIAP.components.field.ComboStatusCuti'
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

									me.down('#id_gridcuti').getStore().proxy.extraParams.tglmulai = tglmulai;
									me.down('#id_gridcuti').getStore().proxy.extraParams.tglselesai = tglselesai;
									me.down('#id_gridcuti').getStore().proxy.extraParams.satkerid = b.get('id');
									me.down('#id_gridcuti').getStore().loadPage(1);
								},
							},
						}
					]
				},
				{
					itemId: 'id_gridcuti', xtype: 'gridcuti', region: 'center', frame: true,
					listeners: {
						beforeload: function (store) {
							var satkerid = '';
							var m = Ext.ComponentQuery.query('#id_unitkerja')[0].getSelectionModel().getSelection();
							var tglmulai = Ext.Date.format(me.down('#id_tglmulai').getValue(), 'd/m/Y');
							var tglselesai = Ext.Date.format(me.down('#id_tglselesai').getValue(), 'd/m/Y');
							var statusid = me.down('#id_statuscuti').getValue();
							if (m.length > 0) {
								satkerid = m[0].get('id');
							}
							store.proxy.extraParams.satkerid = satkerid;
							store.proxy.extraParams.tglmulai = tglmulai;
							store.proxy.extraParams.tglselesai = tglselesai;
							store.proxy.extraParams.statusid = statusid;
						}
					},
					tbar: [
						{ itemId: 'id_tglmulai', xtype: 'datefield', fieldLabel: 'Periode', format: 'd/m/Y', name: 'tglmulai', value: (moment().startOf("month").format('DD/MM/YYYY')), emptyText: 'Tgl Awal', labelWidth: 40, style: 'margin-left:10px;' },
						{ itemId: 'id_tglselesai', xtype: 'datefield', fieldLabel: 's/d', format: 'd/m/Y', name: 'tglselesai', value: (moment().endOf("month").format('DD/MM/YYYY')), emptyText: 'Tgl Akhir', labelWidth: 20, style: 'margin-left:5px;' },
						'-',
						{ itemId: 'id_statuscuti', xtype: 'combostatuscuti', emptyText: 'Status Cuti' },
						{
							glyph: 'xf002@FontAwesome',
							handler: function () {
								me.down('#id_gridcuti').getStore().loadPage(1);
							}
						}, '->',
						// {
						// 	glyph: 'xf196@FontAwesome', text: 'Tambah',
						// 	handler: function () {
						// 		Ext.History.add('#cuti&AddCuti');
						// 	}
						// },
						{
							glyph: 'xf02f@FontAwesome', text: 'Cetak',
							handler: function () {
								var m = me.down('#id_gridcuti').getStore().proxy.extraParams;
								window.open(Settings.SITE_URL + "/cuti/cetakdokumen?" + objectParametize(m));
							}
						}
					],
				}
			],
			listeners: {
				afterrender: function () {
					Ext.get("id_submenu").dom.style.display = "none";
				},
			},
		});
		me.callParent([arguments]);
	}
});