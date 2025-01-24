Ext.define("SIAP.modules.pegawai.App", {
  extend: "Ext.panel.Panel",
  alternateClassName: "SIAP.pegawai",
  alias: "widget.pegawai",
  requires: [
    "SIAP.components.tree.UnitKerja",
    "SIAP.modules.pegawai.GridPegawai",
    "SIAP.components.field.ComboStatusPegawai",
  ],
  initComponent: function () {
    var me = this;
    Ext.apply(me, {
      layout: "border",
      items: [
        {
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
                  me
                    .down("#id_gridpegawai")
                    .getStore().proxy.extraParams.satkerid = b.get("id");
                  me.down("#id_gridpegawai").getStore().loadPage(1);
                },
              },
              viewConfig: {
                plugins: {
                  ptype: "treeviewdragdrop",
                  dropGroup: "gridtotree",
                  enableDrag: false,
                },
                listeners: {
                  beforedrop: function (node, data, overModel, dropPos, opts) {
                    this.droppedRecords = data.records;
                    data.records = [];
                  },
                  drop: function (node, data, overModel, dropPos, opts) {
                    var dataFrom = this.droppedRecords;
                    var pegawaiid = [];
                    Ext.iterate(dataFrom, function (record) {
                      var temp = {
                        pegawaiid: record.get("pegawaiid"),
                        statuspegawaiid: record.get("statuspegawaiid"),
                        tglmulai: record.get("tglmulai"),
                        jabatanid: record.get("jabatanid"),
                        levelid: record.get("levelid"),
                        satkerid: record.get("satkerid"),
                        lokasikerjaid: record.get("lokasikerja"),
                      };
                      pegawaiid.push(temp);
                    });

                    Ext.Msg.show({
                      title: "Konfirmasi",
                      msg: "Are you sure ?",
                      buttons: Ext.Msg.YESNO,
                      icon: Ext.Msg.QUESTION,
                      fn: function (btn) {
                        if (btn == "yes") {
                          Ext.Ajax.request({
                            url: Settings.SITE_URL + "/pegawai/mutasiPegawai",
                            method: "POST",
                            params: {
                              pegawaiid: Ext.encode(pegawaiid),
                              satkertarget: overModel.data.id,
                            },
                            success: function (response) {
                              var obj = Ext.decode(response.responseText);
                              if (obj.success) {
                                me.down("#id_gridpegawai")
                                  .getStore()
                                  .loadPage(1);
                              }
                            },
                          });
                        }
                      },
                    });

                    this.droppedRecords = undefined;
                  },
                },
              },
            },
          ],
        },
        {
          itemId: "id_gridpegawai",
          xtype: "gridpegawai",
          region: "center",
          frame: true,
          tbar: [
            {
              glyph: "xf002@FontAwesome",
              text: "Cari Pegawai",
              handler: function () {
                me.winSearchPegawai();
              },
            },
            "->",
            // {
            //   glyph: "xf196@FontAwesome",
            //   text: "Tambah",
            //   handler: function () {
            //     Ext.History.add("#tambahpegawai");
            //   },
            // },
            {
              glyph: "xf02f@FontAwesome",
              text: "Cetak",
              handler: function () {
                var m = me.down("#id_gridpegawai").getStore().proxy.extraParams;
                window.open(
                  Settings.SITE_URL +
                  "/pegawai/cetak/daftar?" +
                  objectParametize(m)
                );
              },
            },
          ],
          listeners: {
            itemdblclick: function (g, record, item, index, e, opt) {
              Ext.History.add(
                "#detailpegawai&PanelIdentitas&" + record.get("pegawaiid")
              );
            },
          },
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
  winSearchPegawai: function () {
    var me = this;

    var win = Ext.create("Ext.window.Window", {
      title: "Cari Pegawai",
      width: 400,
      closeAction: "destroy",
      modal: true,
      layout: "fit",
      autoScroll: false,
      autoShow: true,
      buttons: [
        {
          text: "Cari",
          handler: function () {
            var form = win.down("form").getForm();
            var nik = form.findField("nik").getValue();
            var nama = form.findField("nama").getValue();
            var tglmulai = Ext.isEmpty(form.findField("tglmulai").getValue())
              ? null
              : Ext.Date.format(form.findField("tglmulai").getValue(), "d/m/Y");
            var tglselesai = Ext.isEmpty(
              form.findField("tglselesai").getValue()
            )
              ? null
              : Ext.Date.format(
                form.findField("tglselesai").getValue(),
                "d/m/Y"
              );
            var statuspegawai = form.findField("statuspegawai").getValue();
            var jeniskelamin = form.findField("jeniskelamin").getValue();
            var tree = me
              .down("#id_unitkerja")
              .getSelectionModel()
              .getSelection();
            treeid = null;
            if (tree.length > 0) {
              treeid = tree[0].get("id");
            }

            me.down("#id_gridpegawai").getStore().proxy.extraParams.satkerid =
              treeid;
            me.down("#id_gridpegawai").getStore().proxy.extraParams.nik = nik;
            me.down("#id_gridpegawai").getStore().proxy.extraParams.nama = nama;
            me.down("#id_gridpegawai").getStore().proxy.extraParams.tglmulai =
              tglmulai;
            me.down("#id_gridpegawai").getStore().proxy.extraParams.tglselesai =
              tglselesai;
            me
              .down("#id_gridpegawai")
              .getStore().proxy.extraParams.statuspegawai = statuspegawai;
            me
              .down("#id_gridpegawai")
              .getStore().proxy.extraParams.jeniskelamin = jeniskelamin;
            me.down("#id_gridpegawai").getStore().loadPage(1);
            win.destroy();
          },
        },
        {
          text: "Batal",
          handler: function () {
            win.destroy();
          },
        },
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
            anchor: "100%",
          },
          items: [
            { fieldLabel: "NIK", name: "nik" },
            { fieldLabel: "Nama", name: "nama" },
            {
              xtype: "datefield",
              fieldLabel: "Tgl Mulai",
              format: "d/m/Y",
              name: "tglmulai",
            },
            {
              xtype: "datefield",
              fieldLabel: "Tgl Selesai",
              format: "d/m/Y",
              name: "tglselesai",
            },
            {
              xtype: "combostatuspegawai",
              fieldLabel: "Status Pegawai",
              name: "statuspegawai",
            },
            {
              xtype: "combobox",
              fieldLabel: "Jenis Kelamin",
              name: "jeniskelamin",
              queryMode: "local",
              displayField: "text",
              valueField: "id",
              store: Ext.create("Ext.data.Store", {
                fields: ["id", "text"],
                data: [
                  { id: "L", text: "Pria" },
                  { id: "P", text: "Wanita" },
                ],
              }),
            },
          ],
        },
      ],
    });
  },
});
