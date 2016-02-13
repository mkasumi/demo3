(function(global){var doc=global.document,nav=global.navigator,loc=global.location;if(global["ACMS"])return false;function parseQuery(query){var s=query.split("&"),data={},i=0,iz=s.length,param,key,value;for(;i<iz;i++){param=s[i].split("=");if(param[0]!==void 0){key=param[0];value=param[1]!==void 0?param.slice(1).join("="):key;data[key]=decodeURIComponent(value)}}return data}function AcmsSyncLoader(options){var opt=options||{};this.charset=opt.charset;this.type=opt.type||"text/javascript";this.error=
opt.error||function(){};this.queue={srcAry:[],asyncAry:[],loaded:[],add:function(src){this.srcAry.push(src)}};return this}AcmsSyncLoader.prototype={next:function(arg,async,callback){if(!!async)this._load(arg,true,callback);else this.queue.add(arg);return this},load:function(callback){this.complete=callback||function(){};this.assign()},assign:function(){var src=this.queue.srcAry.shift();if(src===void 0){this.complete();return false}if(typeof src==="function")this._exe(src);else this._load(src)},_exe:function(callback){var func=
callback||function(){};func(this);this.assign()},_exists:function(src){var already=false;for(i=0;i<this.queue.loaded.length;i++)if(this.queue.loaded[i]===src){already=true;break}return already},_load:function(src,async,callback){var self=this,tag=document.createElement("script"),head=document.getElementsByTagName("script")[0];tag.src=src;tag.type=this.type;tag.onerror=function(){self.error()};if(this.charset)tag.charset=options.charset;if(async){head.parentNode.insertBefore(tag,head.nextSibling);
tag.onload=function(){if(!self._exists(src)){self.queue.loaded.push(src);callback=callback||function(){};callback()}}}else{tag.onload=function(){if(!self._exists(src)){self.queue.loaded.push(src);self.assign()}};head.parentNode.insertBefore(tag,head.nextSibling)}tag.onreadystatechange=function(){if(0||!this.readyState||this.readyState==="loaded"||this.readyState==="complete"){tag.onreadystatechange=null;tag.onload()}}}};var elm=doc.getElementById("acms-js");var query={};if(elm){var s=elm.src.split("?");
if(1<s.length){var Query=parseQuery(s[1]);var key;for(key in Query)query[key]=Query[key]}}query.searchEngineKeyword="";if(doc.referrer.match(/^http:\/\/www\.google\..*(?:\?|&)q=([^&]+).*$|^http:\/\/search\.yahoo\.co\.jp.*(?:\?|&)p=([^&]+).*$|^http:\/\/www\.bing\.com.*(?:\?|&)q=([^&]+).*$/))query.searchEngineKeyword=decodeURIComponent(RegExp.$1||RegExp.$2||RegExp.$3).replace(/\+/g," ");query.root="/";query.offset&&(query.root+=query.offset);query.jsRoot=query.root+query.jsDir;query.hash=loc.hash;query.Gmap=
{sensor:nav.userAgent.match(/iPhone|Android/)?"true":"false"};var path=query.jsRoot;var Dispatch=function(){},Library=function(){},ACMS=function(){},Config=function(key,value){if(!key)return"";if("string"==typeof key)if(!value)return"undefined"!=typeof this.Config[key]?this.Config[key]:"";else{this.Config[key]=value;return true}else for(var prop in key)arguments.callee[prop]=key[prop]};ACMS.Config=Config;ACMS.Dispatch=Dispatch;ACMS.Library=Library;ACMS.SyncLoader=AcmsSyncLoader;ACMS.addListener=function(name,
listener,dom){dom=dom||document;if(dom.addEventListener)dom.addEventListener(name,listener,false);else if(dom.attachEvent){dom.documentElement[name]=0;dom.documentElement.attachEvent("onpropertychange",function(event){if(event.propertyName==name){listener();dom.documentElement.detachEvent("onpropertychange",arguments.callee)}})}};ACMS.dispatchEvent=function(name,obj,dom){obj=obj||{};dom=dom||document;if(document.createEvent){var evn=document.createEvent("HTMLEvents");evn.obj=obj;evn.initEvent(name,
true,false);dom.dispatchEvent(evn)}else if(dom.createEventObject)dom.documentElement[name]++};ACMS.Ready=function(listener){ACMS.addListener("acmsReady",listener)};global.ACMS=ACMS;for(var prop in query)ACMS.Config[prop]=query[prop];var loader=(new AcmsSyncLoader).next(path+"library/underscore-min.js",true).next(path+"config.js");if(typeof jQuery==="undefined")loader.next(path+"library/jquery/jquery-"+query.jQuery+".min.js");if(query.jQueryMigrate==="on")loader.next(path+"library/jquery/jquery-migrate-1.2.1.min.js");
loader.next(path+"library/jquery/ui/jquery-ui.min.js").next(path+"library/jquery/jquery.cookie.js").next(path+"dispatch.js").next(path+"dispatch/utility.js").next(path+"library.js").next(function(){Library.parseQuery=parseQuery;function loadClosureFactory(url,charset,pre,post){url="string"==typeof url?url:"";charset="string"==typeof charset?charset:"";pre=$.isFunction(pre)?pre:function(){};post=$.isFunction(post)?post:function(){};return function(callSpecifiedFunction){if(!$.isFunction(callSpecifiedFunction))callSpecifiedFunction=
function(){};var self=arguments.callee;if(!self.executed){self.executed=true;self.stack=[callSpecifiedFunction];(new AcmsSyncLoader).next(pre).next(url).next(function(){while(self.stack.length)self.stack.shift()()}).load(post)}else{self.stack.push(callSpecifiedFunction);return true}}}loadClosureFactory.css=function(url,charset,prepend){if(!url)return false;var link=doc.createElement("link");link.type="text/css";link.rel="stylesheet";if(charset)link.charset=charset;link.href=url;var head=doc.getElementsByTagName("head")[0];
if(prepend&&head.firstChild)head.insertBefore(link,head.firstChild);else head.appendChild(link);return true};var loadClosureCollection={};loadClosureCollection.Dispatch={};loadClosureCollection.Dispatch._static2dynamic=loadClosureFactory(path+"dispatch/_static2dynamic.js");loadClosureCollection.Dispatch._static2dynamic_yolp=loadClosureFactory(path+"dispatch/_static2dynamic_yolp.js");loadClosureCollection.Dispatch._observefilesize=loadClosureFactory(path+"dispatch/_observefilesize.js");loadClosureCollection.Dispatch._validate=
loadClosureFactory(path+"dispatch/_validate.js");loadClosureCollection.Dispatch._revision=loadClosureFactory(path+"dispatch/_revision.js");loadClosureCollection.Dispatch._imgresize=loadClosureFactory(path+"dispatch/_imgresize.js");loadClosureCollection.Dispatch.ckeditor=loadClosureFactory(path+"dispatch/ckeditor.js");loadClosureCollection.Dispatch.emoditor=loadClosureFactory(path+"dispatch/emoditor.js");loadClosureCollection.Dispatch.Layout=loadClosureFactory(path+"dispatch/layout.js");loadClosureCollection.Dispatch.ModuleDialog=
loadClosureFactory(path+"dispatch/moduleDialog.js");loadClosureCollection.Dispatch.Postinclude=loadClosureFactory(path+"dispatch/postinclude.js");loadClosureCollection.Dispatch.Postinclude._postinclude=loadClosureFactory(path+"dispatch/postinclude/_postinclude.js");loadClosureCollection.Dispatch.Linkmatchlocation=loadClosureFactory(path+"dispatch/linkmatchlocation.js");loadClosureCollection.Dispatch.Admin=loadClosureFactory(path+"dispatch/admin.js");loadClosureCollection.Dispatch.Admin._media=loadClosureFactory(path+
"dispatch/admin/_media.js");loadClosureCollection.Dispatch._tooltip=loadClosureFactory(path+"dispatch/_tooltip.js");loadClosureCollection.Dispatch.Admin.Configunit=loadClosureFactory(path+"dispatch/admin/configunit.js");loadClosureCollection.Dispatch.Edit=loadClosureFactory(path+"dispatch/edit.js");loadClosureCollection.Dispatch.Edit._add=loadClosureFactory(path+"dispatch/edit/_add.js");loadClosureCollection.Dispatch.Edit._related=loadClosureFactory(path+"dispatch/edit/_related.js");loadClosureCollection.Dispatch.Edit._change=
loadClosureFactory(path+"dispatch/edit/_change.js");loadClosureCollection.Dispatch.Edit._emojiedit=loadClosureFactory(path+"dispatch/edit/_emojiedit.js");loadClosureCollection.Dispatch.Edit._item=loadClosureFactory(path+"dispatch/edit/_item.js");loadClosureCollection.Dispatch.Edit._tagassist=loadClosureFactory(path+"dispatch/edit/_tagassist.js");loadClosureCollection.Dispatch.Edit._category=loadClosureFactory(path+"dispatch/edit/_category.js");loadClosureCollection.Dispatch.Edit._inplace=loadClosureFactory(path+
"dispatch/edit/_inplace.js");loadClosureCollection.Dispatch.Edit._direct=loadClosureFactory(path+"dispatch/edit/_direct.js");loadClosureCollection.Dispatch.Edit._experimental=loadClosureFactory(path+"dispatch/edit/_experimental.js");loadClosureCollection.Dispatch.Edit._media=loadClosureFactory(path+"dispatch/edit/_media.js");loadClosureCollection.Dispatch.Edit.map=loadClosureFactory(path+"dispatch/edit/map.js");loadClosureCollection.Dispatch.Edit.yolp=loadClosureFactory(path+"dispatch/edit/yolp.js");
loadClosureCollection.Dispatch.highslide=loadClosureFactory(path+"dispatch/highslide.js");loadClosureCollection.Library={};loadClosureCollection.Library.validator=loadClosureFactory(path+"library/validator.js");loadClosureCollection.Library.highslide=loadClosureFactory(path+"library/highslide/highslide.js",null,function(){global["hs"]=void 0},function(){ACMS.Dispatch._highslideInit()});loadClosureCollection.Library.swfobject=loadClosureFactory(path+"library/swfobject/swfobject.js",null,function(){global["hs"]=
void 0});loadClosureCollection.Library.Jquery={};loadClosureCollection.Library.Jquery.biggerlink=loadClosureFactory(path+"library/jquery/jquery.biggerlink.min.js");loadClosureCollection.Library.Jquery.autoheight=loadClosureFactory(path+"library/jquery/jqueryAutoHeight.js");loadClosureCollection.Library.Jquery.selection=loadClosureFactory(path+"library/jquery/jquery.selection.js");loadClosureCollection.Library.Jquery.prettyphoto=loadClosureFactory(path+"library/jquery/prettyphoto/jquery.prettyPhoto.js",
null,function(){loadClosureFactory.css(ACMS.Config.jsRoot+"library/jquery/prettyphoto/css/prettyPhoto.css")});loadClosureCollection.Library.Jquery.bxslider=loadClosureFactory(path+"library/jquery/bxslider/jquery.bxslider.min.js",null,function(){loadClosureFactory.css(ACMS.Config.jsRoot+"library/jquery/bxslider/jquery.bxslider.css")});loadClosureCollection.Library.googleCodePrettify=loadClosureFactory(path+"library/google-code-prettify/prettify.js",null,function(){loadClosureFactory.css(ACMS.Config.jsRoot+
"library/google-code-prettify/styles/"+ACMS.Config.googleCodePrettifyTheme+".css")},function(){ACMS.Library.googleCodePrettifyPost()});loadClosureCollection.Library.google=loadClosureFactory(loc.protocol+"//www.google.com/jsapi");loadClosureFactory.css(ACMS.Config.jsRoot+"library/jquery/ui/css/jquery-ui.min.css");loadClosureFactory.css(ACMS.Config.jsRoot+"library/jquery/ui/css/"+ACMS.Config.uiTheme+"/jquery-ui.min.css");ACMS.Load=loadClosureCollection;assignLoadClosure("ACMS.Dispatch._tagassist",
loadClosureCollection.Dispatch._tagassist);assignLoadClosure("ACMS.Dispatch._static2dynamic",loadClosureCollection.Dispatch._static2dynamic);assignLoadClosure("ACMS.Dispatch._static2dynamic_yolp",loadClosureCollection.Dispatch._static2dynamic_yolp);assignLoadClosure("ACMS.Dispatch._observefilesize",loadClosureCollection.Dispatch._observefilesize);assignLoadClosure("ACMS.Dispatch._validate",loadClosureCollection.Dispatch._validate);assignLoadClosure("ACMS.Dispatch._revision",loadClosureCollection.Dispatch._revision);
assignLoadClosure("ACMS.Dispatch._imgresize",loadClosureCollection.Dispatch._imgresize);assignLoadClosure("ACMS.Dispatch._highslideInit",loadClosureCollection.Dispatch.highslide);assignLoadClosure("ACMS.Dispatch._ckeditorPre",loadClosureCollection.Dispatch.ckeditor);assignLoadClosure("ACMS.Dispatch._ckeditorPost",loadClosureCollection.Dispatch.ckeditor);assignLoadClosure("ACMS.Dispatch.highslide",loadClosureCollection.Dispatch.highslide);assignLoadClosure("ACMS.Dispatch.ckeditor",loadClosureCollection.Dispatch.ckeditor);
assignLoadClosure("ACMS.Dispatch.emoditor",loadClosureCollection.Dispatch.emoditor);assignLoadClosure("ACMS.Dispatch.Layout",loadClosureCollection.Dispatch.Layout);assignLoadClosure("ACMS.Dispatch.ModuleDialog",loadClosureCollection.Dispatch.ModuleDialog);assignLoadClosure("ACMS.Dispatch.Postinclude.ready",loadClosureCollection.Dispatch.Postinclude);assignLoadClosure("ACMS.Dispatch.Postinclude.bottom",loadClosureCollection.Dispatch.Postinclude);assignLoadClosure("ACMS.Dispatch.Postinclude.interval",
loadClosureCollection.Dispatch.Postinclude);assignLoadClosure("ACMS.Dispatch.Postinclude.submit",loadClosureCollection.Dispatch.Postinclude);assignLoadClosure("ACMS.Dispatch.Postinclude._postinclude",loadClosureCollection.Dispatch.Postinclude._postinclude);assignLoadClosure("ACMS.Dispatch.Linkmatchlocation.part",loadClosureCollection.Dispatch.Linkmatchlocation);assignLoadClosure("ACMS.Dispatch.Linkmatchlocation.full",loadClosureCollection.Dispatch.Linkmatchlocation);assignLoadClosure("ACMS.Dispatch.Linkmatchlocation.blog",
loadClosureCollection.Dispatch.Linkmatchlocation);assignLoadClosure("ACMS.Dispatch.Linkmatchlocation.category",loadClosureCollection.Dispatch.Linkmatchlocation);assignLoadClosure("ACMS.Dispatch.Linkmatchlocation.entry",loadClosureCollection.Dispatch.Linkmatchlocation);assignLoadClosure("ACMS.Dispatch.Admin",loadClosureCollection.Dispatch.Admin);assignLoadClosure("ACMS.Dispatch.Admin._media",loadClosureCollection.Dispatch.Admin._media);assignLoadClosure("ACMS.Dispatch._tooltip",loadClosureCollection.Dispatch._tooltip);
assignLoadClosure("ACMS.Dispatch.Admin.Configunit",loadClosureCollection.Dispatch.Admin.Configunit);assignLoadClosure("ACMS.Dispatch.Admin.Configunit._add",loadClosureCollection.Dispatch.Admin.Configunit);assignLoadClosure("ACMS.Dispatch.Admin.Configunit.remove",loadClosureCollection.Dispatch.Admin.Configunit);assignLoadClosure("ACMS.Dispatch.Edit",loadClosureCollection.Dispatch.Edit);assignLoadClosure("ACMS.Dispatch.Edit._add",loadClosureCollection.Dispatch.Edit._add);assignLoadClosure("ACMS.Dispatch.Edit._related",
loadClosureCollection.Dispatch.Edit._related);assignLoadClosure("ACMS.Dispatch.Edit._change",loadClosureCollection.Dispatch.Edit._change);assignLoadClosure("ACMS.Dispatch.Edit._emojiedit",loadClosureCollection.Dispatch.Edit._emojiedit);assignLoadClosure("ACMS.Dispatch.Edit._item",loadClosureCollection.Dispatch.Edit._item);assignLoadClosure("ACMS.Dispatch.Edit._tagassist",loadClosureCollection.Dispatch.Edit._tagassist);assignLoadClosure("ACMS.Dispatch.Edit._category",loadClosureCollection.Dispatch.Edit._category);
assignLoadClosure("ACMS.Dispatch.Edit._inplace",loadClosureCollection.Dispatch.Edit._inplace);assignLoadClosure("ACMS.Dispatch.Edit._direct",loadClosureCollection.Dispatch.Edit._direct);assignLoadClosure("ACMS.Dispatch.Edit._experimental",loadClosureCollection.Dispatch.Edit._experimental);assignLoadClosure("ACMS.Dispatch.Edit._media",loadClosureCollection.Dispatch.Edit._media);assignLoadClosure("ACMS.Dispatch.Edit.updatetime",loadClosureCollection.Dispatch.Edit);assignLoadClosure("ACMS.Dispatch.Edit.map",
loadClosureCollection.Dispatch.Edit.map);assignLoadClosure("ACMS.Dispatch.Edit.yolp",loadClosureCollection.Dispatch.Edit.yolp);assignLoadClosure("ACMS.Library.Validator.isFunction",loadClosureCollection.Library.validator);assignLoadClosure("ACMS.Library.googleCodePrettify",loadClosureCollection.Library.googleCodePrettify);assignLoadClosure("hs.expand",loadClosureCollection.Library.highslide,true);assignLoadClosure("swfobject.embedSWF",loadClosureCollection.Library.swfobject,true);assignLoadClosure("jQuery.fn.biggerlink",
loadClosureCollection.Library.Jquery.biggerlink);assignLoadClosure("jQuery.fn.bxSlider",loadClosureCollection.Library.Jquery.bxslider);assignLoadClosure("jQuery.fn.autoheight",loadClosureCollection.Library.Jquery.autoheight);assignLoadClosure("jQuery.fn.selection",loadClosureCollection.Library.Jquery.selection);assignLoadClosure("jQuery.fn.prettyPhoto",loadClosureCollection.Library.Jquery.prettyphoto);assignLoadClosure("google.load",loadClosureCollection.Library.google,false);function getObjectPointerFromName(name){var objPointer=
global,nameTokens=name.split("."),i=0,iz=nameTokens.length,token,parentPointer;for(;i<iz;i++){token=nameTokens[i];objPointer[token]===void 0&&(objPointer[token]={});parentPointer=objPointer;objPointer=objPointer[token]}return{current:objPointer,token:token,parent:parentPointer}}function assignLoadClosure(name,loadClosure,del){var pointerInfo=getObjectPointerFromName(name),parentPointer=pointerInfo.parent,token=pointerInfo.token;parentPointer[token]=function(){var scope=this,args=arguments,placeholder=
arguments.callee;if(del)global[name.replace(/\..*$/,"")]=void 0;return loadClosure(function(){var func;try{func=getObjectPointerFromName(name).current}catch(e){return false}if("function"!==typeof func)return false;if(func===placeholder)return false;var key,_key;for(key in placeholder)if(func[key])for(_key in placeholder[key])func[key][_key]=placeholder[key][_key];else func[key]=placeholder[key];return func.apply(scope,args)})}}}).load(function(){$(function(){jQuery.expr.filters.text=function(elem){var attr,
type;return elem.nodeName.toLowerCase()==="input"&&(type=elem.type,attr=elem.getAttribute("type"),"text"===type)&&(attr===type||attr===null)};if(!$.isFunction($.parseHTML))jQuery.parseHTML=function(data){return data};ACMS.dispatchEvent("acmsReady");ACMS.Dispatch(document)})})})(window);
