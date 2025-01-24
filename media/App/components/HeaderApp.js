Ext.define("App.components.HeaderApp",{
	extend 	: "Ext.panel.Panel",
	alternateClassName: "App.HeaderApp" ,
	alias	: 'widget.headerapp',
	border 	:0,	
	dockedItems : [
		{
			xtype  	: 'toolbar',
			height: 70,
			style: {
				padding: '0 0 0 0',
			},
			dock  :'top',
			items : [
				'<div style="width:170px;height:60px;"><img src="'+Settings.BASE_URL+'setting/eci/logo.png'+'" width="60%"></div>',
				{ text: 'Pengumuman', hrefTarget:'_self', scale:'medium', border: false, href :Settings.SITE_URL+'#info'},
				{ text: 'Pegawai', hrefTarget:'_self', scale:'medium', border: false, href :Settings.SITE_URL+'#pegawai'},
			]		
		}
	]
});
