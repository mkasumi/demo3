ACMS.Dispatch.Edit._experimental=function(context){var $inplaceWrapper=$(".js-edit_inplace").parent(),Edit=ACMS.Dispatch.Edit;if(!$inplaceWrapper.length||Edit.exFeaturesInited)return;Edit.exFeaturesInited=true;if($inplaceWrapper.length>1)$inplaceWrapper=$inplaceWrapper.last().parent();$inplaceWrapper.sortable({disabled:true,items:"> *",containment:"document",axis:"y",cursorAt:"top",cursor:"move",placeholder:"js-edit_inplace-sort_placeholder",forcePlaceholderSize:true,scrollSensitivity:100,revert:100,
zIndex:999,opacity:0.7,activate:function(e,s){var $original=s.item,$placeholder=s.placeholder,tmpFloat;$placeholder.width($original.width());$placeholder.height($original.height());tmpFloat=$original.css("float");$placeholder.css("float",tmpFloat);if(tmpFloat==="none")$placeholder.css("clear","both")},deactivate:function(e,s){var $units=$inplaceWrapper.find(".js-edit_inplace"),i=0,iz=$units.length,utidList=[];for(;i<iz;i++)utidList.push($units.get(i).id.replace(/^\d+-/,""));$.ajax({type:"POST",url:ACMS.Library.acmsLink({eid:ACMS.Config.eid,
Query:{hash:Math.random().toString()}}),data:{ACMS_POST_Entry_Update_Sort:true,utid:utidList},success:function(){s.item.fadeTo(250,0.1,function(){s.item.fadeTo(450,1)});adjustAlign()},failed:function(){alert("\u66f4\u65b0\u306b\u5931\u6557\u3057\u307e\u3057\u305f")}})}});$(document).on("click",".js-edit_inplace-sort_close",function(){var item=$(this).parent();$inplaceWrapper.fadeTo(0,0.01,function(){$(".js-edit_inplace").removeClass("js-edit_inplace-sort_enable");$(".js-edit_inplace-sort_enable_column").removeClass("js-edit_inplace-sort_enable_column");
$(".js-edit_inplace-sort_label").remove();$(".js-edit_inplace-sort_close").remove();$inplaceWrapper.sortable("disable")});var xy=item.offset();var offset=$(window).height()/2;ACMS.Library.scrollToElm(item,{y:xy["top"]-offset,m:0,k:1});$inplaceWrapper.fadeTo(200,1);ACMS.Config.Edit.stateSort=false});$(document).on("click","#js-edit_inplace-control-move",function(){if(!ACMS.Config.Edit.stateSort){$inplaceWrapper.fadeTo(0,0.01,function(){$inplaceWrapper.find("> .js-edit_inplace").each(function(){var $self=
$(this);$self.prepend('<div class="js-edit_inplace-sort_label">'+$self.attr("data-align")+"</div>");$self.prepend('<div class="js-edit_inplace-sort_close">\u30bd\u30fc\u30c8OFF</div>');$self.find('a[rel*="prettyPhoto"]').each(function(){$(this).unbind("click");$(this).removeAttr("rel");$(this).removeAttr("href")})}).addClass("js-edit_inplace-sort_enable");$(".ui-sortable > *:not(.js-edit_inplace)").each(function(){var $self=$(this);if($self.hasClass("entryTag")||$self.hasClass("formEntryAction"))return true;
if(!$self.closest("hr").size()){$self.prepend('<div class="js-edit_inplace-sort_close">\u30bd\u30fc\u30c8OFF</div>');$self.find('a[rel*="prettyPhoto"]').each(function(){$(this).unbind("click");$(this).removeAttr("rel");$(this).removeAttr("href")})}}).addClass("js-edit_inplace-sort_enable_column");$inplaceWrapper.sortable("enable")});var xy=ACMS.Config.Edit.directSortItem.offset();var offset=$(window).height()/2;ACMS.Library.scrollToElm(ACMS.Config.Edit.directSortItem,{y:xy["top"]-offset,m:0,k:1});
$inplaceWrapper.fadeTo(200,1);ACMS.Config.Edit.stateSort=true}});function adjustAlign(){$(".js-edit_inplace").each(function(){var $unit=$(this);var $self=$unit.children().filter(":isAlignmentUnit"),$prev=$unit.prev().children().filter(":isAlignmentUnit"),$parent=$self.parent();if(!!$unit.size()){$unit.get(0).className.match(/(left|center|right|auto)/);var self_align=RegExp.$1}if(!!$unit.prev().size()){$unit.prev().get(0).className.match(/(left|center|right|auto)/);var prev_align=RegExp.$1}$unit.children().filter("hr.clearHidden").remove();
do{if(typeof prev_align=="undefined")break;if("left"==self_align&&"left"==prev_align)break;if("rigth"==self_align&&"right"==prev_align)break;if("auto"==self_align){if("left"==prev_align)break;if("right"==prev_align)break;if("auto"==prev_align)break}$unit.prepend('<hr class="clearHidden" />')}while(false)})}};
