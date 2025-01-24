Ext.define("SIAP.modules.kehadiran.GridKehadiran", {
  extend: "Ext.grid.Panel",
  alternateClassName: "SIAP.gridkehadiran",
  alias: "widget.gridkehadiran",
  initComponent: function () {
    var me = this;
    me.addEvents({ beforeload: true });
    var storekehadiran = Ext.create("Ext.data.Store", {
      storeId: "storekehadiran",
      autoLoad: true,
      pageSize: Settings.PAGESIZE,
      proxy: {
        type: "ajax",
        url: Settings.SITE_URL + "/kehadiran/getListKehadiran",
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
        "pengajuanid",
        "pegawaiid",
        "nourut",
        "nik",
        "nama",
        "direktorat",
        "divisi",
        "departemen",
        "seksi",
        "subseksi",
        "tglpermohonan",
        "tglmulai",
        "jam",
        "jenis",
        "status",
        "statusid",
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
      store: storekehadiran,
      columns: [
        { header: "No", xtype: "rownumberer", width: 30 },
        { header: "NIK", dataIndex: "nik", width: 80 },
        { header: "Nama", dataIndex: "nama", width: 150 },
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
        { header: "Status", dataIndex: "status", width: 120 },
        { header: "Jenis", dataIndex: "jenis", width: 120 },
        { header: "Tgl Mulai", dataIndex: "tglmulai", width: 80 },
        { header: "Jam", dataIndex: "jam", width: 50 },
        {
          header: "",
          width: 30,
          renderer: function (value, meta, record, index) {
            return (
              '<a onclick=detailkehadiran("' +
              record.data.pegawaiid +
              '","' +
              record.data.nourut +
              '","' +
              record.data.statusid +
              '")  style="cursor:pointer;"><span class="black-icon-brand"><i class="fa fa-external-link" aria-hidden="true"></i></span></a>'
            );
          },
        },
        {
          header: "",
          width: 30,
          renderer: function (value, meta, record, index) {
            if (record.data.statusid == "2") {
              return (
                '<a onclick=setuju("' +
                record.data.pegawaiid +
                '","' +
                record.data.nourut +
                '")  style="cursor:pointer;"><span class="blue-icon-brand"><i class="fa fa-check" aria-hidden="true"></i></span></a>'
              );
            }
          },
        },
      ],
      bbar: Ext.create("Ext.toolbar.Paging", {
        displayInfo: true,
        height: 35,
        store: "storekehadiran",
      }),
    });
    me.callParent([arguments]);
  },
});

function detailkehadiran(pegawaiid, nourut, statusid) {
  Ext.History.add(
    "#kehadiran&detailkehadiran&" +
    Base64.encode(pegawaiid) +
    "#" +
    Base64.encode(nourut) +
    "#" +
    Base64.encode(statusid)
  );
}
function setuju(pegawaiid, nourut, periode) {
  Ext.Msg.show({
    title: "Informasi",
    msg: "Apakah anda yakin akan menyetujui cuti ini?",
    buttons: Ext.Msg.YESNO,
    icon: Ext.Msg.QUESTION,
    fn: function (btn) {
      if (btn == "yes") {
        Ext.Ajax.request({
          url: Settings.SITE_URL + "/kehadiran/approveKehadiran2",
          method: "POST",
          params: {
            pegawaiid: pegawaiid,
            nourut: nourut,
          },
          success: function (response) {
            var obj = Ext.decode(response.responseText);
            if (obj.success) {
              window.location.reload();
              // me.down('#id_gridkehadiran').getStore().loadPage(1);
            }
          },
        });
      }
    },
  });
}
