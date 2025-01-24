Ext.define("SIAP.modules.cuti.GridCuti", {
  extend: "Ext.grid.Panel",
  alternateClassName: "SIAP.gridcuti",
  alias: "widget.gridcuti",
  requires: ["SIAP.components.field.ComboStatusCuti"],
  initComponent: function () {
    var me = this;
    me.addEvents({ beforeload: true });
    var storecuti = Ext.create("Ext.data.Store", {
      storeId: "storecuti",
      autoLoad: true,
      pageSize: Settings.PAGESIZE,
      proxy: {
        type: "ajax",
        url: Settings.SITE_URL + "/cuti/getListCuti",
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
        { name: "id", mapping: "pengajuanid" },
        "pegawaiid",
        "nourut",
        "nik",
        "nama",
        "jabatan",
        "direktorat",
        "divisi",
        "departemen",
        "seksi",
        "subseksi",
        "periode",
        "tglpermohonan",
        "jeniscuti",
        "statusid",
        "status",
        "verifikasinotes",
        "atasan1id",
        "atasan1nama",
        "atasan2id",
        "atasan2nama",
        "pelimpahanid",
        "pelimpahannama",
        "files",
        "filestype",
        "tglmulai",
        "tglselesai",
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
      enableDragDrop: false,
      store: storecuti,
      selModel: { mode: "MULTI" },
      columns: [
        { header: "No", xtype: "rownumberer", width: 30, sortable: true },
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
        { header: "Tgl Pengajuan", dataIndex: "tglpermohonan", width: 120 },
        { header: "Tgl Mulai", dataIndex: "tglmulai", width: 120 },
        { header: "Tgl Selesai", dataIndex: "tglselesai", width: 120 },
        { header: 'Jenis Cuti', dataIndex: 'jeniscuti', width: 120 },
        {
          header: "Status",
          dataIndex: "status",
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
            } else if (record.data.statusid != "1") {
              if (record.data.statusid == null) {
                return (
                  '<a onclick=statusKosong("' +
                  record.data.id +
                  '") style="cursor:pointer;text-decoration:underline;color:red;">' +
                  "Status Kosong" +
                  "</a>"
                );
              } else {
                return (
                  '<a onclick=statusKosong("' +
                  record.data.id +
                  '") style="cursor:pointer;">' +
                  record.data.status +
                  "</a>"
                );
              }
            } else {
              return record.data.status;
            }
          },
        },
        { header: "Nama Pelimpahan", dataIndex: "pelimpahannama", width: 120 },
        { header: "Verifikator", dataIndex: "atasan1nama", width: 120 },
        { header: "Approval", dataIndex: "atasan2nama", width: 120 },
        {
          header: "",
          width: 30,
          renderer: function (value, meta, record, index) {
            return (
              '<a onclick=detail("' +
              record.data.pegawaiid +
              '",' +
              record.data.nourut +
              "," +
              record.data.periode +
              ',"' +
              record.data.statusid +
              '")  style="cursor:pointer;"><span class="black-icon-brand"><i class="fa fa-external-link" aria-hidden="true"></i></span></a>'
            );
          },
        },
        {
          header: "",
          width: 30,
          renderer: function (value, meta, record, index) {
            if (record.data.statusid == "5" || record.data.statusid == "12") {
              return (
                '<a onclick=setuju("' +
                record.data.pegawaiid +
                '","' +
                record.data.nourut +
                '","' +
                record.data.periode +
                '")  style="cursor:pointer;"><span class="blue-icon-brand"><i class="fa fa-check" aria-hidden="true"></i></span></a>'
              );
            }
          },
        },
      ],
      bbar: Ext.create("Ext.toolbar.Paging", {
        displayInfo: true,
        height: 35,
        store: "storecuti",
      }),
    });

    me.callParent([arguments]);
  },
});

function detail(pegawaiid, nourut, periode, statusid) {
  Ext.History.add(
    "#cuti&detailcuti&" +
    Base64.encode(pegawaiid) +
    "#" +
    Base64.encode(nourut) +
    "#" +
    Base64.encode(periode) +
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
          url: Settings.SITE_URL + "/cuti/approveCuti2",
          method: "POST",
          params: {
            pegawaiid: pegawaiid,
            nourut: nourut,
            periode: periode,
          },
          success: function (response) {
            var obj = Ext.decode(response.responseText);
            if (obj.success) {
              window.location.reload();
            }
          },
        });
      }
    },
  });
}

function detailAlasan(id) {
  var store = Ext.getStore("storecuti");
  var record = store.getById(id);

  var win_remark = Ext.create("Ext.window.Window", {
    title: "Detail Alasan",
    closeAction: "destroy",
    modal: true,
    layout: "fit",
    autoScroll: true,
    autoShow: true,
    width: 400,
    items: [
      {
        xtype: "form",
        waitMsgTarget: true,
        bodyPadding: 15,
        layout: "anchor",
        defaultType: "textfield",
        region: "center",
        autoScroll: true,
        defaults: { labelWidth: 60, anchor: "100%" },
        items: [
          { xtype: "displayfield", value: record.get("verifikasinotes") },
        ],
        buttons: [
          {
            text: "Cancel",
            handler: function () {
              win_remark.destroy();
            },
          },
        ],
      },
    ],
  });
}

function statusKosong(id) {
  var store = Ext.getStore("storecuti");
  var record = store.getById(id);

  var win = Ext.create("Ext.window.Window", {
    title: "Status Kosong",
    closeAction: "destroy",
    modal: true,
    layout: "fit",
    autoScroll: true,
    autoShow: true,
    width: 400,
    items: [
      {
        buttons: [
          {
            text: "Simpan",
            handler: function () {
              win
                .down("form")
                .getForm()
                .submit({
                  waitTitle: "Menyimpan...",
                  waitMsg: "Sedang menyimpan data, mohon tunggu...",
                  url: Settings.SITE_URL + "/cuti/updStatusCutiKosong",
                  method: "POST",
                  success: function (form, action) {
                    var obj = Ext.decode(action.response.responseText);
                    win.destroy();
                    window.location.reload();
                  },
                  failure: function (form, action) { },
                });
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
              { xtype: "hidden", name: "pengajuanid", value: record.get("id") },
              {
                xtype: "combostatuscuti",
                fieldLabel: "Status Cuti",
                name: "statusid",
              },
            ],
          },
        ],
      },
    ],
  });
}
