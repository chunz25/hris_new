Ext.define("SIAP.modules.tambahpegawai.App", {
	extend: "Ext.form.Panel",
	alternateClassName: "SIAP.tambahpegawai",
	alias: 'widget.tambahpegawai',	
	requires: [
		'SIAP.components.field.ComboAgama',
		'SIAP.components.field.ComboStatusPegawai',
		'SIAP.components.field.FieldJabatan',
		'SIAP.components.field.FieldSatker',
		'SIAP.components.field.ComboLevel',
		'SIAP.components.field.ComboStatusNikah'
	],	
	initComponent: function(){
		var me = this;
		
		var tplFoto = new Ext.XTemplate(
			'<tpl for=".">',
				'<img src="{url}" width="150" height="171" />',
			'</tpl>'
		);
				
		Ext.apply(me,{		
			layout: 'border',
			items: [
				{xtype: 'panel', region: 'center', autoScroll: true, bodyPadding: 10,
					tbar: ['->',
						{text: 'Simpan', glyph:'xf0c7@FontAwesome',
							handler: function(){
								me.tambah();
							}
						},
						{text: 'Batal', glyph:'xf00d@FontAwesome',
							handler: function(){
								Ext.History.add('#pegawai');
							}
						},
					],
					items: [
						{ xtype: 'panel', border: false, width: 150, height: 200, 
							html: tplFoto.applyTemplate({
								url: Settings.no_image_person_url
							}),
							tbar: ['File Max : 150 KB','->', {
								xtype: 'fileuploadfield',
								id: 'form-file',
								name: 'foto',
								buttonOnly: true,
								buttonConfig: { text: '', glyph:'xf093@FontAwesome'}
							}],							
						},					
						{ layout:'column', baseCls: 'x-plain', border: false,
							items: [
								{ xtype: 'panel',
									columnWidth:.5,									
									bodyPadding: 10,
									layout: 'form',				
									defaultType: 'displayfield',
									baseCls: 'x-plain',
									border: false,
									defaults:{
										labelWidth: 170,
									},
									items:[ 
										{ xtype: 'textfield', fieldLabel: 'NIK', name: 'nik', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Nama Depan', name: 'namadepan', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Nama Belakang', name: 'namabelakang', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Nama Keluarga', name: 'namakeluarga', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Tempat Lahir', name: 'tempatlahir', anchor: '95%'},
										{ xtype: 'datefield', fieldLabel: 'Tgl Lahir', name: 'tgllahir', format: 'd/m/Y', anchor: '95%'},
										{ xtype: 'combobox', fieldLabel: 'Jenis Kelamin', name: 'jeniskelamin', anchor: '95%',
											queryMode: 'local', displayField: 'text', valueField: 'id',
											store: Ext.create('Ext.data.Store', {
												fields: ['id', 'text'],
												data : [
													{"id":"L", "text":"Pria"},
													{"id":"P", "text":"Wanita"},
												]												 
											}),
										},
										{ xtype: 'textareafield', fieldLabel: 'Alamat KTP', name: 'alamatktp', grow: true, anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Kelurahan KTP', name: 'kelurahanktp', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Kecamatan KTP', name: 'kecamatanktp', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Kota KTP', name: 'kotaktp', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Kodepos KTP', name: 'kodeposktp', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Alamat Tinggal', name: 'alamat', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Kelurahan', name: 'kelurahan', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Kecamatan', name: 'kecamatan', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Kota', name: 'kota', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Kodepos', name: 'kodepos', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Kewarganegaraan', name: 'kewarganegaraan', anchor: '95%'},
										{ xtype: 'combobox', fieldLabel: 'Gol Darah', name: 'goldarah', anchor: '95%',
											queryMode: 'local', displayField: 'text', valueField: 'id',
											store: Ext.create('Ext.data.Store', {
												fields: ['id', 'text'],
												data : [
													{"id":"A", "text":"A"},
													{"id":"B", "text":"B"},
													{"id":"AB", "text":"AB"},
													{"id":"O", "text":"O"},
												]												 
											}),
										},
										{ xtype: 'textfield', fieldLabel: 'Rhesus', name: 'rhesus', anchor: '95%'},
										{ xtype: 'comboagama', fieldLabel: 'Agama', name: 'agama', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'No Telp', name: 'telp', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'No HP', name: 'hp', anchor: '95%'},
									]
								},
								{ xtype: 'panel',
									columnWidth:.5,									
									bodyPadding: 10,
									layout: 'form',				
									defaultType: 'displayfield',
									baseCls: 'x-plain',
									border: false,
									defaults: {
										labelWidth: 170,
									},
									items:[ 
										{ xtype: 'combostatuspegawai', fieldLabel: 'Status Pegawai', name: 'statuspegawai', anchor: '95%'},
										{ xtype: 'hidden', name: 'satkerid'},
										{ xtype: 'hidden', name: 'jabatanid'},
										{ xtype: 'hidden', name: 'levelid'},
										{ xtype: 'fieldsatker', fieldLabel: 'Direktorat', name: 'direktorat', anchor: '95%',
											listeners: {
												pilih: function(p, record){
													me.getForm().findField('satkerid').setValue(record.get('id'));
													me.getForm().findField('direktorat').setValue(record.get('direktorat'));
													me.getForm().findField('divisi').setValue(record.get('divisi'));
													me.getForm().findField('departemen').setValue(record.get('departemen'));
													me.getForm().findField('seksi').setValue(record.get('seksi'));
													me.getForm().findField('subseksi').setValue(record.get('subseksi'));
												}
											}
										},
										{ xtype: 'textfield', fieldLabel: 'Divisi', name: 'divisi', anchor: '95%', readOnly: true},
										{ xtype: 'textfield', fieldLabel: 'Departemen', name: 'departemen', anchor: '95%', readOnly: true},
										{ xtype: 'textfield', fieldLabel: 'Seksi', name: 'seksi', anchor: '95%', readOnly: true},
										{ xtype: 'textfield', fieldLabel: 'Sub Seksi', name: 'subseksi', anchor: '95%', readOnly: true},
										{ xtype: 'fieldjabatan', fieldLabel: 'Jabatan', name: 'jabatan', anchor: '95%',
											listeners: {
												pilih: function(p, rec){
													me.getForm().findField('jabatanid').setValue(rec.get('id'));
												}
											}										
										},
										{ xtype: 'combolevel', fieldLabel: 'Level', name: 'level', anchor: '95%',
											listeners: {
												select: function(combo, rec, opt){
													me.getForm().findField('levelid').setValue(rec[0].data.id);
												}
											}
										},
										{ xtype: 'datefield', fieldLabel: 'Tgl Masuk', name: 'tglmasuk', format: 'd/m/Y', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Email Pribadi', name: 'email', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Email Kantor', name: 'emailkantor', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'No KTP', name: 'noktp', anchor: '95%'},
										{ xtype: 'datefield', fieldLabel: 'Masa Berlaku KTP', name: 'masaberlakuktp', format: 'd/m/Y', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'NPWP', name: 'npwp', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'BPJS Kesehatan', name: 'bpjskes', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'BPJS Ketenagakerjaan', name: 'bpjsnaker', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Asuransi Kesehatan', name: 'askes', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Passpor', name: 'paspor', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Nomor Kartu Keluarga', name: 'nokk', anchor: '95%'},										
										{ xtype: 'combostatusnikah', fieldLabel: 'Status Pernikahan', name: 'statusnikah', anchor: '95%'},
										
										// { xtype: 'combobox', fieldLabel: 'Status Pernikahan', name: 'statusnikah', anchor: '95%',
											// queryMode: 'local', displayField: 'text', valueField: 'id',
											// store: Ext.create('Ext.data.Store', {
												// fields: ['id', 'text'],
												// data : [
													// {"id":"B", "text":"Belum Nikah"},
													// {"id":"K", "text":"Nikah"},
												// ]												 
											// }),
										// },										
										{ xtype: 'datefield', fieldLabel: 'Tgl Menikah', name: 'tglnikah', format: 'd/m/Y', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Berat Badan', name: 'beratbadan', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Tinggi Badan', name: 'tinggibadan', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Nama Kontak Darurat', name: 'namakontakdarurat', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'No Telp Kontak Darurat', name: 'telpkontakdarurat', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Relasi Kontak Darurat', name: 'relasikontakdarurat', anchor: '95%'},
										{ xtype: 'textareafield', fieldLabel: 'Hobby', name: 'hobby', grow: true, anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Shio', name: 'shio', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Unsur', name: 'unsur', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Size Baju', name: 'sizebaju', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Size Celana', name: 'sizecelana', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Size Sepatu', name: 'sizesepatu', anchor: '95%'},
										{ xtype: 'textfield', fieldLabel: 'Size Rompi', name: 'sizerompi', anchor: '95%'},										
									]
								}
							]
						}					
					]
				}
			]
		});		
		me.callParent([arguments]);				
	},	
	tambah: function(){
		var me = this;
		var formp = me.getForm();
		formp.submit({
			url: Settings.SITE_URL + '/pegawai/tambahPegawai',
			waitTitle:'Menyimpan...', 
			waitMsg:'Sedang menyimpan data, mohon tunggu...',
			success: function(form, action){
				var obj = Ext.decode(action.response.responseText);
				if(obj.success){
					Ext.History.add('#pegawai');
				}
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
});