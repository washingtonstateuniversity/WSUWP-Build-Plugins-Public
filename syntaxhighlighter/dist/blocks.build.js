!function(e){function t(r){if(n[r])return n[r].exports;var l=n[r]={i:r,l:!1,exports:{}};return e[r].call(l.exports,l,l.exports,t),l.l=!0,l.exports}var n={};t.m=e,t.c=n,t.d=function(e,n,r){t.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:r})},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="",t(t.s=0)}([function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var r=n(1),l=(n.n(r),n(2)),a=(n.n(l),n(3)),i=(n.n(a),n(4));n.n(i);Object(l.registerBlockType)("syntaxhighlighter/code",{title:Object(r.__)("SyntaxHighlighter Code","syntaxhighlighter"),description:Object(r.__)("Adds syntax highlighting to source code (front end only).","syntaxhighlighter"),icon:"editor-code",category:"formatting",keywords:[Object(r.__)("Source","syntaxhighlighter"),Object(r.__)("Program","syntaxhighlighter"),Object(r.__)("Develop","syntaxhighlighter")],attributes:{content:{type:"string",source:"text",selector:"pre"},language:{type:"string",default:syntaxHighlighterData.settings.language.default},lineNumbers:{type:"boolean",default:syntaxHighlighterData.settings.lineNumbers.default},firstLineNumber:{type:"string",default:syntaxHighlighterData.settings.firstLineNumber.default},highlightLines:{type:"string"},wrapLines:{type:"boolean",default:syntaxHighlighterData.settings.wrapLines.default},makeURLsClickable:{type:"boolean",default:syntaxHighlighterData.settings.makeURLsClickable.default}},supports:{html:!1},transforms:{from:[{type:"enter",regExp:/^```$/,transform:function(){return Object(l.createBlock)("syntaxhighlighter/code")}},{type:"raw",isMatch:function(e){return"PRE"===e.nodeName&&1===e.children.length&&"CODE"===e.firstChild.nodeName},schema:{pre:{children:{code:{children:{"#text":{}}}}}}},{type:"block",blocks:["core/code"],transform:function(e){var t=e.content;return Object(l.createBlock)("syntaxhighlighter/code",{content:t})}}],to:[{type:"block",blocks:["core/code"],transform:function(e){var t=e.content;return Object(l.createBlock)("core/code",{content:t})}}]},edit:function(e){var t=e.attributes,n=e.setAttributes,l=e.className,o=t.content,s=t.language,h=t.lineNumbers,g=t.firstLineNumber,c=t.highlightLines,u=t.wrapLines,p=t.makeURLsClickable,m=[];if(syntaxHighlighterData.settings.language.supported){var b=[];for(var d in syntaxHighlighterData.brushes)b.push({label:syntaxHighlighterData.brushes[d],value:d});m.push(wp.element.createElement(a.PanelRow,null,wp.element.createElement(a.SelectControl,{label:Object(r.__)("Code Language","syntaxhighlighter"),value:s,options:b,onChange:function(e){return n({language:e})}})))}return syntaxHighlighterData.settings.lineNumbers.supported&&m.push(wp.element.createElement(a.PanelRow,null,wp.element.createElement(a.ToggleControl,{label:Object(r.__)("Show Line Numbers","syntaxhighlighter"),checked:h,onChange:function(e){return n({lineNumbers:e})}}))),h&&syntaxHighlighterData.settings.firstLineNumber.supported&&m.push(wp.element.createElement(a.PanelRow,null,wp.element.createElement(a.TextControl,{label:Object(r.__)("First Line Number","syntaxhighlighter"),type:"number",value:g,onChange:function(e){return n({firstLineNumber:e})},min:1,max:1e5}))),syntaxHighlighterData.settings.highlightLines.supported&&m.push(wp.element.createElement(a.TextControl,{label:Object(r.__)("Highlight Lines","syntaxhighlighter"),value:c,help:Object(r.__)("A comma-separated list of line numbers to highlight. Can also be a range. Example: 1,5,10-20","syntaxhighlighter"),onChange:function(e){return n({highlightLines:e})}})),syntaxHighlighterData.settings.wrapLines.supported&&m.push(wp.element.createElement(a.PanelRow,null,wp.element.createElement(a.ToggleControl,{label:Object(r.__)("Wrap Long Lines","syntaxhighlighter"),checked:u,onChange:function(e){return n({wrapLines:e})}}))),syntaxHighlighterData.settings.makeURLsClickable.supported&&m.push(wp.element.createElement(a.PanelRow,null,wp.element.createElement(a.ToggleControl,{label:Object(r.__)("Make URLs Clickable","syntaxhighlighter"),checked:p,onChange:function(e){return n({makeURLsClickable:e})}}))),[wp.element.createElement(i.InspectorControls,{key:"syntaxHighlighterInspectorControls"},wp.element.createElement(a.PanelBody,{title:Object(r.__)("Settings","syntaxhighlighter")},m)),wp.element.createElement("div",{className:l+" wp-block-code"},wp.element.createElement(i.PlainText,{value:o,onChange:function(e){return n({content:e})},placeholder:Object(r.__)("Tip: To the right, choose a code language from the block settings.","syntaxhighlighter"),"aria-label":Object(r.__)("SyntaxHighlighter Code","syntaxhighlighter")}))]},save:function(e){var t=e.attributes,n=t.content;return wp.element.createElement("pre",null,n)}})},function(e,t){e.exports=wp.i18n},function(e,t){e.exports=wp.blocks},function(e,t){e.exports=wp.components},function(e,t){e.exports=wp.editor}]);