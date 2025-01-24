Ext.define('SIAP.abstract.SearchField', {
    extend: 'Ext.form.field.Trigger',
    alias: 'widget.searchfield',
    trigger1Cls: Ext.baseCSSPrefix + 'form-clear-trigger',
    trigger2Cls: Ext.baseCSSPrefix + 'form-search-trigger',
    hasSearch: false,
    paramName: 'query',
    isEnter: false,
    initComponent: function (config) {
        var me = this;

        me.addEvents({
            "search": true,
            "reset": true,
            "click": true,
            "fokus": true,
            "enter": true
        });

        me.initConfig(config);
        me.callParent(arguments);
        me.on('specialkey', function (f, e) {
            if (e.getKey() == e.ENTER) {
                me.isEnter = true;
                me.onTrigger2Click();
            }
        });
    },

    afterRender: function () {
        this.callParent();
        this.triggerCell.item(0).setDisplayed(false);
    },

    onTrigger1Click: function () {
        var me = this;

        if (me.hasSearch) {
            me.fireEvent("reset", me);

            me.setValue('');
            me.hasSearch = false;
            me.triggerCell.item(0).setDisplayed(false);
            me.updateLayout();
        }
    },

    onTrigger2Click: function () {
        var me = this,
            value = me.getValue();

        if (me.isEnter === true) {
            me.fireEvent("enter", value, me);
        } else
            me.fireEvent("click", value, me);

        me.fireEvent("search", value, me);

        me.isEnter = false;

        if (value.length > 0) {

            me.hasSearch = true;
            me.triggerCell.item(0).setDisplayed(true);
            me.updateLayout();
        }
    }
});