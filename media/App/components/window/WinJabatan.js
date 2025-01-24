Ext.define("App.components.window.WinJabatan",{
	extend		: "App.components.window.WinButtons",
	alias		: 'widget.masterjabatan',	
	requires:[
		'App.abstract.SearchField',
		'App.components.tree.Jabatan'
	],	
	config: {
		jabatanid: '',
	},	
	title: 'Master Jabatan',	
	URL_JABATAN	: '',
	initComponent	: function(a) {		
		var me=this;
		this.addEvents({
			"onbeforepostdata"	: true,
			"itemclick"				: true,
			"pilih"				: true,
			"batal"				: true
		});		
		this.callParent([arguments]);		
	},
	
	onbeforepostdata:function(){	
		var params=this.grid.store.proxy.extraParams;	
		params.jabatanid = this.jabatanid;		
		this.fireEvent("onbeforepostdata", this);		
	},
	
	buildContent:function(){
		var me=this;		
		return Ext.create('App.components.tree.Jabatan', {
			listeners: {
				celldblclick: function(v, td, cellIndex, record, tr, rowIndex, e, opt){
				}
			}			
		});
	},	
	onPilih:function(){
		var me = this;
		var content = me.content;
		var m = content.getSelectionModel().getSelection();		
		if(m.length>0){
			me.fireEvent("pilih", m[0]);
			me.hide();
		}
		else{
			Ext.Msg.alert('Pesan', 'Tidak ada data yang dipilih');
		}		
	},
	
	onBatal:function(){
		var me=this;
		me.fireEvent("batal", me);
	}
});

