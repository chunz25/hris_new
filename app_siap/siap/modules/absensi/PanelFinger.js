Ext.define("SIAP.modules.absensi.PanelFinger",{
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.panelfinger" ,
	alias: 'widget.panelfinger',
	title: 'Detail Preferred',	
	isDefaultSelectAll: false,
	isSave: true,
	rowIndex: 0,
	requires: [
		'SIAP.modules.absensi.GridDetailPrice2'
	],
	params: {},
	initComponent:function(){
		var me = this;
		me.addEvents({"pilih": true});
		me.addEvents({"save": true});		
				
		Ext.apply(me,{						
			width: 800, height: 400, closeAction: 'destroy', modal: true, autoShow: true, border: false, bodyPadding: 5,
			layout: 'border',	
			items: [
				{itemId: 'id_detailprice', xtype: 'griddetailprice2', title: 'Daftar Pegawai', region: 'center', layout: 'fit'},
			],
			listeners: {
				afterrender: function(){
					// Ext.getStore('storeprice').proxy.extraParams.fingerid = me.params.fingerid;				
					Ext.getStore('storeprice').load();						
				}
			},
			tbar: [
				{itemId: 'id_lokasi', xtype: 'combolokasikerja', emptyText: 'lokasi'},
				'-',
				{glyph:'xf002@FontAwesome',
					handler: function() {
						var lokasi = Ext.ComponentQuery.query('#id_lokasi')[0].getValue();
						Ext.getStore('storeprice').proxy.extraParams.lokasi = Ext.ComponentQuery.query('#id_lokasi')[0].getValue();
						Ext.getStore('storeprice').load();
					}
				},
				'->',
				{glyph:'xf045@FontAwesome', text: 'Kalkulasi',
					handler: function() {
						var m = me.down('#id_detailprice').getSelectionModel().getSelection();
						if( m.length !== 0 ){
							me.simpan(m);
						}
					}
				}
			]		
		});
		me.callParent([arguments]);
	},	
	simpan: function(recordbrand){
		var me = this;
		var fingerid = '';
		Ext.each(recordbrand, function(rec,index){
			fingerid += rec.data.fingerid;
			if(index != (recordbrand.length - 1)) {				
				fingerid += "'" + "," + "'";
			}
		});
		
		Ext.History.add('#absensi&GridAbsensi&'+Base64.encode(fingerid));
	}
})