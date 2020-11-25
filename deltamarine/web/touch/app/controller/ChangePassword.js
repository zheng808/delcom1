Ext.define('Delta.controller.ChangePassword', {
  extend: 'Ext.app.Controller',

  config: {
    refs: {
      submitButton: 'formpanel button[name="changepass_submit"]'
    },
    control: {
      submitButton: { tap: 'submitPass' }
    }
  },

  submitPass: function(but){
    form = but.up('formpanel');
    form.submit({
      url: '/touch.php/rest/password',
      method: 'POST',
      params: { employee_id: current_employee.get('id') },
      success: function(form, result) {
        if (result && result.success && !result.error) {

          //go back to where we came from
          Ext.ComponentQuery.query('#mainnav')[0].pop();

          var newactive = Ext.ComponentQuery.query('#mainnav')[0].getActiveItem();
          var popup = Ext.create('Ext.Panel', {
            width: 400,
            height: 180,
            layout: 'fit',
            items: [{
              xtype: 'titlebar',
              ui: 'light',
              docked: 'top',
              title: 'Success'
            },{
              xtype: 'container',
              style: 'background-color: #ddd;',
              styleHtmlContent: true,
              items: [{ html: 'Your Password Was Changed!', centered: true }]
            }]
          });

          popup.showBy(Ext.getCmp('bottomtool'), 'bc-tc');
          Ext.defer(function() { popup.hide('fade'); }, 3000);

        } else {
          Ext.Msg.show({
            title: 'Error',
            message: (result.error ? 'The following error was found: <br /><br />' +result.error : 'Unknown Error. Try Again.'),
            buttons: Ext.MessageBox.OK,
            hideOnMaskTap: true
          });
        }
      },
      failure: function(form,result){
        Ext.Msg.show({
          title: 'Error',
          message: (result.error ? 'The following error was found: <br /><br />' +result.error : 'Unknown Error. Try Again.'),
          buttons: Ext.MessageBox.OK,
          hideOnMaskTap: true
        });
        fields = Ext.ComponentQuery.query('passwordfield');
        if (result.errorfields == 'new'){
          fields[1].setValue();
          fields[2].setValue();   
        } else if (result.errorfields == 'old'){
          fields[0].setValue();   
        }

      }
    });
  }

});
