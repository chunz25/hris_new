Ext.define('SIAP.modules.report.PanelReportSDM', {
    extend: 'Ext.panel.Panel',
    alternateClassName: 'SIAP.panelreportsdm',
    alias: 'widget.panelreportsdm',
    initComponent: function () {
        var me = this;
        var storereportsdm = Ext.create('Ext.data.Store', {
            storeId: 'storereportsdm',
            autoLoad: true,
            pageSize: Settings.PAGESIZE,
            proxy: {
                type: 'ajax',
                url: Settings.SITE_URL + '/report/getReportSDM',
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
            fields: [
                'levelid',
                'level',
                'gol',
                { name: 'status1', type: 'int' },
                { name: 'status2', type: 'int' },
                { name: 'totstatus', type: 'int' },
                { name: 'mskerja1', type: 'int' },
                { name: 'mskerja2', type: 'int' },
                { name: 'mskerja3', type: 'int' },
                { name: 'mskerja4', type: 'int' },
                { name: 'totmasakerja', type: 'int' },
                { name: 'pend0', type: 'int' },
                { name: 'pend1', type: 'int' },
                { name: 'pend2', type: 'int' },
                { name: 'pend3', type: 'int' },
                { name: 'pend4', type: 'int' },
                { name: 'pend5', type: 'int' },
                { name: 'totpend', type: 'int' },
                { name: 'lok1', type: 'int' },
                { name: 'lok2', type: 'int' },
                { name: 'lok3', type: 'int' },
                { name: 'lok4', type: 'int' },
                { name: 'lok5', type: 'int' },
                { name: 'lok6', type: 'int' },
                { name: 'lok7', type: 'int' },
                { name: 'totlokasi', type: 'int' },
            ],
            listeners: {
                beforeload: function (store) {
                    me.fireEvent('beforeload', store);
                },
            },
        });

        var storereportsdmbyid = Ext.create('Ext.data.Store', {
            storeId: 'storereportsdmbyid',
            autoLoad: true,
            pageSize: Settings.PAGESIZE,
            proxy: {
                type: 'ajax',
                url: Settings.SITE_URL + '/report/getReportListSDM',
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
            fields: ['pegawaiid', 'nama', 'nik', 'satkerid', 'direktorat', 'divisi', 'departemen', 'seksi', 'subseksi', 'jabatanid', 'jabatan', 'levelid', 'level', 'lokasiid', 'lokasi', 'statuspegawai', 'tglmulai', 'tglakhirkontrak', 'masakerjaseluruhth', 'masakerjaseluruhbl', 'pendidikan'],
            listeners: {
                beforeload: function (store) {
                    me.fireEvent('beforeload', store);
                },
            },
        });

        Ext.apply(me, {
            layout: 'border',
            items: [
                {
                    xtype: 'grid',
                    region: 'center',
                    layout: 'fit',
                    autoScroll: true,
                    frame: false,
                    border: true,
                    loadMask: true,
                    stripeRows: true,
                    store: storereportsdm,
                    columns: [
                        { header: 'No', xtype: 'rownumberer', width: 30 },
                        { header: 'Grade', dataIndex: 'gol', width: 42 },
                        {
                            header: 'Level',
                            dataIndex: 'level',
                            width: 120,
                            renderer: function (value, meta, record, index) {
                                if (record.data.level != null) {
                                    if (record.data.gol == 0) {
                                        return '<a onclick=detailAlasan("' + 'bod' + '") style="cursor:pointer;font-weight:bold;">' + record.data.level + '</a>';
                                    } else {
                                        return '<a onclick=detailAlasan("' + record.data.gol + '") style="cursor:pointer;font-weight:bold;">' + record.data.level + '</a>';
                                    }
                                } else {
                                    return record.data.status;
                                }
                            },
                        },
                        {
                            header: 'Status',
                            dataIndex: 'level',
                            columns: [
                                {
                                    header: 'Permanent',
                                    dataIndex: 'status1',
                                    width: 70,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            tot1 = 0,
                                            totstatus = 0,
                                            persen = 0,
                                            record;

                                        tot1 = records[length - 1].get('status1');
                                        totstatus = records[length - 1].get('totstatus');

                                        if (totstatus > 0) {
                                            persen = (tot1 / totstatus) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: 'Contract',
                                    dataIndex: 'status2',
                                    width: 70,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            tot2 = 0,
                                            totstatus = 0,
                                            persen = 0,
                                            record;

                                        tot2 = records[length - 1].get('status2');
                                        totstatus = records[length - 1].get('totstatus');

                                        if (totstatus > 0) {
                                            persen = (tot2 / totstatus) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: 'Total',
                                    dataIndex: 'totstatus',
                                    width: 70,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totstatus = 0,
                                            persen = 0,
                                            record;

                                        totstatus = records[length - 1].get('totstatus');

                                        if (totstatus > 0) {
                                            persen = (totstatus / totstatus) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                            ],
                        },
                        {
                            header: 'Lokasi',
                            align: 'left',
                            columns: [
                                {
                                    header: 'HO',
                                    dataIndex: 'lok1',
                                    width: 70,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totpend0 = 0,
                                            totpend = 0,
                                            persen = 0,
                                            record;

                                        totpend0 = records[length - 1].get('lok1');
                                        totpend = records[length - 1].get('totlokasi');
                                        if (totpend > 0) {
                                            persen = (totpend0 / totpend) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: 'Store',
                                    dataIndex: 'lok3',
                                    width: 70,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totpend1 = 0,
                                            totpend = 0,
                                            persen = 0,
                                            record;

                                        totpend1 = records[length - 1].get('lok3');
                                        totpend = records[length - 1].get('totlokasi');
                                        if (totpend > 0) {
                                            persen = (totpend1 / totpend) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: 'DC',
                                    dataIndex: 'lok2',
                                    width: 80,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totpend2 = 0,
                                            totpend = 0,
                                            persen = 0,
                                            record;

                                        totpend2 = records[length - 1].get('lok2');
                                        totpend = records[length - 1].get('totlokasi');
                                        if (totpend > 0) {
                                            persen = (totpend2 / totpend) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: 'GKT',
                                    dataIndex: 'lok4',
                                    width: 80,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totpend2 = 0,
                                            totpend = 0,
                                            persen = 0,
                                            record;

                                        totpend2 = records[length - 1].get('lok4');
                                        totpend = records[length - 1].get('totlokasi');
                                        if (totpend > 0) {
                                            persen = (totpend2 / totpend) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: 'CEE',
                                    dataIndex: 'lok5',
                                    width: 80,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totpend2 = 0,
                                            totpend = 0,
                                            persen = 0,
                                            record;

                                        totpend2 = records[length - 1].get('lok5');
                                        totpend = records[length - 1].get('totlokasi');
                                        if (totpend > 0) {
                                            persen = (totpend2 / totpend) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: 'GCI',
                                    dataIndex: 'lok6',
                                    width: 80,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totpend2 = 0,
                                            totpend = 0,
                                            persen = 0,
                                            record;

                                        totpend2 = records[length - 1].get('lok6');
                                        totpend = records[length - 1].get('totlokasi');
                                        if (totpend > 0) {
                                            persen = (totpend2 / totpend) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: 'NRC',
                                    dataIndex: 'lok7',
                                    width: 80,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totpend2 = 0,
                                            totpend = 0,
                                            persen = 0,
                                            record;

                                        totpend2 = records[length - 1].get('lok7');
                                        totpend = records[length - 1].get('totlokasi');
                                        if (totpend > 0) {
                                            persen = (totpend2 / totpend) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: 'Total',
                                    dataIndex: 'totlokasi',
                                    width: 80,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totpend = 0,
                                            persen = 0,
                                            record;

                                        totpend = records[length - 1].get('totlokasi');
                                        if (totpend > 0) {
                                            persen = (totpend / totpend) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                            ],
                        },
                        {
                            header: 'Masa Kerja (th)',
                            align: 'left',
                            columns: [
                                {
                                    header: '< 5',
                                    dataIndex: 'mskerja1',
                                    width: 60,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totmskerja1 = 0,
                                            totmasakerja = 0,
                                            persen = 0,
                                            record;

                                        totmskerja1 = records[length - 1].get('mskerja1');
                                        totmasakerja = records[length - 1].get('totmasakerja');
                                        if (totmasakerja > 0) {
                                            persen = (totmskerja1 / totmasakerja) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: '5 - 10',
                                    dataIndex: 'mskerja2',
                                    width: 60,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totmskerja2 = 0,
                                            totmasakerja = 0,
                                            persen = 0,
                                            record;

                                        totmskerja2 = records[length - 1].get('mskerja2');
                                        totmasakerja = records[length - 1].get('totmasakerja');
                                        if (totmasakerja > 0) {
                                            persen = (totmskerja2 / totmasakerja) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: '10 - 15',
                                    dataIndex: 'mskerja3',
                                    width: 60,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totmskerja3 = 0,
                                            totmasakerja = 0,
                                            persen = 0,
                                            record;

                                        totmskerja3 = records[length - 1].get('mskerja3');
                                        totmasakerja = records[length - 1].get('totmasakerja');
                                        if (totmasakerja > 0) {
                                            persen = (totmskerja3 / totmasakerja) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: '> 15',
                                    dataIndex: 'mskerja4',
                                    width: 60,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totmskerja4 = 0,
                                            totmasakerja = 0,
                                            persen = 0,
                                            record;

                                        totmskerja4 = records[length - 1].get('mskerja4');
                                        totmasakerja = records[length - 1].get('totmasakerja');
                                        if (totmasakerja > 0) {
                                            persen = (totmskerja4 / totmasakerja) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: 'Total',
                                    dataIndex: 'totmasakerja',
                                    width: 60,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totmasakerja = 0,
                                            persen = 0,
                                            record;

                                        totmasakerja = records[length - 1].get('totmasakerja');
                                        if (totmasakerja > 0) {
                                            persen = (totmasakerja / totmasakerja) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                            ],
                        },
                        {
                            header: 'Pendidikan',
                            align: 'left',
                            columns: [
                                {
                                    header: 'SMP',
                                    dataIndex: 'pend0',
                                    width: 70,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totpend0 = 0,
                                            totpend = 0,
                                            persen = 0,
                                            record;

                                        totpend0 = records[length - 1].get('pend0');
                                        totpend = records[length - 1].get('totpend');
                                        if (totpend > 0) {
                                            persen = (totpend0 / totpend) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: 'SMA',
                                    dataIndex: 'pend1',
                                    width: 70,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totpend1 = 0,
                                            totpend = 0,
                                            persen = 0,
                                            record;

                                        totpend1 = records[length - 1].get('pend1');
                                        totpend = records[length - 1].get('totpend');
                                        if (totpend > 0) {
                                            persen = (totpend1 / totpend) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: 'Diploma',
                                    dataIndex: 'pend2',
                                    width: 80,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totpend2 = 0,
                                            totpend = 0,
                                            persen = 0,
                                            record;

                                        totpend2 = records[length - 1].get('pend2');
                                        totpend = records[length - 1].get('totpend');
                                        if (totpend > 0) {
                                            persen = (totpend2 / totpend) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: 'S1',
                                    dataIndex: 'pend3',
                                    width: 80,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totpend3 = 0,
                                            totpend = 0,
                                            persen = 0,
                                            record;

                                        totpend3 = records[length - 1].get('pend3');
                                        totpend = records[length - 1].get('totpend');
                                        if (totpend > 0) {
                                            persen = (totpend3 / totpend) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: 'S2',
                                    dataIndex: 'pend4',
                                    width: 80,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totpend4 = 0,
                                            totpend = 0,
                                            persen = 0,
                                            record;

                                        totpend4 = records[length - 1].get('pend4');
                                        totpend = records[length - 1].get('totpend');
                                        if (totpend > 0) {
                                            persen = (totpend4 / totpend) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: 'S3',
                                    dataIndex: 'pend5',
                                    width: 80,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totpend5 = 0,
                                            totpend = 0,
                                            persen = 0,
                                            record;

                                        totpend5 = records[length - 1].get('pend5');
                                        totpend = records[length - 1].get('totpend');
                                        if (totpend > 0) {
                                            persen = (totpend5 / totpend) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                                {
                                    header: 'Total',
                                    dataIndex: 'totpend',
                                    width: 80,
                                    align: 'center',
                                    summaryType: function (records, values) {
                                        var i = 0,
                                            length = records.length,
                                            totpend = 0,
                                            persen = 0,
                                            record;

                                        totpend = records[length - 1].get('totpend');
                                        if (totpend > 0) {
                                            persen = (totpend / totpend) * 100;
                                            return persen;
                                        }
                                    },
                                    summaryRenderer: function (value, summaryData, dataIndex) {
                                        return Ext.util.Format.numberRenderer('0,000')(value) + ' %';
                                    },
                                },
                            ],
                        },
                    ],
                },
                {
                    xtype: 'grid',
                    region: 'south',
                    title: 'Data Karyawan',
                    height: 250,
                    collapsed: true,
                    collapsible: true,
                    autoScroll: true,
                    frame: false,
                    border: true,
                    loadMask: true,
                    stripeRows: true,
                    store: storereportsdmbyid,
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
                        { header: 'Status', dataIndex: 'statuspegawai', width: 80 },
                        { header: 'Lokasi', dataIndex: 'lokasi', width: 80 },
                        {
                            text: 'Masa Kerja',
                            dataIndex: 'lama',
                            renderer: function (value, p, r) {
                                return r.data['masakerjaseluruhth'] + ' th ' + (r.data['masakerjaseluruhbl'] + ' bln ');
                            },
                        },
                        { header: 'Pendidikan', dataIndex: 'pendidikan', width: 80 },
                    ],
                    bbar: Ext.create('Ext.toolbar.Paging', {
                        displayInfo: true,
                        height: 35,
                        store: 'storereportsdmbyid',
                    }),
                },
            ],
            tbar: [
                '->',
                {
                    glyph: 'xf02f@FontAwesome',
                    text: 'Cetak',
                    handler: function () {
                        var tree = Ext.ComponentQuery.query('#id_unitkerja')[0].getSelectionModel().getSelection();
                        treeid = null;
                        if (tree.length > 0) {
                            treeid = tree[0].get('id');
                        }
                        function detailAlasan(id) {
                            Ext.getStore('storereportsdmbyid').proxy.extraParams.levelid = id;
                        }
                        var obj = null;
                        var m = Ext.getStore('storereportsdmbyid').proxy.extraParams;
                        window.open(Settings.SITE_URL + '/report/cetakdokumen/bysdm?' + objectParametize(m));
                    },
                },
            ],
        });

        me.callParent([arguments]);
    },
});

function detailAlasan(id) {
    Ext.getStore('storereportsdmbyid').proxy.extraParams.levelid = id;
    Ext.getStore('storereportsdmbyid').load();
}
