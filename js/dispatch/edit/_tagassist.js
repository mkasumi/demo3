ACMS.Config.Edit.tagassistEmptyDialogTitle="\u30bf\u30b0\u304c\u767b\u9332\u3055\u308c\u3066\u3044\u307e\u305b\u3093";ACMS.Config.Edit.tagassistEmptyDialogMessage="\u30a8\u30f3\u30c8\u30ea\u30fc\u306b\u4f7f\u7528\u3055\u308c\u3066\u3044\u308b\u30bf\u30b0\u304b\u3089\u5165\u529b\u5019\u88dc\u3092\u9078\u629e\u3059\u308b\u3053\u3068\u304c\u3067\u304d\u307e\u3059\u3002";
ACMS.Dispatch.Edit._tagassist=function(elm){var self=arguments.callee;$(elm).attr("disabled",true);if(!self.$list){var Config=ACMS.Config;var url=ACMS.Library.acmsLink({tpl:"ajax/edit/tag-assist.html"},true);$.get(url,function(html){if(!html.length){var $msg=$("<p></p>");$msg.text(Config.Edit.tagassistEmptyDialogMessage);$msg.dialog({title:Config.Edit.tagassistEmptyDialogTitle,dialogClass:"alert",draggable:false,resizable:false,modal:true,close:function(){$msg.dialog("destroy");$msg.remove()}});$(elm).attr("disabled",
false);return true}self.$list=$($.parseHTML(html));$("a.js-tag_assist-candidate",self.$list).click(function(){var tag=$(this).text();var tagPtn=new RegExp("^"+tag+",|,"+tag+"$|^"+tag+"$","g");var tagPtn2=new RegExp(","+tag+",","g");$i=$(':input[name="tag"]');$i.val($i.val().replace(/[,\s]{2,}/,","));if(0<=$i.val().search(tagPtn))$i.val($i.val().replace(tagPtn,""));else if(0<=$i.val().search(tagPtn2))$i.val($i.val().replace(tagPtn2,","));else $i.val($i.val()+","+tag);$i.val($i.val().replace(/^,/,""));
return false});self.$list.dialog({title:"\u30bf\u30b0\u306e\u9078\u629e ",close:function(){$(elm).attr("disabled",false)}});$win_h=$(window).height();$win_w=$(window).width();self.$list.dialog("option",{maxHeight:$win_h>300?Math.floor(0.6*$win_h):200,width:$win_w>320?Math.floor(0.6*$win_w):250});self.$list.dialog("open");self.$list.dialog("widget").position({of:$(':input[name="tag"]'),my:"center top",at:"cehter bottom",collision:"none"});ACMS.Dispatch.Admin(self.$list)})}else{self.$list.dialog("open");
self.$list.dialog("widget").position({of:$(':input[name="tag"]'),my:"left top",at:"left bottom",collision:"none"})}};