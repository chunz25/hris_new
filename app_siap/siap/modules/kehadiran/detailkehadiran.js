Ext.define('SIAP.modules.kehadiran.detailkehadiran', {
    extend: 'Ext.panel.Panel',
    alternateClassName: 'SIAP.detailkehadiran',
    alias: 'widget.detailkehadiran',
    requires: [],
    initComponent: function () {
        var me = this;
        var arr_params = me.params.split('#');
        var statusid = Base64.decode(arr_params[2]);

        Ext.apply(me, {
            layout: 'border',
            items: [
                {
                    id: 'id_form_detailkehadiran',
                    xtype: 'form',
                    region: 'center',
                    autoScroll: true,
                    bodyPadding: 10,
                    listeners: {
                        afterrender: function (p) {
                            p.getForm().load({
                                url: Settings.SITE_URL + '/kehadiran/getDetailKehadiranPegawai',
                                method: 'POST',
                                params: { pegawaiid: arr_params[0], nourut: arr_params[1], tahun: arr_params[2] },
                                success: function (form, action) {
                                    var obj = Ext.decode(action.response.responseText);
                                    if (obj.success) {
                                        Ext.getStore('storeDetailPengajuanCuti').proxy.extraParams.pengajuanid = obj.data.pengajuanid;
                                        Ext.getStore('storeDetailPengajuanCuti').load();

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
                            xtype: 'fieldset',
                            title: 'Data Karyawan',
                            collapsible: true,
                            items: [
                                { xtype: 'hidden', name: 'tglpermohonan' },
                                { xtype: 'hidden', name: 'fingerid' },
                                { xtype: 'hidden', name: 'pengajuemail' },
                                {
                                    layout: 'column',
                                    baseCls: 'x-plain',
                                    border: false,
                                    items: [
                                        {
                                            xtype: 'panel',
                                            columnWidth: 0.5,
                                            bodyPadding: 10,
                                            layout: 'form',
                                            defaultType: 'displayfield',
                                            baseCls: 'x-plain',
                                            border: false,
                                            defaults: {
                                                labelWidth: 170
                                            },
                                            items: [{ xtype: 'displayfield', fieldLabel: 'NIK', name: 'nik', anchor: '95%' }, { xtype: 'displayfield', fieldLabel: 'Nama', name: 'nama', anchor: '95%' }, { xtype: 'displayfield', fieldLabel: 'Jabatan', name: 'jabatan', anchor: '95%' }]
                                        },
                                        {
                                            xtype: 'panel',
                                            columnWidth: 0.5,
                                            bodyPadding: 10,
                                            layout: 'form',
                                            defaultType: 'displayfield',
                                            baseCls: 'x-plain',
                                            border: false,
                                            defaults: {
                                                labelWidth: 170
                                            },
                                            items: [{ xtype: 'displayfield', fieldLabel: 'Divisi', name: 'divisi', anchor: '95%' }, { xtype: 'displayfield', fieldLabel: 'Lokasi', name: 'lokasi', anchor: '95%' }, { xtype: 'displayfield', fieldLabel: 'Unit Bisnis', name: 'unitbisnis', anchor: '95%' }]
                                        }
                                    ]
                                }
                            ]
                        },
                        { xtype: 'fieldset', title: 'Lampiran', collapsible: true, items: [{ id: 'id_lampiran', xtype: 'displayfield', fieldLabel: 'Lampiran', anchor: '95%' }] },
                        {
                            xtype: 'fieldset',
                            title: 'Pengajuan Kehadiran',
                            collapsible: true,
                            style: 'padding: 5px 10px 5px 10px',
                            items: [
                                { xtype: 'displayfield', fieldLabel: 'Status', name: 'status', anchor: '95%' },
                                {
                                    xtype: 'grid',
                                    store: Ext.create('Ext.data.Store', {
                                        storeId: 'storeDetailPengajuanCuti',
                                        autoLoad: false,
                                        proxy: {
                                            type: 'ajax',
                                            url: Settings.SITE_URL + '/kehadiran/getDetailPengajuanKehadiran',
                                            actionMethods: {
                                                create: 'POST',
                                                read: 'POST'
                                            },
                                            reader: {
                                                type: 'json',
                                                root: 'data',
                                                totalProperty: 'count'
                                            }
                                        },
                                        fields: ['pengajuanid', 'pegawaiid', 'nourut', 'nik', 'nama', 'direktorat', 'divisi', 'departemen', 'seksi', 'subseksi', 'tglpermohonan', 'tglmulai', 'jam', 'jenis', 'status', 'keterangan', 'tglawal', 'tglakhir', 'cutiid', 'fingerid']
                                    }),
                                    columns: [
                                        { text: 'No', xtype: 'rownumberer', width: 30 },
                                        { text: 'Jenis Form', dataIndex: 'jenis' },
                                        { text: 'Tgl Pengajuan', dataIndex: 'tglpermohonan', width: 100 },
                                        { text: 'Tgl Kehadiran', dataIndex: 'tglmulai', width: 100 },
                                        { text: 'Jam', dataIndex: 'jam', width: 50 },
                                        { text: 'Alasan', dataIndex: 'keterangan', flex: 1 }
                                    ]
                                }
                            ]
                        },
                        {
                            xtype: 'panel',
                            layout: 'column',
                            border: false,
                            items: [
                                {
                                    xtype: 'fieldset',
                                    columnWidth: 1,
                                    title: 'Disetujui oleh',
                                    collapsible: true,
                                    defaultType: 'displayfield',
                                    style: 'margin-right:5px;',
                                    defaults: { anchor: '100%' },
                                    layout: 'anchor',
                                    items: [{ fieldLabel: 'Nama', name: 'atasannama' }, { fieldLabel: 'Jabatan', name: 'atasanjabatan' }]
                                }
                            ]
                        }
                    ]
                }
            ],
            tbar: [
                {
                    text: 'Kembali',
                    glyph: 'xf060@FontAwesome',
                    handler: function () {
                        Ext.History.add('#kehadiran');
                    }
                },
                '->',
                {
                    id: 'id_btn_disetujui',
                    glyph: 'xf058@FontAwesome',
                    text: 'Diterima',
                    hidden: true,
                    handler: function () {
                        var form = me.down('#id_form_detailkehadiran').getForm();
                        var nik = form.findField('nik').getValue();
                        var nama = form.findField('nama').getValue();
                        var tglpermohonan = form.findField('tglpermohonan').getValue();
                        var pengajuemail = form.findField('pengajuemail').getValue();
                        var fingerid = form.findField('fingerid').getValue();
                        me.approveKehadiran(arr_params[0], arr_params[1], nik, nama, tglpermohonan, statusid, pengajuemail, fingerid);
                    }
                },
                {
                    id: 'id_btn_ditolak',
                    glyph: 'xf057@FontAwesome',
                    text: 'Ditolak',
                    hidden: true,
                    handler: function () {
                        var form = me.down('#id_form_detailkehadiran').getForm();
                        var nik = form.findField('nik').getValue();
                        var nama = form.findField('nama').getValue();
                        var tglpermohonan = form.findField('tglpermohonan').getValue();
                        var pengajuemail = form.findField('pengajuemail').getValue();
                        me.rejectCuti(arr_params[0], arr_params[1], nik, nama, tglpermohonan, statusid, pengajuemail);
                    }
                }
            ]
        });
        me.callParent([arguments]);

        // Ketika status sudah disetujui oleh Apporval maka tampilkan button approval
        if (statusid == '2') {
            Ext.getCmp('id_btn_disetujui').show();
            Ext.getCmp('id_btn_ditolak').show();
        } else {
            Ext.getCmp('id_btn_disetujui').hide();
            Ext.getCmp('id_btn_ditolak').hide();
        }
    },
    approveKehadiran: function (pegawaiid, nourut, nik, nama, tglpermohonan, statusid, pengajuemail, fingerid) {
        var me = this;

        var paramstemp = [];
        Ext.getStore('storeDetailPengajuanCuti').each(function (recs, index) {
            paramstemp.push(recs.data);
        });

        Ext.Msg.show({
            title: 'Informasi',
            msg: 'Apakah anda yakin akan menyetujui absensi kehadiran ini?',
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.QUESTION,
            fn: function (btn) {
                if (btn == 'yes') {
                    Ext.Ajax.request({
                        url: Settings.SITE_URL + '/kehadiran/approveKehadiran',
                        method: 'POST',
                        params: {
                            pegawaiid: pegawaiid,
                            nourut: nourut,
                            nik: nik,
                            nama: nama,
                            tglpermohonan: tglpermohonan,
                            statusid: statusid,
                            pengajuemail: pengajuemail,
                            fingerid: fingerid,
                            detailallcuti: Ext.encode(paramstemp)
                        },
                        success: function (response) {
                            var obj = Ext.decode(response.responseText);
                            if (obj.success) {
                                Ext.History.add('#kehadiran');
                            }
                        }
                    });
                }
            }
        });
    },
    rejectCuti: function (pegawaiid, nourut, nik, nama, tglpermohonan, statusid, pengajuemail) {
        var me = this;

        var winReject = Ext.create('Ext.window.Window', {
            title: 'Reject Absensi Kehadiran',
            closeAction: 'destroy',
            modal: true,
            layout: 'fit',
            autoScroll: true,
            autoShow: true,
            width: 400,
            buttons: [
                {
                    text: 'Yes',
                    handler: function () {
                        winReject
                            .down('form')
                            .getForm()
                            .submit({
                                waitTitle: 'Menyimpan...',
                                waitMsg: 'Sedang menyimpan data, mohon tunggu...',
                                url: Settings.SITE_URL + '/kehadiran/rejectKehadrian',
                                method: 'POST',
                                params: { pegawaiid: pegawaiid, nourut: nourut, nik: nik, nama: nama, tglpermohonan: tglpermohonan, statusid: statusid, pengajuemail: pengajuemail },
                                success: function (form, action) {
                                    var obj = Ext.decode(action.response.responseText);
                                    winReject.destroy();
                                    if (obj.success) {
                                        Ext.History.add('#kehadiran');
                                    }
                                },
                                failure: function (form, action) { }
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
            items: [
                {
                    xtype: 'form',
                    waitMsgTarget: true,
                    bodyPadding: 15,
                    layout: 'anchor',
                    defaultType: 'textfield',
                    region: 'center',
                    autoScroll: true,
                    defaults: { labelWidth: 60, anchor: '100%' },
                    items: [{ xtype: 'textarea', grow: true, name: 'alasan', fieldLabel: 'Alasan' }]
                }
            ]
        });
    }
});
