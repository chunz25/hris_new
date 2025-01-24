function packages() {
	Ext.Loader.setConfig({
		enabled: true,
		paths: {
			SIAP: Settings.BASE_URL + 'app_siap/siap',
			Ext: Settings.url_ext,
		}
	});

	Ext.require('SIAP.abstract.FieldButton');
	Ext.require('SIAP.abstract.SearchField');
	Ext.require('SIAP.abstract.Window');
}