Ext.define('SIAP.components.field.ComboGolongan', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combogolongan',
	fieldLabel: '',
	name: 'gol',
	initComponent: function () {
		var me = this;
		var numbers = [];
		for (var i = 0; i <= 9; i++) {
			numbers.push([i]);
		}

		Ext.apply(me, {
			store: new Ext.data.SimpleStore({
				fields: ['id'],
				data: numbers
			}),
			queryMode: 'local',
			displayField: 'id',
			valueField: 'id',
			editable: true,
			name: me.name,
		});
		me.callParent([arguments]);
	},
});