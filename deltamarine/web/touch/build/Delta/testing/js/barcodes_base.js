/*
 *  Acunote Shortcuts.
 *  Javascript keyboard shortcuts mini-framework.
 *
 *  Copyright (c) 2007-2008 Pluron, Inc.
 *
 *  Permission is hereby granted, free of charge, to any person obtaining
 *  a copy of this software and associated documentation files (the
 *  "Software"), to deal in the Software without restriction, including
 *  without limitation the rights to use, copy, modify, merge, publish,
 *  distribute, sublicense, and/or sell copies of the Software, and to
 *  permit persons to whom the Software is furnished to do so, subject to
 *  the following conditions:
 *
 *  The above copyright notice and this permission notice shall be
 *  included in all copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 *  EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 *  MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 *  NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 *  LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 *  OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 *  WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

barcodeListener = {

    listen: true,
    parse_symbology: true,
    symbologies: [ 'A', 'E0', 'FF', 'F', 'B1', 'B2', 'B3', ']e0' ], 

    keys: {
        KEY_BACKSPACE: 8,
        KEY_TAB:       9,
        KEY_ENTER:    13,
        KEY_SHIFT:    16,
        KEY_CTRL:     17,
        KEY_ALT:      18,
        KEY_ESC:      27,
        KEY_SPACE:    32,
        KEY_LEFT:     37,
        KEY_UP:       38,
        KEY_RIGHT:    39,
        KEY_DOWN:     40,
        KEY_DELETE:   46,
        KEY_HOME:     36,
        KEY_END:      35,
        KEY_PAGEUP:   33,
        KEY_PAGEDOWN: 34
    },

    prefix: ['!','!'],
    suffix: 91,

    savedinput: '',
    parsedinput: '',
    lastKeypress: 0,
    clearTimeout: 2000,
    eventtarget: '',

    handler: function(code, symbid) {
      alert('Barcode scans have no effect on this page.\n\nBarcode scanned: ' + code + (symbid ? ' (symbology '+symbid+')' : ''));
    },

    handleroverride: null,
    misshandleroverride: null,

    callHandler: function() {
      var symbid = '';
      var code = barcodeListener.parsedinput;

      for (var symindex in barcodeListener.symbologies) {
        var sym = barcodeListener.symbologies[symindex];
        if (code.substr(0, sym.length) == sym)
        {
          symbid = sym;
          code = code.slice(sym.length);
          break;
        }
      }
      barcodeListener.clearCombination(false);
      if (typeof(barcodeListener.handleroverride) == "function") {
        barcodeListener.handleroverride(code,symbid);
      } else {
        barcodeListener.handler(code, symbid);
      }
    },

    init: function() {
        this.setObserver();
    },

    getInputTarget: function(e) {
      if (barcodeListener.isInputTarget(e))
      {
        return (e.target || e.srcElement);
      }

      return '';    
    },

    isInputTarget: function(e) {
        var target = e.target || e.srcElement;
        if (target && target.nodeName) {
            var targetNodeName = target.nodeName.toLowerCase();
            if (targetNodeName == "textarea" || targetNodeName == "select" ||
                (targetNodeName == "input" && target.type &&
                    (target.type.toLowerCase() == "text" ||
                         target.type.toLowerCase() == "password"))
                             )  {
                return true;
            }
        }
        return false;
    },

    stopEvent: function(event) {
        if (event.preventDefault) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            event.returnValue = false;
            event.cancelBubble = true;
        }
    },


    // shortcut notification/status area
    createStatusArea: function() {
        var area = document.createElement('div');
        area.setAttribute('id', 'shortcut_status');
        area.style.display = 'none';
        area.style.position = 'absolute';
        area.style.right = '300px';
        document.getElementById('container').appendChild(area);
    },

    // This method creates event observer for the whole document
    // This is the common way of setting event observer that works 
    // in all modern brwosers with "keypress" fix for
    // Konqueror/Safari/KHTML borrowed from Prototype.js
    setObserver: function() {
        name = 'keypress'
        if (document.addEventListener) {
            document.addEventListener(name, function(e) {barcodeListener.keyCollector(e)}, false);
        } else if (document.attachEvent) {
            document.attachEvent('on'+name, function(e) {barcodeListener.keyCollector(e)});
        }
    },

    // Key press collector. Collects all keypresses into combination 
    // and checks it we have action for it
    keyCollector: function(e) {
        // do not listen if listener was explicitly turned off
        if (!barcodeListener.listen) return false;

        var keyCode = e.keyCode;
        // get letter pressed for different browsers
        var code = e.which ? e.which : e.keyCode
        if (barcodeListener.process(code)){
          barcodeListener.stopEvent(e);
          barcodeListener.eventtarget = barcodeListener.getInputTarget(e);
        }
    },

    // process keys
    process: function(code) {
        var letter = String.fromCharCode(code);

        if (!barcodeListener.listen) return false;
        if (code == barcodeListener.suffix)
        {
          if (barcodeListener.parsedinput.length > 0) {
              barcodeListener.callHandler(); //success!
              return true;  //stop propogation
          } else {
              //output any input collected so far, plus current char
              barcodeListener.clearCombination(true);
              return false;
          }
        }

        //check to see if we're still looking for prefix char(s)
        if (barcodeListener.savedinput.length < barcodeListener.prefix.length) {
          if (letter == barcodeListener.prefix[barcodeListener.savedinput.length]) {
            barcodeListener.savedinput = barcodeListener.savedinput + letter;
          } else {
            //output any input collected so far, plus current char
            barcodeListener.clearCombination(true);
            return false;
          }
        } else{
          barcodeListener.savedinput = barcodeListener.savedinput + letter;
          barcodeListener.parsedinput = barcodeListener.parsedinput + letter;
        }

        // save last keypress timestamp (for autoclear)
        var d = new Date;
        barcodeListener.lastKeypress = d.getTime();
        // autoclear combination in 1 seconds
        setTimeout("barcodeListener.clearCombinationOnTimeout()", barcodeListener.clearTimeout);

        return true;
    },

    // clear combination
    clearCombination: function(output) {
        //output saved input
        if (output && barcodeListener.eventtarget != '' && barcodeListener.savedinput != ''){
          barcodeListener.eventtarget.value = barcodeListener.eventtarget.value + barcodeListener.savedinput;
        }

        barcodeListener.savedinput = '';
        barcodeListener.parsedinput = '';
        barcodeListener.eventtarget = '';
    },

    clearCombinationOnTimeout: function() {
        if (barcodeListener.savedinput != ''){
            var d = new Date;
            // check if last keypress was earlier than (now - clearTimeout)
            // 100ms here is used just to be sure that this will work in superfast browsers :)
            if ((d.getTime() - barcodeListener.lastKeypress) >= (barcodeListener.clearTimeout - 100)) {
                barcodeListener.clearCombination(true);
            }
        }
    }
}

if (window.addEventListener) {
    window.addEventListener('load', function() {barcodeListener.init(); }, false);
} else if (window.attachEvent) {
    window.attachEvent('onload', function() {barcodeListener.init(); });
}

barcode_replace_field = null;

barcode_prefocus_handler = null;

barcode_focus = function(element) {
  barcode_replace_field = element;
  barcode_prefocus_handler = barcodeListener.handleroverride;
  barcodeListener.handleroverride = function(code, symbid){
    if (barcode_replace_field.el){
      barcode_replace_field.setValue(code);
      barcode_replace_field.el.blur();
    } else if (barbode_replace_field) {
      barcode_replace_field.value = code;
      barcode_replace_field.blur();
    }
  }
}

barcode_blur = function(){
  barcode_replace_field = null;
  barcodeListener.handleroverride = barcode_prefocus_handler;
}

