ACMS.Dispatch.Edit.MAP_NOTFOUND="\u898b\u3064\u304b\u308a\u307e\u305b\u3093\u3067\u3057\u305f";ACMS.Dispatch.Edit.MAP_INPUT="\u4f4f\u6240\u3001\u53c8\u306f\u30b9\u30dd\u30c3\u30c8\u540d\u3092\u5165\u529b\u3057\u3066\u304f\u3060\u3055\u3044";
ACMS.Dispatch.Edit.map=function(item){var $img=$(".js-map_editable-container",item).add(".column-map",item);var $lat=$(".js-map_editable-lat",item).add(':input[name^="map_lat_"]',item);var $lng=$(".js-map_editable-lng",item).add(':input[name^="map_lng_"]',item);var $zoom=$(".js-map_editable-zoom",item).add(':input[name^="map_zoom_"]',item);if(!$img.size()||!$img.is("img"))return false;if(!$lat.size()||!$lat.is(":input"))return false;if(!$lng.size()||!$lng.is(":input"))return false;var query=ACMS.Library.parseQuery($img.attr("src").replace(/^[^?]*\?/,
""));var latlng=query.center.split(",");var zoom=parseInt(query.zoom,10);if($zoom.size()&&$zoom.val().length)zoom=parseInt($zoom.val(),10);var GMap=null;var _center=null;var activate=function(){var $div=$($.parseHTML('<div style="overflow:hidden;" class="acms-admin-gmap-box"></div>'));$div.css("width",$img.width()+"px");$div.css("height",$img.height()+"px");$img.replaceWith($div);_center=getCenter();GMap=new google.maps.Map($div.get(0),{zoom:zoom,center:_center,mapTypeId:google.maps.MapTypeId.ROADMAP,
streetViewControl:false,scrollwheel:false,styles:ACMS.Config.s2dStyle});var GMarker=new google.maps.Marker({position:_center,map:GMap,draggable:true});function getCenter(){return new google.maps.LatLng($lat.val(),$lng.val())}function latLngOnChange(){var center=getCenter();GMap.panTo(center);GMarker.setPosition(center)}function setPosition(latLng){GMap.panTo(latLng);GMarker.setPosition(latLng);var lat=Math.round(latLng.lat()*1E6);var lng=Math.round(latLng.lng()*1E6);$lat.val(lat/1E6);$lng.val(lng/
1E6)}$lat.change(latLngOnChange);$lng.change(latLngOnChange);google.maps.event.addListener(GMarker,"dragend",function(event){setPosition(event.latLng)});if($zoom.size()){$zoom.change(function(){GMap.setZoom(parseInt($(this).val(),10))});google.maps.event.addListener(GMap,"zoom_changed",function(){$zoom.val(GMap.getZoom())})}var $stxt=$(".js-editable_map-search_text",item).add(':input[name="mapSearchTexts[]"]',item);var $sbtn=$(".js-editable_map-search_button",item).add(':input[name="mapSearchButtons[]"]',
item);if($stxt.size()&&$sbtn.size()){$stxt.val(ACMS.Dispatch.Edit.MAP_INPUT).removeAttr("disabled").addClass("default");$sbtn.removeAttr("disabled");var search=function(event){event.preventDefault();var address=$stxt.val();if(!address)return false;$stxt.attr("disabled","disabled");$sbtn.attr("disabled","disabled");var geocoder=new google.maps.Geocoder;geocoder.geocode({address:address},function(results,status){if(status==google.maps.GeocoderStatus.OK)setPosition(results[0].geometry.location);else{$stxt.val(ACMS.Dispatch.Edit.MAP_NOTFOUND);
$stxt.addClass("default")}$stxt.removeAttr("disabled");$sbtn.removeAttr("disabled")});return false};$sbtn.click(search);var keyCatch=function(){if(document.all)return function(e){return e.keyCode};else if(document.getElementById)return function(e){return e.keyCode?e.keyCode:e.charCode};else if(document.layers)return function(e){return e.which}}();$stxt.bind("keydown",function(e){if(13===keyCatch(e)){$sbtn.click();return false}});$stxt.focus(function(){var $self=$(this);if($self.is(".default"))$self.val("").removeClass("default");
$self.select()});$stxt.blur(function(){var $self=$(this);if(""==$self.val()){$self.val(ACMS.Dispatch.Edit.MAP_INPUT);$self.addClass("default")}})}};if($lat.val().length&&$lng.val().length)activate();else $img.click(function(){if(!$lat.val().length)$lat.val(latlng[0]);if(!$lng.val().length)$lng.val(latlng[1]);if($zoom.size())$zoom.val(zoom);activate()});$(".js-acms_admin_tabs").each(function(){var $expr=$("a[href^=#]",this);$expr.each(function(){$(this).click(function(){google.maps.event.trigger(GMap,
"resize");GMap.setCenter(_center)})})})};
