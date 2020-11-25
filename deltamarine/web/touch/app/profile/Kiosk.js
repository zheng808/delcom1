Ext.define('Delta.profile.Kiosk', {
    extend: 'Ext.app.Profile',

    config: {
        name: 'Kiosk'
    },

    isActive: function() {
      return (Ext.os.deviceType == 'Desktop');
    }
});
