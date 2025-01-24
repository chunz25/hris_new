Ext.define("SIAP.modules.absensi.GridAbsensi", {
	extend: "Ext.grid.Panel",
	alternateClassName: "SIAP.gridabsensi",
	alias: 'widget.gridabsensi',
	initComponent: function(){
		var me = this;
		var arr_params = me.params.split('#');		
		var fingerid = Base64.decode(arr_params[0]);
		/*var d = new Date();
		var m = d.getMonth();
		var md = d.getMonth()-1;

		var today = moment();
		var day ={month : md , day:16};
		var enday ={month : m , day:15};*/

		me.addEvents({"beforeload": true});

		var storeabsensi = Ext.create('Ext.data.Store', {
			storeId: 'storeabsensi',
			autoLoad: true,
			pageSize: Settings.PAGESIZE,			
			proxy: {
				type: 'ajax',
				url: Settings.SITE_URL + '/absensi/getListAbsensi',
				actionMethods: {
					create : 'POST',
					read   : 'POST',
				},								
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'count'
				}
			},
			fields: [
				{name: 'id', mapping: 'No'},
				'BADGENUMBER','CalendarDate','NAME','masuk','pulang','jammasuk','jamkeluar','keterlambatan',
				'pulangcepat','jmljam','absent','weekdayflag','jadwal'
			],
			listeners: {
				beforeload: function(store){
					var tglmulai = Ext.Date.format(me.down('#id_tglmulai').getValue(),'Y-m-d');
					var tglselesai = Ext.Date.format(me.down('#id_tglselesai').getValue(),'Y-m-d');

					Ext.getStore('storeabsensi').proxy.extraParams.tglmulai = tglmulai;
					Ext.getStore('storeabsensi').proxy.extraParams.tglselesai = tglselesai;
					Ext.getStore('storeabsensi').proxy.extraParams.fingerid = fingerid;
					me.fireEvent("beforeload", store);
				}
			}
		});
		Ext.apply(me, {
			layout: 'fit',
			autoScroll: true, frame: false, border: true, loadMask: true, stripeRows: true, enableDragDrop: true,
			store: storeabsensi,		
			selModel: {mode: 'MULTI'}, 
			columns: [
				{header: 'No', xtype: 'rownumberer', width: 30},
				{header: 'Nik', dataIndex: 'BADGENUMBER', width: 80},
				{header: 'Nama', dataIndex: 'NAME', width: 150},
				{header: 'Tanggal', dataIndex: 'CalendarDate', width: 80}, 
				{header: 'Jam Masuk', dataIndex: 'jammasuk', width: 80},
				{header: 'Jam Pulang', dataIndex: 'jamkeluar', width: 80},
				{header: 'Scan Masuk', dataIndex: 'masuk', width: 80, align:'center',
					renderer : function(value, meta) {
						if( value !== null ){
							if( value >= "08:30:31" ) {
								meta.style = "background-color:#B22222;color:white;";
							}
							return value;
						} else {
							meta.style = "background-color:#DCDCDC;";
						}
					}
				},
				{header: 'Scan Pulang', dataIndex: 'pulang', width: 80, align:'center',
					renderer : function(value, meta) {
						if( value !== null ){
							if( value >= "17:30:00" ) {
								meta.style = "background-color:#4169E1;color:white;";
							}
							return value;
						} else {
							meta.style = "background-color:#DCDCDC;";
						}
					}
				},
				{header: 'Absent', dataIndex: 'absent', width: 50, align:'center',
					renderer : function(value, meta) {
					if( value !== null ) {
						if( value == '1' ){
							value = '<span style=font-size:14px;color:red;>&#10004;</span>';	
						}
					}
					return value;
					}
				},
				{header: 'Terlambat', dataIndex: 'keterlambatan', width: 80, align:'center',
					renderer : function(value, meta) {
					if( value !== null ) {
						meta.style = "background-color:#B22222;color:white;";
					}
					return value;
					}
				},
				{header: 'Pulang Cepat', dataIndex: 'pulangcepat', width: 80, align:'center',
					renderer : function(value, meta) {
					if( value !== null ) {
						meta.style = "background-color:#4169E1;color:white;";
					}
					return value;
					}
				},
				{header: 'Week Flag', dataIndex: 'weekdayflag', width: 80, align:'center',
					renderer : function(value, meta) {
					if( value !== null ) {
						if( value == '1' ){
							value = '<span style=font-size:10px;color:white;>Weekend</span>';	
						}
						meta.style = "background-color:#B22222;"; //#4169E1;
					}
					return value;
					}
				},
				{header: 'Jadwal', dataIndex: 'jadwal', width: 80, align:'center',
					renderer : function(value, meta) {
					if( value !== null ) {
						meta.style = "background-color:#4169E1;color:white;";
					}
					return value;
					}
				},
				{header: 'Jml Jam Kerja', dataIndex: 'jmljam', align:'center', width: 85}								
			],
			tbar: [
				// {itemId: 'id_tglmulai', xtype: 'datefield', fieldLabel: 'Periode', format: 'd/m/Y', name: 'tglmulai', value: (moment(day).startOf("day").format('DD/MM/YYYY')), emptyText: 'Tgl Awal', labelWidth:40, style: 'margin-left:10px;'},
				// {itemId: 'id_tglselesai', xtype: 'datefield', fieldLabel: 's/d', format: 'd/m/Y', name: 'tglselesai', value: (moment(enday).endOf("day").format('DD/MM/YYYY')), emptyText: 'Tgl Akhir', labelWidth:20, style: 'margin-left:5px;'}, 
				{itemId: 'id_tglmulai', xtype: 'datefield', fieldLabel: 'Periode', format: 'd/m/Y', name: 'tglmulai', value: (moment().startOf("month").format('DD/MM/YYYY')), emptyText: 'Tgl Awal', labelWidth:40, style: 'margin-left:10px;'},
				{itemId: 'id_tglselesai', xtype: 'datefield', fieldLabel: 's/d', format: 'd/m/Y', name: 'tglselesai', value: (moment().endOf("month").format('DD/MM/YYYY')), emptyText: 'Tgl Akhir', labelWidth:20, style: 'margin-left:5px;'}, 
				'-',
				{glyph:'xf002@FontAwesome',
					handler: function() {
						var tglmulai = Ext.Date.format(me.down('#id_tglmulai').getValue(),'Y-m-d');
						var tglselesai = Ext.Date.format(me.down('#id_tglselesai').getValue(),'Y-m-d');

						Ext.getStore('storeabsensi').proxy.extraParams.tglmulai = tglmulai;
						Ext.getStore('storeabsensi').proxy.extraParams.tglselesai = tglselesai;
						Ext.getStore('storeabsensi').proxy.extraParams.fingerid = fingerid;
						Ext.getStore('storeabsensi').load();
					}
				},
				'->',
				{glyph:'xf02f@FontAwesome', text: 'Cetak',
					handler: function(){
						var tglmulai = Ext.Date.format(me.down('#id_tglmulai').getValue(),'Y-m-d');
						var tglselesai = Ext.Date.format(me.down('#id_tglselesai').getValue(),'Y-m-d');

						Ext.getStore('storeabsensi').proxy.extraParams.tglmulai = tglmulai;
						Ext.getStore('storeabsensi').proxy.extraParams.tglselesai = tglselesai;
						Ext.getStore('storeabsensi').proxy.extraParams.fingerid = fingerid;
						var m = Ext.getStore('storeabsensi').proxy.extraParams;
						window.open(Settings.SITE_URL + "/absensi/cetakdokumen?" + objectParametize(m));							
					}
				},
				{text: 'Kembali',
					handler: function() {
						Ext.History.add('#absensi');
					}				
				},
			],
			bbar: Ext.create('Ext.toolbar.Paging',{
				displayInfo: true,
				height : 35,
				store: 'storeabsensi'
			})
		});

		me.callParent([arguments]);
	}
});