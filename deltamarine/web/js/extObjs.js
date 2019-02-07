Ext.define('Ext.ux.acForm',{
    extend: 'Ext.form.Panel',
    alias: 'widget.acform',

    //set to true if downloading a file as the result. will use an iframe.
    download: false,
    iframe: null,
    //enable progress tracking. can also enable by specifying progressConfig
    progress: false,
    //title can be left blank and it will default to parent window's title
    progressTitle: null,
    //pass along config options to the progress tracker object
    progressConfig: null,
    //keep track of the progress tracker
    progressTracker: null,
    

    //defaults for consistent UI
    bodyStyle: 'padding: 15px 10px 10px 10px',
    fieldDefaults: {
        labelWidth: 100,
        labelAlign: 'right'
    },

    //customizations
    submitButtonText: 'OK',
    submitButtonHandler: null,
    waitMsg: false,
    formErrorMessageText: null,
    preValidate: true,
    preFocus: true,
    autoSubmit: false,
    autoLoadUrl: null,
    loadRawData: null,
    autoLoaded: false,

    //context-aware
    parentWin: null,

    constructor: function(config){
        var me = this;

        me.callParent(arguments);

        if (me.autoSubmit) me.submit();

    },

    initComponent: function(cfg){
        var me = this;
        var framename;
        
        if (me.progress || me.progressConfig){

            me.progress = true;
            var progressConfig = me.progressConfig || {};
            if (!progressConfig.title) {
                progressConfig.title = me.progressTitle || (me.parentWin ? me.parentWin.title : null);
            }
            me.progressTracker = new Ext.ux.acProgressWin(progressConfig);
        } 
        if (me.download) {
            framename = 'download';
            me.iframe = new Ext.ux.IFrame({
                hidden: true,
                frameName: 'iframe-' + framename,
                listeners: { load: function(){ 
                    if (me.progressTracker){
                        Ext.getCmp(me.progressTracker.getId()).allDone();
                        var timeoutfn = "Ext.getCmp('" + me.progressTracker.getId() + "').showError('"+me.progressTracker.title +" Failed!');";
                        setTimeout(timeoutfn, 1500);
                    }
                }}
            });            

            me.iframe.render(Ext.getBody());
            me.standardSubmit = true;
            me.target = me.iframe.frameName;
        }

        me.buttons = [{
            text: me.submitButtonText,
            formBind: true,
            handler: me.submit,
            scope: me
        },{
            text: 'Cancel',
            handler: me.cancel,
            scope: me
        }];

        me.on('afterrender', me.show, me);
        me.on('afterrender', me.doneSetup, me);

        me.callParent();
    },

    initItems: function(){
        var me = this;

        me.callParent();

        if (me.progress){
            me.add({
                xtype: 'hidden',
                name: 'progress_id',
                value: me.progressTracker.getFilename()
            });
        }
    },

    reset: function(){
        var me = this;

        me.getForm().reset(true);
        me.autoLoaded = false;
    },

    disableAndHide: function(items, disable){
        var me = this;

        if (typeof items == 'string'){
            items = me.query(items);
        }
        if (!Ext.isArray(items)) items = [items];

        Ext.Array.forEach(items, function(e){
            e.setDisabled(disable);
            e.setVisible(!disable);

            if (e.isFormField){
                e.fireEvent('validitychange', e, e.isValid);
            } else {
                Ext.Array.forEach(e.query('field'), function(g){
                    g.fireEvent('validitychange', g, g.isValid);
                });
            }
        });
    },

    show: function(){
        var me = this;

        me.callParent();

        if (me.preValidate){
            Ext.Array.forEach(me.query('component[allowBlank=false]'), function(f){ f.validate(); });
        }

        Ext.Array.forEach(me.query('component[visibleIf]'), function(e){
            var defaultVisibleConfig = { compareValue: 1, compareType: '==', onlyDisable: false };
            var itemid = (Ext.isString(e.visibleIf) ? e.visibleIf : e.visibleIf.itemId);

            if (compareComp = me.down('#' + itemid)){
                e.visiblechangefn = function(f,newval){

                    var visible = null;
                    var visibleConfig = this.visibleIf;
                    if (Ext.isString(visibleConfig)) {
                        visibleConfig = { itemId: visibleConfig }
                    }
                    var vc = {};
                    vc = Ext.merge(vc, defaultVisibleConfig, visibleConfig);

                    var val = newval;
                    var test = vc.compareValue;
                    var comptype = vc.compareType;
                    if (comptype == 'equal' || comptype == '=='){
                        visible = (val == test);
                    } else if (comptype == 'gt' || comptype == '>'){
                        visible = (val > test);
                    } else if (comptype == 'gte' || comptype == '>='){
                        visible = (val >= test);
                    } else if (comptype == 'lt' || comptype == '<'){
                        visible = (val < test);
                    } else if (comptype == 'lte' || comptype == '<='){
                        visible = (val <= test);
                    } else if (comptype == 'notequal' || comptype == '!='){
                        visible = (val != test);
                    } else if (comptype == 'eequal' || comptype == '==='){
                        visible = (val === test);
                    }

                    this.setDisabled(!visible);
                    if (!vc.onlyDisable){
                        this.setVisible(visible);
                    }

                    if (this.isFormField){
                        this.fireEvent('validitychange', this, this.isValid)
                    } else {
                        Ext.Array.forEach(this.query('field'), function(g){
                            g.fireEvent('validitychange', this, g.isValid)
                        });
                    }
                };

                e.mon(compareComp, 'change', e.visiblechangefn, e);
                var task = new Ext.util.DelayedTask(e.visiblechangefn, e, [ e, compareComp.getValue() ]);
                task.delay(0); //this is weird. but Ext.Function.bind didn't work.
            }
            
        });

        if (me.autoLoadUrl && !me.autoLoaded){
            me.setDisabled(true);
            me.reset();
            me.load({
              url: me.autoLoadUrl,
              failure: function (form, action){
                Ext.Msg.alert("Load Failed", "Could not load info for editing");
                me.setDisabled(false);
                me.hideParentWin();
              },
              success: function (form, action){
                me.setDisabled(false);
                me.autoLoaded = true;

                obj = Ext.JSON.decode(action.response.responseText);
                me.formLoad(obj.data);
              }
            });
        } else if (me.loadRawData) {
            me.getForm().setValues(me.loadRawData);
            me.formLoad(me.loadRawData);
        } else if (!me.autoLoadUrl){
            me.setInitialFocus();
        }
    },

    setInitialFocus: function(){
        var me = this;

        var focusfield = me.down('component[initialFocus]:not(hiddenfield):not([hidden]):not([disabled])');
        if (focusfield) {
            var task = new Ext.util.DelayedTask(function(){
                focusfield.focus(true);
            });
            task.delay(200);
        }
    },

    cancel: function(){
        this.reset();
        this.closeParentWin();
    },

    finishUploadSubmit: function(e){
        var me = this;

        if (me.waitingMsg){
            me.waitingMsg.hide();
            me.waitingMsg = null;
        }

        me.submit();
    },

    finishUploadFail: function(e){
        var me = this;

        me.mun(e, 'uploadsuccess', me.finishUploadSubmit, me);

        if (me.waitingMsg){
            me.waitingMsg.hide();
            me.waitingMsg = null;
        }
    },

    submit: function(){
        var me = this;

        var form_cfg = { submitEmptyText: false };

        //check to see if there are active file uploads
        var incomplete = me.query('acuploadfile[isActive]');
        if (incomplete.length){
            Ext.Array.forEach(incomplete, function(e){
                console.log('found an active upload!!!');
                me.mon(e, 'uploadsuccess', me.finishUploadSubmit, me);
                me.mon(e, 'uploadfail', me.finishUploadFail, me);

                me.waitingMsg = Ext.MessageBox.wait('Waiting for uploads to finish...', 'Please Wait');
            }, me);

            return false;
        }

        //force submitting to the iframe
        if (me.progressTracker){
            me.progressTracker.trigger();
        }
        if (me.download && me.iframe){
            form_cfg.target = me.iframe.frameName;
        } 

        //set the success and failure handlers
        if (me.waitMsg){
            form_cfg.waitTitle = 'Please Wait',
            form_cfg.waitMsg = me.waitMsg
        }
        if (me.params){
            form_cfg.params = me.params;
        }
        form_cfg.success = function(form,action){ me.formBaseSuccess(form,action); },
        form_cfg.failure = function(form,action){ me.formBaseFailure(form,action); },

        me.hideParentWin();
        me.callParent([form_cfg]);
    },

    //custom logic called with afterrender handler
    doneSetup: Ext.emptyFn,

    //custom logic called when form is loaded from a remote url
    formLoad: Ext.emptyFn,

    //custom logic called when form is submitted
    formSubmit: Ext.emptyFn,

    //custom logic called when success is given
    formSuccess: Ext.emptyFn,

    formBaseSuccess: function(form,action){
        var me = this;

        if (me.progressTracker) me.progressTracker.allDone();
        me.closeParentWin();

        obj = Ext.JSON.decode(action.response.responseText);
        me.formSuccess(form,action,obj);
    },

    //custom logic called when error is explicitly provided by server
    // formFailure(form,action,response)
    formFailure: function(errors){
        var me = this, redisplay;

        redisplay = (this.parentWin ? this.parentWin.showOnError : false);
        if (errors.reason){
            me.formErrorMessage(errors.reason, redisplay);
        } else if (redisplay) {
            me.showParentWin();
        } else {
            me.reset();
        }
    },

    //callback for form failure, includes server and network erros
    formBaseFailure: function(form,action){
        var me = this, errorobj, response;

        if (me.progressTracker) me.progressTracker.allDone();

        if (action.response)
        {
            response = Ext.JSON.decode(action.response.responseText);
            if (response && response.errors){
                errorobj = response.errors;
            } else {
                errorobj = { reason: me.formErrorMessageText || 'Could not submit form' };
            }
            me.formFailure(errorobj);
        }
        else
        {
            me.formErrorMessage('Form failed client-side validation. Check inputs and try again.', true);
        }
    },


    formErrorMessage: function(msg,redisplay){
        var me = this, handler;

        if (!redisplay){
            me.reset();
        }

        Ext.Msg.show({
          closable:false, 
          fn: (redisplay ? me.showParentWin : null),
          scope: me,
          modal: true,
          title: 'Oops',
          icon: Ext.MessageBox.ERROR,
          buttons: Ext.MessageBox.OK,
          msg: msg
        });              
    },

    closeParentWin: function(){
        if (this.parentWin) this.parentWin.close();
    },        

    hideParentWin: function(){
        if (this.parentWin) this.parentWin.hide();
    },

    showParentWin: function(){
        if (this.parentWin) this.parentWin.show();  
    },

    setValues: function(values){
        return this.getForm().setValues(values);
    }

});

