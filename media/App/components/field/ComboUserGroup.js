Ext.define('App.components.field.ComboUserGroup', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combousergroup',
	fieldLabel: '',
	name: 'usergroup',
	initComponent	: function() {	
		var me = this;	
		var store_grade = Ext.create('Ext.data.Store', {
			autoLoad : true,
			storeId: 'gradeStore',
			fields: ['usrgroup_id','usrgroup_name'],
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/master/c_user/get_master_usergroup',
				reader: {
					type: 'json',
					root:'data'
				}
			},
		});		
		Ext.apply(me,{		
			store: store_grade,
			triggerAction : 'all',
			editable : false,
			displayField: 'usrgroup_name',
			valueField: 'usrgroup_id',
			name: me.name,		
		});		
		me.callParent([arguments]);
	},
});