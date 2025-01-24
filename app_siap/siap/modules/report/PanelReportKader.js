Ext.define('SIAP.modules.report.PanelReportKader', {
    extend: 'Ext.panel.Panel',
    alternateClassName: 'SIAP.panelreportkader',
    alias: 'widget.panelreportkader',
    requires: ['SIAP.components.field.ComboLevel', 'SIAP.components.field.ComboLokasiKerja'],
    initComponent: function () {
        var me = this;

        var storereportkader = Ext.create('Ext.data.Store', {
            storeId: 'storereportkader',
            autoLoad: false,
            pageSize: Settings.PAGESIZE,
            proxy: {
                type: 'ajax',
                timeout: 10000000,
                url: Settings.SITE_URL + '/report/getReportListKaderPegawai',
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
            fields: ['pegawaiid', 'nama', 'nik', 'satkerid', 'direktorat', 'divisi', 'departemen', 'seksi', 'subseksi', 'jabatanid', 'jabatan', 'levelid', 'level', 'statuspegawaiid', 'statuspegawai', 'tglmulai', 'tglselesai', 'keterangan', 'lokasi', 'tgllahir', 'tahun', 'bulan'],
            listeners: {
                beforeload: function (store) {
                    me.fireEvent('beforeload', store);
                },
            },
        });

        var cb = {};
        cb = Ext.create('Ext.selection.CheckboxModel', {
            checkOnly: false,
        });

        Ext.apply(me, {
            layout: 'border',
            items: [
                {
                    itemId: 'id_detailkader',
                    xtype: 'grid',
                    region: 'center',
                    layout: 'fit',
                    autoScroll: true,
                    frame: false,
                    border: true,
                    loadMask: true,
                    stripeRows: true,
                    selModel: cb,
                    store: storereportkader,
                    columns: [
                        { header: 'No', xtype: 'rownumberer', width: 30 },
                        { header: 'NIK', dataIndex: 'nik', width: 80 },
                        { header: 'Nama', dataIndex: 'nama', width: 150 },
                        { header: 'Level', dataIndex: 'level', width: 120 },
                        { header: 'Jabatan', dataIndex: 'jabatan', width: 180 },
                        {
                            header: 'Unit',
                            align: 'left',
                            columns: [
                                { header: 'Direktorat', dataIndex: 'direktorat', width: 120 },
                                { header: 'Divisi', dataIndex: 'divisi', width: 120 },
                                { header: 'Departemen', dataIndex: 'departemen', width: 120 },
                                { header: 'Seksi', dataIndex: 'seksi', width: 120 },
                                { header: 'Sub Seksi', dataIndex: 'subseksi', width: 120 },
                            ],
                        },
                        { header: 'Lokasi', dataIndex: 'lokasi', width: 120 },
                    ],
                    /*bbar: Ext.create('Ext.toolbar.Paging',{
                        displayInfo: true,
                        height : 35,
                        store: 'storereportkader'
                    })*/
                },
            ],
            tbar: [
                { itemId: 'id_ketlevel', xtype: 'combolevel' },
                { itemId: 'id_ketlokasi', xtype: 'combolokasikerja' },
                {
                    glyph: 'xf002@FontAwesome',
                    handler: function () {
                        var tree = Ext.ComponentQuery.query('#id_unitkerja')[0].getSelectionModel().getSelection();
                        treeid = null;
                        if (tree.length > 0) {
                            treeid = tree[0].get('id');
                        }
                        Ext.getStore('storereportkader').proxy.extraParams.satkerid = treeid;
                        Ext.getStore('storereportkader').proxy.extraParams.level = Ext.ComponentQuery.query('#id_ketlevel')[0].getValue();
                        Ext.getStore('storereportkader').proxy.extraParams.lokasi = Ext.ComponentQuery.query('#id_ketlokasi')[0].getValue();
                        Ext.getStore('storereportkader').load();
                    },
                },
                '->',
                {
                    glyph: 'xf1da@FontAwesome',
                    text: 'Reset',
                    handler: function () {
                        storereportkader.removeAll();
                    },
                },
                // {
                //     glyph: 'xf02f@FontAwesome',
                //     text: 'Export',
                //     handler: function () {
                //         var m = me.down('#id_detailkader').getSelectionModel().getSelection();
                //         if (m.length !== 0) {
                //             me.simpan(m);
                //         }
                //     },
                // },
                {
                    glyph: 'xf02f@FontAwesome',
                    text: 'Export',
                    handler: function () {
                        var m = me.down('#id_detailkader').getSelectionModel().getSelection();
                        if (m.length !== 0) {
                            me.simpan2(m);
                        }
                    },
                },
            ],
        });

        me.callParent([arguments]);
    },
    simpan: function (recordbrand) {
        var me = this;
        var fingerid = '';
        Ext.each(recordbrand, function (rec, index) {
            fingerid += rec.data.pegawaiid;
            if (index != recordbrand.length - 1) {
                fingerid += "','";
            }
        });

        Ext.getStore('storereportkader').proxy.extraParams.fingerid = fingerid;
        var m = Ext.getStore('storereportkader').proxy.extraParams;
        window.open(Settings.SITE_URL + '/report/cetakdokumen/reportkaderold?' + objectParametize(m));
    },
    simpan2: function (recordbrand) {
        var me = this;
        var fingerid = '';
        Ext.each(recordbrand, function (rec, index) {
            fingerid += rec.data.pegawaiid;
            if (index != recordbrand.length - 1) {
                fingerid += "','";
            }
        });

        Ext.getStore('storereportkader').proxy.extraParams.fingerid = fingerid;
        var m = Ext.getStore('storereportkader').proxy.extraParams;
        window.open(Settings.SITE_URL + '/report/cetakdokumen/reportkader?' + objectParametize(m));
    },
});
