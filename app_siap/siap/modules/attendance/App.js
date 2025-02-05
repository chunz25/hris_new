Ext.define("SIAP.modules.attendance.App", {
  extend: "Ext.panel.Panel",
  alternateClassName: "SIAP.attendance",
  alias: "widget.attendance",
  requires: [
    "SIAP.components.tree.UnitKerja",
    "SIAP.modules.attendance.GridAttendance",
    "SIAP.components.progressbar.WinProgress"
  ],
  initComponent: function() {
    var me = this;
    Ext.apply(me, {
      layout: "border",
      items: [
        {
          itemId: "id_gridattendance",
          xtype: "GridAttendance",
          region: "center",
          frame: true,
          listeners: {
            beforeload: function(store) {
              var tglstr = moment().subtract(7, "days").format("YYYY-MM-DD");
              var tglend = moment().format("YYYY-MM-DD");

              store.proxy.extraParams.tglstr = tglstr;
              store.proxy.extraParams.tglend = tglend;
            }
          },
          tbar: [
            {
              glyph: "xf002@FontAwesome",
              text: "Cari Data",
              handler: function() {
                me.winSearchPegawai();
              }
            },
            "->",
            {
              glyph: "xf02f@FontAwesome",
              text: "Cetak",
              handler: function() {
                var m = me.down("#id_gridattendance").getStore().proxy
                  .extraParams;
                console.log(m);
                window.open(
                  Settings.SITE_URL +
                    "/attendance/cetakdokumen?" +
                    objectParametize(m)
                );
              }
            }
          ]
        }
      ]
    });
    me.callParent([arguments]);
  },

  winSearchPegawai: function() {
    var me = this;

    var win = Ext.create("Ext.window.Window", {
      title: "Pilih Tanggal Absensi",
      width: 400,
      closeAction: "destroy",
      modal: true,
      layout: "fit",
      autoScroll: true,
      autoShow: true,
      buttons: [
        {
          text: "Pilih Data",
          handler: function() {
            var form = win.down("form").getForm();
            var nik = form.findField("nik").getValue();
            var tglmulai = Ext.isEmpty(form.findField("tglmulai").getValue())
              ? null
              : Ext.Date.format(form.findField("tglmulai").getValue(), "Y-m-d");
            var tglselesai = Ext.isEmpty(
              form.findField("tglselesai").getValue()
            )
              ? null
              : Ext.Date.format(
                  form.findField("tglselesai").getValue(),
                  "Y-m-d"
                );

            me
              .down("#id_gridattendance")
              .getStore().proxy.extraParams.nik = nik;
            me
              .down("#id_gridattendance")
              .getStore().proxy.extraParams.tglmulai = tglmulai;
            me
              .down("#id_gridattendance")
              .getStore().proxy.extraParams.tglselesai = tglselesai;
            me.down("#id_gridattendance").getStore().loadPage(1);
            win.destroy();
          }
        },
        {
          text: "Batal",
          handler: function() {
            win.destroy();
          }
        }
      ],
      items: [
        {
          xtype: "form",
          waitMsgTarget: true,
          bodyPadding: 15,
          layout: "anchor",
          defaultType: "textfield",
          region: "center",
          autoScroll: true,
          defaults: {
            labelWidth: 100,
            anchor: "100%"
          },
          items: [
            { fieldLabel: "NIK", name: "nik" },
            {
              xtype: "datefield",
              fieldLabel: "Tanggal Awal",
              format: "d/m/Y",
              name: "tglmulai",
              editable: false,
              value: moment().subtract(7, "days").format("DD/MM/YYYY")
            },
            {
              xtype: "datefield",
              fieldLabel: "Tanggal Akhir",
              format: "d/m/Y",
              name: "tglselesai",
              editable: false,
              value: moment().format("DD/MM/YYYY")
            }
          ]
        }
      ]
    });
  }
});
