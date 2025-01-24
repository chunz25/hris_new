Ext.define('SIAP.abstract.FieldButton', {
	extend: 'Ext.form.FieldContainer',
	requires: ['SIAP.abstract.SearchField'],
	config: {
		name: 'unknown',
		window: null,
		button: 'unknown',
		fieldid: 'unknown',
		field: 'unknown',
		triggerCls: 'form-search-trigger',
		isShowFocus: true,
		value: '',
		isShowEnter: false,
		emptyText: '',
	},
	idRoot: '0',
	isSetStore: true,

	initComponent: function (config) {
		var me = this;

		this.layout = 'form';

		this.initConfig(config);

		var field = Ext.create('SIAP.abstract.SearchField', {
			xtype: 'textfield',
			hideLabel: true,
			name: this.name,
			itemId: this.name,
			trigger2Cls: Ext.baseCSSPrefix + this.triggerCls,
			emptyText: this.emptyText,
			anchor: '100%',
			margin: '0 0 0 0',
			listeners: {
				enter: function () {
					if (me.isShowEnter) {
						me.window = me.createWindow();
						me.window.show();
					}
					me.onEnter(me.field.getValue(), me);
				},
				focus: function () {
					me.onFocus(me.field.getValue(), me);
					if (me.isShowFocus) {
						me.window = me.createWindow();
						me.window.show();
					}
				},
				click: function () {
					me.onClick(me.field.getValue(), me);
					me.window = me.createWindow();
					me.window.show();
				},
				reset: function (a) {
					me.reset(a);
				}
			}
		});


		var fieldid = Ext.create('Ext.form.field.Hidden', {
			name: this.name + 'ID',
			hidden: true,
			itemId: this.name + 'ID',
		});

		this.field = field;
		this.fieldid = fieldid;

		this.items = [
			field, fieldid
		];

		this.callParent([arguments]);

	},

	onClick: function (val, me) {

	},

	onFocus: function (val, me) {

	},
	onEnter: function (val, me) {

	},
	createWindow: function () {


	},

	reset: function () {
		var me = this;
		if (me.up('grid')) {
			var grid = me.up('grid');
			var rec = grid.getSelectionModel().getSelection()[0];
			if (me.isSetStore && rec)
				rec.set(this.name + 'ID', '');
		}

	},

	isValid: function () {

		return true;
	},


	getValue: function () {
		return this.fieldid.getValue();
	},

	getRawValue: function () {
		return this.field.getValue();
	}



});