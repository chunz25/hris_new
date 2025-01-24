Ext.define('SIAP.components.field.FieldPegawai', {
	extend: 'SIAP.abstract.FieldButton',
	alias: 'widget.fieldpegawai',
	requires: ['SIAP.components.window.WinAllPegawai'],
	config: {
		onlyFungsional: false,
	},
	URL_JABATAN: '',
	initComponent: function () {
		var me = this;
		me.addEvents({
			"itemclick": true,
			"pilih": true,
			"batal": true
		});
		me.fieldLabel = me.getFieldLabel();
		me.name = me.getName();
		me.autoHeight = true;

		me.callParent([arguments]);
	},
	getName: function () {
		if (this.name == 'unknown')
			return 'PEGAWAI';
		else return this.name;
	},

	getFieldLabel: function () {
		if (this.fieldLabel == 'unknown')
			return 'Form Pegawai';
		else return this.fieldLabel;
	},
	createWindow: function () {
		var me = this;
		return Ext.create('SIAP.components.window.WinAllPegawai', {
			width: 600, height: 400, bodyPadding: 5,
			listeners: {
				pilih: function (records) {
					me.field.setValue(records.get('nik'));
					me.fieldid.setValue(records.get('nik'));
					me.fireEvent('pilih', me, records);
				}
			}
		});
	}
});

