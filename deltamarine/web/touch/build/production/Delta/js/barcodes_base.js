function _84a5fef780ea5fbf689901672971fa34669cac3e(){};barcodeListener={listen:true,parse_symbology:true,symbologies:["A","E0","FF","F","B1","B2","B3","]e0"],keys:{KEY_BACKSPACE:8,KEY_TAB:9,KEY_ENTER:13,KEY_SHIFT:16,KEY_CTRL:17,KEY_ALT:18,KEY_ESC:27,KEY_SPACE:32,KEY_LEFT:37,KEY_UP:38,KEY_RIGHT:39,KEY_DOWN:40,KEY_DELETE:46,KEY_HOME:36,KEY_END:35,KEY_PAGEUP:33,KEY_PAGEDOWN:34},prefix:["!","!"],suffix:91,savedinput:"",parsedinput:"",lastKeypress:0,clearTimeout:2000,eventtarget:"",handler:function(b,a){alert("Barcode scans have no effect on this page.\n\nBarcode scanned: "+b+(a?" (symbology "+a+")":""))},handleroverride:null,misshandleroverride:null,callHandler:function(){var b="";var c=barcodeListener.parsedinput;for(var d in barcodeListener.symbologies){var a=barcodeListener.symbologies[d];if(c.substr(0,a.length)==a){b=a;c=c.slice(a.length);break}}barcodeListener.clearCombination(false);if(typeof(barcodeListener.handleroverride)=="function"){barcodeListener.handleroverride(c,b)}else{barcodeListener.handler(c,b)}},init:function(){this.setObserver()},getInputTarget:function(a){if(barcodeListener.isInputTarget(a)){return(a.target||a.srcElement)}return""},isInputTarget:function(c){var b=c.target||c.srcElement;if(b&&b.nodeName){var a=b.nodeName.toLowerCase();if(a=="textarea"||a=="select"||(a=="input"&&b.type&&(b.type.toLowerCase()=="text"||b.type.toLowerCase()=="password"))){return true}}return false},stopEvent:function(a){if(a.preventDefault){a.preventDefault();a.stopPropagation()}else{a.returnValue=false;a.cancelBubble=true}},createStatusArea:function(){var a=document.createElement("div");a.setAttribute("id","shortcut_status");a.style.display="none";a.style.position="absolute";a.style.right="300px";document.getElementById("container").appendChild(a)},setObserver:function(){name="keypress";if(document.addEventListener){document.addEventListener(name,function(a){barcodeListener.keyCollector(a)},false)}else{if(document.attachEvent){document.attachEvent("on"+name,function(a){barcodeListener.keyCollector(a)})}}},keyCollector:function(c){if(!barcodeListener.listen){return false}var b=c.keyCode;var a=c.which?c.which:c.keyCode;if(barcodeListener.process(a)){barcodeListener.stopEvent(c);barcodeListener.eventtarget=barcodeListener.getInputTarget(c)}},process:function(b){var a=String.fromCharCode(b);if(!barcodeListener.listen){return false}if(b==barcodeListener.suffix){if(barcodeListener.parsedinput.length>0){barcodeListener.callHandler();return true}else{barcodeListener.clearCombination(true);return false}}if(barcodeListener.savedinput.length<barcodeListener.prefix.length){if(a==barcodeListener.prefix[barcodeListener.savedinput.length]){barcodeListener.savedinput=barcodeListener.savedinput+a}else{barcodeListener.clearCombination(true);return false}}else{barcodeListener.savedinput=barcodeListener.savedinput+a;barcodeListener.parsedinput=barcodeListener.parsedinput+a}var c=new Date;barcodeListener.lastKeypress=c.getTime();setTimeout("barcodeListener.clearCombinationOnTimeout()",barcodeListener.clearTimeout);return true},clearCombination:function(a){if(a&&barcodeListener.eventtarget!=""&&barcodeListener.savedinput!=""){barcodeListener.eventtarget.value=barcodeListener.eventtarget.value+barcodeListener.savedinput}barcodeListener.savedinput="";barcodeListener.parsedinput="";barcodeListener.eventtarget=""},clearCombinationOnTimeout:function(){if(barcodeListener.savedinput!=""){var a=new Date;if((a.getTime()-barcodeListener.lastKeypress)>=(barcodeListener.clearTimeout-100)){barcodeListener.clearCombination(true)}}}};if(window.addEventListener){window.addEventListener("load",function(){barcodeListener.init()},false)}else{if(window.attachEvent){window.attachEvent("onload",function(){barcodeListener.init()})}}barcode_replace_field=null;barcode_prefocus_handler=null;barcode_focus=function(a){barcode_replace_field=a;barcode_prefocus_handler=barcodeListener.handleroverride;barcodeListener.handleroverride=function(c,b){if(barcode_replace_field.el){barcode_replace_field.setValue(c);barcode_replace_field.el.blur()}else{if(barbode_replace_field){barcode_replace_field.value=c;barcode_replace_field.blur()}}}};barcode_blur=function(){barcode_replace_field=null;barcodeListener.handleroverride=barcode_prefocus_handler};