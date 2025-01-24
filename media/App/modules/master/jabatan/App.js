Ext.define("App.modules.master.jabatan.App", {
	extend: "Ext.panel.Panel",
	alternateClassName: "App.master_jabatan",
	alias: 'widget.content_jabatan',
	requires : [
		'App.components.tree.Jabatan'
	],

	initComponent: function() {
		var me = this;
		Ext.apply(me, {
			layout: 'fit', border: false,
			items: [
				Ext.create('App.components.tree.Jabatan', {
					itemId: 'id_tree_jabatan'
				})
			],
			tbar: ['->',
				{text: 'Tambah',
					handler: function(){
						var m = me.down('#id_tree_jabatan').getSelectionModel().getSelection();
						if(m.length > 0){
							me.win_crud('1',m[0]);
						}												
					}
				},
				{text: 'Ubah',
					handler: function(){
						var m = me.down('#id_tree_jabatan').getSelectionModel().getSelection();
						if(m.length > 0){
							me.win_crud('2',m[0]);
						}																		
					}
				},
				{text: 'Hapus',
					handler: function(){
						var m = me.down('#id_tree_jabatan').getSelectionModel().getSelection();
						if(m.length > 0){
							me.hapus(m);
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
			title: (flag == '1') ? 'Tambah Jabatan' : 'Ubah Jabatan',
			width: 400, closeAction: 'destroy', modal: true, autoShow: true,
			layout: 'fit',
			buttons: [
				{text: 'Simpan', 
					handler: function(){
						if(win.down('form').getForm().isValid()){
							win.down('form').getForm().submit({
								url: Settings.SITE_URL + '/master/c_jabatan/crud_master_jabatan',
								waitTitle:'Menyimpan...', 
								waitMsg:'Sedang menyimpan data, mohon tunggu...',
								success: function(form, action){
									var obj = Ext.decode(action.response.responseText);
									win.destroy();
									Ext.Msg.alert('Informasi', obj.message);
									me.down('#id_tree_jabatan').getStore().load({
										params: {
											node: '0'
										}
									});
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
					defaults: {anchor: '100%', labelWidth: 110},
					border: false,
					defaultType: 'textfield',
					items: [
						{xtype: 'hidden', name: 'flag', value: flag},
						{xtype: 'hidden', name: 'id'},
						{xtype: 'textfield',fieldLabel: 'Nama Jabatan',name: 'text'}
					],				
				}
			]
		});
		win.down('form').getForm().loadRecord(record);
		if(flag == '1'){
			win.down('form').getForm().findField('text').setValue(null);
		}
	},		
	hapus: function(record){
		var me = this;			
		var params = [];	
		Ext.Array.each(record, function(rec, i){
			var temp = {};
			temp.id = rec.get('id');
			params.push(temp);			
		});
		Ext.Msg.show({
			title: 'Konfirmasi',
			msg: 'Apakah anda yakin akan menghapus data ?',
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn: function(btn){
				if(btn == 'yes'){
					Ext.Ajax.request({
						url: Settings.SITE_URL + '/master/c_jabatan/hapus_master_jabatan',
						method: 'POST',
						params: {
							params: Ext.encode(params)
						},
						success: function(response){
							var obj = Ext.decode(response.responseText);
							Ext.Msg.alert('Informasi', obj.message);
							me.down('#id_tree_jabatan').getStore().load({
								params: {
									node: '0'
								}
							});							
						}
					});
				}
			}
		});				
	}	
});