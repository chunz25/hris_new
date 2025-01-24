Ext.define('SIAP.modules.detailpegawai.RiwayatKeluarga', {
    extend: 'Ext.panel.Panel',
    alternateClassName: 'SIAP.RiwayatKeluarga',
    alias: 'widget.riwayatkeluarga',
    requires: ['SIAP.modules.detailpegawai.TreeDRH', 'SIAP.components.field.ComboRelasiKeluarga'],
    initComponent: function () {
        var me = this;
        var storeriwkeluarga = Ext.create('Ext.data.Store', {
            storeId: 'storeriwkeluarga',
            autoLoad: true,
            pageSize: Settings.PAGESIZE,
            proxy: {
                type: 'ajax',
                url: Settings.SITE_URL + '/pegawai/getRiwayatKeluarga',
                actionMethods: {
                    create: 'POST',
                    read: 'POST',
                },
                reader: {
                    type: 'json',
                    root: 'data',
                    totalProperty: 'count',
                },
            },
            fields: ['pegawaiid', 'nourut', 'relasiid', 'relasi', 'nama', 'jeniskelamin', 'tgllahir', 'pendidikan', 'pekerjaan', 'foto', 'fotourl', 'tmptlahir', 'alamat'],
            listeners: {
                beforeload: function (store) {
                    store.proxy.extraParams.pegawaiid = me.params;
                },
            },
        });

        Ext.apply(me, {
            layout: 'border',
            items: [
                { region: 'west', title: 'Daftar Riwayat Hidup', collapsible: true, collapsed: false, layout: 'fit', border: false, split: true, resizable: { dynamic: true }, items: [{ xtype: 'treedrh', params: me.params }] },
                {
                    title: 'Data Keluarga',
                    xtype: 'grid',
                    region: 'center',
                    layout: 'fit',
                    autoScroll: true,
                    frame: false,
                    border: true,
                    loadMask: true,
                    stripeRows: true,
                    store: storeriwkeluarga,
                    columns: [
                        { header: 'No', xtype: 'rownumberer', width: 30 },
                        {
                            header: 'Foto',
                            dataIndex: 'fotourl',
                            width: 100,
                            renderer: function (value, meta, record, rowIndex, colIndex, stor) {
                                return '<img src="' + value + '" width="70%" />';
                            },
                        },
                        { header: 'Relasi', dataIndex: 'relasi', width: 160 },
                        { header: 'Nama', dataIndex: 'nama', width: 160 },
                        { header: 'Jenis Kelamin', dataIndex: 'jeniskelamin', width: 100 },
                        { header: 'Tempat Lahir', dataIndex: 'tmptlahir', width: 80 },
                        { header: 'Tgl Lahir', dataIndex: 'tgllahir', width: 80 },
                        { header: 'Alamat', dataIndex: 'alamat', width: 160 },
                        { header: 'Pendidikan', dataIndex: 'pendidikan', width: 150 },
                        { header: 'Pekerjaan', dataIndex: 'pekerjaan', width: 160 },
                    ],
                    bbar: Ext.create('Ext.toolbar.Paging', {
                        displayInfo: true,
                        height: 35,
                        store: 'storeriwkeluarga',
                    }),
                    tbar: [
                        {
                            text: 'Kembali',
                            glyph: 'xf060@FontAwesome',
                            handler: function () {
                                Ext.History.add('#pegawai');
                            },
                        },
                        '->',
                        {
                            text: 'Tambah',
                            glyph: 'xf196@FontAwesome',
                            handler: function () {
                                me.wincrud('1', {});
                            },
                        },
                        {
                            text: 'Ubah',
                            glyph: 'xf044@FontAwesome',
                            handler: function () {
                                var m = me.down('grid').getSelectionModel().getSelection();
                                if (m.length > 0) {
                                    me.wincrud('2', m[0]);
                                } else {
                                    Ext.Msg.alert('Pesan', 'Harap pilih data terlebih dahulu');
                                }
                            },
                        },
                        {
                            text: 'Hapus',
                            glyph: 'xf014@FontAwesome',
                            handler: function () {
                                var m = me.down('grid').getSelectionModel().getSelection();
                                if (m.length > 0) {
                                    me.winDelete(m);
                                } else {
                                    Ext.Msg.alert('Pesan', 'Harap pilih data terlebih dahulu');
                                }
                            },
                        },
                    ],
                },
            ],
        });
        me.callParent([arguments]);
    },
    wincrud: function (flag, records) {
        var me = this;
        var win = Ext.create('Ext.window.Window', {
            title: 'Data Keluarga',
            width: 400,
            closeAction: 'destroy',
            modal: true,
            layout: 'fit',
            autoScroll: true,
            autoShow: true,
            buttons: [
                {
                    text: 'Simpan',
                    handler: function () {
                        var formp = win.down('form').getForm();
                        formp.submit({
                            url: Settings.SITE_URL + '/pegawai/crudRiwayatKeluarga',
                            waitTitle: 'Menyimpan...',
                            waitMsg: 'Sedang menyimpan data, mohon tunggu...',
                            success: function (form, action) {
                                var obj = Ext.decode(action.response.responseText);
                                // console.log(obj);
                                if (obj.success) {
                                    win.destroy();
                                    me.down('grid').getSelectionModel().deselectAll();
                                    me.down('grid').getStore().reload();
                                    window.location.reload(true);
                                }
                            },
                            failure: function (form, action) {
                                var obj = Ext.decode(action.response.responseText);
                                console.log(obj);
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
                            },
                        });
                    },
                },
                {
                    text: 'Batal',
                    handler: function () {
                        win.destroy();
                    },
                },
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
                    defaults: {
                        labelWidth: 100,
                        anchor: '100%',
                    },
                    items: [
                        { xtype: 'hidden', name: 'flag', value: flag },
                        { xtype: 'hidden', name: 'pegawaiid', value: me.params },
                        { xtype: 'hidden', name: 'nourut' },
                        { xtype: 'hidden', name: 'fotoname' },
                        { xtype: 'hidden', name: 'relasiid' },
                        {
                            xtype: 'comborelasikeluarga',
                            fieldLabel: 'Relasi',
                            name: 'relasi',
                            listeners: {
                                select: function (combo, rec, opt) {
                                    win.down('form').getForm().findField('relasi').setValue(rec[0].data.id);
                                },
                            },
                        },
                        { fieldLabel: 'Nama', name: 'nama' },
                        {
                            xtype: 'combobox',
                            fieldLabel: 'Jenis Kelamin',
                            name: 'jeniskelamin',
                            queryMode: 'local',
                            displayField: 'text',
                            valueField: 'id',
                            store: Ext.create('Ext.data.Store', {
                                fields: ['id', 'text'],
                                data: [
                                    { id: 'L', text: 'Pria' },
                                    { id: 'P', text: 'Wanita' },
                                ],
                            }),
                        },
                        { xtype: 'datefield', fieldLabel: 'Tgl Lahir', format: 'd/m/Y', name: 'tgllahir' },
                        { fieldLabel: 'Pendidikan', name: 'pendidikan' },
                        { fieldLabel: 'Pekerjaan', name: 'pekerjaan' },
                        { xtype: 'filefield', name: 'foto', fieldLabel: 'Foto', buttonConfig: { text: '', glyph: 'xf093@FontAwesome' } },
                        { fieldLabel: 'Tempat Lahir', name: 'tmptlahir' },
                        { fieldLabel: 'Alamat', name: 'alamat' },
                    ],
                },
            ],
        });

        if (flag == '2') {
            win.down('form').getForm().loadRecord(records);
            win.down('form').getForm().findField('fotoname').setValue(records.get('foto'));
        }
    },
    winDelete: function (record) {
        var me = this;
        var params = [];
        Ext.Array.each(record, function (rec, i) {
            var temp = {};
            temp.pegawaiid = rec.get('pegawaiid');
            temp.nourut = rec.get('nourut');
            temp.foto = rec.get('foto');
            params.push(temp);
        });
        Ext.Msg.show({
            title: 'Konfirmasi',
            msg: 'Apakah anda yakin akan menghapus data ?',
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.QUESTION,
            fn: function (btn) {
                if (btn == 'yes') {
                    Ext.Ajax.request({
                        url: Settings.SITE_URL + '/pegawai/delRiwayatKeluarga',
                        method: 'POST',
                        params: {
                            params: Ext.encode(params),
                        },
                        success: function (response) {
                            var obj = Ext.decode(response.responseText);
                            if (obj.success) {
                                Ext.Msg.alert('Informasi', obj.message);
                                me.down('grid').getSelectionModel().deselectAll();
                                me.down('grid').getStore().reload();
                            }
                        },
                    });
                }
            },
        });
    },
});
