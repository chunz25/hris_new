Ext.define("SIAP.modules.reportcuti.PanelReportCuti", {
  extend: "Ext.panel.Panel",
  alternateClassName: "SIAP.panelreportcuti",
  alias: "widget.panelreportcuti",
  initComponent: function () {
    var me = this;

    var storereportcuti = Ext.create("Ext.data.Store", {
      storeId: "storereportcuti",
      autoLoad: true,
      pageSize: Settings.PAGESIZE,
      proxy: {
        type: "ajax",
        url: Settings.SITE_URL + "/reportcuti/getListCutiPegawai",
        actionMethods: {
          create: "POST",
          read: "POST",
        },
        reader: {
          type: "json",
          root: "data",
          totalProperty: "count",
        },
      },
      fields: [
        "pegawaiid",
        "namadepan",
        "nik",
        "satkerid",
        "direktorat",
        "divisi",
        "departemen",
        "seksi",
        "subseksi",
        "tglpermohonan",
        "tglmulai",
        "tglselesai",
        "lama",
        "status",
        "alasan",
        "jeniscuti",
        "statusid",
        "alasancuti",
      ],
      listeners: {
        beforeload: function (store) {
          var tglmulai = Ext.Date.format(
            me.down("#id_tglmulai").getValue(),
            "d/m/Y"
          );
          var tglselesai = Ext.Date.format(
            me.down("#id_tglselesai").getValue(),
            "d/m/Y"
          );

          Ext.getStore("storereportcuti").proxy.extraParams.tglmulai = tglmulai;
          Ext.getStore("storereportcuti").proxy.extraParams.tglselesai =
            tglselesai;
          me.fireEvent("beforeload", store);
        },
      },
    });

    Ext.apply(me, {
      layout: "border",
      items: [
        {
          xtype: "grid",
          region: "center",
          layout: "fit",
          autoScroll: true,
          frame: false,
          border: true,
          loadMask: true,
          stripeRows: true,
          store: storereportcuti,
          columns: [
            { header: "No", xtype: "rownumberer", width: 30 },
            { header: "NIK", dataIndex: "nik", width: 80 },
            { header: "Nama", dataIndex: "namadepan", width: 150 },
            {
              header: "Unit",
              align: "left",
              columns: [
                { header: "Direktorat", dataIndex: "direktorat", width: 120 },
                { header: "Divisi", dataIndex: "divisi", width: 120 },
                { header: "Departemen", dataIndex: "departemen", width: 120 },
                { header: "Seksi", dataIndex: "seksi", width: 120 },
                { header: "Sub Seksi", dataIndex: "subseksi", width: 120 },
              ],
            },
            { header: "Jenis Cuti", dataIndex: "jeniscuti", width: 100 },
            { header: "Tgl Pengajuan", dataIndex: "tglpermohonan", width: 120 },
            { header: "Tgl Mulai", dataIndex: "tglmulai", width: 120 },
            { header: "Tgl Selesai", dataIndex: "tglselesai", width: 120 },
            { header: "Lama", dataIndex: "lama", width: 50 },
            {
              header: "Status",
              dataIndex: "statusid",
              width: 120,
              renderer: function (value, meta, record, index) {
                if (
                  record.data.statusid == "4" ||
                  record.data.statusid == "6" ||
                  record.data.statusid == "8"
                ) {
                  return (
                    '<a onclick=detailAlasan("' +
                    record.data.id +
                    '") style="cursor:pointer;text-decoration:underline;">' +
                    record.data.status +
                    "</a>"
                  );
                } else if (record.data.statusid == null) {
                  return (
                    '<a onclick=statusKosong("' +
                    record.data.id +
                    '") style="cursor:pointer;text-decoration:underline;color:red;">' +
                    "Status Kosong" +
                    "</a>"
                  );
                } else {
                  return record.data.status;
                }
              },
            },
            { header: "Keterangan", dataIndex: "alasancuti", width: 120 },
          ],
          bbar: Ext.create("Ext.toolbar.Paging", {
            displayInfo: true,
            height: 35,
            store: "storereportcuti",
          }),
        },
      ],
      tbar: [
        {
          itemId: "id_tglmulai",
          xtype: "datefield",
          fieldLabel: "Periode",
          format: "d/m/Y",
          name: "tglmulai",
          value: moment().startOf("month").format("DD/MM/YYYY"),
          emptyText: "Tgl Awal",
          labelWidth: 40,
          style: "margin-left:10px;",
        },
        {
          itemId: "id_tglselesai",
          xtype: "datefield",
          fieldLabel: "s/d",
          format: "d/m/Y",
          name: "tglselesai",
          value: moment().endOf("month").format("DD/MM/YYYY"),
          emptyText: "Tgl Akhir",
          labelWidth: 20,
          style: "margin-left:5px;",
        },
        "-",
        {
          glyph: "xf002@FontAwesome",
          handler: function () {
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
            Ext.getStore("storereportcuti").proxy.extraParams.tglselesai =
              tglselesai;
            Ext.getStore("storereportcuti").load();
          },
        },
        /*{glyph:'xf002@FontAwesome', text: 'Cari Pegawai',
          handler: function(){
            me.winSearchPegawai();
          }
        },*/
        "->",
        {
          glyph: "xf02f@FontAwesome",
          text: "Cetak",
          handler: function () {
            var m = me.down("grid").getStore().proxy.extraParams;
            window.open(
              Settings.SITE_URL +
              "/reportcuti/cetakdokumen/cutikaryawan?" +
              objectParametize(m)
            );
          },
        },
      ],
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
      autoScroll: true,
      autoShow: true,
      buttons: [
        {
          text: "Cari",
          handler: function () {
            var form = win.down("form").getForm();
            var nama = form.findField("nama").getValue();

            me.down("grid").getStore().proxy.extraParams.nama = nama;
            me.down("grid").getStore().loadPage(1);
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
          items: [{ fieldLabel: "Nama", name: "nama" }],
        },
      ],
    });
  },
});
