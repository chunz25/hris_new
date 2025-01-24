Ext.define("SIAP.components.tree.Lokasi", {
	extend: "Ext.tree.Panel",
	alternateClassName: "SIAP.lokasi",
	rootTitle: 'Electronic City',
	alias: 'widget.lokasi',
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
					url: Settings.MASTER_URL + '/c_lokasi/getmasterlokasi'
				},
				root: {
					text: me.rootTitle,
					id: '0',
					expanded: true,
				},
				fields: [
					'id', 'text',
				]
			}),
			columns: [
				{ xtype: 'treecolumn', text: me.rootTitle, flex: 1, sortable: true, dataIndex: 'text' }
			],
			useArrows: true
		});
		me.callParent([arguments]);
	}
})