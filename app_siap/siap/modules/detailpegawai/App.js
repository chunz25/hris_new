Ext.define("SIAP.modules.detailpegawai.App", {
  extend: "Ext.panel.Panel",
  alternateClassName: "SIAP.detailpegawai",
  alias: "widget.detailpegawai",
  requires: [
    "SIAP.components.field.ComboAgama",
    "SIAP.components.field.ComboStatusPegawai",
    "SIAP.components.field.FieldJabatan",
  ],
  initComponent: function () {
    var me = this;

    var tplFoto = new Ext.XTemplate(
      '<tpl for=".">',
      '<img src="{url}" width="150" height="171" />',
      "</tpl>"
    );

    Ext.apply(me, {
      layout: "border",
      items: [
        {
          region: "west",
          title: "Daftar Unit",
          collapsible: true,
          collapsed: false,
          layout: "fit",
          border: false,
          resizable: { dynamic: true },
          items: [
            {
              xtype: "treepanel",
              width: 200,
              border: false,
              rootVisible: false,
              store: Ext.create("Ext.data.TreeStore", {
                root: {
                  expanded: true,
                  children: me.dataTreeDRH(),
                },
              }),
              listeners: {
                itemclick: function (a, b, c) { },
              },
            },
          ],
        },
        {
          id: "id_paneldetaildrh",
          xtype: "panel",
          region: "center",
          layout: "fit",
          split: true,
          frame: true,
          loader: Ext.create("Ext.Component", {
            loader: {},
            renderTo: Ext.getBody(),
          }),
          defaults: {
            split: true,
          },
          listeners: {
            afterrender: function (t, o) {
              Ext.History.add("#detailpegawai&PanelIdentitas");
            },
          },
        },
      ],
      listeners: {
        afterrender: function () {
          dispatch = function (token) {
            var tokens = token.split("&");
            var m = tokens[0];
            var act = tokens[1];
            var params = tokens[2];

            var require = "SIAP.modules.detailpegawai." + file;
            Ext.require(require, function () {
              Ext.getCmp("id_paneldetaildrh").removeAll();
              Ext.getCmp("id_paneldetaildrh").add({
                xtype: widget,
                layout: "fit",
              });
              Ext.getCmp("id_paneldetaildrh").doLayout();
            });
            Ext.getCmp("id_paneldetaildrh").doLayout();
          };
          Ext.History.init(function () {
            var hashTag = document.location.hash;
            var tag = hashTag.replace("#", "");
            dispatch(tag);
          });
          Ext.History.on("change", dispatch);
        },
      },
    });
    me.callParent([arguments]);
  },
  dataTreeDRH: function () {
    var me = this;
    var data = [
      { id: "1", text: "A. Detail Pegawai", leaf: true, link: "", files: "" },
      { id: "2", text: "B. History Pegawai", leaf: true, link: "", files: "" },
      { id: "3", text: "C. Data Keluarga", leaf: true, link: "", files: "" },
      { id: "4", text: "D. Pendidikan Formal", leaf: true, link: "", files: "", },
      { id: "5", text: "E. Pengalaman Kerja", leaf: true, link: "", files: "" },
      { id: "6", text: "F. Rekening Bank", leaf: true, link: "", files: "" },
      { id: "7", text: "G. Training/Kursus", leaf: true, link: "", files: "" },
      { id: "8", text: "H. Bahasa", leaf: true, link: "", files: "" },
      { id: "9", text: "I. Alergi dan Riwayat", leaf: true, link: "", files: "", },
      { id: "10", text: "J. Penyakit", leaf: true, link: "", files: "" },
      { id: "11", text: "K. Kegiatan AGP", leaf: true, link: "", files: "" },
      { id: "12", text: "L. Mutasi & Promosi", leaf: true, link: "", files: "", },
      { id: "13", text: "M. Indiplisiner", leaf: true, link: "", files: "" },
      { id: "14", text: "N. Performance Appraisal", leaf: true, link: "", files: "", },
      { id: "15", text: "O. Acting As", leaf: true, link: "", files: "" },
      { id: "16", text: "P. Keahlian Khusus/Catatan Tambahan", leaf: true, link: "", files: "", },
    ];
    return data;
  },
});
