Ext.define("SIAP.components.window.UnitKerja", {
	extend: "Ext.window.Window",
	alternateClassName: "SIAP.winunitkerja",
	alias: 'widget.winunitkerja',
	title: 'Daftar Unit',
	requires: [
		'SIAP.components.tree.UnitKerja'
	],
	initComponent: function () {
		var me = this;
		me.addEvents({ "pilih": true });
		Ext.apply(me, {
			width: 400,
			height: 400,
			closeAction: 'destroy',
			modal: true,
			autoShow: true,
			editable: false,
			layout: 'fit',
			items: [
				{
					xtype: 'unitkerja',
					listeners: {
						itemdblclick: function (p, record, item, index, e, opt) {
							me.fireEvent("pilih", me, record);
							me.destroy();
						}
					}
				}
			],
			buttons: [
				{
					text: 'Pilih',
					handler: function () {
						var m = me.down('#id_unitkerja').getSelectionModel().getSelection();
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