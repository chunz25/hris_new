Ext.define("App.components.tree.UnitKerja",{
	extend: "Ext.tree.Panel",
	alternateClassName: "App.unitkerja" ,
	alias: 'widget.unitkerja',
	initComponent:function(){
		var me = this;
		Ext.apply(me,{	
			itemId: 'id_unitkerja',
			useArrows: true, border: false,
			store : Ext.create('Ext.data.TreeStore', {
				proxy: {
					type: 'ajax',
					actionMethods: {
						read: 'POST'
					},
					reader:{
						root: 'data',
						totalProperty: 'count'
					},
					url: Settings.SITE_URL + '/master/c_unitkerja/get_unit_kerja'
				},				
				root: {
					text: 'Daftar Unit',
					id: '0',
					expanded: true,					
				}
			}),
			columns: [{xtype: 'treecolumn', text: 'Daftar Unit', flex: 1, sortable : true, dataIndex: 'text'}]
		});
		me.callParent([arguments]);
	}
})