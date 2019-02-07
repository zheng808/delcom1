Ext.define('Delta.controller.Login', {
  extend: 'Ext.app.Controller',

  config: {
    refs: {
      filterButton: '#filterloginlist button',
      loginButtons: 'loginlist button'
    },
    control: {
      filterButton: { tap: 'filterLoginList' },
      loginButtons: { tap: 'showPasswordBox' }
    }

  },

  showPasswordBox: function(but) {
    employee_id = but.parent.getRecord().data.id;
    Ext.Msg.show({
      title: 'Enter your Password', 
      buttons: [
          {text: 'Cancel', itemId: 'cancel'},
          {text: 'OK',     itemId: 'ok',  ui : 'action'}
      ],
      hideOnMaskTap: true,
      prompt: {
        xtype: 'passwordfield',
        name: 'password',
        focus: true,
        listeners: {
          'action': function(f,e,eOpts){
            //this forces the equivalent of a press of the OK button when enter key is pressed
            var but_conf = f.getParent().getButtons()[1];
            var scope = but_conf.scope;
            var handler = scope[but_conf.handler];

            e.preventDefault();

            var but = f.getParent().down('#ok');
            handler.apply(scope, [but,e,eOpts]);
          }
        }
      },
      fn: function(but,pass,conf){
        if (but == 'ok'){
          Ext.Ajax.request({
            url: '/touch.php/general/login',
            params: {
              id: employee_id,
              pass: pass,
              checkonly: 1
            },
            success: function(response){
              var text = Ext.JSON.decode(response.responseText);
              if (text.success) {
                var Emp = Ext.ModelMgr.getModel('Delta.model.Employee');
                Emp.load(text.empid, {
                  success: function(mod){
                    current_employee = mod; 
                    view = Ext.create('Delta.view.Home');
                    Ext.Viewport.add(view);
                    Ext.Viewport.setActiveItem(view);
                  },
                  failure: function(){
                    Ext.Msg.show({
                     title: 'Error', 
                     message: 'Error Setting active Employee. Try again.',
                     buttons: Ext.MessageBox.OK,
                     hideOnMaskTap: true
                    });
                  }
                });
              } else {
                Ext.Msg.show({
                  title: 'Error', 
                  message: (text.error ? text.error : 'Unknown Error'),
                  buttons: Ext.MessageBox.OK,
                  hideOnMaskTap: true
                });
              }
        
            },
            failure: function(){
              Ext.Msg.alert('Error','Could not log in. Try again in a moment.');
            }
          }); 
        }
      }
    });
    Ext.Msg.down('passwordfield').element.query('input')[0].focus();
  },

  filterLoginList: function(but){
    store = Ext.StoreManager.get('Employee');
    if (but.initialConfig.text == 'Employees'){
      store.clearFilter();
      store.filter([{property: 'contractor', value: false}]);
    } else if (but.initialConfig.text == 'Contractors'){
      store.clearFilter();
      store.filter([{property: 'contractor', value: true}]);
    }
    if (store.getCount() == 0){
    }
  }

});
