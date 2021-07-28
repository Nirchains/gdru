/*! Fabrik */

"use strict";var FabrikModalRepeat=new Class({options:{j3:!0},initialize:function(t,e,i,n){this.names=e,this.field=i,this.content=!1,this.setup=!1,this.elid=t,this.win={},this.el={},this.field={},this.options=Object.append(this.options,n),this.ready()?this.setUp():this.timer=this.testReady.periodical(500,this)},ready:function(){return"null"!==typeOf(document.id(this.elid))},testReady:function(){this.ready()&&(this.timer&&clearInterval(this.timer),this.setUp())},setUp:function(){this.button=document.id(this.elid+"_button"),this.mask=new Mask(document.body,{style:{"background-color":"#000",opacity:.4,"z-index":9998}}),document.addEvent("click:relay(*[data-modal="+this.elid+"])",function(t,e){t.preventDefault();var i,n=e.getNext("input").id,s=e.getParent("li");this.field[n]=e.getNext("input"),s||(s=e.getParent("div.control-group")),i=(this.origContainer=s).getElement("table"),"null"!==typeOf(i)&&(this.el[n]=i),this.openWindow(n)}.bind(this))},openWindow:function(t){var e=!1;this.win[t]||(e=!0,this.makeTarget(t)),this.el[t].inject(this.win[t],"top"),this.el[t].show(),this.win[t]&&!e||this.makeWin(t),this.win[t].show(),this.win[t].position(),this.resizeWin(!0,t),this.win[t].position(),this.mask.show()},makeTarget:function(t){this.win[t]=new Element("div",{"data-modal-content":t,styles:{padding:"5px","background-color":"#fff",display:"none","z-index":9999}}).inject(document.body)},makeWin:function(e){var t=new Element("button.btn.button.btn-primary").set("text","close");t.addEvent("click",function(t){t.stop(),this.store(e),this.el[e].hide(),this.el[e].inject(this.origContainer),this.close()}.bind(this));var i=new Element("div.controls.form-actions",{styles:{"text-align":"right","margin-bottom":0}}).adopt(t);this.win[e].adopt(i),this.win[e].position(),this.content=this.el[e],this.build(e),this.watchButtons(this.win[e],e)},resizeWin:function(o,t){Object.each(this.win,function(t,e){var i=this.el[e].getDimensions(!0),n=t.getDimensions(!0);if(t.setStyles({width:i.x+"px"}),"undefined"!=typeof Fabrik&&!Fabrik.bootstrapped){var s=o?n.y:i.y+30;t.setStyle("height",s+"px")}}.bind(this))},close:function(){Object.each(this.win,function(t,e){t.hide()}),this.mask.hide()},_getRadioValues:function(t){var i,n=[];return this.getTrs(t).each(function(t){var e=(i=t.getElement("input[type=radio]:checked"))?i.get("value"):"";n.push(e)}),n},_setRadioValues:function(i,t){var n;this.getTrs(t).each(function(t,e){(n=t.getElement("input[type=radio][value="+i[e]+"]"))&&(n.checked="checked")})},addRow:function(t,e){var i=this._getRadioValues(t),n=e.getParent("table").getElement("tbody"),s=this.tmpl.clone(!0,!0);s.inject(n),this.stripe(t),this.fixUniqueAttributes(e,s),this._setRadioValues(i,t),this.resetChosen(s),this.resizeWin(!1,t)},fixUniqueAttributes:function(t,e){var i=t.getParent("table").getElements("tr").length-1;e.getElements("*[name]").each(function(t){t.name+="-"+i}),e.getElements("*[id]").each(function(t){t.id+="-"+i}),e.getElements("label[for]").each(function(t){t.label+="-"+i})},watchButtons:function(e,i){var n;e.addEvent("click:relay(a.add)",function(t){(n=this.findTr(t))&&this.addRow(i,n),e.position(),t.stop()}.bind(this)),e.addEvent("click:relay(a.remove)",function(t){(n=this.findTr(t))&&n.dispose(),this.resizeWin(!1,i),e.position(),t.stop()}.bind(this))},resetChosen:function(t){this.options.j3&&jQuery&&"null"!==typeOf(jQuery("select").chosen)&&(t.getElements("select").removeClass("chzn-done").show(),t.getElements("select").each(function(t){t.id=t.id+"_"+(1e7*Math.random()).toInt()}),t.getElements(".chzn-container").destroy(),jQuery(t).find("select").chosen({disable_search_threshold:10,allow_single_deselect:!0,width:"265px"}))},getTrs:function(t){return this.win[t].getElement("tbody").getElements("tr")},stripe:function(t){for(var e=this.getTrs(t),i=0;i<e.length;i++)e[i].removeClass("row1").removeClass("row0"),e[i].addClass("row"+i%2)},build:function(t){this.win[t]||this.makeWin(t);var i=JSON.parse(this.field[t].get("value"));"null"===typeOf(i)&&(i={});for(var e=this.win[t].getElement("tbody").getElement("tr"),n=Object.keys(i),s=0===n.length||0===i[n[0]].length,o=s?1:i[n[0]].length,a=1;a<o;a++){var h=e.clone();this.fixUniqueAttributes(e,h),h.inject(e,"after"),this.resetChosen(h)}this.stripe(t);var r=this.getTrs(t);for(a=0;a<o;a++)n.each(function(e){r[a].getElements("*[name*="+e+"]").each(function(t){"radio"===t.get("type")?t.value===i[e][a]&&(t.checked=!0):(t.value=i[e][a],"select"===t.get("tag")&&"undefined"!=typeof jQuery&&jQuery(t).trigger("liszt:updated"))})});this.tmpl=e,s&&e.dispose()},findTr:function(t){var e=t.target.getParents().filter(function(t){return"tr"===t.get("tag")});return 0!==e.length&&e[0]},store:function(t){var e=this.content;e=this.el[t];for(var i={},n=0;n<this.names.length;n++){var s=this.names[n],o=e.getElements("*[name*="+s+"]");i[s]=[],o.each(function(t){"radio"===t.get("type")?!0===t.get("checked")&&i[s].push(t.get("value")):i[s].push(t.get("value"))}.bind(this))}return this.field[t].value=JSON.stringify(i),!0}});