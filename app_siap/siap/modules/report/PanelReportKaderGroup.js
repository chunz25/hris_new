Ext.define('SIAP.modules.report.PanelReportKaderGroup', {
    extend: 'Ext.panel.Panel',
    alternateClassName: 'SIAP.panelreportkadergroup',
    alias: 'widget.panelreportkadergroup',
    requires: ['SIAP.components.field.ComboLevel', 'SIAP.components.field.ComboLokasiKerja'],
    initComponent: function () {
        var me = this;

        var storereportkadergroup = Ext.create('Ext.data.Store', {
            storeId: 'storereportkadergroup',
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
                    itemId: 'id_detailkadergroup',
                    xtype: 'grid',
                    region: 'center',
                    layout: 'fit',
                    autoScroll: true,
                    frame: false,
                    border: true,
                    loadMask: true,
                    stripeRows: true,
                    selModel: cb,
                    store: storereportkadergroup,
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
                },
            ],
            tbar: [
                '->',
                {
                    glyph: 'xf1da@FontAwesome',
                    text: 'Reset',
                    handler: function () {
                        storereportkadergroup.removeAll();
                    },
                },
                {
                    glyph: 'xf02f@FontAwesome',
                    text: 'Export',
                    handler: function () {
                        var m = me.down('#id_detailkadergroup').getSelectionModel().getSelection();
                        if (m.length !== 0) {
                            me.simpan2(m);
                        }
                    },
                },
            ],
        });

        me.callParent([arguments]);
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

        Ext.getStore('storereportkadergroup').proxy.extraParams.fingerid = fingerid;
        var m = Ext.getStore('storereportkadergroup').proxy.extraParams;
        window.open(Settings.SITE_URL + '/report/cetakdokumen/reportkadergroup?' + objectParametize(m));
    },
});
