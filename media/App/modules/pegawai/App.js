Ext.define("App.modules.pegawai.App", {
	extend: "Ext.panel.Panel",
	alternateClassName: "App.pegawai",
	alias: 'widget.pegawai',
	requires: [
		'App.components.progressbar.WinProgress',
		'App.components.field.ComboStatusPegawai',
		'App.modules.pegawai.GridPegawai'
	],		
	initComponent: function(){
		var me = this;
		Ext.apply(me,{		
			layout: 'border',
			items: [
				{ region: 'west', title: 'Daftar Unit', collapsible: true, collapsed: false, layout: 'fit', border: false,
                    resizable:{dynamic:true},		
					items:[
						{xtype: 'unitkerja', width: 300, border: false,
							listeners:{
								itemclick : function(a,b,c){
									me.down('grid').getStore().loadPage(1);
								},
							}												
						}
					]
				},			
				{xtype: 'gridpegawai', region: 'center', frame: true,
					listeners: {
						beforeload:function(store){
							var keyword = me.down('#keyword').getValue();
							var satkerid = '';
							var m = Ext.ComponentQuery.query('#id_unitkerja')[0].getSelectionModel().getSelection();
							if(m.length > 0){
								satkerid = m[0].get('id');
							}
							store.proxy.extraParams.satkerid = satkerid;
							store.proxy.extraParams.keyword = keyword;
						}
					},
					tbar: [					
						{itemId: 'keyword', xtype: 'searchfield', width: 200, emptyText: 'Keyword ...',
							listeners: {
								specialkey: function(f, e){
									if(e.getKey() == e.ENTER){
										me.down('grid').getStore().load();
									}
								},						
								search: function(values, p){
									me.down('grid').getStore().load();
								}								
							}						
						},
					]
				}
			]
		});		
		me.callParent([arguments]);
	},
	win_crud: function(satkerid, record, flag){
		var me = this;		
		var win = Ext.create('Ext.window.Window',{
			title: 'Pegawai', 
			width: 400,
			closeAction: 'destroy', modal:true, layout: 'fit', autoScroll: true, autoShow: true,
			buttons: [
				{text: 'Simpan', 
					handler: function(){
						win.down('form').getForm().submit({
							waitTitle: 'Menyimpan...', 
							waitMsg: 'Sedang menyimpan data, mohon tunggu...',
							url: Settings.SITE_URL + '/c_pegawai/crud_pegawai',
							method: 'POST',
							success: function(form, action){
								var obj = Ext.decode(action.response.responseText);
								win.destroy();
								me.down('grid').getStore().load();
							},
							failure: function(form, action) {
							}																				
						});												
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
					defaults: {
						labelWidth: 100, anchor: '100%'
					},					
					items: [
						{xtype: 'hidden', name:'flag', value: flag},
						{xtype: 'hidden', name:'satkerid', value: satkerid},
						{xtype: 'hidden', name:'pegawaiid'},
						{fieldLabel: 'NIK', name: 'nik'}, 
						{fieldLabel: 'Nama', name: 'nama'}, 
						{xtype: 'textfield', fieldLabel: 'Tempat Lahir', name: 'tempatlahir', hidden: true}, 
						{xtype: 'datefield', fieldLabel: 'Tanggal Lahir', format: 'd/m/Y', name: 'tgllahir', hidden: true}, 
						{xtype: 'combostatuspegawai', fieldLabel: 'Status Pegawai', name: 'statuspegawaiid', hidden: true}, 
						{itemId: 'id_jabatan', xtype: 'fieldjabatan', fieldLabel: 'Jabatan', name: 'JABATAN'},
						{xtype: 'textfield', fieldLabel: 'Email', name: 'email'}, 
						{xtype: 'textfield', fieldLabel: 'Telp', name: 'telp'}, 
						{xtype: 'textfield', fieldLabel: 'VOIP', name: 'voip'}, 
						{xtype: 'textfield', fieldLabel: 'Ext', name: 'extension'}, 						
					]
				},
			]
		});		
		if(flag == '2'){
			var jabatan = win.down('#id_jabatan').items;
			jabatan.items[0].setValue(record.get('jabatan'));
			jabatan.items[1].setValue(record.get('jabatanid'));
			win.down('form').getForm().loadRecord(record);
		}		
	},	
	hapus: function(record){
		var me = this;
		var params = [];	
		Ext.Array.each(record, function(rec, i){
			var temp = {};
			temp.id = rec.get('pegawaiid');
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
						url: Settings.SITE_URL + '/c_pegawai/hapus_pegawai',
						method: 'POST',
						params: {
							params: Ext.encode(params)
						},
						success: function(response){
							var obj = Ext.decode(response.responseText);
							Ext.Msg.alert('Informasi', obj.message);
							me.down('grid').getStore().load();
						}
					});
				}
			}
		});								
	},
	win_import: function(){
		var me = this;
		var win = Ext.create('Ext.window.Window',{
			title: 'Import Data', 
			width: 430,
			closeAction: 'destroy', modal:true, layout: 'fit', autoScroll: true, autoShow: true,
			buttons: [
				{text: 'Simpan', 
					handler: function(){
						win.down('form').getForm().submit({
							waitTitle: 'Menyimpan...', 
							waitMsg: 'Sedang menyimpan data, mohon tunggu...',
							url: Settings.SITE_URL + '/c_pegawai/get_import_data',
							method: 'POST',
							success: function(form, action){
								var obj = Ext.decode(action.response.responseText);
								var winprogress = Ext.create('App.components.progressbar.WinProgress',{
									title: 'Proses Import Data',
									URL: Settings.SITE_URL + '/c_pegawai/proses_import_data',
								});		
								var extraParams = {};
								winprogress.proses_exec(obj.data, extraParams);		
								winprogress.on('finished', function(p){
									Ext.Msg.alert('Pesan', 'Data berhasil diproses');
									me.down('grid').getStore().load();
								});								
							},
							failure: function(form, action) {
							}																				
						});																		
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
					defaults: {
						labelWidth: 100, anchor: '100%'
					},					
					items: [
						{ xtype: 'filefield', name: 'dokumen', fieldLabel: 'Dokumen', allowBlank: false, buttonText: 'Pilih Dokumen ...'}, 
					]
				},
			]
		});				
	},
	cetak: function(){
		var me = this;
		var satker = '';
		var m = me.down('grid').getStore().proxy.extraParams;		
		window.open(Settings.SITE_URL + "/c_pegawai/cetak/daftar?" + objectParametize(m));
	}
});