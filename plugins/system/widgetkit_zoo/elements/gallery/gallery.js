/* Copyright (C) YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

(function(b){var a=function(){};b.extend(a.prototype,{name:"opacity",initialize:function(a){a.each(function(){var a=b(this).find("img");a.css("opacity",0.3);b(this).bind("mouseenter",function(){a.fadeTo("fast",1)}).bind("mouseleave",function(){a.fadeTo("slow",0.3)})})}});b.fn[a.prototype.name]=function(){var e=arguments,c=e[0]?e[0]:null;return this.each(function(){var d=b(this);if(a.prototype[c]&&d.data(a.prototype.name)&&"initialize"!=c)d.data(a.prototype.name)[c].apply(d.data(a.prototype.name),
Array.prototype.slice.call(e,1));else if(!c||b.isPlainObject(c)){var f=new a;a.prototype.initialize&&f.initialize.apply(f,b.merge([d],e));d.data(a.prototype.name,f)}else b.error("Method "+c+" does not exist on jQuery."+a.name)})}})(jQuery);
