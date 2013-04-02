/* Modernizr 2.6.2 (Custom Build) | MIT & BSD
 * Build: http://modernizr.com/download/#-fontface-backgroundsize-borderimage-borderradius-boxshadow-flexbox-hsla-multiplebgs-opacity-rgba-textshadow-cssanimations-csscolumns-generatedcontent-cssgradients-cssreflections-csstransforms-csstransforms3d-csstransitions-applicationcache-canvas-canvastext-draganddrop-hashchange-history-audio-video-indexeddb-input-inputtypes-localstorage-postmessage-sessionstorage-websockets-websqldatabase-webworkers-geolocation-inlinesvg-smil-svg-svgclippaths-touch-webgl-shiv-mq-cssclasses-addtest-prefixed-teststyles-testprop-testallprops-hasevent-prefixes-domprefixes-load
 */
;window.Modernizr=function(a,b,c){function D(a){j.cssText=a}function E(a,b){return D(n.join(a+";")+(b||""))}function F(a,b){return typeof a===b}function G(a,b){return!!~(""+a).indexOf(b)}function H(a,b){for(var d in a){var e=a[d];if(!G(e,"-")&&j[e]!==c)return b=="pfx"?e:!0}return!1}function I(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:F(f,"function")?f.bind(d||b):f}return!1}function J(a,b,c){var d=a.charAt(0).toUpperCase()+a.slice(1),e=(a+" "+p.join(d+" ")+d).split(" ");return F(b,"string")||F(b,"undefined")?H(e,b):(e=(a+" "+q.join(d+" ")+d).split(" "),I(e,b,c))}function K(){e.input=function(c){for(var d=0,e=c.length;d<e;d++)u[c[d]]=c[d]in k;return u.list&&(u.list=!!b.createElement("datalist")&&!!a.HTMLDataListElement),u}("autocomplete autofocus list placeholder max min multiple pattern required step".split(" ")),e.inputtypes=function(a){for(var d=0,e,f,h,i=a.length;d<i;d++)k.setAttribute("type",f=a[d]),e=k.type!=="text",e&&(k.value=l,k.style.cssText="position:absolute;visibility:hidden;",/^range$/.test(f)&&k.style.WebkitAppearance!==c?(g.appendChild(k),h=b.defaultView,e=h.getComputedStyle&&h.getComputedStyle(k,null).WebkitAppearance!=="textfield"&&k.offsetHeight!==0,g.removeChild(k)):/^(search|tel)$/.test(f)||(/^(url|email)$/.test(f)?e=k.checkValidity&&k.checkValidity()===!1:e=k.value!=l)),t[a[d]]=!!e;return t}("search tel url email datetime date month week time datetime-local number range color".split(" "))}var d="2.6.2",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k=b.createElement("input"),l=":)",m={}.toString,n=" -webkit- -moz- -o- -ms- ".split(" "),o="Webkit Moz O ms",p=o.split(" "),q=o.toLowerCase().split(" "),r={svg:"http://www.w3.org/2000/svg"},s={},t={},u={},v=[],w=v.slice,x,y=function(a,c,d,e){var f,i,j,k,l=b.createElement("div"),m=b.body,n=m||b.createElement("body");if(parseInt(d,10))while(d--)j=b.createElement("div"),j.id=e?e[d]:h+(d+1),l.appendChild(j);return f=["&#173;",'<style id="s',h,'">',a,"</style>"].join(""),l.id=h,(m?l:n).innerHTML+=f,n.appendChild(l),m||(n.style.background="",n.style.overflow="hidden",k=g.style.overflow,g.style.overflow="hidden",g.appendChild(n)),i=c(l,a),m?l.parentNode.removeChild(l):(n.parentNode.removeChild(n),g.style.overflow=k),!!i},z=function(b){var c=a.matchMedia||a.msMatchMedia;if(c)return c(b).matches;var d;return y("@media "+b+" { #"+h+" { position: absolute; } }",function(b){d=(a.getComputedStyle?getComputedStyle(b,null):b.currentStyle)["position"]=="absolute"}),d},A=function(){function d(d,e){e=e||b.createElement(a[d]||"div"),d="on"+d;var f=d in e;return f||(e.setAttribute||(e=b.createElement("div")),e.setAttribute&&e.removeAttribute&&(e.setAttribute(d,""),f=F(e[d],"function"),F(e[d],"undefined")||(e[d]=c),e.removeAttribute(d))),e=null,f}var a={select:"input",change:"input",submit:"form",reset:"form",error:"img",load:"img",abort:"img"};return d}(),B={}.hasOwnProperty,C;!F(B,"undefined")&&!F(B.call,"undefined")?C=function(a,b){return B.call(a,b)}:C=function(a,b){return b in a&&F(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=w.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(w.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(w.call(arguments)))};return e}),s.flexbox=function(){return J("flexWrap")},s.canvas=function(){var a=b.createElement("canvas");return!!a.getContext&&!!a.getContext("2d")},s.canvastext=function(){return!!e.canvas&&!!F(b.createElement("canvas").getContext("2d").fillText,"function")},s.webgl=function(){return!!a.WebGLRenderingContext},s.touch=function(){var c;return"ontouchstart"in a||a.DocumentTouch&&b instanceof DocumentTouch?c=!0:y(["@media (",n.join("touch-enabled),("),h,")","{#modernizr{top:9px;position:absolute}}"].join(""),function(a){c=a.offsetTop===9}),c},s.geolocation=function(){return"geolocation"in navigator},s.postmessage=function(){return!!a.postMessage},s.websqldatabase=function(){return!!a.openDatabase},s.indexedDB=function(){return!!J("indexedDB",a)},s.hashchange=function(){return A("hashchange",a)&&(b.documentMode===c||b.documentMode>7)},s.history=function(){return!!a.history&&!!history.pushState},s.draganddrop=function(){var a=b.createElement("div");return"draggable"in a||"ondragstart"in a&&"ondrop"in a},s.websockets=function(){return"WebSocket"in a||"MozWebSocket"in a},s.rgba=function(){return D("background-color:rgba(150,255,150,.5)"),G(j.backgroundColor,"rgba")},s.hsla=function(){return D("background-color:hsla(120,40%,100%,.5)"),G(j.backgroundColor,"rgba")||G(j.backgroundColor,"hsla")},s.multiplebgs=function(){return D("background:url(https://),url(https://),red url(https://)"),/(url\s*\(.*?){3}/.test(j.background)},s.backgroundsize=function(){return J("backgroundSize")},s.borderimage=function(){return J("borderImage")},s.borderradius=function(){return J("borderRadius")},s.boxshadow=function(){return J("boxShadow")},s.textshadow=function(){return b.createElement("div").style.textShadow===""},s.opacity=function(){return E("opacity:.55"),/^0.55$/.test(j.opacity)},s.cssanimations=function(){return J("animationName")},s.csscolumns=function(){return J("columnCount")},s.cssgradients=function(){var a="background-image:",b="gradient(linear,left top,right bottom,from(#9f9),to(white));",c="linear-gradient(left top,#9f9, white);";return D((a+"-webkit- ".split(" ").join(b+a)+n.join(c+a)).slice(0,-a.length)),G(j.backgroundImage,"gradient")},s.cssreflections=function(){return J("boxReflect")},s.csstransforms=function(){return!!J("transform")},s.csstransforms3d=function(){var a=!!J("perspective");return a&&"webkitPerspective"in g.style&&y("@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}",function(b,c){a=b.offsetLeft===9&&b.offsetHeight===3}),a},s.csstransitions=function(){return J("transition")},s.fontface=function(){var a;return y('@font-face {font-family:"font";src:url("https://")}',function(c,d){var e=b.getElementById("smodernizr"),f=e.sheet||e.styleSheet,g=f?f.cssRules&&f.cssRules[0]?f.cssRules[0].cssText:f.cssText||"":"";a=/src/i.test(g)&&g.indexOf(d.split(" ")[0])===0}),a},s.generatedcontent=function(){var a;return y(["#",h,"{font:0/0 a}#",h,':after{content:"',l,'";visibility:hidden;font:3px/1 a}'].join(""),function(b){a=b.offsetHeight>=3}),a},s.video=function(){var a=b.createElement("video"),c=!1;try{if(c=!!a.canPlayType)c=new Boolean(c),c.ogg=a.canPlayType('video/ogg; codecs="theora"').replace(/^no$/,""),c.h264=a.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/,""),c.webm=a.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/,"")}catch(d){}return c},s.audio=function(){var a=b.createElement("audio"),c=!1;try{if(c=!!a.canPlayType)c=new Boolean(c),c.ogg=a.canPlayType('audio/ogg; codecs="vorbis"').replace(/^no$/,""),c.mp3=a.canPlayType("audio/mpeg;").replace(/^no$/,""),c.wav=a.canPlayType('audio/wav; codecs="1"').replace(/^no$/,""),c.m4a=(a.canPlayType("audio/x-m4a;")||a.canPlayType("audio/aac;")).replace(/^no$/,"")}catch(d){}return c},s.localstorage=function(){try{return localStorage.setItem(h,h),localStorage.removeItem(h),!0}catch(a){return!1}},s.sessionstorage=function(){try{return sessionStorage.setItem(h,h),sessionStorage.removeItem(h),!0}catch(a){return!1}},s.webworkers=function(){return!!a.Worker},s.applicationcache=function(){return!!a.applicationCache},s.svg=function(){return!!b.createElementNS&&!!b.createElementNS(r.svg,"svg").createSVGRect},s.inlinesvg=function(){var a=b.createElement("div");return a.innerHTML="<svg/>",(a.firstChild&&a.firstChild.namespaceURI)==r.svg},s.smil=function(){return!!b.createElementNS&&/SVGAnimate/.test(m.call(b.createElementNS(r.svg,"animate")))},s.svgclippaths=function(){return!!b.createElementNS&&/SVGClipPath/.test(m.call(b.createElementNS(r.svg,"clipPath")))};for(var L in s)C(s,L)&&(x=L.toLowerCase(),e[x]=s[L](),v.push((e[x]?"":"no-")+x));return e.input||K(),e.addTest=function(a,b){if(typeof a=="object")for(var d in a)C(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof f!="undefined"&&f&&(g.className+=" "+(b?"":"no-")+a),e[a]=b}return e},D(""),i=k=null,function(a,b){function k(a,b){var c=a.createElement("p"),d=a.getElementsByTagName("head")[0]||a.documentElement;return c.innerHTML="x<style>"+b+"</style>",d.insertBefore(c.lastChild,d.firstChild)}function l(){var a=r.elements;return typeof a=="string"?a.split(" "):a}function m(a){var b=i[a[g]];return b||(b={},h++,a[g]=h,i[h]=b),b}function n(a,c,f){c||(c=b);if(j)return c.createElement(a);f||(f=m(c));var g;return f.cache[a]?g=f.cache[a].cloneNode():e.test(a)?g=(f.cache[a]=f.createElem(a)).cloneNode():g=f.createElem(a),g.canHaveChildren&&!d.test(a)?f.frag.appendChild(g):g}function o(a,c){a||(a=b);if(j)return a.createDocumentFragment();c=c||m(a);var d=c.frag.cloneNode(),e=0,f=l(),g=f.length;for(;e<g;e++)d.createElement(f[e]);return d}function p(a,b){b.cache||(b.cache={},b.createElem=a.createElement,b.createFrag=a.createDocumentFragment,b.frag=b.createFrag()),a.createElement=function(c){return r.shivMethods?n(c,a,b):b.createElem(c)},a.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+l().join().replace(/\w+/g,function(a){return b.createElem(a),b.frag.createElement(a),'c("'+a+'")'})+");return n}")(r,b.frag)}function q(a){a||(a=b);var c=m(a);return r.shivCSS&&!f&&!c.hasCSS&&(c.hasCSS=!!k(a,"article,aside,figcaption,figure,footer,header,hgroup,nav,section{display:block}mark{background:#FF0;color:#000}")),j||p(a,c),a}var c=a.html5||{},d=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,e=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,f,g="_html5shiv",h=0,i={},j;(function(){try{var a=b.createElement("a");a.innerHTML="<xyz></xyz>",f="hidden"in a,j=a.childNodes.length==1||function(){b.createElement("a");var a=b.createDocumentFragment();return typeof a.cloneNode=="undefined"||typeof a.createDocumentFragment=="undefined"||typeof a.createElement=="undefined"}()}catch(c){f=!0,j=!0}})();var r={elements:c.elements||"abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output progress section summary time video",shivCSS:c.shivCSS!==!1,supportsUnknownElements:j,shivMethods:c.shivMethods!==!1,type:"default",shivDocument:q,createElement:n,createDocumentFragment:o};a.html5=r,q(b)}(this,b),e._version=d,e._prefixes=n,e._domPrefixes=q,e._cssomPrefixes=p,e.mq=z,e.hasEvent=A,e.testProp=function(a){return H([a])},e.testAllProps=J,e.testStyles=y,e.prefixed=function(a,b,c){return b?J(a,b,c):J(a,"pfx")},g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+v.join(" "):""),e}(this,this.document),function(a,b,c){function d(a){return"[object Function]"==o.call(a)}function e(a){return"string"==typeof a}function f(){}function g(a){return!a||"loaded"==a||"complete"==a||"uninitialized"==a}function h(){var a=p.shift();q=1,a?a.t?m(function(){("c"==a.t?B.injectCss:B.injectJs)(a.s,0,a.a,a.x,a.e,1)},0):(a(),h()):q=0}function i(a,c,d,e,f,i,j){function k(b){if(!o&&g(l.readyState)&&(u.r=o=1,!q&&h(),l.onload=l.onreadystatechange=null,b)){"img"!=a&&m(function(){t.removeChild(l)},50);for(var d in y[c])y[c].hasOwnProperty(d)&&y[c][d].onload()}}var j=j||B.errorTimeout,l=b.createElement(a),o=0,r=0,u={t:d,s:c,e:f,a:i,x:j};1===y[c]&&(r=1,y[c]=[]),"object"==a?l.data=c:(l.src=c,l.type=a),l.width=l.height="0",l.onerror=l.onload=l.onreadystatechange=function(){k.call(this,r)},p.splice(e,0,u),"img"!=a&&(r||2===y[c]?(t.insertBefore(l,s?null:n),m(k,j)):y[c].push(l))}function j(a,b,c,d,f){return q=0,b=b||"j",e(a)?i("c"==b?v:u,a,b,this.i++,c,d,f):(p.splice(this.i++,0,a),1==p.length&&h()),this}function k(){var a=B;return a.loader={load:j,i:0},a}var l=b.documentElement,m=a.setTimeout,n=b.getElementsByTagName("script")[0],o={}.toString,p=[],q=0,r="MozAppearance"in l.style,s=r&&!!b.createRange().compareNode,t=s?l:n.parentNode,l=a.opera&&"[object Opera]"==o.call(a.opera),l=!!b.attachEvent&&!l,u=r?"object":l?"script":"img",v=l?"script":u,w=Array.isArray||function(a){return"[object Array]"==o.call(a)},x=[],y={},z={timeout:function(a,b){return b.length&&(a.timeout=b[0]),a}},A,B;B=function(a){function b(a){var a=a.split("!"),b=x.length,c=a.pop(),d=a.length,c={url:c,origUrl:c,prefixes:a},e,f,g;for(f=0;f<d;f++)g=a[f].split("="),(e=z[g.shift()])&&(c=e(c,g));for(f=0;f<b;f++)c=x[f](c);return c}function g(a,e,f,g,h){var i=b(a),j=i.autoCallback;i.url.split(".").pop().split("?").shift(),i.bypass||(e&&(e=d(e)?e:e[a]||e[g]||e[a.split("/").pop().split("?")[0]]),i.instead?i.instead(a,e,f,g,h):(y[i.url]?i.noexec=!0:y[i.url]=1,f.load(i.url,i.forceCSS||!i.forceJS&&"css"==i.url.split(".").pop().split("?").shift()?"c":c,i.noexec,i.attrs,i.timeout),(d(e)||d(j))&&f.load(function(){k(),e&&e(i.origUrl,h,g),j&&j(i.origUrl,h,g),y[i.url]=2})))}function h(a,b){function c(a,c){if(a){if(e(a))c||(j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}),g(a,j,b,0,h);else if(Object(a)===a)for(n in m=function(){var b=0,c;for(c in a)a.hasOwnProperty(c)&&b++;return b}(),a)a.hasOwnProperty(n)&&(!c&&!--m&&(d(j)?j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}:j[n]=function(a){return function(){var b=[].slice.call(arguments);a&&a.apply(this,b),l()}}(k[n])),g(a[n],j,b,n,h))}else!c&&l()}var h=!!a.test,i=a.load||a.both,j=a.callback||f,k=j,l=a.complete||f,m,n;c(h?a.yep:a.nope,!!i),i&&c(i)}var i,j,l=this.yepnope.loader;if(e(a))g(a,0,l,0);else if(w(a))for(i=0;i<a.length;i++)j=a[i],e(j)?g(j,0,l,0):w(j)?B(j):Object(j)===j&&h(j,l);else Object(a)===a&&h(a,l)},B.addPrefix=function(a,b){z[a]=b},B.addFilter=function(a){x.push(a)},B.errorTimeout=1e4,null==b.readyState&&b.addEventListener&&(b.readyState="loading",b.addEventListener("DOMContentLoaded",A=function(){b.removeEventListener("DOMContentLoaded",A,0),b.readyState="complete"},0)),a.yepnope=k(),a.yepnope.executeStack=h,a.yepnope.injectJs=function(a,c,d,e,i,j){var k=b.createElement("script"),l,o,e=e||B.errorTimeout;k.src=a;for(o in d)k.setAttribute(o,d[o]);c=j?h:c||f,k.onreadystatechange=k.onload=function(){!l&&g(k.readyState)&&(l=1,c(),k.onload=k.onreadystatechange=null)},m(function(){l||(l=1,c(1))},e),i?k.onload():n.parentNode.insertBefore(k,n)},a.yepnope.injectCss=function(a,c,d,e,g,i){var e=b.createElement("link"),j,c=i?h:c||f;e.href=a,e.rel="stylesheet",e.type="text/css";for(j in d)e.setAttribute(j,d[j]);g||(n.parentNode.insertBefore(e,n),m(c,0))}}(this,document),Modernizr.load=function(){yepnope.apply(window,[].slice.call(arguments,0))};

