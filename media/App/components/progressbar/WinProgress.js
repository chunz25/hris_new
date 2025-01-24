Ext.define("App.components.progressbar.WinProgress",{
	extend: "Ext.window.Window",
	alternateClassName: "App.WinProgress",
	alias: 'widget.winprogress',
	URL: '',
	stop_recursive: false,
	title: 'Progress Bar',
	headInfo: 'Jumlah Data :',
	headInfoSatuan: 'data',
	initComponent: function(){
		var me = this;
		me.addEvents({"finished": true});		
		Ext.apply(me, {			
			height: 180, width: 450, closeAction: 'destroy', modal: true, autoShow: true, border: false,
			layout: 'fit',
			buttons: [
				{text: 'Selesai',
					handler: function(){
						me.destroy();
						me.stop_recursive = true;
					}
				}
			],
			listeners: {
				destroy: function(){
					me.stop_recursive = true;
				}
			},
			items: [
				{xtype: 'panel', border: false,
					items: [
						{ style: 'padding:5px 5px 5px 10px;border:none;', html: '<div id="idjmldata"> Jumlah Data : </div>'},
						{ style: 'padding:10px',html: '<div id="panelprogress" style="background-color:#ddd;height:20px" width="20"></div>'}					
					]
				}
			]			
		});		
		me.callParent([arguments]);
	},
	proses_exec: function(data, extraParams){
		var me = this;
		var pbar = Ext.create('Ext.ProgressBar', {layout: 'fit', cls: 'custom', renderTo: 'panelprogress'});
		pbar.updateProgress(0, (0 * 100).toString().substring(0, 5) + '%');
		document.getElementById('idjmldata').innerHTML = me.headInfo + ' ' + data.length + ' ' + me.headInfoSatuan;
		me.proses_script(data, extraParams, 0, pbar);
	},
	proses_script: function(data, extraParams, index, pbar){
		var me = this;
		var count = data.length;
		if(data[index]){
			Ext.Ajax.request({
				url: me.URL,
				method: 'post',
				params: {params: Ext.encode(data[index]), extraParams: Ext.encode(extraParams)},
				success: function(response){
					var obj = Ext.decode(response.responseText);
					if( !me.stop_recursive){
						if(obj.success){
							if(index < count){
								var prog_count = (index + 1) / count;																
								pbar.updateProgress(prog_count, ((prog_count * 100).toString().substring(0, 5) + '%'));
								document.getElementById('idjmldata').innerHTML = me.headInfo + ' ' + count + ' ' + me.headInfoSatuan;
								me.proses_script(data, extraParams, (index + 1), pbar);																	
								if(prog_count >= 1){									
									me.finish();
								}
							}
							else{
								me.finish();
							}
						}
						else{
							me.stop_recursive = true;
						}
					}
				}
			});
		}
	},
	finish: function(){
		var me = this;
		me.fireEvent("finished", me);
		me.destroy();
	}
});