Ext.define('Ext.ux.acFormWindow',{
    extend: 'Ext.window.Window',

    alias: 'widget.acformwin',

    //general settings
    showOnError: true, //when form submitted...

    //form info
    formConfig: null,
    defaultFormConfig: null,
    form: null,

    //override / set defaults
    closable: true,
    border: false,
    modal: true,
    resizable: false,
    closeAction: 'hide',
    overflowY: 'auto',
    layout: {
        type: 'auto',
        reserveScrollbar: true
    },
    autoResize: true,
    constrain: true,
    relHeight: 50,

    initComponent: function(cfg){
        var me = this;

        me.on('afterrender', me.doneSetup, me);

        me.callParent();
    },

    initItems: function(){
        var me = this;
        
        me.callParent();

        var formConfig = me.formConfig || {};
        var defaultFormConfig = me.defaultFormConfig || {};
        var fc = {};
        formConfig = Ext.merge(fc, defaultFormConfig, formConfig);
        fc.parentWin = me;
        fc.border = false;

        me.form = new Ext.ux.acForm(fc);
        me.add(me.form);
    },

    getForm: function(){
        return this.form;
    },

    setValues: function(values){
        return this.getForm().setValues(values);
    },

    show: function(){
        this.callParent();
        this.doAutoResize();
    },

    submit: function(){
        this.getForm().submit();
    },

    reset: function(){
        this.getForm().reset();
    },

    doAutoResize: function(){
        var me = this;

        var minsize = me.minHeight || 400;
        var relheight = me.relHeight || 250;

        var formheight = me.getForm().getHeight();
        var winheight = me.getHeight();
        var layoutheight = me.getLayout().getTarget().el.getHeight();
        var chromeheight =  winheight - layoutheight;

        me.setHeight(Math.max(minsize, Math.min(formheight + chromeheight, window.innerHeight - relheight)));
        me.center();
    },

    //custom logic called with afterrender handler
    doneSetup: Ext.emptyFn,

});


