!function(e){var t={};function n(o){if(t[o])return t[o].exports;var i=t[o]={i:o,l:!1,exports:{}};return e[o].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=e,n.c=t,n.d=function(e,t,o){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)n.d(o,i,function(t){return e[t]}.bind(null,i));return o},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=368)}({206:function(e,t){e.exports=window.wp.hooks},207:function(e,t){e.exports=window.wp.data},368:function(e,t,n){"use strict";n.r(t);const o="excerpt";function i(){return"undefined"!=typeof tinyMCE&&void 0!==tinyMCE.editors&&tinyMCE.editors.length>=0}function r(e){if(!i())return!1;const t=tinyMCE.get(e);return null!==t&&!t.isHidden()}function a(){if(r(o)){return tinyMCE.get(o).getContent()}return e=o,document.getElementById(e)&&document.getElementById(e).value||"";var e}function s(e){const t=a();e.sendMessage("updateProductDescription",t,"YoastWooCommerce"),YoastSEO.app.refresh()}function c(e){const t=tinyMCE.get(o);t.on("change",()=>s(e)),t.on("input",()=>s(e))}function d(e){!function(e){const t=jQuery("#excerpt");t.on("change",()=>s(e)),t.on("input",()=>s(e))}(e),r(o)&&c(e),i()&&tinyMCE.on("AddEditor",t=>{"excerpt"===t.editor.id&&c(e)})}var u=n(206),l=n(207);const p="YoastWooCommerce";var f=0;let y="";class g{constructor(){this.loadWorkerScript(),YoastSEO.app.registerPlugin("YoastWooCommercePlugin",{status:"ready"}),this.registerModifications(),this.bindEvents(),this.dispatchGooglePreviewData(),Object(u.addFilter)("yoast.socials.imageFallback","yoast/yoast-woocommerce-seo/image_fallback",this.addProductGalleryImageAsFallback)}loadWorkerScript(){if("undefined"==typeof YoastSEO||void 0===YoastSEO.analysis||void 0===YoastSEO.analysis.worker)return;const e=YoastSEO.analysis.worker,t=a();e.loadScript(wpseoWooL10n.script_url).then(()=>e.sendMessage("initialize",{l10n:wpseoWooL10n,productDescription:t},p)).then(YoastSEO.app.refresh),d(e)}bindEvents(){if(r("excerpt")){tinyMCE.get("excerpt").on("change",(function(){YoastSEO.app.analyzeTimer()}))}jQuery(".add_product_images").find("a").on("click",this.bindLinkEvent.bind(this)),this.bindDeleteEvent()}dispatchGooglePreviewData(){Object(l.dispatch)("yoast-seo/editor").setShoppingData(window.wpseoWooL10n.wooGooglePreviewData)}bindLinkEvent(){0===jQuery(".media-modal-content").find(".media-button").length?++f<10&&setTimeout(this.bindLinkEvent.bind(this)):(f=0,jQuery(".media-modal-content").find(".media-button").on("click",this.buttonCallback.bind(this)))}buttonCallback(){YoastSEO.app.analyzeTimer()}bindDeleteEvent(){jQuery("#product_images_container").on("click",".delete",YoastSEO.app.analyzeTimer.bind(YoastSEO.app))}registerModifications(){var e=this.addContent.bind(this);YoastSEO.app.registerModification("content",e,"YoastWooCommercePlugin",10)}addContent(e){e+="\n\n"+a();for(var t=jQuery("#product_images_container").find("img"),n=0;n<t.length;n++)e+=t[n].outerHTML;return y=t[0]?t[0].src.replace(/-\d+x\d+(\.[a-zA-Z0-9]+)$/,"$1"):"",e}addProductGalleryImageAsFallback(e){if(y){const t=e.findIndex(e=>"socialImage"===Object.keys(e)[0]||"siteWideImage"===Object.keys(e)[0]);e.splice(t,0,{productGalleryImage:y})}return e}}"undefined"!=typeof YoastSEO&&void 0!==YoastSEO.app?new g:jQuery(window).on("YoastSEO:ready",(function(){new g}))}});