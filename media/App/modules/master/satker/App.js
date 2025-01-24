Ext.define("App.modules.master.satker.App", {
	extend: "Ext.panel.Panel",
	alternateClassName: "App.master_satker",
	alias: 'widget.content_satker',
	requires : [
		'App.components.tree.UnitKerja'
	],
	initComponent: function() {
		var me = this;
		Ext.apply(me, {
			layout: 'fit', border: false,
			items: [Ext.create('App.components.tree.UnitKerja', {})],
			tbar: ['->',
				{text: 'Tambah',
					handler: function(){
						var m = me.down('#id_unitkerja').getSelectionModel().getSelection();						
						if(m.length > 0){
							me.win_crud('1', m[0]);
						}																		
					}
				},
				{text: 'Ubah',
					handler: function(){
						var m = me.down('#id_unitkerja').getSelectionModel().getSelection();
						if(m.length > 0){
							me.win_crud('2',m[0]);
						}																		
					}
				},
				{text: 'Hapus',
					handler: function(){
						var m = me.down('#id_unitkerja').getSelectionModel().getSelection();
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
		
		var win = Ext.create('Ext.window.Window',{
			title: 'Master Unit', 
			width: 400,
			closeAction: 'destroy', modal:true, layout: 'fit', autoScroll: true, autoShow: true,
			buttons: [
				{text: 'Simpan', 
					handler: function(){
						var formp = win.down('form').getForm();
						if(formp.isValid()){
							formp.submit({
								url: Settings.SITE_URL + '/master/c_unitkerja/crud_master_unitkerja',
								waitTitle:'Menyimpan...', 
								waitMsg:'Sedang menyimpan data, mohon tunggu...',
								success: function(form, action){
									var obj = Ext.decode(action.response.responseText);
									win.destroy();
									Ext.Msg.alert('Informasi', obj.message);
									me.down('#id_unitkerja').getStore().load({
										params: {node: '0'}
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
				{ xtype: 'form', waitMsgTarget:true, bodyPadding: 15, layout: 'anchor', defaultType: 'textfield', region: 'center', autoScroll: true,
					defaults: {labelWidth: 100, anchor: '100%'},					
					items: [
						{xtype: 'hidden', name: 'flag', value: flag},
						{xtype: 'hidden', name: 'id'},
						{xtype: 'textfield', fieldLabel: 'Nama Unit', name: 'text'}, 
					]
				},
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
						url: Settings.SITE_URL + '/master/c_unitkerja/hapus_master_unitkerja',
						method: 'POST',
						params: {
							params: Ext.encode(params)
						},
						success: function(response){
							var obj = Ext.decode(response.responseText);
							Ext.Msg.alert('Informasi', obj.message);
							me.down('#id_unitkerja').getStore().load({
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