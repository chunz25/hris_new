Ext.define("SIAP.modules.reportcuti.App", {
  extend: "Ext.panel.Panel",
  alternateClassName: "SIAP.reportcuti",
  alias: "widget.reportcuti",
  requires: [
    "SIAP.components.tree.UnitKerja",
    "SIAP.modules.reportcuti.PanelReportCuti",
    "SIAP.modules.reportcuti.PanelReportSisaCuti",
    "SIAP.modules.reportcuti.PanelReportBatalCutiBersama",
  ],
  initComponent: function () {
    var me = this;
    window.loadTask = new Ext.util.DelayedTask();
    Ext.apply(me, {
      layout: "border",
      items: [
        {
          id: "id_west_treesatker",
          region: "west",
          title: "Daftar Unit Kerja",
          collapsible: true,
          collapsed: false,
          layout: "fit",
          border: false,
          resizable: { dynamic: true },
          items: [
            {
              xtype: "unitkerja",
              width: 200,
              border: false,
              listeners: {
                itemclick: function (a, b, c) {
                  var itemTab = me.down("tabpanel").getActiveTab().getItemId();
                  console.log(itemTab);
                  console.log(b.get("id"));
                  if (itemTab == "panelreportcuti") {
                    var tglmulai = Ext.Date.format(
                      me.down("#id_tglmulai").getValue(),
                      "d/m/Y"
                    );
                    var tglselesai = Ext.Date.format(
                      me.down("#id_tglselesai").getValue(),
                      "d/m/Y"
                    );

                    Ext.getStore("storereportcuti").proxy.extraParams.tglmulai =
                      tglmulai;
                    Ext.getStore(
                      "storereportcuti"
                    ).proxy.extraParams.tglselesai = tglselesai;
                    Ext.getStore("storereportcuti").proxy.extraParams.satkerid =
                      b.get("id");
                    Ext.getStore("storereportcuti").load();
                  } else if (itemTab == "panelreportsisacuti") {
                    Ext.getStore(
                      "storereportsisacuti"
                    ).proxy.extraParams.statuspegawaiid = null;
                    Ext.getStore(
                      "storereportsisacuti"
                    ).proxy.extraParams.satkerid = b.get("id");
                    Ext.getStore("storereportsisacuti").load();
                  } else if (itemTab == "panelreportbatalcutibersama") {
                    Ext.getStore(
                      "storereportbatalcutibersama"
                    ).proxy.extraParams.statuspegawaiid = null;
                    Ext.getStore(
                      "storereportbatalcutibersama"
                    ).proxy.extraParams.satkerid = b.get("id");
                    Ext.getStore("storereportbatalcutibersama").load();
                  }
                },
              },
            },
          ],
        },
        {
          xtype: "tabpanel",
          region: "center",
          border: false,
          loadMask: true,
          activeItem: 0,
          layoutOnTabChange: true,
          defaults: { autoScroll: true },
          defaults: {
            layout: "border",
            listeners: {
              activate: function (tab, opt) {
                var itemTab = tab.itemId;
                if (itemTab == "panelreportcuti") {
                  Ext.getCmp("id_west_treesatker").show();
                } else if (itemTab == "panelreportsisacuti") {
                  Ext.getCmp("id_west_treesatker").show();
                } else if (itemTab == "panelreportbatalcutibersama") {
                  Ext.getCmp("id_west_treesatker").show();
                }
              },
            },
          },
          items: [
            {
              itemId: "panelreportcuti",
              xtype: "panelreportcuti",
              title: "Cuti Karyawan",
            },
            {
              itemId: "panelreportsisacuti",
              xtype: "panelreportsisacuti",
              title: "Sisa Cuti Karyawan",
            },
            {
              itemId: "panelreportbatalcutibersama",
              xtype: "panelreportbatalcutibersama",
              title: "Cuti Bersama",
            },
          ],
        },
      ],
      listeners: {
        afterrender: function () {
          Ext.get("id_submenu").dom.style.display = "none";
        },
      },
    });
    me.callParent([arguments]);
  },
});
