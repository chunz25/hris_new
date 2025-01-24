Ext.define("SIAP.components.grid.Pegawai", {
	extend: "Ext.grid.Panel",
	alternateClassName: "SIAP.griddaftarpegawai",
	alias: 'widget.griddaftarpegawai',
	isSex: '',
	initComponent: function () {
		var me = this;
		var store = Ext.create('Ext.data.Store', {
			storeId: 'storedaftarpegawai',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/pegawai/getListPegawai',
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
				'levelid', 'level', 'statuspegawaiid', 'statuspegawai', 'jeniskelamin', 'email', 'emailkantor', 'telp', 'tglmulai', 'tglselesai', 'keterangan', 'lokasikerja'
			],
			listeners: {
				beforeload: function (store) {
					var key = me.down('#cari_pegawai').getValue();
					store.proxy.extraParams.nik = key;
				}
			}
		});
		Ext.apply(me, {
			store: store,
			autoScroll: true,
			columns: [
				{ xtype: 'rownumberer', width: 30, header: 'No' },
				{ header: "NIK", dataIndex: 'nik', width: 80 },
				{ header: "Nama", dataIndex: 'nama', width: 200 },
				{ header: "Jabatan", dataIndex: 'jabatan', width: 250 },
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
			],
			dockedItems: [{
				xtype: 'pagingtoolbar',
				store: store,
				dock: 'bottom',
				displayInfo: true
			}],
			tbar: [
				{
					itemId: 'cari_pegawai', xtype: 'textfield', fieldLabel: 'Pencarian', name: 'keyword', enableKeyEvents: true, emptyText: 'NIK ...',
					listeners: {
						keypress: function (t, e) {
							if (e.getKey() == e.ENTER) {
								me.getStore().load();
							}
						}
					}
				},
				{
					text: 'Cari',
					handler: function () {
						me.getStore().load();
					}
				}
			]
		});
		me.callParent([arguments]);
	}
})