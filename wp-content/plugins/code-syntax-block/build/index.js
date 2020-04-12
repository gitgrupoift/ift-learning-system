!function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(r,o,function(t){return e[t]}.bind(null,o));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=6)}([function(e,t){!function(){e.exports=this.wp.element}()},function(e,t){!function(){e.exports=this.wp.i18n}()},function(e,t){!function(){e.exports=this.wp.components}()},function(e,t){!function(){e.exports=this.wp.blockEditor}()},function(e,t){e.exports=function(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}},function(e,t){!function(){e.exports=this.wp.hooks}()},function(e,t,n){"use strict";n.r(t);var r=n(4),o=n.n(r),a=n(5),l=n(0),c=n(3),u=n(2),i=n(1),s={fontFamily:"sans-serif",fontSize:".6rem",color:"#999999",position:"absolute",top:".3rem",right:".5rem"},b=function(e){var t=e.attributes,n=e.className,r=e.isSelected,o=e.setAttributes;Object(l.useEffect)((function(){!t.language&&mkaz_code_syntax_default_lang&&o({language:mkaz_code_syntax_default_lang}),r||Prism.highlightAll()}));var a="";return a=t.language?"language-"+t.language:"",a=t.lineNumbers?a+" line-numbers":a,Object(l.createElement)(l.Fragment,null,Object(l.createElement)(c.InspectorControls,{key:"controls"},Object(l.createElement)(u.PanelBody,{title:Object(i.__)("Settings")},Object(l.createElement)(u.SelectControl,{label:Object(i.__)("Language"),value:t.language,options:[{label:Object(i.__)("Select code language"),value:""}].concat(Object.keys(mkaz_code_syntax_languages).map((function(e){return{label:mkaz_code_syntax_languages[e],value:e}}))),onChange:function(e){return o({language:e})}}),Object(l.createElement)(u.ToggleControl,{label:Object(i.__)("Show line numbers"),checked:t.lineNumbers,onChange:function(e){return o({lineNumbers:e})}}),Object(l.createElement)(u.TextControl,{label:Object(i.__)("Title for Code Block"),value:t.title,onChange:function(e){return o({title:e})},placeholder:Object(i.__)("Title or File (optional)")}))),r||!t.language?Object(l.createElement)("div",{key:"editor-wrapper",className:n},Object(l.createElement)(c.PlainText,{value:t.content,onChange:function(e){return o({content:e})},placeholder:Object(i.__)("Write code…")}),Object(l.createElement)("div",{style:s},mkaz_code_syntax_languages[t.language])):Object(l.createElement)("pre",{title:t.title},Object(l.createElement)("code",{lang:t.language,className:a},t.content)))},g=function(e){var t=e.attributes,n="";return n=t.language?"language-"+t.language:"",n=t.lineNumbers?n+" line-numbers":n,Object(l.createElement)("pre",{title:t.title},Object(l.createElement)("code",{lang:t.language,className:n},t.content))};function f(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function p(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?f(Object(n),!0).forEach((function(t){o()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):f(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}Object(a.addFilter)("blocks.registerBlockType","mkaz/code-syntax-block",(function(e){return"core/code"!==e.name?e:p({},e,{attributes:p({},e.attributes,{language:{type:"string",selector:"code",source:"attribute",attribute:"lang"},lineNumbers:{type:"boolean"},title:{type:"string",source:"attribute",selector:"pre",attribute:"title"}}),edit:b,save:g})}))}]);