var bootbox=window.bootbox||function(w,n){function k(b,a){"undefined"===typeof a&&(a=p);return"string"===typeof j[a][b]?j[a][b]:a!=t?k(b,t):b}var p="en",t="en",u=!0,s="static",v="",l={},g={},m={setLocale:function(b){for(var a in j)if(a==b){p=b;return}throw Error("Invalid locale: "+b);},addLocale:function(b,a){"undefined"===typeof j[b]&&(j[b]={});for(var c in a)j[b][c]=a[c]},setIcons:function(b){g=b;if("object"!==typeof g||null===g)g={}},setBtnClasses:function(b){l=b;if("object"!==typeof l||null===
  l)l={}},alert:function(){var b="",a=k("OK"),c=null;switch(arguments.length){case 1:b=arguments[0];break;case 2:b=arguments[0];"function"==typeof arguments[1]?c=arguments[1]:a=arguments[1];break;case 3:b=arguments[0];a=arguments[1];c=arguments[2];break;default:throw Error("Incorrect number of arguments: expected 1-3");}return m.dialog(b,{label:a,icon:g.OK,"class":l.OK,callback:c},{onEscape:c||!0})},confirm:function(){var b="",a=k("CANCEL"),c=k("CONFIRM"),e=null;switch(arguments.length){case 1:b=arguments[0];
  break;case 2:b=arguments[0];"function"==typeof arguments[1]?e=arguments[1]:a=arguments[1];break;case 3:b=arguments[0];a=arguments[1];"function"==typeof arguments[2]?e=arguments[2]:c=arguments[2];break;case 4:b=arguments[0];a=arguments[1];c=arguments[2];e=arguments[3];break;default:throw Error("Incorrect number of arguments: expected 1-4");}var h=function(){if("function"===typeof e)return e(!1)};return m.dialog(b,[{label:a,icon:g.CANCEL,"class":l.CANCEL,callback:h},{label:c,icon:g.CONFIRM,"class":l.CONFIRM,
  callback:function(){if("function"===typeof e)return e(!0)}}],{onEscape:h})},prompt:function(){var b="",a=k("CANCEL"),c=k("CONFIRM"),e=null,h="";switch(arguments.length){case 1:b=arguments[0];break;case 2:b=arguments[0];"function"==typeof arguments[1]?e=arguments[1]:a=arguments[1];break;case 3:b=arguments[0];a=arguments[1];"function"==typeof arguments[2]?e=arguments[2]:c=arguments[2];break;case 4:b=arguments[0];a=arguments[1];c=arguments[2];e=arguments[3];break;case 5:b=arguments[0];a=arguments[1];
  c=arguments[2];e=arguments[3];h=arguments[4];break;default:throw Error("Incorrect number of arguments: expected 1-5");}var q=n("<form></form>");q.append("<input autocomplete=off type=text value='"+h+"' />");var h=function(){if("function"===typeof e)return e(null)},d=m.dialog(q,[{label:a,icon:g.CANCEL,"class":l.CANCEL,callback:h},{label:c,icon:g.CONFIRM,"class":l.CONFIRM,callback:function(){if("function"===typeof e)return e(q.find("input[type=text]").val())}}],{header:b,show:!1,onEscape:h});d.on("shown",
  function(){q.find("input[type=text]").focus();q.on("submit",function(a){a.preventDefault();d.find(".btn-primary").click()})});d.modal("show");return d},dialog:function(b,a,c){function e(){var a=null;"function"===typeof c.onEscape&&(a=c.onEscape());!1!==a&&f.modal("hide")}var h="",l=[];c||(c={});"undefined"===typeof a?a=[]:"undefined"==typeof a.length&&(a=[a]);for(var d=a.length;d--;){var g=null,k=null,j=null,m="",p=null;if("undefined"==typeof a[d].label&&"undefined"==typeof a[d]["class"]&&"undefined"==
  typeof a[d].callback){var g=0,k=null,r;for(r in a[d])if(k=r,1<++g)break;1==g&&"function"==typeof a[d][r]&&(a[d].label=k,a[d].callback=a[d][r])}"function"==typeof a[d].callback&&(p=a[d].callback);a[d]["class"]?j=a[d]["class"]:d==a.length-1&&2>=a.length&&(j="btn-primary");g=a[d].label?a[d].label:"Option "+(d+1);a[d].icon&&(m="<i class='"+a[d].icon+"'></i> ");k=a[d].href?a[d].href:"javascript:;";h="<a data-handler='"+d+"' class='btn "+j+"' href='"+k+"'>"+m+""+g+"</a>"+h;l[d]=p}d=["<div class='bootbox modal' tabindex='-1' style='overflow:hidden;'>"];
  if(c.header){j="";if("undefined"==typeof c.headerCloseButton||c.headerCloseButton)j="<a href='javascript:;' class='close'>&times;</a>";d.push("<div class='modal-header'>"+j+"<h3>"+c.header+"</h3></div>")}d.push("<div class='modal-body'></div>");h&&d.push("<div class='modal-footer'>"+h+"</div>");d.push("</div>");var f=n(d.join("\n"));("undefined"===typeof c.animate?u:c.animate)&&f.addClass("fade");(h="undefined"===typeof c.classes?v:c.classes)&&f.addClass(h);f.find(".modal-body").html(b);f.on("keyup.dismiss.modal",
    function(a){27===a.which&&c.onEscape&&e("escape")});f.on("click","a.close",function(a){a.preventDefault();e("close")});f.on("shown",function(){f.find("a.btn-primary:first").focus()});f.on("hidden",function(){f.remove()});f.on("click",".modal-footer a",function(b){var c=n(this).data("handler"),d=l[c],e=null;"undefined"!==typeof c&&"undefined"!==typeof a[c].href||(b.preventDefault(),"function"===typeof d&&(e=d()),!1!==e&&f.modal("hide"))});n("body").append(f);f.modal({backdrop:"undefined"===typeof c.backdrop?
    s:c.backdrop,keyboard:!1,show:!1});f.on("show",function(){n(w).off("focusin.modal")});("undefined"===typeof c.show||!0===c.show)&&f.modal("show");return f},modal:function(){var b,a,c,e={onEscape:null,keyboard:!0,backdrop:s};switch(arguments.length){case 1:b=arguments[0];break;case 2:b=arguments[0];"object"==typeof arguments[1]?c=arguments[1]:a=arguments[1];break;case 3:b=arguments[0];a=arguments[1];c=arguments[2];break;default:throw Error("Incorrect number of arguments: expected 1-3");}e.header=a;
  c="object"==typeof c?n.extend(e,c):e;return m.dialog(b,[],c)},hideAll:function(){n(".bootbox").modal("hide")},animate:function(b){u=b},backdrop:function(b){s=b},classes:function(b){v=b}},j={en:{OK:"OK",CANCEL:"Cancel",CONFIRM:"OK"},fr:{OK:"OK",CANCEL:"Annuler",CONFIRM:"D'accord"},de:{OK:"OK",CANCEL:"Abbrechen",CONFIRM:"Akzeptieren"},es:{OK:"OK",CANCEL:"Cancelar",CONFIRM:"Aceptar"},br:{OK:"OK",CANCEL:"Cancelar",CONFIRM:"Sim"},nl:{OK:"OK",CANCEL:"Annuleren",CONFIRM:"Accepteren"},ru:{OK:"OK",CANCEL:"\u041e\u0442\u043c\u0435\u043d\u0430",
  CONFIRM:"\u041f\u0440\u0438\u043c\u0435\u043d\u0438\u0442\u044c"},it:{OK:"OK",CANCEL:"Annulla",CONFIRM:"Conferma"}};return m}(document,window.jQuery);window.bootbox=bootbox;

// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

$(function() {

    if ($.isFunction($('input[type=checkbox],input[type=radio],input[type=file]').uniform))
    {
        $('input[type=checkbox],input[type=radio],input[type=file]').uniform();

    }


    var argetFwkUtilsLib = new ArgetFwkUtilsLib();

    $('textarea[name=seoDescription],input[name=seoTitle], input[name=seoH1]').live('keyup', function() {

        argetFwkUtilsLib.messageBox('#messageBox', $(this).val().length + ' caractères', 500);

    });


    /* 
     * ArgetFwk - Lib : Login Function
     */
    $('#submit').click(function() {

        var login = $('#inputEmail').val();
        var password = $('#inputPassword').val();

        var objAjax = new AjaxLib();
        objAjax.setController('login');
        objAjax.setMethod('login');
        objAjax.setDataString('&login=' + login + '&password=' + password);

        var html = objAjax.execute();
        var result = html;
        var regex = /^\d/;
        if (result === 'User checked.') {
            $('#validation-login').submit();
        }
        else if (result.match(regex)) {
            $('#texteLogin').html('<p>Trop de tentatives de connexion.<br/>Merci de patienter ' + result + ' secondes.</p>');
            $("#warning").modal({
                keyboard: false
            });
        } else {
            $('#texteLogin').html('<p>Vos identifiants sont incorrects.</p>');
            $("#warning").modal({
                keyboard: false
            });
        }

    });

    /* 
     * ArgetFwk - Lib : Forget Password Function
     */
    $('#submitForget').click(function(e) {

        e.preventDefault();

        var messageSent = 'Le champ email est incorrect ...';
        var expreg = '';
        var type = 'email';
        var ajax = '';
        var repo = '';
        var method = '';

        if (!argetFwkUtilsLib.checkInputs($('#inputEmail'), expreg, type, ajax, repo, method)) {
            argetFwkUtilsLib.messageBox('#messageBox', messageSent, 2000);
        } else {
            var login = $('#inputEmail').val();

            var objAjax = new AjaxLib();
            objAjax.setController('login');
            objAjax.setMethod('forget');
            objAjax.setDataString('&login=' + login);

            var html = objAjax.execute();
            var result = html;
            var regex = /^\d/;
            if (result === 'User checked.') {
                $('#validation-login').submit();
            }
            else if (result.match(regex)) {
                $('#texteLogin').html('<p>Trop de tentatives de demandes de mot de passe.<br/>Merci de patienter ' + result + ' secondes.</p>');
                $("#warning").modal({
                    keyboard: false
                });
            } else {
                $('#texteLogin').html('<p>Votre message a bien été envoyé.</p>');
                $("#warning").modal({
                    keyboard: false
                });
            }

        }

    });


    /**
     * ArgetFwk - Lib : Gestion dynamique des tableaux
     * -- Rafraîchissement des lignes de body
     */

    /*
     * Tri ASC ou DESC
     */
    $('.sort').click(function() {
        argetFwkUtilsLib.refreshContent($(this), new Array());
    });

    $('.sortSelect').change(function() {
        argetFwkUtilsLib.refreshContent($(this), new Array());
    });
    
    
    
    /*
     * Gestion des boutons custom du tableau
     */
    $('.ajaxRefreshWhenClick').live('click', function(e){
        e.preventDefault();
        
        var link = $(this).attr('href');
        var dataStr = '';
        if ($(this).attr('data-id'))
            dataStr = '&idItem=' + $(this).attr('data-id');
        var arrayLink = link.split('/');
        
        var objAjax = new AjaxLib();
        objAjax.setController(arrayLink[0]);
        objAjax.setMethod(arrayLink[1]);
        objAjax.setAsyncValue(false);
        objAjax.setDataString(dataStr);
        objAjax.execute();
        
        argetFwkUtilsLib.refreshContent($(this), new Array());
    });


    $('.delete-item').live('click', function(e) {
        e.preventDefault();

        $('#confirmBox').modal({backdrop: false});
        $('#confirmTrue').attr('href', $(this).attr('href'));
        $('#confirmTrue').attr('data-id', $(this).attr('data-id'));
        $('#confirmTrue').attr('data-class', $(this).attr('data-class'));

    });

    $('#confirmTrue').live('click', function(e) {

        e.preventDefault();
        var objAjax = new AjaxLib();
        objAjax.setController('table');
        objAjax.setMethod('deleteLine');
        objAjax.setAsyncValue(false);
        objAjax.setDataString('&class=' + $(this).attr('data-class') + '&idProduct=' + $(this).attr('data-id'));
        var result = objAjax.execute();

        $('#confirmBox').modal('hide');

        if (result === 'done.') {
            if ($(this).attr('data-refresh')) {
                if ($(this).attr('data-refresh') === 'refreshreponses') {
                    argetFwkUtilsLib.refreshReponses($(this).attr('data-idticket'));
                }
            } else
                argetFwkUtilsLib.refreshContent($(document), new Array());
        } else if (result === 'error.')
            argetFwkUtilsLib.messageBox('#messageBox', 'Erreur serveur. Merci de contacter l\'administrateur.', 2000);
        else
            argetFwkUtilsLib.messageBox('#messageBox', 'Impossible de supprimer cet élément. D\'autre tables sont liées.', 2000);

    });

    $('.modify-item').live('click', function(e) {

        if ($(this).attr('data-edit') === 'false' && $(e.target).attr('id') !== 'iconValidation') {
            argetFwkUtilsLib.inputsUnselect();
            argetFwkUtilsLib.inputsActive($(this));
        }

    });

    $('#modif-save').live('click', function() {

        var target = $(this).prev('input');
        argetFwkUtilsLib.modifInfoValidation(target);

    });

    $('.modif-container').live('click', function() {

        argetFwkUtilsLib.modifyInfoInactiv();
        $(this).attr('class', 'modif-container-edit');
        var stylesInput = ' style="font-size: 14px; margin-top: 6px; height: 14px;"';
        var value = $(this).children('span').html();
        if ($(this).children('span').attr('data-modify-type') !== 'password') {
            $(this).children('span').html('<input class="modif-input"' + stylesInput + ' value="' + value + '" type="text" id="modif-input" /> <i class="icon-ok hand modif-input-save" id="modif-save"></i>');
        } else {
            $(this).children('span').html('<input class="modif-input"' + stylesInput + ' value="password" type="password" id="modif-input" /> <i class="icon-ok hand modif-input-save" id="modif-save"></i>');
        }

        $(this).children('span').children('input').select();
    });

    $('.modifySave').live('click', function(e) {

        e.preventDefault();
        var target = $(this).prev('input');
        argetFwkUtilsLib.inputsValidation(target);

    });

    $('.addEditItem').live('click', function(e) {

        e.preventDefault();
        var link = $(this).attr('href');
        var modalWidth = $('#addItem').attr('data-width');
        var dataStr = '';
        if ($(this).attr('data-id'))
            dataStr = '&idItem=' + $(this).attr('data-id');
        arrayLink = link.split('/');
        var objAjax = new AjaxLib();
        objAjax.setController(arrayLink[0]);
        objAjax.setMethod(arrayLink[1]);
        objAjax.setAsyncValue(false);
        objAjax.setDataString(dataStr);
        $('#editBody').html(objAjax.execute());

        $('#editBox').modal({backdrop: false}).css({
            width: modalWidth,
            'margin-left': function() {
                return -($(this).width() / 2);
            }
        });

        $('input[type=checkbox],input[type=radio],input[type=file]').uniform();

    });

    $('form').live('submit', function(e) {

        if ($(this).attr('class') !== 'sendFile') {

            e.preventDefault();

            var checked = true;

            if ($(this).attr('id')) {
                $('#' + $(this).attr('id') + ' :input').each(function() {
                    if ($(this).attr('data-verif')) {
                        var messageSent = 'Le champ ' + $(this).attr('name') + ' est incorrect ...';
                        var expreg = '';
                        var type = '';
                        var ajax = '';
                        var repo = '';
                        var method = '';
                        var datalength = '';
                        if ($(this).attr('data-expreg'))
                            expreg = $(this).attr('data-expreg');
                        if ($(this).attr('data-message'))
                            messageSent = $(this).attr('data-message');
                        if ($(this).attr('data-type'))
                            type = $(this).attr('data-type');
                        if ($(this).attr('data-ajax'))
                            ajax = $(this).attr('data-ajax');
                        if ($(this).attr('data-repo'))
                            repo = $(this).attr('data-repo');
                        if ($(this).attr('data-method'))
                            method = $(this).attr('data-method');
                        if ($(this).attr('data-length'))
                            datalength = $(this).attr('data-length');

                        if (!argetFwkUtilsLib.checkInputs($(this), expreg, type, ajax, repo, method, datalength)) {
                            argetFwkUtilsLib.messageBox('#messageBox', messageSent, 2000);
                            checked = false;
                        }
                    }
                });
            }

            if ($(this).attr('id') === 'editAddForm' && checked) {

                checked = false;

                var objAjax = new AjaxLib();
                objAjax.setController($(this).attr('data-controller'));
                objAjax.setMethod($(this).attr('data-method'));
                objAjax.setDataString('&' + $(this).serialize());
                objAjax.setAsyncValue(false);
                var result = objAjax.execute();

                var regexpDuplicate = '1062 Duplicate entry \'([a-zA-Z0-9àâäçèéêëìíîïòóôùúûü& -\.@]+)\' for key';
                var regDuplicate = new RegExp(regexpDuplicate, 'i');

                if (result === '') {
                    argetFwkUtilsLib.refreshContent($('#search'), new Array());
                    $('#editBox').modal('hide');
                } else if (regDuplicate.test(result)) {
                    if (result.match(regexpDuplicate)[1])
                        var txt = ' l\'élément : "' + result.match(regexpDuplicate)[1] + '"';
                    else
                        var txt = ' un autre élément.'
                    argetFwkUtilsLib.messageBox('#messageBox', 'Impossible de sauvegarder.<br/>Il existe des similarités avec' + txt, 2500);
                } else
                    argetFwkUtilsLib.messageBox('#messageBox', 'Impossible de sauvegarder cet élément.<br/> Une erreur est survenue.', 2000);
            }

            if (checked)
                this.submit();

        }

    });

    $(document).keydown(function(e) {

        var code = (e.keyCode ? e.keyCode : e.which);

        if (code === 9) {
            var target = $(e.target);
            if (target.attr('id') === 'modifyInput') {
                e.preventDefault();
                var parent = target.parent('.modify-item');
                argetFwkUtilsLib.inputsUnselect();
                if (parent.next('.modify-item').length > 0)
                    argetFwkUtilsLib.inputsActive(parent.next('.modify-item'));
                else {
                    var nextTr = parent.parent('tr').next('tr');
                    argetFwkUtilsLib.inputsActive(nextTr.children('.modify-item').eq(0));
                }
            }

        } else if (code === 27) {
            e.preventDefault();
            argetFwkUtilsLib.inputsUnselect();
            argetFwkUtilsLib.modifyInfoInactiv();
        } else if (code === 13) {
            var target = $(e.target);
            if (target.attr('type') === 'search') {
                argetFwkUtilsLib.refreshContent($('#search'), new Array());
            } else {
                argetFwkUtilsLib.inputsValidation(target);
                argetFwkUtilsLib.modifInfoValidation(target);
            }
        }

    });

    $(document).live('click', function(e) {
        var target = $(e.target);
        if (target.attr('class') !== 'modify-item' && target.attr('id') !== 'modifyInput' && target.attr('class') !== 'modifySave' && target.attr('class') !== 'modif-container' && target.attr('class') !== 'modif-container-edit' && target.attr('class') !== 'value' && target.attr('id') !== 'modif-save' && target.attr('class') !== 'modif-input') {
            argetFwkUtilsLib.inputsUnselect();
            argetFwkUtilsLib.modifyInfoInactiv();
        }

    });

    $('#exportCsvRefresh').click(function(e) {
        e.preventDefault();
        var arrayParams = new Array();

        var ids = '';
        $('td.checkItemTd input:checked').each(function() {
            ids += $(this).attr('data-id') + ',';
        });

        arrayParams['ids'] = ids;
        arrayParams['csv'] = true;
        arrayParams['href'] = $(this).attr('href');
        argetFwkUtilsLib.refreshContent($('#search'), arrayParams);
    });

    $('#checkAll').click(function() {

        var checkedVal = $(this).attr('checked');

        $('td.checkItemTd input').each(function() {
            if (checkedVal !== 'checked') {
                var uniUpdate = $(this).removeAttr('checked');
                $.uniform.update(uniUpdate);
            } else {
                var uniUpdate = $(this).attr('checked', checkedVal);
                $.uniform.update(uniUpdate);
            }
            argetFwkUtilsLib.checkChecked();
        });

    });

    $('.checkitem').live('change', function() {

        argetFwkUtilsLib.checkChecked();

    });

    $('#linkDelete').click(function() {

        var ids = '';
        var classItem = '';
        $('td.checkItemTd input:checked').each(function() {
            ids += $(this).attr('data-id') + ',';
            classItem = $(this).attr('data-class');
        });

        $('#confirmBox').modal({backdrop: false});
        $('#confirmTrue').attr('href', '');
        $('#confirmTrue').attr('data-id', ids);
        $('#confirmTrue').attr('data-class', classItem);

    });

    $('.ajaxImageUpload').live('change', function() {

        $('#editAddForm').attr('class', 'sendFile');

        var elem = $(this).parent().next().next();
        elem.html('');
        elem.html('<br/><img src="web/img/bibliotheque/wait.gif" alt="Uploading ...">');

        if ($(this).attr('data-max-size'))
            var maxsizeVar = $(this).attr('data-max-size');
        else
            var maxsizeVar = 5000;

        if ($(this).attr('data-formats'))
            var formatsVar = $(this).attr('data-formats');
        else
            var formatsVar = 'jpg,jpeg,gif,png';

        if ($(this).attr('data-filename'))
            var fileNameVar = $(this).attr('data-filename');
        else
            var fileNameVar = 'logo';

        if ($(this).attr('data-path'))
            var filePathVar = $(this).attr('data-path');
        else
            var filePathVar = 'default';

        if ($(this).attr('data-perso'))
            var dataPerso = $(this).attr('data-perso');
        else
            var dataPerso = 0;

        if ($(this).attr('data-type'))
            var dataType = $(this).attr('data-type');
        else
            var dataType = 'imageUpload';

        $("#editAddForm").ajaxForm({
            url: 'app/ajax.php',
            data: {method: dataType, controller: 'dashboard', upmaxsize: maxsizeVar, upformat: formatsVar, upfilename: fileNameVar, upfilepath: filePathVar, dataPersoId: dataPerso},
            success: function(data) {

                $('#editAddForm').attr('class', '');
                if (data.length > 2) {

                    elem.html('<img src="' + data + '" style="max-width: 75px; max-height: 75px;"');
                    elem.prev('input').val(data);
                    $('.ajaxImageUpload').val('');

                } else if (data.length === 1) {
                    window.location.reload();
                } else {
                    if ($('.ajaxImageUpload').val() !== "")
                        argetFwkUtilsLib.messageBox('#messageBox', 'Erreur lors de l\'upload. Vérifiez que le format est bien en (png,jpg,jpeg ou gif) et que le fichier ne dépasse pas les ' + argetFwkUtilsLib.getBytesWithUnit(maxsizeVar) + '.', 5000);
                    elem.html('');
                    $('.ajaxImageUpload').val('');
                }
            }
        }).submit();



    });

    $.fn.UItoTop = function(options) {

        var defaults = {
            text: 'Top',
            min: 200,
            inDelay: 600,
            outDelay: 400,
            containerID: 'toTop',
            containerHoverID: 'toTopHover',
            scrollSpeed: 1200,
            easingType: 'linear'
        };

        var settings = $.extend(defaults, options);
        var containerIDhash = '#' + settings.containerID;
        var containerHoverIDHash = '#' + settings.containerHoverID;

        $('body').append('<a href="#" id="' + settings.containerID + '">' + settings.text + '</a>');
        $(containerIDhash).hide().click(function() {
            $('html, body').animate({scrollTop: 0}, settings.scrollSpeed, settings.easingType);
            $('#' + settings.containerHoverID, this).stop().animate({'opacity': 0}, settings.inDelay, settings.easingType);
            return false;
        })
                .prepend('<span id="' + settings.containerHoverID + '"></span>')
                .hover(function() {
            $(containerHoverIDHash, this).stop().animate({
                'opacity': 1
            }, 600, 'linear');
        }, function() {
            $(containerHoverIDHash, this).stop().animate({
                'opacity': 0
            }, 700, 'linear');
        });

        $(window).scroll(function() {
            var sd = $(window).scrollTop();
            if (typeof document.body.style.maxHeight === "undefined") {
                $(containerIDhash).css({
                    'position': 'absolute',
                    'top': $(window).scrollTop() + $(window).height() - 50
                });
            }
            if (sd > settings.min)
                $(containerIDhash).fadeIn(settings.inDelay);
            else
                $(containerIDhash).fadeOut(settings.Outdelay);
        });

    };


});