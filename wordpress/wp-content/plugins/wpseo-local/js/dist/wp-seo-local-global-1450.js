!function(e){var t={};function o(i){if(t[i])return t[i].exports;var n=t[i]={i:i,l:!1,exports:{}};return e[i].call(n.exports,n,n.exports,o),n.l=!0,n.exports}o.m=e,o.c=t,o.d=function(e,t,i){o.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:i})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(e,t){if(1&t&&(e=o(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var i=Object.create(null);if(o.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var n in e)o.d(i,n,function(t){return e[t]}.bind(null,n));return i},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="",o(o.s=60)}({60:function(e,t,o){"use strict";jQuery(document).ready((function(e){function t(){if(e("#multiple_locations_primary_location option[value!='']").length>0)return;let t=e("#multiple_locations_primary_location");t.prop("disabled",!0),wp.ajax.post("multiple_locations_location_data",{}).done((function(o){t.prop("disabled",!1),t.empty(),t.prepend(new Option),e.each(o,(function(e,o){let i=new Option(o.text,o.id,!1,!1);t.append(i)})),t.trigger("change")})).fail((function(){console.error("Could not fetch locations for primary location..")}))}jQuery(".yoast-local-help-button").on("click",(function(){var e=jQuery(this),t=jQuery("#"+e.attr("aria-controls")),o=t.is(":visible");jQuery(t).slideToggle(200,(function(){e.attr("aria-expanded",!o)}))})),e("input#use_multiple_locations").on("change",(function(){e(this).is(":checked")?(e("#single-location-settings").hide(),e("#multiple-locations-settings").show(),e("#non-shared-business-info").hide(),e(".default-setting").show(),e("#sl-settings").show(),e("#wpseo-local-permalinks").show(),e("#wpseo-local-admin_labels").show(),e("#wpseo-local-enhanced").show(),e("#wpseo-local-multiple-locations-notification").hide(),e("#location-coordinates-settings").hide(),e("input#multiple_locations_same_organization").is(":checked")?(e("#multiple-locations-same-organization-settings").show(),e("#business-info-settings").show(),t()):(e("#multiple-locations-same-organization-settings").hide(),e("#business-info-settings").hide())):(e("#single-location-settings").show(),e("#multiple-locations-settings").hide(),e("#business-info-settings").show(),e("#non-shared-business-info").show(),e("#sl-settings").hide(),e(".default-setting").hide(),e("#wpseo-local-permalinks").hide(),e("#wpseo-local-admin_labels").hide(),e("#wpseo-local-enhanced").hide(),e("#wpseo-local-multiple-locations-notification").show(),e("#location-coordinates-settings").show())})),e("input#multiple_locations_same_organization").on("change",(function(){e(this).is(":checked")?(t(),e("#multiple-locations-same-organization-settings").show(),e("#business-info-settings").show()):(e("#multiple-locations-same-organization-settings").hide(),e("#business-info-settings").hide())})),e(".wpseo-toggle").on("change",(function(){"false"===e(this).find(".wpseo-toggle-switch").attr("aria-checked")?e(this).find(".wpseo-toggle-switch").attr("aria-checked","true"):e(this).find(".wpseo-toggle-switch").attr("aria-checked","false"),"false"===e(this).find(".wpseo-toggle-feedback").attr("aria-checked")?e(this).find(".wpseo-toggle-feedback").attr("aria-checked","true").text(e(this).data("label-true")):e(this).find(".wpseo-toggle-feedback").attr("aria-checked","false").text(e(this).data("label-false"))})),jQuery(".wpseo-local-metabox-content .wpseo-local-meta-section-link").on("click",(function(e){e.preventDefault();var t=jQuery(this).attr("href"),o=jQuery(t);jQuery(".wpseo-local-metabox-menu li").removeClass("active").find("[role='tab']").removeClass("yoast-active-tab"),jQuery(".wpseo-local-metabox-content .wpseo-local-meta-section.active").removeClass("active"),o.addClass("active"),jQuery(this).parent("li").addClass("active").find("[role='tab']").addClass("yoast-active-tab")})),e(".widget-content").on("click","#wpseo-checkbox-multiple-locations-wrapper input[type=checkbox]",(function(){wpseo_show_all_locations_selectbox(e(this))})),e("#wpseo_locations").length>0&&e("#wpseo_meta").length>0&&e("#wpseo_locations").insertBefore(e("#wpseo_meta")),e(".set_custom_images").length>0&&"undefined"!=typeof wp&&wp.media&&wp.media.editor&&e(".wrap, #wpseo-local-metabox").on("click",".set_custom_images",(function(t){t.preventDefault();var o=e(this),i=o.attr("data-id");return wp.media.editor.send.attachment=function(t,o){if(o.hasOwnProperty("sizes"))var n=o.sizes[t.size].url;else n=o.url;e("#"+i+"_image_container").attr("src",n).show(),e(".wpseo-local-"+i+"-wrapper .wpseo-local-hide-button").removeClass("hidden"),e("#hidden_"+i).attr("value",o.id)},wp.media.editor.open(o),!1})),e(".remove_custom_image").on("click",(function(t){t.preventDefault();var o=e(this).attr("data-id");e("#"+o+"_image_container").attr("src","").hide(),e("#hidden_"+o).attr("value",""),e(".wpseo-local-"+o+"-wrapper .wpseo-local-hide-button").addClass("hidden")})),e("#wpseo_copy_from_location").on("change",(function(){var t=e(this).val();""!=t&&e.post(window.wpseo_local_data.ajaxurl,{location_id:t,security:window.wpseo_local_data.sec_nonce,action:"wpseo_copy_location"},(function(t){0==t.charAt(t.length-1)?t=t.slice(0,-1):"-1"==t.substring(t.length-2)&&(t=t.slice(0,-2));var o=JSON.parse(t);if("true"==o.success||1==o.success)for(var i in o.location){var n=o.location[i];null!=n&&""!=n&&void 0!==n&&("is_postal_address"==i||"multiple_opening_hours"==i?"1"==n&&(e("#wpseo_"+i).attr("checked","checked"),e(".opening-hours .opening-hour-second").slideDown()):i.indexOf("opening_hours")>-1?e("#"+i).val(n):e("#wpseo_"+i).val(n))}}))}))})),window.wpseo_show_all_locations_selectbox=function(e){$=jQuery,$obj=$(e);var t=$obj.parents(".widget-inside"),o=$("#wpseo-locations-wrapper",t);$obj.is(":checked")?o.slideUp():o.slideDown()}}});