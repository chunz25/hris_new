Ext.define("App.modules.master.user.App", {
	extend: "Ext.grid.Panel",
	alternateClassName: "App.master_user",
	alias: 'widget.content_user',
	requires: [
		// 'App.components.field.ComboUserGroup',
	],

	initComponent: function() {
		var me = this;
		Ext.apply(Ext.form.field.VTypes, {        
			password: function(val, field) {
				if (field.initialPassField) {
					var pwd = field.up('form').down('#' + field.initialPassField);
					return (val == pwd.getValue());
				}
				return true;
			},
			passwordText: 'Maaf Password Anda Masih Salah'
		});
		var store_user = Ext.create('Ext.data.Store', {
			storeId: 'store_user',
			autoLoad: true,			
			fields: [
				'userid', 'username', 'password', 'usergroupid', 'usergroup', 'pegawaiid', 'nama', 'nik'
			],
			proxy: {
				type: 'ajax',				
				url: Settings.SITE_URL + '/master/c_user/get_master_user',
				actionMethods: {
					create : 'POST',
					read   : 'POST',
				},				
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'count'
				}
			},
			listeners: {
				beforeload: function(store){
					store.proxy.extraParams.keyword = me.down('#keyword').getValue();
				}
			}
		});
		Ext.apply(me, {
			region: 'center', stripeRows: true, autoScroll: true, loadMask: true, border: false,
			store: store_user,
			columns: [
				{xtype: 'rownumberer', width: 50, header: 'No'}, 
				{header: "Nama Login", dataIndex: 'username', flex: 0.5}, 
				{header: "Nama", xtype: 'templatecolumn',tpl: '{nik} - {nama}', flex: 1}, 
				{header: "User Group", dataIndex: 'usergroup', flex: 0.5}
			],	
			dockedItems: [{
				xtype: 'pagingtoolbar',
				store: store_user,
				dock: 'bottom',
				displayInfo: true
			}],				
			tbar: [
				{itemId: 'keyword', xtype: 'textfield', emptyText: 'Nama/NIP', fieldLabel: 'Cari', labelWidth: 40, width: 300, name: 'keyword', enableKeyEvents: true,
					listeners: {
						specialkey: function(f, e) {
							if(e.getKey() == e.ENTER){
								me.getStore().load();
							}
						}
					},					
				},
				{text: 'Tampilkan',
					handler: function(){
						me.getStore().load();
					}
				},
				'->',
				{text: 'Tambah',
					handler: function(){
						me.win_crud('1',{});
					}
				},
				{text: 'Ubah',
					handler: function(){
						var m = me.getSelectionModel().getSelection();
						if(m.length > 0){
						}						
					}
				},
				{text: 'Hapus',
					handler: function(){
						var m = me.getSelectionModel().getSelection();
						if(m.length > 0){
						}												
					}
				},
			]
		});

		me.callParent([arguments]);
	},
	win_crud: function(flag, record){
		var me = this;
		var win = Ext.create('Ext.window.Window', {
			title: (flag == '1') ? 'Tambah Data' : 'Ubah Data',
			width: 450, closeAction: 'destroy', modal: true, autoShow: true,
			layout: 'fit',
			buttons: [
				{text: 'Simpan', 
					handler: function(){
						if(win.down('form').getForm().isValid()){
							win.down('form').getForm().submit({
								url: Settings.SITE_URL + '/master/c_user/crud_master_userlogin',
								waitTitle:'Menyimpan...', 
								waitMsg:'Sedang menyimpan data, mohon tunggu...',
								success: function(form, action){
									var obj = Ext.decode(action.response.responseText);
									win.destroy();
									Ext.Msg.alert('Informasi', obj.message);
									me.getStore().load();
								},
								failure: function(form, action){
									switch (action.failureType) {
										case Ext.form.action.Action.CLIENT_INVALID:
											Ext.Msg.alert('Failure', 'Harap isi semua data');
										break;
										case Ext.form.action.Action.CONNECT_FAILURE:
											Ext.Msg.alert('Failure', 'Terjadi kesalahan');
										break;
										case Ext.form.action.Action.SERVER_INVALID:
											Ext.Msg.alert('Failure', action.result.msg);
									}									
								}
							});							
						}												
					}
				},
				{text: 'Batal', 
					handler: function(){
						win.destroy();
					}
				},
			],
			items: [
				{ xtype: 'form', bodyPadding: 15, layout: 'anchor',
					defaults: {anchor: '100%', labelWidth: 130},
					border: false,
					defaultType: 'textfield',
					items: [
						{xtype: 'hidden', name: 'flag', value: flag},
						{xtype: 'hidden', name: 'user_id'},
						{fieldLabel: 'Nama Login', name: 'user_login', allowBlank: false},
						// {fieldLabel: 'Nama Pegawai', allowBlank: false, name: 'nama_pekerja'},
						{fieldLabel: 'Password', name: 'user_pwd', inputType: 'password', id: 'pass',
							listeners: {
								change: function(field) {
									var confirmField = field.up('form').down('[name=konfirm]');
									confirmField.validate();
								}
							}							
						},
						{fieldLabel: 'Konfirmasi Password', allowBlank : false, name: 'konfirm', vtype: 'password', inputType: 'password', initialPassField: 'pass'},
					],									
				}
			]
		});
		if(flag == '2'){
			win.down('form').getForm().loadRecord(record);
			win.down('form').getForm().findField('user_pwd').allowBlank = true;
			win.down('form').getForm().findField('user_pwd').hide();
			win.down('form').getForm().findField('konfirm').allowBlank = true;
			win.down('form').getForm().findField('konfirm').hide();
		}				
	},	
});