Ext.define("App.components.tree.Jabatan",{
	extend: "Ext.tree.Panel",
	alternateClassName: "App.jabatan" ,
	alias: 'widget.jabatan',
	initComponent:function(){
		var me = this;
		Ext.apply(me,{			
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
					url: Settings.SITE_URL + '/master/c_jabatan/get_master_jabatan'
				},				
				root: {
					text: 'Jabatan',
					id: '0',
					expanded: true,					
				},
				fields: [
					'id','text','tipejabatan'
				]
			}),
			columns: [
				{xtype: 'treecolumn', text: 'Nama Jabatan', flex: 1, sortable : true, dataIndex: 'text'}				
			],						
			useArrows: true
		});
		me.callParent([arguments]);
	}
})