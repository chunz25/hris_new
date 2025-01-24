Ext.define("SIAP.modules.detailpegawai.TreeDRH", {
	extend: "Ext.tree.Panel",
	alternateClassName: "SIAP.treedrh",
	alias: 'widget.treedrh',
	params: '',
	initComponent: function () {
		var me = this;
		Ext.apply(me, {
			useArrows: true, border: false, rootVisible: false, width: 200,
			store: Ext.create('Ext.data.TreeStore', {
				root: {
					expanded: true,
					children: me.dataTreeDRH()
				}
			}),
			listeners: {
				itemclick: function (a, b, c) {
					Ext.History.add('#detailpegawai&' + b.raw.link + '&' + me.params);
				}
			}
		});
		me.callParent([arguments]);
	},
	dataTreeDRH: function () {
		var me = this;
		var data = [
			{ id: '1', text: 'A. Data Karyawan', leaf: true, link: 'PanelIdentitas', files: '' },
			{ id: '2', text: 'B. History Status Karyawan', leaf: true, link: 'HistoryPegawai', files: '' },
			{ id: '3', text: 'C. Data Keluarga', leaf: true, link: 'RiwayatKeluarga', files: '' },
			{ id: '4', text: 'D. Pendidikan Formal', leaf: true, link: 'RiwayatPendidikan', files: '' },
			{ id: '5', text: 'E. Pengalaman Kerja', leaf: true, link: 'PengalamanKerja', files: '' },
			{ id: '6', text: 'F. Rekening Bank', leaf: true, link: 'RiwayatRekening', files: '' },
			{ id: '7', text: 'G. Training/Kursus', leaf: true, link: 'RiwayatKursus', files: '' },
			{ id: '8', text: 'H. Bahasa', leaf: true, link: 'RiwayatBahasa', files: '' },
			{ id: '9', text: 'I. Alergi dan Penyakit', leaf: true, link: 'RiwayatPenyakit', files: '' },
			{ id: '10', text: 'J. Kegiatan AGP', leaf: true, link: 'RiwayatAGP', files: '' },
			{ id: '11', text: 'K. Mutasi & Promosi', leaf: true, link: 'MutasiPromosi', files: '' },
			{ id: '12', text: 'L. Indisipliner', leaf: true, link: 'RiwayatIndiplisiner', files: '' },
			{ id: '13', text: 'M. Performance Appraisal', leaf: true, link: 'RiwayatPerformanceAppraisal', files: '' },
			{ id: '14', text: 'N. Acting As', leaf: true, link: 'RiwayatActingAs', files: '' },
			{ id: '15', text: 'O. Keahlian Khusus', leaf: true, link: 'RiwayatKeahlian', files: '' },
			{ id: '16', text: 'P. Catatan Tambahan', leaf: true, link: 'RiwayatCatatanTambahan', files: '' },

		];
		return data;
	}
})