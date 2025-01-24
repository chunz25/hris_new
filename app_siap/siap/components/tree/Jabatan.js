Ext.define("SIAP.components.tree.Jabatan", {
	extend: "Ext.tree.Panel",
	alternateClassName: "SIAP.jabatan",
	alias: 'widget.jabatan',
	initComponent: function () {
		var me = this;
		Ext.apply(me, {
			store: Ext.create('Ext.data.TreeStore', {
				proxy: {
					type: 'ajax',
					actionMethods: {
						read: 'POST'
					},
					reader: {
						root: 'data',
						totalProperty: 'count'
					},
					url: Settings.MASTER_URL + '/c_jabatan/getmasterjabatan'
				},
				root: {
					text: 'Jabatan',
					id: '0',
					expanded: true,
				},
				fields: [
					'id', 'text',
				]
			}),
			columns: [
				{ xtype: 'treecolumn', text: 'Nama Jabatan', flex: 1, sortable: true, dataIndex: 'text' }
			],
			useArrows: true
		});
		me.callParent([arguments]);
	}
})