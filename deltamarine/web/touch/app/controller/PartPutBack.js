Ext.define('Delta.controller.PartPutBack', {
  extend: 'Ext.app.Controller',

  config: {

    refs: {
      submitButton: 'formpanel button[name="putbackpart_submit"]'
    },
    control: {
      submitButton: { tap: 'putbackPart' }
    }
  },

  putbackPart: function(but){
    var pan = but.up('partputback');
    var form = but.up('formpanel');
    var values = form.getValues();

    var confirmMsg = '';
    if (pan.config.existingRecord.data.quantity == 1){
      confirmMsg = 'Are you sure you want to put back this part?';
    } else if (pan.config.existingRecord.data.quantity == values.quantity) {
      confirmMsg = 'Are you sure you want to put back the entire quantity of this part?';
    } else {
      confirmMsg = 'Are you sure you want to put back the selected quantity of this part?';
    }

    Ext.Msg.confirm('Put Back Part', confirmMsg, function(butid){
      if (butid == 'yes'){
        Ext.Ajax.request({
          url: '/touch.php/rest/partputback/' + values.id,
          method: 'POST',
          params: values,

          success: function(response){
            result = Ext.JSON.decode(response.responseText);
            if (result && result.success && !result.errors) {

              //go back to where we came from
              pan.config.existingRecord = null; //prevent form fields from reloading
              Ext.ComponentQuery.query('#mainnav')[0].pop();

              //refresh the store if needed
              if (result.date_string) {
                if (st = Ext.StoreMgr.get('part-day-' + result.date_string)){
                  st.load();
                }
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
          failure: function(response){
            Ext.Msg.alert('Error', 'Could not save part. Try again later.');
          }
        });
      }
    });
    return false; //prevent from firing twice
  }

});


