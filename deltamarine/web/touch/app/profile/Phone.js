Ext.define('Delta.profile.Phone', {
    extend: 'Ext.app.Profile',

    config: {
        name: 'Phone'
    },

    isActive: function() {
        return (Ext.os.deviceType == 'Phone');
    }
});
