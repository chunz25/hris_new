Ext.define('SIAP.components.field.ComboBulan', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.combobulan',
	fieldLabel: '',
	name: 'bulan',
	editable: false,
	initComponent: function () {
		var me = this;
		Ext.apply(me, {
			store: Ext.create('Ext.data.SimpleStore', {
				fields: ['id', 'text'],
				data: [
					['', 'All'],
					['1', 'Januari'],
					['2', 'Februari'],
					['3', 'Maret'],
					['4', 'April'],
					['5', 'Mei'],
					['6', 'Juni'],
					['7', 'Juli'],
					['8', 'Agustus'],
					['9', 'September'],
					['10', 'Oktober'],
					['11', 'November'],
					['12', 'Desember']
				]
			}),
			triggerAction: 'all',
			editable: me.editable,
			displayField: 'text',
			valueField: 'id',
			name: me.name,
		});
		me.callParent([arguments]);
	},
});