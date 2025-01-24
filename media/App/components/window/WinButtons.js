Ext.define("App.components.window.WinButtons",{
	extend: "App.abstract.Window",
	layout:'fit', border:false,	
	content:'',	
	initComponent: function(a){		
		var me=this;
		this.bbar=['->',
			{ xtype:'button', text:'Pilih', itemId:'pilih',
				handler:function(){
					var a=me.onPilih();				
					if(a!==false) me.destroy();
				}
			},
			{ xtype:'button', text:'Batal', itemId:'batal',
				handler:function(){
					me.onBatal();
					me.destroy();
				}
			}
		];		
		this.content=me.buildContent();
		this.items=this.content;		
		this.callParent([arguments]);		
	},
	buildContent:function(){
		
	},
	onPilih:function (){
		
	},
	onBatal:function (){
		
	},	
	onDestroy : function(){
        this.callParent(arguments);
    },
});

