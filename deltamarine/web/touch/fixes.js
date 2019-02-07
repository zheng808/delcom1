Ext.feature.registerTest({
  Touch:function(){
    return this.isEventSupported('touchstart') && !(Ext.os && Ext.os.name.match(/MacOS|Linux/) && !Ext.os.is.BlackBerry6);
  }
});

Ext.define('Ext.ux.touch.fixes.event.Tap', {
  override:'Ext.event.recognizer.Tap',

  onTouchStart: function(e) {
    this.startPoint = e.touch.point;
  },

  onTouchMove: function(e) {
    var point = e.touch.point;
    if (Math.abs(this.startPoint.x - point.x) > 20 || Math.abs(this.startPoint.y - point.y) > 20){
      this.fire('tapcancel', e, [e.changedTouches[0]]);
      return this.callOverridden(arguments);
    }
  }
});
