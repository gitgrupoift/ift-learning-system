
(function(a,b){function c(a,c){var d=" to exec \u300C"+a+"\u300D command"+(c?" with value: "+c:"");try{b.execCommand(a,!1,c)}catch(a){return P.log("fail"+d,!0)}P.log("success"+d)}function d(a,b,d){var e=B(a);if(e)return a._range.selectNode(e),a._range.collapse(!1),"insertimage"===b&&a._menu&&L(a._menu,!0),c(b,d)}function e(a,b){var d=C(a),e=d.map(function(a){return a.nodeName.toLowerCase()});return-1!==e.indexOf(b)&&(b="p"),c("formatblock",b)}function f(a,b,d){return d="<"+b+">"+(d||O.toString())+"</"+b+">",c("insertHTML",d)}function g(a,b,d){return a.config.linksInNewWindow&&"unlink"!==b?(d="<a href=\""+d+"\" target=\"_blank\">"+O.toString()+"</a>",c("insertHTML",d)):c(b,d)}function h(a,b,c,d){var e=a.config.titles[b]||"",f=document.createElement("div");f.classList.add("pen-icon"),f.setAttribute("title",e),"parent"===c?(f.classList.add("pen-group-icon"),f.setAttribute("data-group-toggle",b)):f.setAttribute("data-action",b),"child"===c&&f.setAttribute("data-group",d);var g=a.config.toolbarIconsDictionary[b];if(g&&g.text)f.textContent=g.text;else{var h;h=g&&g.className?g.className:a.config.toolbarIconsPrefix+b,f.innerHTML+="<i class=\""+h+"\"  ></i>"}return f.outerHTML}function i(a){return Q.call(a._menu.children)}function j(a,b){var c=i(a);c.forEach(function(a){L(a,a.getAttribute("data-group")!==b)}),o(a,!b),a.refreshMenuPosition()}function k(a){j(a,null),m(a,!0),n(a,!a._urlInput||""===a._urlInput.value)}function l(a){var b=i(a);b.forEach(function(a){L(a,!0)}),m(a),o(a)}function m(a,b){var c=a._menu.querySelector(".pen-input-wrapper");c&&L(c,b)}function n(a,b){var c=a._menu.querySelector("[data-action=\"unlink\"]");c&&(L(c,b),a.refreshMenuPosition())}function o(a,b){var c=a._menu.querySelector("[data-action=\"close\"]");L(c,b),a.refreshMenuPosition()}function p(a){var c=b.createElement("div"),d=b.createElement("input"),e=b.createElement("label"),f=b.createElement("input"),g=b.createElement("i");return c.className="pen-input-wrapper",d.className="pen-url-input",d.type="url",d.placeholder="http://",e.className="pen-icon pen-input-label",f.className="pen-external-url-checkbox",f.type="checkbox",g.className=a.config.toolbarIconsDictionary.externalLink.className,e.appendChild(f),e.appendChild(g),c.appendChild(d),c.appendChild(e),c}function q(a,b,c){a.execCommand(b,c),a._range=a.getRange(),a.highlight().menu()}function r(a,b){for(var c,d=a._toolbar||a._menu;!(c=b.getAttribute("data-action"))&&b.parentNode!==d;)b=b.parentNode;var e=b.getAttribute("data-group-toggle");if(e&&j(a,e),!!c){if("close"===c)return void k(a);if(!/(?:createlink)|(?:insertimage)/.test(c))return q(a,c);if(a._urlInput){var f=a._urlInput;if(d===a._menu?l(a):(a._inputActive=!0,a.menu()),"none"!==a._menu.style.display){setTimeout(function(){f.focus()},10);var g=function(){var b=f.value;b?(a.config.linksInNewWindow=a._externalUrlCheckbox.checked,b=f.value.replace(U.whiteSpace,"").replace(U.mailTo,"mailto:$1").replace(U.http,"http://$1")):c="unlink",q(a,c,b)};f.onkeypress=function(a){13===a.which&&(a.preventDefault(),g())},a._externalUrlCheckbox.onchange=g}}}}function s(a){var c="",d=p(a).outerHTML;if(a._toolbar=a.config.toolbar,!a._toolbar){var e=a.config.list;if(!Object.values(e).length)return;P.forEach(e,function(b,d){if(Array.isArray(b)){var e=b;b=d,c+=h(a,b,"parent"),P.forEach(e,function(d){c+=h(a,d,"child",b)},!0)}else c+=h(a,b)});var f=Object.values(e);(0<=f.indexOf("createlink")||0<=f.indexOf("insertimage"))&&(c+=d),c+=h(a,"close")}else(a._toolbar.querySelectorAll("[data-action=createlink]").length||a._toolbar.querySelectorAll("[data-action=insertimage]").length)&&(c+=d);c&&(a._menu=b.createElement("div"),a._menu.setAttribute("class",a.config.class+"-menu pen-menu"),a._menu.innerHTML=c,a._urlInput=a._menu.querySelector(".pen-url-input"),a._externalUrlCheckbox=a._menu.querySelector(".pen-external-url-checkbox"),L(a._menu,!0),b.body.appendChild(a._menu))}function t(d){function f(a){d._range=d.getRange(),i(a)}var g=d._toolbar||d._menu,h=d.config.editor,i=P.delayExec(function(){g&&d.highlight().menu()}),j=function(){};if(d._menu){var k=function(){"flex"===d._menu.style.display&&d.menu()};u(d,a,"resize",k),u(d,a,"scroll",k);var l=!1;u(d,h,"mousedown",function(){l=!0}),u(d,h,"mouseleave",function(){l&&f(800),l=!1}),u(d,h,"mouseup",function(){l&&f(200),l=!1}),j=function(a){!d._menu||A(h,a.target)||A(d._menu,a.target)||(w(d,b,"click",j),i(100))}}else u(d,h,"click",function(){f(0)});u(d,h,"keyup",function(a){if(y(d),d.isEmpty())return void("advanced"===d.config.mode&&E(d));if(G(d)&&!H(d)&&"advanced"!==d.config.mode&&(h.innerHTML=h.innerHTML.replace(/\u200b/,""),F(d)),13!==a.which||a.shiftKey)return f(400);var c=B(d,!0);c&&c.nextSibling&&S.test(c.nodeName)&&c.nodeName===c.nextSibling.nodeName&&("BR"!==c.lastChild.nodeName&&c.appendChild(b.createElement("br")),P.forEach(c.nextSibling.childNodes,function(a){a&&c.appendChild(a)},!0),c.parentNode.removeChild(c.nextSibling),I(d,c.lastChild,d.getRange()))}),u(d,h,"keydown",function(a){if(h.classList.remove(d.config.placeholderClass),!(13!==a.which||a.shiftKey)){if(d.config.ignoreLineBreak)return void a.preventDefault();var e=B(d,!0);if(!e||!S.test(e.nodeName))return void("basic"===d.config.mode&&(a.preventDefault(),c("insertHTML","<br>")));if(e){var f=e.lastChild;if(f&&f.previousSibling&&!(f.previousSibling.textContent||f.textContent)){a.preventDefault();var g=b.createElement("p");g.innerHTML="<br>",e.removeChild(f),e.nextSibling?e.parentNode.insertBefore(g,e.nextSibling):e.parentNode.appendChild(g),I(d,g,d.getRange())}}}}),g&&u(d,g,"click",function(a){r(d,a.target)}),u(d,h,"focus",function(){d.isEmpty()&&"advanced"===d.config.mode&&E(d),u(d,b,"click",j)}),u(d,h,"blur",function(){y(d),d.checkContentChange()}),u(d,h,"paste",function(){setTimeout(function(){d.cleanContent()})})}function u(a,b,c,d){if(a._events.hasOwnProperty(c))a._events[c].push(d);else{a._eventTargets=a._eventTargets||[],a._eventsCache=a._eventsCache||[];var e=a._eventTargets.indexOf(b);0>e&&(e=a._eventTargets.push(b)-1),a._eventsCache[e]=a._eventsCache[e]||{},a._eventsCache[e][c]=a._eventsCache[e][c]||[],a._eventsCache[e][c].push(d),b.addEventListener(c,d,!1)}return a}function v(a,b){if(a._events.hasOwnProperty(b)){var c=Q.call(arguments,2);P.forEach(a._events[b],function(b){b.apply(a,c)})}}function w(a,b,c,d){var e=a._events[c];if(!e){var f=a._eventTargets.indexOf(b);0<=f&&(e=a._eventsCache[f][c])}if(!e)return a;var g=e.indexOf(d);return 0<=g&&e.splice(g,1),b.removeEventListener(c,d,!1),a}function x(a){return(P.forEach(this._events,function(a){a.length=0},!1),!a._eventsCache)?a:(P.forEach(a._eventsCache,function(b,c){var d=a._eventTargets[c];P.forEach(b,function(a,b){P.forEach(a,function(a){d.removeEventListener(b,a,!1)},!0)},!1)},!0),a._eventTargets=[],a._eventsCache=[],a)}function y(a){a.config.editor.classList[a.isEmpty()?"add":"remove"](a.config.placeholderClass)}function z(a){return(a||"").trim().replace(/\u200b/g,"")}function A(a,b){if(a===b)return!0;for(b=b.parentNode;b;){if(b===a)return!0;b=b.parentNode}return!1}function B(a,b){var c,d=a.config.editor;if(a._range=a._range||a.getRange(),c=a._range.commonAncestorContainer,c.hasChildNodes()&&a._range.startOffset+1===a._range.endOffset&&(c=c.childNodes[a._range.startOffset]),!c||c===d)return null;for(;c&&1!==c.nodeType&&c.parentNode!==d;)c=c.parentNode;for(;c&&b&&c.parentNode!==d;)c=c.parentNode;return A(d,c)?c:null}function C(a){return D(a).filter(function(a){return a.nodeName.match(T)})}function D(a){for(var b=[],c=B(a);c&&c!==a.config.editor;)c.nodeType===Node.ELEMENT_NODE&&b.push(c),c=c.parentNode;return b}function E(a){var c=a._range=a.getRange();a.config.editor.innerHTML="";var d=b.createElement("p");d.innerHTML="<br>",c.insertNode(d),I(a,d.childNodes[0],c)}function F(a){var c=a.getRange(),d=b.createTextNode("\u200B");c.selectNodeContents(a.config.editor),c.collapse(!1),c.insertNode(d),I(a,d,c)}function G(a){var b=a.getRange(),c=b.cloneRange();return c.selectNodeContents(a.config.editor),c.setStart(b.endContainer,b.endOffset),""===c.toString()}function H(a){var b=a.getRange(),c=b.cloneRange();return c.selectNodeContents(a.config.editor),c.setEnd(b.startContainer,b.startOffset),""===c.toString()}function I(a,b,c){c.setStartAfter(b),c.setEndBefore(b),c.collapse(!1),a.setRange(c)}function J(a){if(1===a.nodeType){if(V.notLink.test(a.tagName))return;P.forEach(a.childNodes,function(a){J(a)},!0)}else if(3===a.nodeType){var c=K(a.nodeValue||"");if(!c.links)return;var d=b.createDocumentFragment(),e=b.createElement("div");for(e.innerHTML=c.text;e.childNodes.length;)d.appendChild(e.childNodes[0]);a.parentNode.replaceChild(d,a)}}function K(a){var b=0;return a=a.replace(V.url,function(a){var c=a,d=a;return b++,a.length>V.maxLength&&(d=a.slice(0,V.maxLength)+"..."),V.prefix.test(c)||(c="http://"+c),"<a href=\""+c+"\">"+d+"</a>"}),{links:b,text:a}}function L(a,b){a.style.display=b?"none":"flex"}var M,N,O,P={},Q=Array.prototype.slice,R={block:/^(?:p|h[1-6]|blockquote|pre)$/,inline:/^(?:justify(center|full|left|right)|strikethrough|insert(un)?orderedlist|(in|out)dent)$/,biu:/^(bold|italic|underline)$/,source:/^(?:createlink|unlink)$/,insert:/^(?:inserthorizontalrule|insertimage|insert)$/,wrap:/^(?:code)$/},S=/^(?:blockquote|pre|div)$/i,T=/(?:[pubia]|strong|em|h[1-6]|blockquote|code|[uo]l|li)/i,U={whiteSpace:/(^\s+)|(\s+$)/g,mailTo:/^(?!mailto:|.+\/|.+#|.+\?)(.*@.*\..+)$/,http:/^(?!\w+?:\/\/|mailto:|\/|\.\/|\?|#)(.*)$/},V={url:/((https?|ftp):\/\/|www\.)[^\s<]{3,}/gi,prefix:/^(?:https?|ftp):\/\//i,notLink:/^(?:img|a|input|audio|video|source|code|pre|script|head|title|style)$/i,maxLength:100},W={bold:{styleKey:"font-weight",correctValue:"normal"},italic:{styleKey:"font-style",correctValue:"normal"},underline:{styleKey:"text-decoration",correctValue:"none"}};P.is=function(a,b){return Object.prototype.toString.call(a).slice(8,-1)===b},P.forEach=function(a,b,c){if(a)if(null==c&&(c=P.is(a,"Array")),c)for(var d=0,e=a.length;d<e;d++)b(a[d],d,a);else for(var f in a)a.hasOwnProperty(f)&&b(a[f],f,a)},P.copy=function(a,b){return P.forEach(b,function(b,c){a[c]=P.is(b,"Object")?P.copy({},b):P.is(b,"Array")?P.copy([],b):b}),a},P.log=function(a,b){(N||b)&&console.log("%cPEN DEBUGGER: %c"+a,"font-family:arial,sans-serif;color:#1abf89;line-height:2em;","font-family:cursor,monospace;color:#333;")},P.delayExec=function(a){var b=null;return function(c){clearTimeout(b),b=setTimeout(function(){a()},c||1)}},P.merge=function(a){var c={class:"pen",placeholderClass:"pen-placeholder",placeholderAttr:"data-pen-placeholder",debug:!1,toolbar:null,mode:"basic",ignoreLineBreak:!1,toolbarIconsPrefix:"fa fa-",toolbarIconsDictionary:{externalLink:"eicon-editor-external-link"},stay:a.stay||!a.debug,stayMsg:"Are you going to leave here?",textarea:"<textarea name=\"content\"></textarea>",list:["blockquote","h2","h3","p","code","insertOrderedList","insertUnorderedList","inserthorizontalrule","indent","outdent","bold","italic","underline","createlink","insertimage"],titles:{},cleanAttrs:["id","class","style","name"],cleanTags:["script"],linksInNewWindow:!1};return 1===a.nodeType?c.editor=a:a.match&&a.match(/^#[\S]+$/)?c.editor=b.getElementById(a.slice(1)):c=P.copy(c,a),c},M=function(a){if(!a)throw new Error("Can't find config");N=a.debug;var b=P.merge(a),c=b.editor;if(!c||1!==c.nodeType)throw new Error("Can't find editor");c.classList.add.apply(c.classList,b.class.split(" ")),c.setAttribute("contenteditable","true"),this.config=b,b.placeholder&&c.setAttribute(this.config.placeholderAttr,b.placeholder),y(this),this.selection=O,this._events={change:[]},s(this),t(this),this._prevContent=this.getContent(),this.markdown&&this.markdown.init(this),this.config.stay&&this.stay(this.config),this.config.input&&this.addOnSubmitListener(this.config.input),"advanced"===this.config.mode?(this.getRange().selectNodeContents(c),this.setRange()):F(this)},M.prototype.on=function(a,b){return u(this,this.config.editor,a,b),this},M.prototype.addOnSubmitListener=function(a){var b=a.form,c=this;b.addEventListener("submit",function(){a.value=c.config.saveAsMarkdown?c.toMd(c.config.editor.innerHTML):c.config.editor.innerHTML})},M.prototype.isEmpty=function(a){return a=a||this.config.editor,!a.querySelector("img")&&!a.querySelector("blockquote")&&!a.querySelector("li")&&!z(a.textContent)},M.prototype.getContent=function(){return this.isEmpty()?"":z(this.config.editor.innerHTML)},M.prototype.setContent=function(a){return this.config.editor.innerHTML=a,this.cleanContent(),this},M.prototype.checkContentChange=function(){var a=this._prevContent,b=this.getContent();a===b||(this._prevContent=b,v(this,"change",b,a))},M.prototype.getRange=function(){var a=this.config.editor,c=O.rangeCount&&O.getRangeAt(0);return c||(c=b.createRange()),A(a,c.commonAncestorContainer)||(c.selectNodeContents(a),c.collapse(!1)),c},M.prototype.setRange=function(a){a=a||this._range,a||(a=this.getRange(),a.collapse(!1));try{O.removeAllRanges(),O.addRange(a)}catch(a){}return this},M.prototype.focus=function(a){return a||this.setRange(),this.config.editor.focus(),this},M.prototype.execCommand=function(a,b){if(a=a.toLowerCase(),this.setRange(),R.block.test(a))e(this,a);else if(R.inline.test(a))c(a,b);else if(R.biu.test(a)){var h=W[a];h.backupValue=this.config.editor.style[h.styleKey],this.config.editor.style[h.styleKey]=h.correctValue,c(a,b),this.config.editor.style[h.styleKey]=h.backupValue}else R.source.test(a)?g(this,a,b):R.insert.test(a)?d(this,a,b):R.wrap.test(a)?f(this,a,b):P.log("can not find command function for name: "+a+(b?", value: "+b:""),!0);"indent"===a&&this.checkContentChange()},M.prototype.cleanContent=function(a){var b=this.config.editor;return a||(a=this.config),P.forEach(a.cleanAttrs,function(a){P.forEach(b.querySelectorAll("["+a+"]"),function(b){b.removeAttribute(a)},!0)},!0),P.forEach(a.cleanTags,function(a){P.forEach(b.querySelectorAll(a),function(a){a.parentNode.removeChild(a)},!0)},!0),y(this),this.checkContentChange(),this},M.prototype.autoLink=function(){return J(this.config.editor),this.getContent()},M.prototype.highlight=function(){var a=this._toolbar||this._menu,b=B(this);if(P.forEach(a.querySelectorAll(".active"),function(a){a.classList.remove("active")},!0),!b)return this;var c,d=D(this),e=this._urlInput,f=this._externalUrlCheckbox;return e&&a===this._menu&&(e.value="",this._externalUrlCheckbox.checked=!1),c=function(b){if(b){var c=a.querySelector("[data-action="+b+"]");return c&&c.classList.add("active")}},P.forEach(d,function(a){var b=a.nodeName.toLowerCase(),d=a.style.textAlign,g=a.style.textDecoration;(d&&("justify"===d&&(d="full"),c("justify"+d[0].toUpperCase()+d.slice(1))),"underline"===g&&c("underline"),!!b.match(T))&&("a"===b?(e.value=a.getAttribute("href"),f.checked="_blank"===a.getAttribute("target"),b="createlink"):"img"===b?(e.value=a.getAttribute("src"),b="insertimage"):"i"===b||"em"===b?b="italic":"u"===b?b="underline":"b"===b||"strong"===b?b="bold":"strike"===b?b="strikethrough":"ul"===b?b="insertUnorderedList":"ol"===b?b="insertOrderedList":"li"===b?b="indent":void 0,c(b))},!0),this},M.prototype.menu=function(){return this._menu?O.isCollapsed?(this._menu.style.display="none",this._inputActive=!1,this):!this._toolbar||this._urlInput&&this._inputActive?void k(this):this:this},M.prototype.refreshMenuPosition=function(){var a=this._range.getBoundingClientRect(),b=a.top-10,c=a.left+a.width/2,d=this._menu,e={x:0,y:0},f=this._stylesheet;if(0===a.width&&0===a.height)return this;if(void 0===this._stylesheet){var g=document.createElement("style");document.head.appendChild(g),this._stylesheet=f=g.sheet}return d.style.display="flex",e.x=c-d.clientWidth/2,e.y=b-d.clientHeight,0<f.cssRules.length&&f.deleteRule(0),0>e.x?(e.x=0,f.insertRule(".pen-menu:after {left: "+c+"px;}",0)):f.insertRule(".pen-menu:after {left: 50%; }",0),0>e.y?(d.classList.add("pen-menu-below"),e.y=a.top+a.height+10):d.classList.remove("pen-menu-below"),d.style.top=e.y+"px",d.style.left=e.x+"px",this},M.prototype.stay=function(a){var b=this;window.onbeforeunload||(window.onbeforeunload=function(){if(!b._isDestroyed)return a.stayMsg})},M.prototype.destroy=function(){var a=this.config;x(this),a.editor.classList.remove.apply(a.editor.classList,a.class.split(" ").concat(a.placeholderClass)),a.editor.removeAttribute("contenteditable"),a.editor.removeAttribute(a.placeholderAttr);try{O.removeAllRanges(),this._menu&&this._menu.parentNode.removeChild(this._menu)}catch(a){}return this._isDestroyed=!0,this},M.prototype.rebuild=function(){return s(this),t(this),this},a.ElementorInlineEditor=function(a){if(!a)return P.log("can't find config",!0);var b=P.merge(a),c=b.editor.getAttribute("class");return c=c?c.replace(/\bpen\b/g,"")+" pen-textarea "+b.class:"pen pen-textarea",b.editor.setAttribute("class",c),b.editor.innerHTML=b.textarea,b.editor};var X={a:[/<a\b[^>]*href=["']([^"]+|[^']+)\b[^>]*>(.*?)<\/a>/ig,"[$2]($1)"],img:[/<img\b[^>]*src=["']([^\"+|[^']+)[^>]*>/ig,"![]($1)"],b:[/<b\b[^>]*>(.*?)<\/b>/ig,"**$1**"],i:[/<i\b[^>]*>(.*?)<\/i>/ig,"***$1***"],h:[/<h([1-6])\b[^>]*>(.*?)<\/h\1>/ig,function(d,a,b){return"\n"+"######".slice(0,a)+" "+b+"\n"}],li:[/<(li)\b[^>]*>(.*?)<\/\1>/ig,"* $2\n"],blockquote:[/<(blockquote)\b[^>]*>(.*?)<\/\1>/ig,"\n> $2\n"],pre:[/<pre\b[^>]*>(.*?)<\/pre>/ig,"\n```\n$1\n```\n"],code:[/<code\b[^>]*>(.*?)<\/code>/ig,"\n`\n$1\n`\n"],p:[/<p\b[^>]*>(.*?)<\/p>/ig,"\n$1\n"],hr:[/<hr\b[^>]*>/ig,"\n---\n"]};M.prototype.toMd=function(){var a=this.getContent().replace(/\n+/g,"").replace(/<([uo])l\b[^>]*>(.*?)<\/\1l>/ig,"$2");for(var b in X)X.hasOwnProperty(b)&&(a=a.replace.apply(a,X[b]));return a.replace(/\*{5}/g,"**")},b.getSelection&&(O=b.getSelection(),a.ElementorInlineEditor=M)})(window,document);