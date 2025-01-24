Ext.define("SIAP.components.window.Pegawai", {
	extend: "Ext.window.Window",
	alternateClassName: "SIAP.pegawai",
	alias: 'widget.windaftarpegawai',
	title: 'Daftar Pegawai',
	isSex: '',
	requires: [
		'SIAP.components.grid.Pegawai'
	],
	initComponent: function () {
		var me = this;
		me.addEvents({ "pilih": true });
		Ext.apply(me, {
			width: 600, height: 400, closeAction: 'destroy', modal: true, autoShow: true,
			layout: 'fit',
			items: [
				{
					xtype: 'griddaftarpegawai', layout: 'fit', isSex: me.isSex,
					listeners: {
						celldblclick: function (p, td, cellIndex, record, tr, rowIndex, e, opt) {
							me.fireEvent("pilih", me, record);
							me.destroy();
						}
					},
				}
			],
			buttons: [
				{
					text: 'Pilih',
					handler: function () {
						var m = me.down('griddaftarpegawai').getSelectionModel().getSelection();
						if (m.length > 0) {
							me.fireEvent("pilih", me, m[0]);
							me.destroy();
						}
					}
				},
				{
					text: 'Batal',
					handler: function () {
						me.destroy();
					}
				},
			]
		});
		me.callParent([arguments]);
	}
})