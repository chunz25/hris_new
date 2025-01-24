Ext.define('App.components.field.FieldPeriode', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.fieldperiode',
	config: {
		rangeAwal: 0,
		rangeAkhir: 0,
	},  
	fieldLabel: '',
	initComponent	: function() {	
		var me = this;	
		var years = [];
		year = parseInt(me.rangeAwal);
		lastyear = parseInt(me.rangeAkhir);
		if(me.rangeAwal == 0) year = parseInt(me.value)-2;		
		if(me.rangeAkhir == 0) lastyear = parseInt(me.value)+2;
		while (year <= lastyear){
			years.push([year]);
			year++;
		}		
		Ext.apply(me,{		
			store : new Ext.data.SimpleStore({
				fields : ['years'],
				data : years
			}),
			queryMode : 'local',
			displayField : 'years',
			valueField : 'years',
			editable : true,
		});		
		me.callParent([arguments]);
	},
});