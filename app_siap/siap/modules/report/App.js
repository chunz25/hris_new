Ext.define('SIAP.modules.report.App', {
    extend: 'Ext.panel.Panel',
    alternateClassName: 'SIAP.report',
    alias: 'widget.report',
    requires: [
        'SIAP.components.tree.UnitKerja',
        'SIAP.components.tree.Lokasi',
        'SIAP.modules.report.PanelReportDivisi',
        'SIAP.modules.report.PanelReportStatusPegawai',
        'SIAP.modules.report.PanelReportSDM',
        'SIAP.modules.report.PanelReportLocation',
        'SIAP.modules.report.PanelReportLevel',
        'SIAP.modules.report.PanelReportKetStatus',
        'SIAP.modules.report.PanelReportRemindContract',
        'SIAP.modules.report.PanelReportGender',
        'SIAP.modules.report.PanelReportMutasiPromosi',
        'SIAP.modules.report.PanelReportActingAs',
        'SIAP.modules.report.PanelReportUsia',
        'SIAP.modules.report.PanelReportKader',
        'SIAP.modules.report.PanelReportKaderGroup',
        'SIAP.modules.report.PanelReportUlangtahun'
    ],
    initComponent: function () {
        var me = this;
        window.loadTask = new Ext.util.DelayedTask();
        Ext.apply(me, {
            layout: 'border',
            items: [
                {
                    id: 'id_west_treesatker',
                    region: 'west',
                    title: 'Daftar Unit Kerja',
                    collapsible: true,
                    collapsed: false,
                    layout: 'fit',
                    border: false,
                    resizable: { dynamic: true },
                    items: [
                        {
                            xtype: 'unitkerja',
                            width: 200,
                            border: false,
                            listeners: {
                                itemclick: function (a, b, c) {
                                    var itemTab = me.down('tabpanel').getActiveTab().getItemId();
                                    console.log(itemTab);
                                    console.log(b.get('id'));
                                    if (itemTab == 'panelreportdivisi') {
                                        me.down('#chartdivisi').surface.removeAll();
                                        window.loadTask.delay(150, function () {
                                            Ext.getStore('statistikdivisi').proxy.extraParams.satkerid = b.get('id');
                                            Ext.getStore('statistikdivisi').load({
                                                callback: function () {
                                                    me.down('#chartdivisi').redraw();
                                                },
                                            });
                                            Ext.getStore('storereportdivisi').proxy.extraParams.satkerid = b.get('id');
                                            Ext.getStore('storereportdivisi').load();
                                        });
                                    } else if (itemTab == 'panelreportstatuspegawai') {
                                        Ext.getStore('statistikstatuspegawai').proxy.extraParams.satkerid = b.get('id');
                                        Ext.getStore('statistikstatuspegawai').load();
                                        Ext.getStore('storereportstatuspeg').proxy.extraParams.statuspegawaiid = null;
                                        Ext.getStore('storereportstatuspeg').proxy.extraParams.satkerid = b.get('id');
                                        Ext.getStore('storereportstatuspeg').load();
                                    } else if (itemTab == 'panelreportlevel') {
                                        me.down('#chartlevel').surface.removeAll();

                                        window.loadTask.delay(150, function () {
                                            Ext.getStore('statistiklevel').proxy.extraParams.satkerid = b.get('id');
                                            Ext.getStore('statistiklevel').load({
                                                callback: function () {
                                                    me.down('#chartlevel').redraw();
                                                },
                                            });
                                            Ext.getStore('storereportlevel').proxy.extraParams.satkerid = b.get('id');
                                            Ext.getStore('storereportlevel').proxy.extraParams.levelid = null;
                                            Ext.getStore('storereportlevel').load();
                                        });
                                    } else if (itemTab == 'panelreportremindcontract') {
                                        Ext.getStore('storeremindcontract').proxy.extraParams.satkerid = b.get('id');
                                        Ext.getStore('storeremindcontract').load();
                                    } else if (itemTab == 'panelreportsdm') {
                                        Ext.getStore('storereportsdm').proxy.extraParams.satkerid = b.get('id');
                                        Ext.getStore('storereportsdm').load();
                                        Ext.getStore('storereportsdmbyid').proxy.extraParams.satkerid = b.get('id');
                                        Ext.getStore('storereportsdmbyid').load();
                                    } else if (itemTab == 'panelreportgender') {
                                        Ext.getStore('statistikgenderpegawai').proxy.extraParams.satkerid = b.get('id');
                                        Ext.getStore('statistikgenderpegawai').load();
                                        Ext.getStore('storereportgenderpeg').proxy.extraParams.jeniskelamin = null;
                                        Ext.getStore('storereportgenderpeg').proxy.extraParams.satkerid = b.get('id');
                                        Ext.getStore('storereportgenderpeg').load();
                                    } else if (itemTab == 'panelreportmutasipromosi') {
                                        Ext.getStore('storemutasipromosi').proxy.extraParams.satkerid = b.get('id');
                                        Ext.getStore('storemutasipromosi').load();
                                    } else if (itemTab == 'panelreportusia') {
                                        Ext.getStore('statistikstatususia').proxy.extraParams.satkerid = b.get('id');
                                        Ext.getStore('statistikstatususia').load();
                                        Ext.getStore('storereportusia').proxy.extraParams.satkerid = b.get('id');
                                        Ext.getStore('storereportusia').load();
                                    } else if (itemTab == 'panelreportkader') {
                                        Ext.getStore('storereportkader').proxy.extraParams.satkerid = b.get('id');
                                        Ext.getStore('storereportkader').load();
                                    } else if (itemTab == 'panelreportkadergroup') {
                                        Ext.getStore('storereportkadergroup').proxy.extraParams.satkerid = b.get('id');
                                        Ext.getStore('storereportkadergroup').load();
                                    } else if (itemTab == 'panelreportulangtahun') {
                                        Ext.getStore('storerulangtahun').proxy.extraParams.satkerid = b.get('id');
                                        Ext.getStore('storerulangtahun').load();
                                    }
                                    // else if (itemTab == 'panelreportketstatus') {
                                    //     Ext.getStore('statistikketstatus').proxy.extraParams.satkerid = b.get('id');
                                    //     Ext.getStore('statistikketstatus').proxy.extraParams.bulan = Ext.ComponentQuery.query('#id_ketstatus_bulan')[0].getValue();
                                    //     Ext.getStore('statistikketstatus').proxy.extraParams.tahun = Ext.ComponentQuery.query('#id_ketstatus_thn')[0].getValue();
                                    //     Ext.getStore('statistikketstatus').load();

                                    //     Ext.getStore('storereportketstatus').proxy.extraParams.satkerid = b.get('id');
                                    //     Ext.getStore('storereportketstatus').proxy.extraParams.bulan = Ext.ComponentQuery.query('#id_ketstatus_bulan')[0].getValue();
                                    //     Ext.getStore('storereportketstatus').proxy.extraParams.tahun = Ext.ComponentQuery.query('#id_ketstatus_thn')[0].getValue();
                                    //     Ext.getStore('storereportketstatus').proxy.extraParams.ketstatus = null;
                                    //     Ext.getStore('storereportketstatus').load();
                                    // } else if (itemTab == 'panelreportactingas') {
                                    //     Ext.getStore('storereportactingas').proxy.extraParams.satkerid = b.get('id');
                                    //     Ext.getStore('storereportactingas').load();
                                    //     Ext.getStore('storereportactingasbysatker').proxy.extraParams.satkerid = b.get('id');
                                    //     Ext.getStore('storereportactingasbysatker').load();
                                    // }
                                },
                            },
                        },
                    ],
                },
                {
                    id: 'id_west_treesatker1',
                    region: 'west',
                    title: 'Lokasi',
                    collapsible: true,
                    collapsed: false,
                    layout: 'fit',
                    border: false,
                    resizable: { dynamic: true },
                    items: [
                        {
                            xtype: 'lokasi',
                            width: 200,
                            border: false,
                            listeners: {
                                itemclick: function (a, b, c) {
                                    var itemTab = me.down('tabpanel').getActiveTab().getItemId();
                                    console.log(itemTab);
                                    console.log(b.get('id'));
                                    if (itemTab == 'panelreportlocation') {
                                        Ext.getStore('storereportlocation').proxy.extraParams.lokasiid = b.get('id');
                                        Ext.getStore('storereportlocation').load();
                                        Ext.getStore('storereportlocationbyid').proxy.extraParams.lokasiid = b.get('id');
                                        Ext.getStore('storereportlocationbyid').load();
                                    }
                                },
                            },
                        },
                    ],
                },
                {
                    xtype: 'tabpanel',
                    region: 'center',
                    border: false,
                    loadMask: true,
                    activeItem: 0,
                    layoutOnTabChange: true,
                    defaults: { autoScroll: true },
                    defaults: {
                        layout: 'border',
                        listeners: {
                            activate: function (tab, opt) {
                                var itemTab = tab.itemId;
                                if (itemTab == 'panelreportdivisi') {
                                    Ext.getCmp('id_west_treesatker').show();
                                    Ext.getCmp('id_west_treesatker1').hide();
                                } else if (itemTab == 'panelreportstatuspegawai') {
                                    Ext.getCmp('id_west_treesatker').show();
                                    Ext.getCmp('id_west_treesatker1').hide();
                                } else if (itemTab == 'panelreportsdm') {
                                    Ext.getCmp('id_west_treesatker').show();
                                    Ext.getCmp('id_west_treesatker1').hide();
                                } else if (itemTab == 'panelreportlocation') {
                                    Ext.getCmp('id_west_treesatker').hide();
                                    Ext.getCmp('id_west_treesatker1').show();
                                } else if (itemTab == 'panelreportlevel') {
                                    Ext.getCmp('id_west_treesatker').show();
                                    Ext.getCmp('id_west_treesatker1').hide();
                                } else if (itemTab == 'panelreportketstatus') {
                                    Ext.getCmp('id_west_treesatker').hide();
                                    Ext.getCmp('id_west_treesatker1').hide();
                                } else if (itemTab == 'panelreportremindcontract') {
                                    Ext.getCmp('id_west_treesatker').show();
                                    Ext.getCmp('id_west_treesatker1').hide();
                                } else if (itemTab == 'panelreportgender') {
                                    Ext.getCmp('id_west_treesatker').show();
                                    Ext.getCmp('id_west_treesatker1').hide();
                                } else if (itemTab == 'panelreportmutasipromosi') {
                                    Ext.getCmp('id_west_treesatker').hide();
                                    Ext.getCmp('id_west_treesatker1').hide();
                                } else if (itemTab == 'panelreportusia') {
                                    Ext.getCmp('id_west_treesatker').show();
                                    Ext.getCmp('id_west_treesatker1').hide();
                                } else if (itemTab == 'panelreportkader') {
                                    Ext.getCmp('id_west_treesatker').show();
                                    Ext.getCmp('id_west_treesatker1').hide();
                                } else if (itemTab == 'panelreportkadergroup') {
                                    Ext.getCmp('id_west_treesatker').show();
                                    Ext.getCmp('id_west_treesatker1').hide();
                                } else if (itemTab == 'panelreportulangtahun') {
                                    Ext.getCmp('id_west_treesatker').show();
                                    Ext.getCmp('id_west_treesatker1').hide();
                                }
                            },
                        },
                    },
                    items: [
                        { itemId: 'panelreportdivisi', xtype: 'panelreportdivisi', title: 'By Divisi' },
                        { itemId: 'panelreportstatuspegawai', xtype: 'panelreportstatuspegawai', title: 'By Status Pegawai' },
                        { itemId: 'panelreportsdm', xtype: 'panelreportsdm', title: 'SDM' },
                        { itemId: 'panelreportlocation', xtype: 'panelreportlocation', title: 'By Location' },
                        { itemId: 'panelreportlevel', xtype: 'panelreportlevel', title: 'By Level' },
                        { itemId: 'panelreportketstatus', xtype: 'panelreportketstatus', title: 'New Hired & Turn Over' },
                        { itemId: 'panelreportremindcontract', xtype: 'panelreportremindcontract', title: 'Remind of Contract' },
                        { itemId: 'panelreportgender', xtype: 'panelreportgender', title: 'By Gender' },
                        { itemId: 'panelreportmutasipromosi', xtype: 'panelreportmutasipromosi', title: 'Mutasi & Promosi' },
                        { itemId: 'panelreportusia', id: 'panelreportusia', xtype: 'panelreportusia', title: 'By Usia' },
                        { itemId: 'panelreportkader', id: 'panelreportkader', xtype: 'panelreportkader', title: 'Kader' },
                        { itemId: 'panelreportkadergroup', id: 'panelreportkadergroup', xtype: 'panelreportkadergroup', title: 'Kader Group' },
                        { itemId: 'panelreportulangtahun', id: 'panelreportulangtahun', xtype: 'panelreportulangtahun', title: 'Birthday' },
                        // { itemId: 'panelreportactingas', xtype: 'panelreportactingas', title: 'Acting As' },
                    ],
                },
            ],
            listeners: {
                afterrender: function () {
                    Ext.get('id_submenu').dom.style.display = 'none';
                },
            },
        });
        me.callParent([arguments]);
        if (Settings.userid == '139') {
            Ext.getCmp('panelreportbudget').setVisible(true);
            var tabitems = me.down('tabpanel').getTabBar().items.items;
            for ($x = 0; $x <= tabitems.length; $x++) {
                if ($x <= '12') {
                    tabitems[$x].setVisible(false);
                }
            }
        } else {
            var tabitems = me.down('tabpanel').getTabBar().items.items;
            for ($x = 13; $x <= tabitems.length - 1; $x++) {
                if ($x > 13) {
                    tabitems[$x].setVisible(false);
                }
            }
        }
    },
});
