Ext.define("SIAP.modules.absensi.App", {
	extend: "Ext.panel.Panel",
	alternateClassName: "SIAP.absensi",
	alias: 'widget.absensi',	
	requires: [
		'SIAP.components.tree.UnitKerja',
		'SIAP.components.tree.Lokasi',
		'SIAP.modules.absensi.PanelSchedule',
		'SIAP.modules.absensi.PanelFinger',
	],	
	initComponent: function(){
		var me = this;
		window.loadTask = new Ext.util.DelayedTask();
		Ext.apply(me,{		
			layout: 'border',			
			items: [
				{ id: 'id_west_treesatker', region: 'west', title: 'Daftar Unit Kerja', collapsible: true, collapsed: false, layout: 'fit', border: false,
                    resizable:{dynamic:true},		
					items:[
						{xtype: 'unitkerja', width: 200, border: false,
							listeners:{
								itemclick : function(a,b,c){
									var itemTab = me.down('tabpanel').getActiveTab().getItemId();									
									console.log(itemTab);
									console.log(b.get('id'));

									if(itemTab == 'panelschedule'){
										var tglmulai = Ext.Date.format(me.down('#id_tglmulai').getValue(),'d/m/Y');
										var tglselesai = Ext.Date.format(me.down('#id_tglselesai').getValue(),'d/m/Y');
										
										Ext.getStore('storeschedule').proxy.extraParams.tglmulai = tglmulai;
										Ext.getStore('storeschedule').proxy.extraParams.tglselesai = tglselesai;
										Ext.getStore('storeschedule').proxy.extraParams.satkerid = b.get('id');
										Ext.getStore('storeschedule').load();

									} else if(itemTab == 'panelfinger'){
										
										Ext.getStore('storeprice').proxy.extraParams.satkerid = b.get('id');
										Ext.getStore('storeprice').load();																				
									
									}
								},
							},							
						}
					]
				},		
				{ xtype: 'tabpanel', region: 'center', border: false, loadMask: true, activeItem: 0, layoutOnTabChange: true,
					defaults: {autoScroll: true},
					defaults: {
						layout: 'border',
						listeners: {
							activate: function(tab, opt){
								var itemTab = tab.itemId;
								if(itemTab == 'panelschedule'){
									Ext.getCmp('id_west_treesatker').show();
								} else if (itemTab == 'panelfinger') {
									Ext.getCmp('id_west_treesatker').show();
								}
							}
						}
					},
					items: [
						{itemId: 'panelschedule', xtype: 'panelschedule', title: 'Schedule'},
						{itemId: 'panelfinger', xtype: 'panelfinger', title: 'Absensi'},
					]
				}				
			],
			listeners: {
				afterrender: function(){
					Ext.get('id_submenu').dom.style.display = 'none';
				}
			}			
		});		
		me.callParent([arguments]);
	},	
});