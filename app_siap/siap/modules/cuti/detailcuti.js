Ext.define("SIAP.modules.cuti.detailcuti", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.detailcuti",
	alias: 'widget.detailcuti',
	requires: [],
	initComponent: function () {
		var me = this;
		var arr_params = me.params.split('#');
		var statusid = Base64.decode(arr_params[3]);

		Ext.apply(me, {
			layout: 'border',
			items: [
				{
					id: 'id_form_detailcuti', xtype: 'form', region: 'center', autoScroll: true, bodyPadding: 10,
					listeners: {
						afterrender: function (p) {
							p.getForm().load({
								url: Settings.SITE_URL + '/cuti/getDetailCutiPegawai',
								method: 'POST',
								params: { pegawaiid: arr_params[0], nourut: arr_params[1], tahun: arr_params[2] },
								success: function (form, action) {
									var obj = Ext.decode(action.response.responseText);
									if (obj.success) {
										Ext.getStore('storeDetailPengajuanCuti').proxy.extraParams.pengajuanid = obj.data.pengajuanid;
										Ext.getStore('storeDetailPengajuanCuti').load();

										Ext.getStore('storeDetailPengajuanCutihidden').proxy.extraParams.pengajuanid = obj.data.pengajuanid;
										Ext.getStore('storeDetailPengajuanCutihidden').load();

										console.log(obj.data);

										if (!Ext.isEmpty(obj.data.files)) {
											Ext.getCmp('id_lampiran').setValue('<a href="' + Settings.SITE_URL + '/cuti/download?filename=' + obj.data.files + '">' + obj.data.files + '</a>');
										}

									}
								}
							});
						}
					},
					items: [
						{
							xtype: 'fieldset', title: 'Data Karyawan', collapsible: true,
							items: [
								{ xtype: 'hidden', name: 'tglpermohonan' },
								{ xtype: 'hidden', name: 'fingerid' },
								{
									layout: 'column', baseCls: 'x-plain', border: false,
									items: [
										{
											xtype: 'panel',
											columnWidth: .5,
											bodyPadding: 10,
											layout: 'form',
											defaultType: 'displayfield',
											baseCls: 'x-plain',
											border: false,
											defaults: {
												labelWidth: 170,
											},
											items: [
												{ xtype: 'displayfield', fieldLabel: 'NIK', name: 'nik', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Nama', name: 'nama', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Jabatan', name: 'jabatan', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Divisi', name: 'divisi', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Lokasi', name: 'lokasi', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Unit Bisnis', name: 'unitbisnis', anchor: '95%' },
											]
										},
										{
											xtype: 'panel',
											columnWidth: .5,
											bodyPadding: 10,
											layout: 'form',
											defaultType: 'displayfield',
											baseCls: 'x-plain',
											border: false,
											defaults: {
												labelWidth: 170,
											},
											items: [
												{ xtype: 'displayfield', fieldLabel: 'Jatah Cuti', name: 'jatahcuti', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: ('Sisa Cuti ' + (moment().year() - 1)), name: 'sisacutithnlalu', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: ('Sisa Cuti ' + moment().year()), name: 'sisacutithnini', anchor: '95%' },
											]
										}
									]
								}
							]
						},
						{
							xtype: 'fieldset', title: 'Kontak Selama Ketidakhadiran', collapsible: true,
							items: [
								{ xtype: 'displayfield', fieldLabel: 'HP', name: 'hp', anchor: '95%' },
								{ id: 'id_lampiran', xtype: 'displayfield', fieldLabel: 'Lampiran', anchor: '95%' },
							]
						},
						{
							xtype: 'fieldset', title: 'Pengajuan Cuti', collapsible: true, style: 'padding: 5px 10px 5px 10px',
							items: [
								{ xtype: 'displayfield', fieldLabel: 'Status', name: 'status', anchor: '95%' },
								{
									xtype: 'grid',
									store: Ext.create('Ext.data.Store', {
										storeId: 'storeDetailPengajuanCuti',
										autoLoad: false,
										proxy: {
											type: 'ajax',
											url: Settings.SITE_URL + '/cuti/getDetailPengajuanCuti',
											actionMethods: {
												create: 'POST',
												read: 'POST',
											},
											reader: {
												type: 'json',
												root: 'data',
												totalProperty: 'count'
											}
										},
										fields: [
											'detailpengajuanid', 'jeniscutiid', 'jeniscuti', 'detailjeniscutiid', 'detailjeniscuti',
											'tglmulai', 'tglselesai', 'lama', 'satuan', 'sisacuti', 'alasancuti', 'tglawal', 'tglakhir', 'status', 'batalcuti'
										],
									}),
									columns: [
										{ text: 'No', xtype: 'rownumberer', width: 30 },
										{ text: 'Jenis Cuti', dataIndex: 'jeniscuti' },
										{ text: 'Alasan', dataIndex: 'alasancuti', flex: 1 },
										{ text: 'Tanggal Awal', dataIndex: 'tglmulai' },
										{ text: 'Tanggal Akhir', dataIndex: 'tglselesai' },
										{ text: 'Lama Cuti', dataIndex: 'lama' },
										{ text: 'Sisa Cuti', dataIndex: 'sisacuti' },
										{
											dataIndex: 'batalcuti', renderer: function (value, meta, record, index) {
												if (record.data.status == '12') {
													if (record.data.batalcuti != null) {
														return '<a onclick="#" style="text-decoration:underline;color:blue;">' + '✓' + '</a>';
													} else {
														return '<a onclick="#" style="text-decoration:underline;color:red;">' + 'x' + '</a>';
													}
												}
											}
										},
									],
								}
							]
						},
						{
							xtype: 'hidden', title: 'Pengajuan Cuti', collapsible: true, style: 'padding: 5px 10px 5px 10px',
							items: [
								{ xtype: 'displayfield', fieldLabel: 'Status', name: 'status', anchor: '95%' },
								{
									xtype: 'grid',
									store: Ext.create('Ext.data.Store', {
										storeId: 'storeDetailPengajuanCutihidden',
										autoLoad: false,
										proxy: {
											type: 'ajax',
											url: Settings.SITE_URL + '/cuti/getDetailPengajuanCutiHidden',
											actionMethods: {
												create: 'POST',
												read: 'POST',
											},
											reader: {
												type: 'json',
												root: 'data',
												totalProperty: 'count'
											}
										},
										fields: [
											'detailpengajuanid', 'jeniscutiid', 'jeniscuti', 'detailjeniscutiid', 'detailjeniscuti',
											'tglmulai', 'tglselesai', 'lama', 'satuan', 'sisacuti', 'alasancuti', 'tglawal', 'tglakhir', 'cutiid', 'dow'
										],
									}),
									columns: [
										{ text: 'No', xtype: 'rownumberer', width: 30 },
										{ text: 'Jenis Cuti', dataIndex: 'jeniscuti' },
										{ text: 'Alasan', dataIndex: 'alasancuti', flex: 1 },
										{ text: 'Tanggal Awal', dataIndex: 'tglmulai' },
										{ text: 'Tanggal Akhir', dataIndex: 'tglselesai' },
										{ text: 'Lama Cuti', dataIndex: 'lama' },
										{ text: 'Sisa Cuti', dataIndex: 'sisacuti' }
									],
								}
							]
						},
						{
							xtype: 'fieldset', title: 'Pelimpahan Tanggung Jawab', collapsible: true,
							items: [
								{
									layout: 'column', baseCls: 'x-plain', border: false,
									items: [
										{
											xtype: 'panel',
											columnWidth: .5,
											bodyPadding: 10,
											layout: 'form',
											defaultType: 'displayfield',
											baseCls: 'x-plain',
											border: false,
											defaults: {
												labelWidth: 170,
											},
											items: [
												{ xtype: 'displayfield', fieldLabel: 'NIK', name: 'pelimpahannik', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Nama', name: 'pelimpahannama', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'HP', name: 'pelimpahanhp', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Jabatan', name: 'pelimpahanjabatan', anchor: '95%' },
											]
										},
										{
											xtype: 'panel',
											columnWidth: .5,
											bodyPadding: 10,
											layout: 'form',
											defaultType: 'displayfield',
											baseCls: 'x-plain',
											border: false,
											defaults: {
												labelWidth: 170,
											},
											items: [
												{ xtype: 'displayfield', fieldLabel: 'Divisi', name: 'pelimpahansatker', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Lokasi', name: 'pelimpahanlokasi', anchor: '95%' },
												{ xtype: 'displayfield', fieldLabel: 'Unit Bisnis', name: 'pelimpahanunitbisnis', anchor: '95%' },
											]
										}
									]
								}
							]
						},
						{
							xtype: 'panel', layout: 'column', border: false,
							items: [
								{
									xtype: 'fieldset', columnWidth: 0.5, title: 'Diperiksa oleh', collapsible: true, defaultType: 'displayfield', style: 'margin-right:5px;',
									defaults: { anchor: '100%' }, layout: 'anchor',
									items: [
										{ fieldLabel: 'Nama', name: 'atasannama' },
										{ fieldLabel: 'Jabatan', name: 'atasanjabatan' }
									]
								},
								{
									xtype: 'fieldset', columnWidth: 0.5, title: 'Disetujui oleh', collapsible: true, defaultType: 'displayfield', style: 'margin-left:5px;',
									defaults: { anchor: '100%' }, layout: 'anchor',
									items: [
										{ fieldLabel: 'Nama', name: 'atasan2nama' },
										{ fieldLabel: 'Jabatan', name: 'atasan2jabatan' }
									]
								}
							]
						},
					]
				}
			],
			tbar: [
				{
					text: 'Kembali', glyph: 'xf060@FontAwesome',
					handler: function () {
						Ext.History.add('#cuti');
					}
				},
				'->',
				{
					id: 'id_btn_disetujui', glyph: 'xf058@FontAwesome', text: 'Diterima', hidden: true,
					handler: function () {
						var form = me.down('#id_form_detailcuti').getForm();
						var nik = form.findField('nik').getValue();
						var nama = form.findField('nama').getValue();
						var tglpermohonan = form.findField('tglpermohonan').getValue();
						var fingerid = form.findField('fingerid').getValue();
						me.approveCuti(arr_params[0], arr_params[1], nik, nama, tglpermohonan, statusid, fingerid);
					}
				},
				{
					id: 'id_btn_ditolak', glyph: 'xf057@FontAwesome', text: 'Ditolak', hidden: true,
					handler: function () {
						var form = me.down('#id_form_detailcuti').getForm();
						var nik = form.findField('nik').getValue();
						var nama = form.findField('nama').getValue();
						var tglpermohonan = form.findField('tglpermohonan').getValue();
						me.rejectCuti(arr_params[0], arr_params[1], nik, nama, tglpermohonan, statusid);
					}
				},
			]
		});
		me.callParent([arguments]);

		// Ketika status sudah disetujui oleh Apporval maka tampilkan button approval
		if (statusid == '5' || statusid == '12') {
			Ext.getCmp('id_btn_disetujui').show();
			Ext.getCmp('id_btn_ditolak').show();
		}
		else {
			Ext.getCmp('id_btn_disetujui').hide();
			Ext.getCmp('id_btn_ditolak').hide();
		}
	},
	approveCuti: function (pegawaiid, nourut, nik, nama, tglpermohonan, statusid, fingerid) {
		var me = this;

		var paramstemp = [];
		Ext.getStore('storeDetailPengajuanCutihidden').each(function (recs, index) {
			paramstemp.push(recs.data);
		});

		Ext.Msg.show({
			title: 'Informasi',
			msg: 'Apakah anda yakin akan menyetujui cuti ini?',
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn: function (btn) {
				if (btn == 'yes') {
					Ext.Ajax.request({
						url: Settings.SITE_URL + '/cuti/approveCuti',
						method: 'POST',
						params: {
							pegawaiid: pegawaiid,
							nourut: nourut,
							nik: nik,
							nama: nama,
							tglpermohonan: tglpermohonan,
							statusid: statusid,
							fingerid: fingerid,
							detailallcuti: Ext.encode(paramstemp)
						},
						success: function (response) {
							var obj = Ext.decode(response.responseText);
							if (obj.success) {
								Ext.History.add('#cuti');
							}
						}
					});
				}
			}
		});
	},
	rejectCuti: function (pegawaiid, nourut, nik, nama, tglpermohonan, statusid) {
		var me = this;

		var winReject = Ext.create('Ext.window.Window', {
			title: 'Reject Cuti',
			closeAction: 'destroy', modal: true, layout: 'fit', autoScroll: true, autoShow: true, width: 400,
			buttons: [
				{
					text: 'Yes',
					handler: function () {
						winReject.down('form').getForm().submit({
							waitTitle: 'Menyimpan...',
							waitMsg: 'Sedang menyimpan data, mohon tunggu...',
							url: Settings.SITE_URL + '/cuti/rejectCuti',
							method: 'POST',
							params: { pegawaiid: pegawaiid, nourut: nourut, nik: nik, nama: nama, tglpermohonan: tglpermohonan, statusid: statusid },
							success: function (form, action) {
								var obj = Ext.decode(action.response.responseText);
								winReject.destroy();
								if (obj.success) {
									Ext.History.add('#cuti');
								}
							},
							failure: function (form, action) {
							}
						});
					}
				},
				{
					text: 'No',
					handler: function () {
						winReject.destroy();
					}
				}
			],
			items: [{
				xtype: 'form', waitMsgTarget: true, bodyPadding: 15, layout: 'anchor', defaultType: 'textfield', region: 'center', autoScroll: true,
				defaults: { labelWidth: 60, anchor: '100%' },
				items: [
					{ xtype: 'textarea', grow: true, name: 'alasan', fieldLabel: 'Alasan' }
				]
			}]
		});
	}
});