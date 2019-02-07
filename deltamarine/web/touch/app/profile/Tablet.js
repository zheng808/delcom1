Ext.define('Delta.profile.Tablet', {
    extend: 'Ext.app.Profile',

    config: {
        name: 'Tablet'
    },

    isActive: function() {
        return (Ext.os.deviceType == 'Tablet');
    }
});
