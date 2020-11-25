
barcode_default_handler = function(code, symbid){
  //check for not-logged in
  if (!current_employee){
    Ext.Msg.alert('Error', 'Must Log In before Scanning');
    return;
  }

  //check for on parts page -- fill in parts thingo.
  Ext.Ajax.request({
    url: '/touch.php/rest/part',
    method: 'GET',
    params: {code: code, symbid: symbid},
    callback: function(opt,success,response){
      if (success){
        data = Ext.decode(response.responseText);
        if (data && data.success && data.parts.length > 0){
          if (data.parts && data.parts.length == 1){
            //SINGLE PART FOUND

            //check for not on parts page
            var mainnav = Ext.ComponentQuery.query('#mainnav')[0];
            if (mainnav.getActiveItem().xtype == 'partadd'){
              var foundfield = Ext.ComponentQuery.query('partadd partfield')[0];
              foundfield.setValue(data.parts[0].part_variant_id);
              var highlightAnim = new Ext.Anim({
                type: 'highlight',
                before: function(el) {
                  var fromColor = '#aaccaa';
                  var toColor = el.getStyle('background-color') ? el.getStyle('background-color') : '#ffffff';
                  this.from = {'background-color': fromColor };
                  this.to = {'background-color': toColor };
                }
              });
              Ext.Anim.run(foundfield, highlightAnim, { duration: 1500 });
            } else {
              Ext.Msg.confirm(
                'Part Found', 
                'Found Part: <strong>'+data.parts[0].name+'</strong><br /><br />Do you want to add it to a workorder?',
                function(btn){
                  if (btn == 'yes'){
                    mainnav.push(Ext.create('Delta.view.part.Add', {
                      defaultPart: data.parts[0].part_variant_id
                    }));
                    Ext.defer(function() { Ext.Anim.run(Ext.ComponentQuery.query('partadd partfield')[0], highlightAnim, { duration: 1500 }); }, 500);
                  }
                }
              );
            }
          } else {
            Ext.Msg.alert('Mulitple Parts Found', 'Error: matched multiple parts!');
          }
        } else if (barcodeListener.misshandleroverride) {
          barcodeListener.misshandleroverride(data, code, symbid);
        }
      }
    }
  });
};


if (window.addEventListener) {
    window.addEventListener('load', function() {
      barcodeListener.handleroverride = barcode_default_handler;
      barcodeListener.misshandleroverride = function(data,code,symbid){
        Ext.Msg.alert('Not Found', 'Scanned Barcode Not Found!');
      }
    }, false);
} else if (window.attachEvent) {
    window.attachEvent('onload', function() {
      barcodeListener.handleroverride = barcode_default_handler;
      barcodeListener.misshandleroverride = function(data,code,symbid){
        Ext.Msg.alert('Not Found', 'Scanned Barcode Not Found!');
      }
    });
}
