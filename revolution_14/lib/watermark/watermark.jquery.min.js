/*
 *	watermark.js jQuery plugin
 *	Watermarked images with javascript and htmlcanvas	
 *
 *	author: Patrick Wied ( http://www.patrick-wied.at )
 *	version: 1.0
 *	license: MIT - feel free to use, modify, redistribute
 *	http://letmein.at/software/how-to-correctly-use-code-you-didnt-write/
 */
(function(c){c.fn.watermark=function(n){var d={},g={},f="watermark",b=!1,k="bottom-right",l="watermark.png?"+ +new Date,i=127.5,o=function(){b=c('<img src="'+l+'" />');i!=255?b[0].complete?m():b[0].onload=function(){m()}:applyWatermarks()},m=function(){var a=b[0].width||b[0].offsetWidth,j=b[0].height||b[0].offsetHeight;setCanvasSize(a,j);g.drawImage(b[0],0,0);for(var c=g.getImageData(0,0,a,j),e=c.data,f=e.length,h=3;h<f;h+=4)e[h]=e[h]<i?e[h]:i;c.data=e;g.putImageData(c,0,0);b[0].onload=null;b.attr("src", "");b.attr("src",d[0].toDataURL());b.width(a);b.height(j);applyWatermarks()};setCanvasSize=function(a,b){d[0].width=a;d[0].height=b};applyWatermark=function(a){setCanvasSize(a[0].width||a[0].offsetWidth,a[0].height||a[0].offsetHeight);g.drawImage(a[0],0,0);var c=k,f=0,e=0,e=c.indexOf("top")!=-1?10:d.height()-b.height()-10,f=c.indexOf("left")!=-1?10:d.width()-b.width()-10;g.drawImage(b[0],f,e);a[0].onload=null;a.attr("src",d[0].toDataURL())};applyWatermarks=function(){setTimeout(function(){c("."+f).each(function(){var a= c(this);if(a[0].tagName.toUpperCase()=="IMG")a[0].complete?applyWatermark(a):a[0].onload=function(){applyWatermark(a)}})},10)};(function(a){if(a)a.watermark&&(b=a.watermark),a.path&&(l=a.path),a.position&&(k=a.position),a.opacity&&(i=255/(100/a.opacity)),a.className&&(f=a.className);d=c('<canvas style="display:none"></canvas>');g=d[0].getContext("2d");c("body").append(d);o()})(n)}})(jQuery);