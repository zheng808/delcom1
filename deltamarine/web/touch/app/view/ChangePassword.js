Ext.define('Delta.view.ChangePassword', {
  extend: 'Ext.Panel',
  xtype: 'changepassword',
  requires: [],

  config: {
    title: 'Change User Password',
    styleHtmlContent: true,
    items: [{
      xtype: 'container',
      width: 500,
      height: 600,
      centered: true,
      layout: 'fit',
      items: [{
        xtype: 'formpanel',
        items: [{
          xtype: 'panel',
          styleHtmlContent: true,
          height: 100,
          html: 'Enter your old password and your new password (twice, to make sure), to change your password.'
        },{
          xtype: 'fieldset',
          defaults: { labelWidth: 220 },
          items: [{
            xtype: 'passwordfield',
            name: 'oldpass',
            label: 'Old Password'
          },{
            xtype: 'passwordfield',
            name: 'newpass',
            label: 'New Password'
          },{
            xtype: 'passwordfield',
            name: 'newpass2',
            label: 'Re-Type New Password'
          }]
        },{
          xtype: 'button',
          ui: 'action',
          text: 'Change Password',
          name: 'changepass_submit'
        }]
      }]
    }]
  }
});
