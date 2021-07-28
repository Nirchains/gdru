/* jce - 2.9.9 | 2021-06-24 | https://www.joomlacontenteditor.net | Copyright (C) 2006 - 2021 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
!function(){var each=tinymce.each,DOM=tinymce.DOM,Event=tinymce.dom.Event;tinymce.create("tinymce.plugins.AdvListPlugin",{init:function(ed,url){function buildFormats(str){var formats=[];return each(str.split(/,/),function(type){var title=type.replace(/-/g,"_");"default"===type&&(title="def"),formats.push({title:"advlist."+title,styles:{listStyleType:"default"===type?"":type}})}),formats}var self=this;self.editor=ed;var numlist=ed.getParam("advlist_number_styles","default,lower-alpha,lower-greek,lower-roman,upper-alpha,upper-roman");numlist&&(self.numlist=buildFormats(numlist));var bullist=ed.getParam("advlist_bullet_styles","default,circle,disc,square");bullist&&(self.bullist=buildFormats(bullist))},createControl:function(name,cm){function hasFormat(node,format){var state=!0;return each(format.styles,function(value,name){if(editor.dom.getStyle(node,name)!=value)return state=!1,!1}),state}function applyListFormat(){var list,dom=editor.dom,sel=editor.selection;list=dom.getParent(sel.getNode(),"ol,ul"),list&&list.nodeName!=("bullist"==name?"OL":"UL")&&format&&!hasFormat(list,format)||editor.execCommand("bullist"==name?"InsertUnorderedList":"InsertOrderedList"),format&&(list=dom.getParent(sel.getNode(),"ol,ul"),list&&(dom.setStyles(list,format.styles),list.removeAttribute("data-mce-style"))),editor.focus()}function openDialog(){var form=cm.createForm("numlist_form");form.empty();var start_ctrl=cm.createTextBox("numlist_start_ctrl",{label:editor.getLang("advlist.start","Start"),name:"start",subtype:"number",attributes:{min:"1"}});form.add(start_ctrl);var reversed_ctrl=cm.createCheckBox("numlist_reversed_ctrl",{label:editor.getLang("advlist.reversed","Reversed"),name:"reversed"});form.add(reversed_ctrl),editor.windowManager.open({title:editor.getLang("advanced.numlist_desc","Ordered List"),items:[form],size:"mce-modal-landscape-small",open:function(){var label=editor.getLang("update","Update"),node=editor.selection.getNode(),list=editor.dom.getParent(node,"ol");list&&(start_ctrl.value(editor.dom.getAttrib(list,"start")||1),reversed_ctrl.checked(!!editor.dom.getAttrib(list,"reversed"))),DOM.setHTML(this.id+"_insert",label)},buttons:[{title:editor.getLang("remove","Remove"),id:"remove",onclick:function(){applyListFormat(),this.close()}},{title:editor.getLang("cancel","Cancel"),id:"cancel"},{title:editor.getLang("insert","Insert"),id:"insert",onsubmit:function(e){var data=form.submit();Event.cancel(e);var list=editor.dom.getParent(editor.selection.getNode(),"ol");list&&each(data,function(value,key){value||(value=null),"start"==key&&"1"==value&&(value=null),editor.dom.setAttrib(list,key,value)})},classes:"primary",scope:self}]})}var btn,format,self=this,editor=self.editor;if("numlist"==name||"bullist"==name)return self[name]&&"advlist.def"===self[name][0].title&&(format=self[name][0]),self[name]?(btn=cm.createSplitButton(name,{title:"advanced."+name+"_desc",class:"mce_"+name,onclick:function(){if("numlist"===name){var list=editor.dom.getParent(editor.selection.getNode(),"ol");if(list)return openDialog()}applyListFormat()}}),btn.onRenderMenu.add(function(btn,menu){menu.onHideMenu.add(function(){self.bookmark&&(editor.selection.moveToBookmark(self.bookmark),self.bookmark=0)}),menu.onShowMenu.add(function(){var fmtList,dom=editor.dom,list=dom.getParent(editor.selection.getNode(),"ol,ul");(list||format)&&(fmtList=self[name],each(menu.items,function(item){var state=!0;item.setSelected(0),list&&!item.isDisabled()&&(each(fmtList,function(fmt){if(fmt.id==item.id&&!hasFormat(list,fmt))return state=!1,!1}),state&&item.setSelected(1))}),list||menu.items[format.id].setSelected(1)),editor.focus(),tinymce.isIE&&(self.bookmark=editor.selection.getBookmark(1))}),each(self[name],function(item){item.id=editor.dom.uniqueId();var style=item.styles.listStyleType,icon=style.replace(/-/g,"_");menu.add({id:item.id,title:item.title,icon:"list_"+icon,onclick:function(){format=item,applyListFormat()}})})}),btn):btn=cm.createButton(name,{title:"advanced."+name+"_desc",class:"mce_"+name,onclick:function(){applyListFormat()}})}}),tinymce.PluginManager.add("advlist",tinymce.plugins.AdvListPlugin)}();