!function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(r,o,function(t){return e[t]}.bind(null,o));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=5)}([function(e,t,n){var r=n(1);e.exports=function(e,t){if(e){if("string"==typeof e)return r(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);return"Object"===n&&e.constructor&&(n=e.constructor.name),"Map"===n||"Set"===n?Array.from(e):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?r(e,t):void 0}}},function(e,t){e.exports=function(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}},function(e,t,n){var r=n(6),o=n(7),a=n(0),i=n(8);e.exports=function(e,t){return r(e)||o(e,t)||a(e,t)||i()}},function(e,t,n){var r=n(9),o=n(10),a=n(0),i=n(11);e.exports=function(e){return r(e)||o(e)||a(e)||i()}},function(e,t){e.exports=function(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}},function(e,t,n){e.exports=n(12)},function(e,t){e.exports=function(e){if(Array.isArray(e))return e}},function(e,t){e.exports=function(e,t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e)){var n=[],r=!0,o=!1,a=void 0;try{for(var i,l=e[Symbol.iterator]();!(r=(i=l.next()).done)&&(n.push(i.value),!t||n.length!==t);r=!0);}catch(e){o=!0,a=e}finally{try{r||null==l.return||l.return()}finally{if(o)throw a}}return n}}},function(e,t){e.exports=function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}},function(e,t,n){var r=n(1);e.exports=function(e){if(Array.isArray(e))return r(e)}},function(e,t){e.exports=function(e){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e))return Array.from(e)}},function(e,t){e.exports=function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}},function(e,t,n){"use strict";n.r(t);var r=n(2),o=n.n(r),a=n(3),i=n.n(a),l=n(4),u=n.n(l);function c(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function s(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?c(Object(n),!0).forEach((function(t){u()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):c(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var f=wp.hooks.addFilter,p=wp.compose.createHigherOrderComponent,d=wp.blockEditor.InspectorControls,g=wp.components,b=g.PanelBody,y=g.SelectControl,m=wp.i18n.__,v=[{label:m("All"),value:"all"}],_=p((function(e){var t=[];return t.push({label:v[0].label,value:v[0].value}),t.push.apply(t,i()(Object.entries(wpml_active_and_selected_languages.active_languages).map((function(e){var t=o()(e,2),n=(t[0],t[1]);return{label:n.native_name,value:n.code}})))),function(n){var r=n.attributes.wpml_language;return void 0!==n.attributes.__internalWidgetId&&"core/legacy-widget"===n.name&&(v[0].value=wpml_active_and_selected_languages.legacy_widgets_languages[n.attributes.__internalWidgetId]),React.createElement(React.Fragment,null,React.createElement(e,n),React.createElement(d,null,React.createElement(b,{title:m("Display on language","sitepress"),initialOpen:!0},React.createElement(y,{value:r||v[0].value,options:t,onChange:function(e){if(n.setAttributes({wpml_language:e}),void 0!==n.attributes.__internalWidgetId&&"core/legacy-widget"===n.name){var r=t.find((function(t){return t.value===e}));void 0!==r&&w(n.attributes.__internalWidgetId,r)}}}))))}}),"languageControls"),w=function(e,t){var n=new FormData;n.append("action","wpml_change_selected_language_for_legacy_widget"),n.append("id",e),n.append("selected_language_value",t.value),n.append("nonce",wpml_active_and_selected_languages.nonce),fetch(ajaxurl,{method:"POST",body:n}).then((function(e){if(!e.ok)throw new Error("Changing selected language for legacy widgets action has failed!.")}))};f("blocks.registerBlockType","sitepress-multilingual-cms/attribute/wpml_language",(function(e){return e.attributes=s(s({},e.attributes),{},{wpml_language:{type:"string"}}),e})),f("editor.BlockEdit","sitepress-multilingual-cms/language-controls",_)}]);