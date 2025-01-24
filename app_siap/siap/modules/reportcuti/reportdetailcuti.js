Ext.define("SIAP.modules.reportcuti.reportdetailcuti", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.reportdetailcuti",
	alias: 'widget.reportdetailcuti',
	requires: [],
	initComponent: function () {
		var me = this;
		var arr_params = me.params.split('#');
		var pegawaiid = Base64.decode(arr_params[0]);

		var storereportdetailcuti = Ext.create('Ext.data.Store', {
			storeId: 'storereportdetailcuti',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/reportcuti/getreporthistorycuti',
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
				'pegawaiid', 'nama', 'nik', 'satkerid', 'tglpermohonan', 'tglmulai', 'tglselesai', 'lama', 'jeniscuti', 'status', 'alasancuti'
			],
			listeners: {
				beforeload: function (store) {
					Ext.getStore('storereportdetailcuti').proxy.extraParams.pegawaiid = pegawaiid;
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
					store: storereportdetailcuti,
					columns: [
						{ text: 'No', xtype: 'rownumberer', width: 30 },
						{ text: 'Nik', dataIndex: 'nik', width: 100 },
						{ text: 'Nama', dataIndex: 'nama', width: 120 },
						{ text: 'Tgl Pengajuan', dataIndex: 'tglpermohonan', width: 120 },
						{ text: 'Tgl Awal', dataIndex: 'tglmulai', width: 120 },
						{ text: 'Tgl Akhir', dataIndex: 'tglselesai', width: 120 },
						{ text: 'Lama', dataIndex: 'lama', width: 50 },
						{ text: 'Jenis Cuti', dataIndex: 'jeniscuti', width: 180 },
						{ text: 'Status', dataIndex: 'status', width: 200 },
						{ text: 'Alasan', dataIndex: 'alasancuti', width: 300 },
					],
					bbar: Ext.create('Ext.toolbar.Paging', {
						displayInfo: true,
						height: 35,
						store: 'storereportdetailcuti'
					})

				},
			],
			tbar: [
				{
					text: 'Kembali', glyph: 'xf060@FontAwesome',
					handler: function () {
						Ext.History.add('#reportcuti');
					}
				},
			]
		});
		me.callParent([arguments]);
	},
});