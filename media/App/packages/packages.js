function packages(){
	Ext.Loader.setConfig({ 
		enabled	: true, 
		paths	: { 
			App		: Settings.url_app,
			Ext		: Settings.url_ext4,
		} 		
	});
	
	Ext.require('App.abstract.FieldButton');
	Ext.require('App.abstract.SearchField');
	Ext.require('App.abstract.Window');
	
	Ext.require('App.components.HeaderApp');
	
	Ext.require('App.components.window.WinJabatan');
	Ext.require('App.components.field.FieldJabatan');
	Ext.require('App.components.tree.UnitKerja');
	Ext.require('App.components.tree.Jabatan');
	
	Ext.require('App.components.HeaderApp');
	Ext.require('App.modules.pegawai.App');
	Ext.require('App.modules.info.App');
}