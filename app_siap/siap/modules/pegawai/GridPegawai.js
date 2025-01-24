Ext.define("SIAP.modules.pegawai.GridPegawai", {
  extend: "Ext.grid.Panel",
  alternateClassName: "SIAP.gridpegawai",
  alias: "widget.gridpegawai",
  initComponent: function () {
    var me = this;
    me.addEvents({ beforeload: true });
    var storepegawai = Ext.create("Ext.data.Store", {
      storeId: "storepegawai",
      autoLoad: true,
      pageSize: Settings.PAGESIZE,
      proxy: {
        type: "ajax",
        url: Settings.SITE_URL + "/pegawai/getListPegawai",
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
        "nama",
        "nik",
        "satkerid",
        "direktorat",
        "divisi",
        "departemen",
        "seksi",
        "subseksi",
        "jabatanid",
        "jabatan",
        "levelid",
        "level",
        "statuspegawaiid",
        "statuspegawai",
        "jeniskelamin",
        "email",
        "emailkantor",
        "telp",
        "tglmulai",
        "tglselesai",
        "keterangan",
        "lokasikerja",
        "tglakhirkontrak",
        "lokasi",
        "gol",
        "masakerjaseluruhth",
        "masakerjaseluruhbl",
      ],
      listeners: {
        beforeload: function (store) {
          me.fireEvent("beforeload", store);
        },
      },
    });
    Ext.apply(me, {
      layout: "fit",
      autoScroll: true,
      frame: false,
      border: true,
      loadMask: true,
      stripeRows: true,
      enableDragDrop: true,
      store: storepegawai,
      selModel: { mode: "MULTI" },
      columns: [
        { header: "No", xtype: "rownumberer", width: 30 },
        { header: "NIK", dataIndex: "nik", width: 80 },
        { header: "Nama", dataIndex: "nama", width: 150 },
        { header: "Grade", dataIndex: "gol", width: 60 },
        { header: "Level", dataIndex: "level", width: 120 },
        { header: "Jabatan", dataIndex: "jabatan", width: 180 },
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
        { header: "Lokasi", dataIndex: "lokasi", width: 120 },
        { header: "Status", dataIndex: "statuspegawai", width: 80 },
        { header: "Tgl Masuk", dataIndex: "tglmulai", width: 80 },
        {
          header: "Tgl Habis Kontrak",
          dataIndex: "tglakhirkontrak",
          width: 120,
        },
        {
          text: "Masa Kerja",
          dataIndex: "lama",
          renderer: function (value, p, r) {
            return (
              r.data["masakerjaseluruhth"] +
              " thn " +
              (r.data["masakerjaseluruhbl"] + " bln ")
            );
          },
        },
      ],
      viewConfig: {
        plugins: [
          Ext.create("Ext.grid.plugin.DragDrop", {
            ddGroup: "gridtotree",
            enableDrop: true,
          }),
        ],
      },
      bbar: Ext.create("Ext.toolbar.Paging", {
        displayInfo: true,
        height: 35,
        store: "storepegawai",
      }),
    });

    me.callParent([arguments]);
  },
});