Ext.define('Ext.ux.acToggleButtons', {
    extend:'Ext.form.FieldContainer',
    mixins: {
        field: 'Ext.form.field.Field'
    },

    alias: 'widget.acbuttongroup',

    fieldType: null,
    vertical: false,
    defaults: { 
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        cls: 'buttongroup-middle',
        flex: 1,
        getValue: function(){
            return (this.value ? this.value : this.text);
        }
    },
    
    initComponent: function(){
        var me = this;

        me.layout = (me.vertical ? { type: 'vbox', align: 'stretch' } : 'hbox');

        Ext.Array.forEach(me.items, function(b,idx,arr){ 
            //convert array of strings into objects
            if (!b.value && typeof b == 'string') me.items[idx] = { value: b, text: b };
            //convert items with no text into their values
            else if (b.value && !b.text) b.text = b.value;
        });

        me.callParent();
        me.initField();

        if (me.vertical){
            Ext.Array.forEach(me.query('button'), function(b){ b.removeCls(me.defaults.cls).addCls('buttongroup-vmiddle'); });
            me.down('button:first').removeCls('buttongroup-vmiddle').addCls('buttongroup-top');
            me.down('button:last').removeCls('buttongroup-vmiddle').addCls('buttongroup-bottom');
        } else {
            me.down('button:first').removeCls(me.defaults.cls).addCls('buttongroup-first');
            me.down('button:last').removeCls(me.defaults.cls).addCls('buttongroup-last');
        }
    },

    initValue: function() {
        var me = this,
            valueCfg = me.value;
        me.originalValue = me.lastValue = valueCfg || me.getValue();
        if (valueCfg) {
            me.setValue(valueCfg);
        }
    },

    onAdd: function(item) {
        var me = this;

        if (item.getXType() == 'button') {
            me.mon(item, 'toggle', me.checkChange, me);
            item.toggleGroup = 'actoggle-'+me.name;
        }
        me.callParent(arguments);
    },

    onRemove: function(item) {
        var me = this;

        if (item.getXType() == 'button') {
            me.mun(item, 'change', me.checkChange, me);
        }
        me.callParent(arguments);
    },

    getButtons: function(query){
        return this.query('button' + (query || ''));
    },

    getSelectedButton: function(){
        return this.down('button[pressed]');
    },

    setValue: function(value) {
        var me = this;

        Ext.Array.forEach(me.query('button'), function(b){
            b.toggle(value == b.getValue());
        });

        return this;
    },

    getValue: function(){
        var me = this;

        var selBtn = me.getSelectedButton();
        if (selBtn){
            return selBtn.getValue();
        } else {
            return null;
        }
    }

});
