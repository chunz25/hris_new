Ext.define("SIAP.components.window.WinAllPegawai", {
	extend: "SIAP.components.window.WinButtons",
	alias: 'widget.winallpegawai',
	requires: [
		'SIAP.abstract.SearchField',
		'SIAP.components.grid.Pegawai'
	],
	config: {
		jabatanid: '',
	},
	title: 'Search Employe',
	URL_JABATAN: '',
	initComponent: function (a) {
		var me = this;
		this.addEvents({
			"onbeforepostdata": true,
			"itemclick": true,
			"pilih": true,
			"batal": true
		});
		this.callParent([arguments]);
	},

	onbeforepostdata: function () {
		var params = this.grid.store.proxy.extraParams;
		params.jabatanid = this.jabatanid;
		this.fireEvent("onbeforepostdata", this);
	},

	buildContent: function () {
		var me = this;
		return Ext.create('SIAP.components.grid.Pegawai', {
			border: false
		});
	},
	onPilih: function () {
		var me = this;
		var content = me.content;
		var m = content.getSelectionModel().getSelection();
		if (m.length > 0) {
			me.fireEvent("pilih", m[0]);
			me.hide();
		}
		else {
			Ext.Msg.alert('Pesan', 'Tidak ada data yang dipilih');
		}
	},

	onBatal: function () {
		var me = this;
		me.fireEvent("batal", me);
	}
});

