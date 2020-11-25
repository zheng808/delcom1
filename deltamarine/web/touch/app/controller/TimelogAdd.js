Ext.define('Delta.controller.TimelogAdd', {
  extend: 'Ext.app.Controller',

  config: {

    refs: {
      submitButton: 'formpanel button[name="addtimelog_submit"]'
    },
    control: {
      submitButton: { tap: 'submitTimelog' }
    }
  },

  submitTimelog: function(but){
    var pan = but.up('timelogadd');
    var form = but.up('formpanel');
    var values = form.getValues();

    if (pan.config.existingRecord){
      tl = pan.config.existingRecord;
    } else {
      tl = Ext.create('Delta.model.Timelog');
    }
    tl.setData(values); 
    tl.set('employee_id', current_employee.get('id'));
    tl.set('generated', '1');
    tl.save({
      success: function(mod,op){
        result = Ext.JSON.decode(op.getResponse().responseText);
        if (result && result.success && !result.errors) {
          var showActionsPanel = (!pan.config.existingRecord && tl.get('billable') == 1);

          //make sure forms are not-repopulated
          pan.config.existingRecord = null; 
          pan.config.defaultWo = null;
          pan.config.defaultWoi = null;
          
          //go back to where we came from
          Ext.ComponentQuery.query('#mainnav')[0].pop();

          //refresh the store if needed
          var formatteddate = Ext.Date.format(mod.data.end_date, 'Y-m-d');
          if (st = Ext.StoreMgr.get('timelog-day-' + formatteddate)){
            st.load();
          }

          if (showActionsPanel){ 
            var newactive = Ext.ComponentQuery.query('#mainnav')[0].getActiveItem();
            var popup = Ext.create('Ext.Panel', {
              width: 600,
              height: 180,
              layout: 'vbox',
              items: [{ 
                xtype: 'titlebar',
                ui: 'light',
                docked: 'top',
                height: 40,
                title: 'Timelog Added! What Next...',
                items: [{
                  xtype: 'button',
                  ui: 'decline',
                  text: 'Close',
                  height: 20,
                  align: 'right',
                  handler: function(){ this.up('panel').destroy(); }
                }]
              },{
                flex: 1,
                xtype: 'container',
                layout: { type: 'hbox', pack: 'right', align: 'center' },
                style: 'background-color: #ddd;',
                items: [{
                  xtype: 'container', width: 200, styleHtmlContent: true, html: 'Add Another Timelog:', margin: '0 0 0 10' 
                },{
                  xtype: 'segmentedbutton',
                  flex: 1,
                  allowToggle: false,
                  defaults: { height: 25, ui: 'action' },
                  items: [{ 
                    text: 'Same Task',
                    handler: function(){
                      Ext.ComponentQuery.query('#mainnav')[0].push(Ext.create('Delta.view.timelog.Add', {
                        defaultWo: tl.get('workorder_id'),
                        defaultWoi: tl.get('task_id')
                      }));
                      this.up('panel').destroy();
                    }
                  },{
                    text: 'Same Workorder',
                    handler: function(){
                      Ext.ComponentQuery.query('#mainnav')[0].push(Ext.create('Delta.view.timelog.Add', {
                        defaultWo: tl.get('workorder_id')
                      }));
                      this.up('panel').destroy();
                    }
                  },{
                    text: 'Blank',
                    handler: function(){
                      Ext.ComponentQuery.query('#mainnav')[0].push(Ext.create('Delta.view.timelog.Add', {}));
                      this.up('panel').destroy();
                    }
                  }]
                }]
              },{
                flex: 1,
                xtype: 'container',
                style: 'background-color: #ddd;',
                layout: { type: 'hbox', pack: 'right', align: 'center' },
                items: [{
                  xtype: 'container', width: 200, styleHtmlContent: true, html: 'Add A Part:', margin: '0 0 0 10'
                },{
                  flex: 1,
                  xtype: 'segmentedbutton',
                  allowToggle: false,
                  defaults: { height: 25, ui: 'action' },
                  items: [{   
                    text: 'Same Task',
                    handler: function(){
                      Ext.ComponentQuery.query('#mainnav')[0].push(Ext.create('Delta.view.part.Add', {
                        defaultWo: tl.get('workorder_id'),
                        defaultWoi: tl.get('task_id')
                      }));
                      this.up('panel').destroy();
                    }

                  },{
                    text: 'Same Workorder',
                    handler: function(){
                      Ext.ComponentQuery.query('#mainnav')[0].push(Ext.create('Delta.view.part.Add', {
                        defaultWo: tl.get('workorder_id')
                      }));
                      this.up('panel').destroy();
                    }
                  },{
                    text: 'Blank',
                    handler: function(){
                      Ext.ComponentQuery.query('#mainnav')[0].push(Ext.create('Delta.view.part.Add', {}));
                      this.up('panel').destroy();
                    }
                  }]
                }] 
              }]
            });

            popup.showBy(Ext.getCmp('bottomtool'), 'bc-tc');
            Ext.defer(function() { popup.hide('fade'); }, 10000);

          }
        } else {
          Ext.Msg.show({
            title: 'Error',
            message: (result.errors ? 'The following error(s) were found: <br /><br />' +result.errors : 'Unknown Error. Try Again.'),
            buttons: Ext.MessageBox.OK,
            hideOnMaskTap: true
          });
        }
      }, 
      failure: function(mod,op){
        Ext.Msg.alert('Error', 'Could not save timelog. Try again later.');
      }
    });
    return false; //prevent from firing twice
  }

});


