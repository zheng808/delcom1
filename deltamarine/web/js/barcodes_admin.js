var barcode_default_handler = function(code, symbid){
  Ext.Msg.show({
    closable: false,
    msg: 'Looking Up Scanned Barcode...',
    title: 'Please Wait'
  })
  Ext.Ajax.request({
    url: '/part/findByBarcode',
    params: {code: code, symbid: symbid},
    callback: function(opt,success,response){
      Ext.Msg.hide();
      if (success){
        data = Ext.decode(response.responseText);
        if (data && data.success && data.parts.length > 0){
          if (data.parts && data.parts.length == 1){
            Ext.Msg.confirm(
              'Part Found', 
              'Found Part: <strong>'+data.parts[0].name+'</strong><br /><br />Do you want to see part info?',
              function(btn){
                if (btn == 'yes'){
                  location.href = '/part/view/id/'+data.parts[0].id;
                }
              }
            );
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

Ext.onReady(function(){

  barcodeListener.handleroverride = barcode_default_handler;

  barcodeListener.misshandleroverride = function(data,code,symbid){
    Ext.Msg.alert('Not Found', 'Scanned Barcode Not Found!');
  };

});
