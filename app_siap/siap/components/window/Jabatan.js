Ext.define("SIAP.components.window.Jabatan", {
	extend: "Ext.window.Window",
	alternateClassName: "SIAP.jabatan",
	alias: 'widget.winmasterjabatan',
	title: 'Master Jabatan',
	isSex: '',
	requires: [
		'SIAP.components.tree.Jabatan'
	],
	initComponent: function () {
		var me = this;
		me.addEvents({ "pilih": true });
		Ext.apply(me, {
			width: 500,
			height: 400,
			closeAction: 'destroy',
			modal: true,
			autoShow: true,
			layout: 'fit',
			items: [
				{
					xtype: 'jabatan',
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
						var m = me.down('jabatan').getSelectionModel().getSelection();
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