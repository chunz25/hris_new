Ext.define("SIAP.abstract.Window", {
	extend: "Ext.window.Window",
	closeAction: 'destroy',
	layout: 'fit',
	cls: "app-window",
	modal: true,
	autoShow: true,
	resizable: true,
	bodyPadding: 10,
});