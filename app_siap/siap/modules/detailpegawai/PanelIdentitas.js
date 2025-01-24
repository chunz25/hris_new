Ext.define("SIAP.modules.detailpegawai.PanelIdentitas", {
  extend: "Ext.form.Panel",
  alternateClassName: "SIAP.PanelIdentitas",
  alias: "widget.panelidentitas",
  requires: [
    "SIAP.components.field.ComboAgama",
    "SIAP.components.field.ComboGolDarah",
    "SIAP.components.field.ComboShio",
    "SIAP.components.field.ComboStatusPegawai",
    "SIAP.components.field.FieldJabatan",
    "SIAP.components.field.FieldSatker",
    "SIAP.components.field.ComboStatusNikah",
    "SIAP.modules.detailpegawai.TreeDRH",
  ],
  save: function () {
    this.getForm().submit({
      url: Settings.SITE_URL + "/pegawai/ubahPegawai",
      scope: this,
      waitTitle: "Menyimpan...",
      waitMsg: "Sedang menyimpan data, mohon tunggu...",
      success: function (form, action) {
        var data = Ext.decode(action.response.responseText);
        Ext.Msg.alert("Success: ", data.msg, function () {
          // This function will be called when the user clicks "OK"
          window.location.reload(true);
        });
      },
      failure: function (form, action) {
        var data = Ext.decode(action.response.responseText);
        switch (action.failureType) {
          case Ext.form.action.Action.CLIENT_INVALID:
            Ext.Msg.alert("Failure", "Harap isi semua data");
            break;
          case Ext.form.action.Action.CONNECT_FAILURE:
            Ext.Msg.alert("Failure", "Terjadi kesalahan");
            break;
          case Ext.form.action.Action.SERVER_INVALID:
            Ext.Msg.alert("Failure", data.msg);
        }
      },
    });
  },
  initComponent: function () {
    var me = this;
    var tplFoto = new Ext.XTemplate(
      '<tpl for=".">',
      '<img id="id_foto_peg" src="{url}" width="150" height="171" />',
      "</tpl>"
    );

    Ext.apply(me, {
      layout: "border",
      listeners: {
        afterrender: function (p) {
          p.getForm().load({
            url: Settings.SITE_URL + "/pegawai/getPegawaiByID",
            method: "POST",
            params: { pegawaiid: me.params },
            success: function (action, form) {
              var obj = Ext.decode(form.response.responseText);
              if (obj.success) {
                var agama = obj.data.agama.toString();

                me.down("#id_agama").reload();
                me.down("#id_agama").setValue(agama);
                Ext.getDom("id_foto_peg").src = obj.data.fotonew;
              }
            },
          });
        },
      },
      items: [
        {
          region: "west",
          title: "Daftar Riwayat Hidup",
          collapsible: true,
          collapsed: false,
          layout: "fit",
          border: false,
          split: true,
          resizable: { dynamic: true },
          items: [{ xtype: "treedrh", params: me.params }],
        },
        {
          xtype: "panel",
          region: "center",
          autoScroll: true,
          bodyPadding: 10,
          tbar: [
            {
              text: "Kembali",
              glyph: "xf060@FontAwesome",
              handler: function () {
                Ext.History.add("#pegawai");
              },
            },
            "->",
            {
              text: "Simpan",
              glyph: "xf044@FontAwesome",
              handler: function () {
                me.save();
              },
            },
            {
              glyph: "xf02f@FontAwesome",
              text: "Cetak Word",
              handler: function () {
                var m = { pegawaiid: me.params };
                window.open(
                  Settings.SITE_URL +
                  "/pegawai/cetak/identitasword?" +
                  objectParametize(m)
                );
              },
            },
            {
              glyph: "xf02f@FontAwesome",
              text: "Cetak Excel",
              handler: function () {
                var m = { pegawaiid: me.params };
                window.open(
                  Settings.SITE_URL +
                  "/pegawai/cetak/cetakexcel?" +
                  objectParametize(m)
                );
              },
            },
          ],
          items: [
            { xtype: "hidden", name: "pegawaiid" },
            { xtype: "hidden", name: "fotoname" },
            { xtype: "hidden", name: "satkerid" },
            { xtype: "hidden", name: "idsatker" },
            { xtype: "hidden", name: "shioid" },
            { xtype: "hidden", name: "goldarahid" },
            { xtype: 'hidden', name: 'pkktp' },
            { xtype: 'hidden', name: 'pk' },
            { xtype: 'hidden', name: 'statusnikahid' },
            { xtype: 'hidden', name: 'statuspegawaiid' },
            {
              xtype: "panel",
              border: false,
              width: 150,
              height: 200,
              html: tplFoto.applyTemplate({
                url: Settings.no_image_person_url,
              }),
              tbar: [
                "File Max : 1 MB",
                "->",
                {
                  xtype: "fileuploadfield",
                  id: "form_file",
                  name: "foto",
                  buttonOnly: true,
                  buttonConfig: {
                    text: "Upload",
                    glyph: "xf093@FontAwesome",
                  },
                },
              ],
            },
            {
              layout: "column",
              baseCls: "x-plain",
              border: false,
              items: [
                {
                  xtype: "panel",
                  columnWidth: 0.5,
                  bodyPadding: 10,
                  layout: "form",
                  defaultType: "displayfield",
                  baseCls: "x-plain",
                  border: false,
                  defaults: {
                    labelWidth: 170,
                  },
                  items: [
                    {
                      xtype: "textfield",
                      fieldLabel: "NIK",
                      name: "nik",
                      anchor: "95%",
                      readOnly: false,
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Nama Depan",
                      name: "namadepan",
                      anchor: "95%",
                      readOnly: false,
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Nama Tengah",
                      name: "namabelakang",
                      anchor: "95%",
                      readOnly: false,
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Nama Belakang",
                      name: "namakeluarga",
                      anchor: "95%",
                      readOnly: false,
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Tempat Lahir",
                      name: "tempatlahir",
                      anchor: "95%",
                    },
                    {
                      xtype: "datefield",
                      fieldLabel: "Tgl Lahir",
                      name: "tgllahir",
                      format: "d/m/Y",
                      anchor: "95%",
                      editable: false
                    },
                    {
                      xtype: "combobox",
                      fieldLabel: "Jenis Kelamin",
                      name: "jeniskelamin",
                      anchor: "95%",
                      editable: false,
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
                    {
                      xtype: "textareafield",
                      fieldLabel: "Alamat KTP",
                      name: "alamatktp",
                      grow: true,
                      anchor: "95%",
                    },
                    {
                      xtype: "numberfield",
                      fieldLabel: "Kode Pos KTP",
                      name: "kodeposktp",
                      anchor: "95%",
                      hideTrigger: true,
                      keyNavEnabled: false,
                      mouseWheelEnabled: false,
                      maxLength: 5,
                      enforceMaxLength: true,
                      listeners: {
                        specialkey: function (field, event) {
                          if (event.getKey() === Ext.EventObject.ENTER) { // Check if the Enter key is pressed
                            var kodepos = field.getValue();
                            if (kodepos && kodepos.toString().length === 5) { // Ensure kodepos is 5 digits
                              Ext.Ajax.request({
                                url: Settings.SITE_URL + "/pegawai/get_data_by_kodepos", // Adjust the URL as needed
                                method: "POST",
                                params: {
                                  kodepos: kodepos
                                },
                                success: function (response) {
                                  var data = Ext.decode(response.responseText);
                                  if (data.success) {
                                    // Assuming the response contains kelurahan, kecamatan, and kota
                                    var pkField = field.up('form').getForm().findField('pkktp');
                                    var kelurahanField = field.up('form').getForm().findField('kelurahanktp');
                                    var kecamatanField = field.up('form').getForm().findField('kecamatanktp');
                                    var kotaField = field.up('form').getForm().findField('kotaktp');

                                    pkField.setValue(data.data.id);
                                    kelurahanField.setValue(data.data.kelurahan);
                                    kecamatanField.setValue(data.data.kecamatan);
                                    kotaField.setValue(data.data.kota);
                                  } else {
                                    Ext.Msg.alert("Error", data.message);
                                  }
                                },
                                failure: function () {
                                  Ext.Msg.alert("Error", "Gagal menghubungi server.");
                                }
                              });
                            } else if (!kodepos) {
                              var pkField = field.up('form').getForm().findField('pkktp');
                              var kelurahanField = field.up('form').getForm().findField('kelurahanktp');
                              var kecamatanField = field.up('form').getForm().findField('kecamatanktp');
                              var kotaField = field.up('form').getForm().findField('kotaktp');

                              pkField.setValue('');
                              kelurahanField.setValue('');
                              kecamatanField.setValue('');
                              kotaField.setValue('');
                            } else {
                              Ext.Msg.alert("Warning", "Kode pos harus terdiri dari 5 digit.");
                            }
                          }
                        }
                      }
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Kelurahan KTP",
                      name: "kelurahanktp",
                      anchor: "95%",
                      readOnly: true
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Kecamatan KTP",
                      name: "kecamatanktp",
                      anchor: "95%",
                      readOnly: true
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Kota KTP",
                      name: "kotaktp",
                      anchor: "95%",
                      readOnly: true
                    },
                    {
                      xtype: "textareafield",
                      fieldLabel: "Alamat Domisili",
                      name: "alamat",
                      grow: true,
                      anchor: "95%",
                    },
                    {
                      xtype: "numberfield",
                      fieldLabel: "Kode Pos Domisili",
                      name: "kodepos",
                      anchor: "95%",
                      hideTrigger: true,
                      keyNavEnabled: false,
                      mouseWheelEnabled: false,
                      maxLength: 5,
                      enforceMaxLength: true,
                      listeners: {
                        specialkey: function (field, event) {
                          if (event.getKey() === Ext.EventObject.ENTER) { // Check if the Enter key is pressed
                            var kodepos = field.getValue();
                            if (kodepos && kodepos.toString().length === 5) { // Ensure kodepos is 5 digits
                              Ext.Ajax.request({
                                url: Settings.SITE_URL + "/pegawai/get_data_by_kodepos", // Adjust the URL as needed
                                method: "POST",
                                params: {
                                  kodepos: kodepos
                                },
                                success: function (response) {
                                  var data = Ext.decode(response.responseText);
                                  if (data.success) {
                                    // Assuming the response contains kelurahan, kecamatan, and kota
                                    var pkField = field.up('form').getForm().findField('pk');
                                    var kelurahanField = field.up('form').getForm().findField('kelurahan');
                                    var kecamatanField = field.up('form').getForm().findField('kecamatan');
                                    var kotaField = field.up('form').getForm().findField('kota');

                                    pkField.setValue(data.data.id);
                                    kelurahanField.setValue(data.data.kelurahan);
                                    kecamatanField.setValue(data.data.kecamatan);
                                    kotaField.setValue(data.data.kota);
                                  } else {
                                    Ext.Msg.alert("Error", data.message);
                                  }
                                },
                                failure: function () {
                                  Ext.Msg.alert("Error", "Gagal menghubungi server.");
                                }
                              });
                            } else if (!kodepos) {
                              var pkField = field.up('form').getForm().findField('pk');
                              var kelurahanField = field.up('form').getForm().findField('kelurahan');
                              var kecamatanField = field.up('form').getForm().findField('kecamatan');
                              var kotaField = field.up('form').getForm().findField('kota');

                              pkField.setValue('');
                              kelurahanField.setValue('');
                              kecamatanField.setValue('');
                              kotaField.setValue('');
                            } else {
                              Ext.Msg.alert("Warning", "Kode pos harus terdiri dari 5 digit.");
                            }
                          }
                        }
                      }
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Kelurahan Domisili",
                      name: "kelurahan",
                      anchor: "95%",
                      readOnly: true
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Kecamatan Domisili",
                      name: "kecamatan",
                      anchor: "95%",
                      readOnly: true
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Kota Domisili",
                      name: "kota",
                      anchor: "95%",
                      readOnly: true
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Kewarganegaraan",
                      name: "kewarganegaraan",
                      anchor: "95%",
                    },
                    {
                      xtype: "combogoldarah",
                      fieldLabel: "Gol Darah",
                      name: "goldarah",
                      anchor: "95%",
                      listeners: {
                        select: function (combo, record) {
                          // Assuming the rhesus value is stored in the selected record
                          var rhesusValue = record[0].data.rhesus; // Adjust this if the field name is different
                          var rhesusField = combo.up('form').getForm().findField('rhesus'); // Use combo instead of field
                          rhesusField.setValue(rhesusValue); // Set the value of the rhesus field

                          var goldarahidValue = record[0].data.id; // Adjust this if the field name is different
                          var goldarahidField = combo.up('form').getForm().findField('goldarahid'); // Use combo instead of field
                          goldarahidField.setValue(goldarahidValue); // Set the value of the rhesus field
                        }
                      }
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Rhesus",
                      name: "rhesus",
                      anchor: "95%",
                      readOnly: true
                    },
                    {
                      itemId: "id_agama",
                      xtype: "comboagama",
                      fieldLabel: "Agama",
                      name: "agama",
                      isLoad: true,
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "No Telp",
                      name: "telp",
                      anchor: "95%",
                      enforceMaxLength: true,
                      maxLength: 15, // Set a maximum length if needed
                      regex: /^[0-9]*$/, // Regular expression to allow only numbers
                      regexText: 'Hanya angka yang diperbolehkan.', // Message to show when validation fails
                      enableKeyEvents: true, // Enable key events to handle input
                      listeners: {
                        keypress: function (field, event) {
                          // Allow only numbers and control keys (backspace, delete, etc.)
                          if (!/[0-9]/.test(String.fromCharCode(event.getCharCode())) &&
                            event.getKey() !== Ext.EventObject.BACKSPACE &&
                            event.getKey() !== Ext.EventObject.DELETE &&
                            event.getKey() !== Ext.EventObject.TAB &&
                            event.getKey() !== Ext.EventObject.ENTER) {
                            event.stopEvent(); // Prevent the input
                          }
                        }
                      }
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "No HP",
                      name: "hp",
                      anchor: "95%",
                      enforceMaxLength: true,
                      maxLength: 15, // Set a maximum length if needed
                      regex: /^[0-9]*$/, // Regular expression to allow only numbers
                      regexText: 'Hanya angka yang diperbolehkan.', // Message to show when validation fails
                      enableKeyEvents: true, // Enable key events to handle input
                      listeners: {
                        keypress: function (field, event) {
                          // Allow only numbers and control keys (backspace, delete, etc.)
                          if (!/[0-9]/.test(String.fromCharCode(event.getCharCode())) &&
                            event.getKey() !== Ext.EventObject.BACKSPACE &&
                            event.getKey() !== Ext.EventObject.DELETE &&
                            event.getKey() !== Ext.EventObject.TAB &&
                            event.getKey() !== Ext.EventObject.ENTER) {
                            event.stopEvent(); // Prevent the input
                          }
                        }
                      }
                    },
                  ],
                },
                {
                  xtype: "panel",
                  columnWidth: 0.5,
                  bodyPadding: 10,
                  layout: "form",
                  defaultType: "displayfield",
                  baseCls: "x-plain",
                  border: false,
                  defaults: {
                    labelWidth: 170,
                  },
                  items: [
                    {
                      xtype: "combostatuspegawai",
                      fieldLabel: "Status Pegawai",
                      name: "statuspegawai",
                      anchor: "95%",
                      readOnly: false,
                      listeners: {
                        select: function (combo, record) {
                          // Assuming the rhesus value is stored in the selected record
                          var spValue = record[0].data.id; // Adjust this if the field name is different
                          var spField = combo.up('form').getForm().findField('statuspegawaiid'); // Use combo instead of field
                          spField.setValue(spValue); // Set the value of the rhesus field
                        }
                      }
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Divisi",
                      name: "divisi",
                      anchor: "95%",
                      readOnly: true,
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Departement",
                      name: "departemen",
                      anchor: "95%",
                      readOnly: true,
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Jabatan",
                      name: "jabatan",
                      anchor: "95%",
                      readOnly: true,
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Lokasi",
                      name: "lokasi",
                      anchor: "95%",
                      readOnly: true,
                    },
                    {
                      xtype: "datefield",
                      fieldLabel: "Tgl Masuk",
                      name: "tglmulai",
                      format: "d/m/Y",
                      anchor: "95%",
                      readOnly: true,
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Email Pribadi",
                      name: "email",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Email Kantor",
                      name: "emailkantor",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "No KTP",
                      name: "noktp",
                      anchor: "95%",
                      enforceMaxLength: true,
                      maxLength: 16, // Set a maximum length if needed (adjust based on KTP length)
                      regex: /^[0-9]*$/, // Regular expression to allow only numbers
                      regexText: 'Hanya angka yang diperbolehkan.', // Message to show when validation fails
                      enableKeyEvents: true, // Enable key events to handle input
                      listeners: {
                        keypress: function (field, event) {
                          // Allow only numbers and control keys (backspace, delete, etc.)
                          if (!/[0-9]/.test(String.fromCharCode(event.getCharCode())) &&
                            event.getKey() !== Ext.EventObject.BACKSPACE &&
                            event.getKey() !== Ext.EventObject.DELETE &&
                            event.getKey() !== Ext.EventObject.TAB &&
                            event.getKey() !== Ext.EventObject.ENTER) {
                            event.stopEvent(); // Prevent the input
                          }
                        }
                      }
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Masa Berlaku KTP",
                      name: "masaberlakuktp",
                      anchor: "95%",
                      readOnly: true
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "NPWP",
                      name: "npwp",
                      anchor: "95%",
                      enforceMaxLength: true,
                      maxLength: 15, // Set a maximum length if needed (adjust based on NPWP format)
                      regex: /^[0-9]*$/, // Regular expression to allow only numbers
                      regexText: 'Hanya angka yang diperbolehkan.', // Message to show when validation fails
                      enableKeyEvents: true, // Enable key events to handle input
                      listeners: {
                        keypress: function (field, event) {
                          // Allow only numbers and control keys (backspace, delete, etc.)
                          if (!/[0-9]/.test(String.fromCharCode(event.getCharCode())) &&
                            event.getKey() !== Ext.EventObject.BACKSPACE &&
                            event.getKey() !== Ext.EventObject.DELETE &&
                            event.getKey() !== Ext.EventObject.TAB &&
                            event.getKey() !== Ext.EventObject.ENTER) {
                            event.stopEvent(); // Prevent the input
                          }
                        }
                      }
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "BPJS Kesehatan",
                      name: "bpjskes",
                      anchor: "95%",
                      enforceMaxLength: true,
                      maxLength: 13, // Set a maximum length if needed (adjust based on BPJS number format)
                      regex: /^[0-9]*$/, // Regular expression to allow only numbers
                      regexText: 'Hanya angka yang diperbolehkan.', // Message to show when validation fails
                      enableKeyEvents: true, // Enable key events to handle input
                      listeners: {
                        keypress: function (field, event) {
                          // Allow only numbers and control keys (backspace, delete, etc.)
                          if (!/[0-9]/.test(String.fromCharCode(event.getCharCode())) &&
                            event.getKey() !== Ext.EventObject.BACKSPACE &&
                            event.getKey() !== Ext.EventObject.DELETE &&
                            event.getKey() !== Ext.EventObject.TAB &&
                            event.getKey() !== Ext.EventObject.ENTER) {
                            event.stopEvent(); // Prevent the input
                          }
                        }
                      }
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "BPJS Ketenagakerjaan",
                      name: "bpjsnaker",
                      anchor: "95%",
                      enforceMaxLength: true,
                      maxLength: 13, // Set a maximum length if needed (adjust based on BPJS number format)
                      regex: /^[0-9]*$/, // Regular expression to allow only numbers
                      regexText: 'Hanya angka yang diperbolehkan.', // Message to show when validation fails
                      enableKeyEvents: true, // Enable key events to handle input
                      listeners: {
                        keypress: function (field, event) {
                          // Allow only numbers and control keys (backspace, delete, etc.)
                          if (!/[0-9]/.test(String.fromCharCode(event.getCharCode())) &&
                            event.getKey() !== Ext.EventObject.BACKSPACE &&
                            event.getKey() !== Ext.EventObject.DELETE &&
                            event.getKey() !== Ext.EventObject.TAB &&
                            event.getKey() !== Ext.EventObject.ENTER) {
                            event.stopEvent(); // Prevent the input
                          }
                        }
                      }
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Asuransi Kesehatan",
                      name: "askes",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Passpor",
                      name: "paspor",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Nomor Kartu Keluarga",
                      name: "nokk",
                      anchor: "95%",
                      enforceMaxLength: true,
                      maxLength: 16, // Set a maximum length if needed (adjust based on Kartu Keluarga number format)
                      regex: /^[0-9]*$/, // Regular expression to allow only numbers
                      regexText: 'Hanya angka yang diperbolehkan.', // Message to show when validation fails
                      enableKeyEvents: true, // Enable key events to handle input
                      listeners: {
                        keypress: function (field, event) {
                          // Allow only numbers and control keys (backspace, delete, etc.)
                          if (!/[0-9]/.test(String.fromCharCode(event.getCharCode())) &&
                            event.getKey() !== Ext.EventObject.BACKSPACE &&
                            event.getKey() !== Ext.EventObject.DELETE &&
                            event.getKey() !== Ext.EventObject.TAB &&
                            event.getKey() !== Ext.EventObject.ENTER) {
                            event.stopEvent(); // Prevent the input
                          }
                        }
                      }
                    },
                    {
                      xtype: "combostatusnikah",
                      fieldLabel: "Status Pernikahan",
                      name: "statusnikah",
                      anchor: "95%"
                    },
                    {
                      xtype: "datefield",
                      fieldLabel: "Tgl Menikah",
                      name: "tglnikah",
                      format: "d/m/Y",
                      anchor: "95%",
                      editable: false
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Berat Badan",
                      name: "beratbadan",
                      anchor: "95%",
                      enforceMaxLength: true,
                      maxLength: 5, // Set a maximum length if needed (adjust based on expected input)
                      regex: /^[0-9]*$/, // Regular expression to allow only numbers
                      regexText: 'Hanya angka yang diperbolehkan.', // Message to show when validation fails
                      enableKeyEvents: true, // Enable key events to handle input
                      listeners: {
                        keypress: function (field, event) {
                          // Allow only numbers and control keys (backspace, delete, etc.)
                          if (!/[0-9]/.test(String.fromCharCode(event.getCharCode())) &&
                            event.getKey() !== Ext.EventObject.BACKSPACE &&
                            event.getKey() !== Ext.EventObject.DELETE &&
                            event.getKey() !== Ext.EventObject.TAB &&
                            event.getKey() !== Ext.EventObject.ENTER) {
                            event.stopEvent(); // Prevent the input
                          }
                        }
                      }
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Tinggi Badan",
                      name: "tinggibadan",
                      anchor: "95%",
                      enforceMaxLength: true,
                      maxLength: 5, // Set a maximum length if needed (adjust based on expected input)
                      regex: /^[0-9]*$/, // Regular expression to allow only numbers
                      regexText: 'Hanya angka yang diperbolehkan.', // Message to show when validation fails
                      enableKeyEvents: true, // Enable key events to handle input
                      listeners: {
                        keypress: function (field, event) {
                          // Allow only numbers and control keys (backspace, delete, etc.)
                          if (!/[0-9]/.test(String.fromCharCode(event.getCharCode())) &&
                            event.getKey() !== Ext.EventObject.BACKSPACE &&
                            event.getKey() !== Ext.EventObject.DELETE &&
                            event.getKey() !== Ext.EventObject.TAB &&
                            event.getKey() !== Ext.EventObject.ENTER) {
                            event.stopEvent(); // Prevent the input
                          }
                        }
                      }
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Nama Kontak Darurat",
                      name: "namakontakdarurat",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "No Telp Kontak Darurat",
                      name: "telpkontakdarurat",
                      anchor: "95%",
                      enforceMaxLength: true,
                      maxLength: 15, // Set a maximum length if needed (adjust based on expected input)
                      regex: /^[0-9]*$/, // Regular expression to allow only numbers
                      regexText: 'Hanya angka yang diperbolehkan.', // Message to show when validation fails
                      enableKeyEvents: true, // Enable key events to handle input
                      listeners: {
                        keypress: function (field, event) {
                          // Allow only numbers and control keys (backspace, delete, etc.)
                          if (!/[0-9]/.test(String.fromCharCode(event.getCharCode())) &&
                            event.getKey() !== Ext.EventObject.BACKSPACE &&
                            event.getKey() !== Ext.EventObject.DELETE &&
                            event.getKey() !== Ext.EventObject.TAB &&
                            event.getKey() !== Ext.EventObject.ENTER) {
                            event.stopEvent(); // Prevent the input
                          }
                        }
                      }
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Relasi Kontak Darurat",
                      name: "relasikontakdarurat",
                      anchor: "95%",
                    },
                    {
                      xtype: "textareafield",
                      fieldLabel: "Hobby",
                      name: "hobby",
                      grow: true,
                      anchor: "95%",
                    },
                    {
                      xtype: "comboshio",
                      fieldLabel: "Shio / Unsur",
                      name: "shio",
                      anchor: "95%",
                      listeners: {
                        select: function (combo, record) {

                          // Assuming the rhesus value is stored in the selected record
                          var shioValue = record[0].data.id; // Adjust this if the field name is different
                          var shioField = combo.up('form').getForm().findField('shioid'); // Use combo instead of field
                          shioField.setValue(shioValue); // Set the value of the rhesus field
                        }
                      }
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Size Baju",
                      name: "sizebaju",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Size Celana",
                      name: "sizecelana",
                      anchor: "95%",
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Size Sepatu",
                      name: "sizesepatu",
                      anchor: "95%",
                      enforceMaxLength: true,
                      maxLength: 3, // Set a maximum length if needed (adjust based on expected input)
                      regex: /^[0-9]*$/, // Regular expression to allow only numbers
                      regexText: 'Hanya angka yang diperbolehkan.', // Message to show when validation fails
                      enableKeyEvents: true, // Enable key events to handle input
                      listeners: {
                        keypress: function (field, event) {
                          // Allow only numbers and control keys (backspace, delete, etc.)
                          if (!/[0-9]/.test(String.fromCharCode(event.getCharCode())) &&
                            event.getKey() !== Ext.EventObject.BACKSPACE &&
                            event.getKey() !== Ext.EventObject.DELETE &&
                            event.getKey() !== Ext.EventObject.TAB &&
                            event.getKey() !== Ext.EventObject.ENTER) {
                            event.stopEvent(); // Prevent the input
                          }
                        }
                      }
                    },
                    {
                      xtype: "textfield",
                      fieldLabel: "Size Rompi",
                      name: "sizerompi",
                      anchor: "95%",
                    },
                  ],
                },
              ],
            },
          ],
        },
      ],
    });
    me.callParent([arguments]);
  },
});
