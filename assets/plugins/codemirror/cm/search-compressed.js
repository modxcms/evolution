(function(mod){if(typeof exports=="object"&&typeof module=="object")
mod(require("../../lib/codemirror"),require("../dialog/dialog"));else if(typeof define=="function"&&define.amd)
define(["../../lib/codemirror","../dialog/dialog"],mod);else
mod(CodeMirror);})(function(CodeMirror){"use strict";function dialog(cm,text,shortText,deflt,f){if(cm.openDialog)cm.openDialog(text,f,{value:deflt,selectValueOnOpen:true});else f(prompt(shortText,deflt));}
var jumpDialog='Jump to line: <input type="text" style="width: 10em" class="CodeMirror-search-field"/> <span style="color: #888" class="CodeMirror-search-hint">(Use line:column or scroll% syntax)</span>';function interpretLine(cm,string){var num=Number(string)
if(/^[-+]/.test(string))return cm.getCursor().line+num
else return num-1}
CodeMirror.commands.jumpToLine=function(cm){var cur=cm.getCursor();dialog(cm,jumpDialog,"Jump to line:",(cur.line+1)+":"+cur.ch,function(posStr){if(!posStr)return;var match;if(match=/^\s*([\+\-]?\d+)\s*\:\s*(\d+)\s*$/.exec(posStr)){cm.setCursor(interpretLine(cm,match[1]),Number(match[2]))}else if(match=/^\s*([\+\-]?\d+(\.\d+)?)\%\s*/.exec(posStr)){var line=Math.round(cm.lineCount()*Number(match[1])/ 100);if(/^[-+]/.test(match[1]))line=cur.line+line+1;cm.setCursor(line-1,cur.ch);}else if(match=/^\s*\:?\s*([\+\-]?\d+)\s*/.exec(posStr)){cm.setCursor(interpretLine(cm,match[1]),cur.ch);}});};CodeMirror.keyMap["default"]["Alt-G"]="jumpToLine";});(function(mod){if(typeof exports=="object"&&typeof module=="object")
mod(require("../../lib/codemirror"),require("./matchesonscrollbar"));else if(typeof define=="function"&&define.amd)
define(["../../lib/codemirror","./matchesonscrollbar"],mod);else
mod(CodeMirror);})(function(CodeMirror){"use strict";var defaults={style:"matchhighlight",minChars:2,delay:100,wordsOnly:false,annotateScrollbar:false,showToken:false,trim:true}
function State(options){this.options={}
for(var name in defaults)
this.options[name]=(options&&options.hasOwnProperty(name)?options:defaults)[name]
this.overlay=this.timeout=null;this.matchesonscroll=null;this.active=false;}
CodeMirror.defineOption("highlightSelectionMatches",false,function(cm,val,old){if(old&&old!=CodeMirror.Init){removeOverlay(cm);clearTimeout(cm.state.matchHighlighter.timeout);cm.state.matchHighlighter=null;cm.off("cursorActivity",cursorActivity);cm.off("focus",onFocus)}
if(val){var state=cm.state.matchHighlighter=new State(val);if(cm.hasFocus()){state.active=true
highlightMatches(cm)}else{cm.on("focus",onFocus)}
cm.on("cursorActivity",cursorActivity);}});function cursorActivity(cm){var state=cm.state.matchHighlighter;if(state.active||cm.hasFocus())scheduleHighlight(cm,state)}
function onFocus(cm){var state=cm.state.matchHighlighter
if(!state.active){state.active=true
scheduleHighlight(cm,state)}}
function scheduleHighlight(cm,state){clearTimeout(state.timeout);state.timeout=setTimeout(function(){highlightMatches(cm);},state.options.delay);}
function addOverlay(cm,query,hasBoundary,style){var state=cm.state.matchHighlighter;cm.addOverlay(state.overlay=makeOverlay(query,hasBoundary,style));if(state.options.annotateScrollbar&&cm.showMatchesOnScrollbar){var searchFor=hasBoundary?new RegExp("\\b"+query+"\\b"):query;state.matchesonscroll=cm.showMatchesOnScrollbar(searchFor,false,{className:"CodeMirror-selection-highlight-scrollbar"});}}
function removeOverlay(cm){var state=cm.state.matchHighlighter;if(state.overlay){cm.removeOverlay(state.overlay);state.overlay=null;if(state.matchesonscroll){state.matchesonscroll.clear();state.matchesonscroll=null;}}}
function highlightMatches(cm){cm.operation(function(){var state=cm.state.matchHighlighter;removeOverlay(cm);if(!cm.somethingSelected()&&state.options.showToken){var re=state.options.showToken===true?/[\w$]/:state.options.showToken;var cur=cm.getCursor(),line=cm.getLine(cur.line),start=cur.ch,end=start;while(start&&re.test(line.charAt(start-1)))--start;while(end<line.length&&re.test(line.charAt(end)))++end;if(start<end)
addOverlay(cm,line.slice(start,end),re,state.options.style);return;}
var from=cm.getCursor("from"),to=cm.getCursor("to");if(from.line!=to.line)return;if(state.options.wordsOnly&&!isWord(cm,from,to))return;var selection=cm.getRange(from,to)
if(state.options.trim)selection=selection.replace(/^\s+|\s+$/g,"")
if(selection.length>=state.options.minChars)
addOverlay(cm,selection,false,state.options.style);});}
function isWord(cm,from,to){var str=cm.getRange(from,to);if(str.match(/^\w+$/)!==null){if(from.ch>0){var pos={line:from.line,ch:from.ch-1};var chr=cm.getRange(pos,from);if(chr.match(/\W/)===null)return false;}
if(to.ch<cm.getLine(from.line).length){var pos={line:to.line,ch:to.ch+1};var chr=cm.getRange(to,pos);if(chr.match(/\W/)===null)return false;}
return true;}else return false;}
function boundariesAround(stream,re){return(!stream.start||!re.test(stream.string.charAt(stream.start-1)))&&(stream.pos==stream.string.length||!re.test(stream.string.charAt(stream.pos)));}
function makeOverlay(query,hasBoundary,style){return{token:function(stream){if(stream.match(query)&&(!hasBoundary||boundariesAround(stream,hasBoundary)))
return style;stream.next();stream.skipTo(query.charAt(0))||stream.skipToEnd();}};}});(function(mod){if(typeof exports=="object"&&typeof module=="object")
mod(require("../../lib/codemirror"),require("./searchcursor"),require("../scroll/annotatescrollbar"));else if(typeof define=="function"&&define.amd)
define(["../../lib/codemirror","./searchcursor","../scroll/annotatescrollbar"],mod);else
mod(CodeMirror);})(function(CodeMirror){"use strict";CodeMirror.defineExtension("showMatchesOnScrollbar",function(query,caseFold,options){if(typeof options=="string")options={className:options};if(!options)options={};return new SearchAnnotation(this,query,caseFold,options);});function SearchAnnotation(cm,query,caseFold,options){this.cm=cm;this.options=options;var annotateOptions={listenForChanges:false};for(var prop in options)annotateOptions[prop]=options[prop];if(!annotateOptions.className)annotateOptions.className="CodeMirror-search-match";this.annotation=cm.annotateScrollbar(annotateOptions);this.query=query;this.caseFold=caseFold;this.gap={from:cm.firstLine(),to:cm.lastLine()+1};this.matches=[];this.update=null;this.findMatches();this.annotation.update(this.matches);var self=this;cm.on("change",this.changeHandler=function(_cm,change){self.onChange(change);});}
var MAX_MATCHES=1000;SearchAnnotation.prototype.findMatches=function(){if(!this.gap)return;for(var i=0;i<this.matches.length;i++){var match=this.matches[i];if(match.from.line>=this.gap.to)break;if(match.to.line>=this.gap.from)this.matches.splice(i--,1);}
var cursor=this.cm.getSearchCursor(this.query,CodeMirror.Pos(this.gap.from,0),this.caseFold);var maxMatches=this.options&&this.options.maxMatches||MAX_MATCHES;while(cursor.findNext()){var match={from:cursor.from(),to:cursor.to()};if(match.from.line>=this.gap.to)break;this.matches.splice(i++,0,match);if(this.matches.length>maxMatches)break;}
this.gap=null;};function offsetLine(line,changeStart,sizeChange){if(line<=changeStart)return line;return Math.max(changeStart,line+sizeChange);}
SearchAnnotation.prototype.onChange=function(change){var startLine=change.from.line;var endLine=CodeMirror.changeEnd(change).line;var sizeChange=endLine-change.to.line;if(this.gap){this.gap.from=Math.min(offsetLine(this.gap.from,startLine,sizeChange),change.from.line);this.gap.to=Math.max(offsetLine(this.gap.to,startLine,sizeChange),change.from.line);}else{this.gap={from:change.from.line,to:endLine+1};}
if(sizeChange)for(var i=0;i<this.matches.length;i++){var match=this.matches[i];var newFrom=offsetLine(match.from.line,startLine,sizeChange);if(newFrom!=match.from.line)match.from=CodeMirror.Pos(newFrom,match.from.ch);var newTo=offsetLine(match.to.line,startLine,sizeChange);if(newTo!=match.to.line)match.to=CodeMirror.Pos(newTo,match.to.ch);}
clearTimeout(this.update);var self=this;this.update=setTimeout(function(){self.updateAfterChange();},250);};SearchAnnotation.prototype.updateAfterChange=function(){this.findMatches();this.annotation.update(this.matches);};SearchAnnotation.prototype.clear=function(){this.cm.off("change",this.changeHandler);this.annotation.clear();};});(function(mod){if(typeof exports=="object"&&typeof module=="object")
mod(require("../../lib/codemirror"),require("./searchcursor"),require("../dialog/dialog"));else if(typeof define=="function"&&define.amd)
define(["../../lib/codemirror","./searchcursor","../dialog/dialog"],mod);else
mod(CodeMirror);})(function(CodeMirror){"use strict";function searchOverlay(query,caseInsensitive){if(typeof query=="string")
query=new RegExp(query.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g,"\\$&"),caseInsensitive?"gi":"g");else if(!query.global)
query=new RegExp(query.source,query.ignoreCase?"gi":"g");return{token:function(stream){query.lastIndex=stream.pos;var match=query.exec(stream.string);if(match&&match.index==stream.pos){stream.pos+=match[0].length||1;return"searching";}else if(match){stream.pos=match.index;}else{stream.skipToEnd();}}};}
function SearchState(){this.posFrom=this.posTo=this.lastQuery=this.query=null;this.overlay=null;}
function getSearchState(cm){return cm.state.search||(cm.state.search=new SearchState());}
function queryCaseInsensitive(query){return typeof query=="string"&&query==query.toLowerCase();}
function getSearchCursor(cm,query,pos){return cm.getSearchCursor(query,pos,{caseFold:queryCaseInsensitive(query),multiline:true});}
function persistentDialog(cm,text,deflt,onEnter,onKeyDown){cm.openDialog(text,onEnter,{value:deflt,selectValueOnOpen:true,closeOnEnter:false,onClose:function(){clearSearch(cm);},onKeyDown:onKeyDown});}
function dialog(cm,text,shortText,deflt,f){if(cm.openDialog)cm.openDialog(text,f,{value:deflt,selectValueOnOpen:true});else f(prompt(shortText,deflt));}
function confirmDialog(cm,text,shortText,fs){if(cm.openConfirm)cm.openConfirm(text,fs);else if(confirm(shortText))fs[0]();}
function parseString(string){return string.replace(/\\(.)/g,function(_,ch){if(ch=="n")return"\n"
if(ch=="r")return"\r"
return ch})}
function parseQuery(query){var isRE=query.match(/^\/(.*)\/([a-z]*)$/);if(isRE){try{query=new RegExp(isRE[1],isRE[2].indexOf("i")==-1?"":"i");}
catch(e){}}else{query=parseString(query)}
if(typeof query=="string"?query=="":query.test(""))
query=/x^/;return query;}
var queryDialog='<span class="CodeMirror-search-label">Search:</span> <input type="text" style="width: 10em" class="CodeMirror-search-field"/> <span style="color: #888" class="CodeMirror-search-hint">(Use /re/ syntax for regexp search)</span>';function startSearch(cm,state,query){state.queryText=query;state.query=parseQuery(query);cm.removeOverlay(state.overlay,queryCaseInsensitive(state.query));state.overlay=searchOverlay(state.query,queryCaseInsensitive(state.query));cm.addOverlay(state.overlay);if(cm.showMatchesOnScrollbar){if(state.annotate){state.annotate.clear();state.annotate=null;}
state.annotate=cm.showMatchesOnScrollbar(state.query,queryCaseInsensitive(state.query));}}
function doSearch(cm,rev,persistent,immediate){var state=getSearchState(cm);if(state.query)return findNext(cm,rev);var q=cm.getSelection()||state.lastQuery;if(q instanceof RegExp&&q.source=="x^")q=null
if(persistent&&cm.openDialog){var hiding=null
var searchNext=function(query,event){CodeMirror.e_stop(event);if(!query)return;if(query!=state.queryText){startSearch(cm,state,query);state.posFrom=state.posTo=cm.getCursor();}
if(hiding)hiding.style.opacity=1
findNext(cm,event.shiftKey,function(_,to){var dialog
if(to.line<3&&document.querySelector&&(dialog=cm.display.wrapper.querySelector(".CodeMirror-dialog"))&&dialog.getBoundingClientRect().bottom-4>cm.cursorCoords(to,"window").top)
(hiding=dialog).style.opacity=.4})};persistentDialog(cm,queryDialog,q,searchNext,function(event,query){var keyName=CodeMirror.keyName(event)
var extra=cm.getOption('extraKeys'),cmd=(extra&&extra[keyName])||CodeMirror.keyMap[cm.getOption("keyMap")][keyName]
if(cmd=="findNext"||cmd=="findPrev"||cmd=="findPersistentNext"||cmd=="findPersistentPrev"){CodeMirror.e_stop(event);startSearch(cm,getSearchState(cm),query);cm.execCommand(cmd);}else if(cmd=="find"||cmd=="findPersistent"){CodeMirror.e_stop(event);searchNext(query,event);}});if(immediate&&q){startSearch(cm,state,q);findNext(cm,rev);}}else{dialog(cm,queryDialog,"Search for:",q,function(query){if(query&&!state.query)cm.operation(function(){startSearch(cm,state,query);state.posFrom=state.posTo=cm.getCursor();findNext(cm,rev);});});}}
function findNext(cm,rev,callback){cm.operation(function(){var state=getSearchState(cm);var cursor=getSearchCursor(cm,state.query,rev?state.posFrom:state.posTo);if(!cursor.find(rev)){cursor=getSearchCursor(cm,state.query,rev?CodeMirror.Pos(cm.lastLine()):CodeMirror.Pos(cm.firstLine(),0));if(!cursor.find(rev))return;}
cm.setSelection(cursor.from(),cursor.to());cm.scrollIntoView({from:cursor.from(),to:cursor.to()},20);state.posFrom=cursor.from();state.posTo=cursor.to();if(callback)callback(cursor.from(),cursor.to())});}
function clearSearch(cm){cm.operation(function(){var state=getSearchState(cm);state.lastQuery=state.query;if(!state.query)return;state.query=state.queryText=null;cm.removeOverlay(state.overlay);if(state.annotate){state.annotate.clear();state.annotate=null;}});}
var replaceQueryDialog=' <input type="text" style="width: 10em" class="CodeMirror-search-field"/> <span style="color: #888" class="CodeMirror-search-hint">(Use /re/ syntax for regexp search)</span>';var replacementQueryDialog='<span class="CodeMirror-search-label">With:</span> <input type="text" style="width: 10em" class="CodeMirror-search-field"/>';var doReplaceConfirm='<span class="CodeMirror-search-label">Replace?</span> <button>Yes</button> <button>No</button> <button>All</button> <button>Stop</button>';function replaceAll(cm,query,text){cm.operation(function(){for(var cursor=getSearchCursor(cm,query);cursor.findNext();){if(typeof query!="string"){var match=cm.getRange(cursor.from(),cursor.to()).match(query);cursor.replace(text.replace(/\$(\d)/g,function(_,i){return match[i];}));}else cursor.replace(text);}});}
function replace(cm,all){if(cm.getOption("readOnly"))return;var query=cm.getSelection()||getSearchState(cm).lastQuery;var dialogText='<span class="CodeMirror-search-label">'+(all?'Replace all:':'Replace:')+'</span>';dialog(cm,dialogText+replaceQueryDialog,dialogText,query,function(query){if(!query)return;query=parseQuery(query);dialog(cm,replacementQueryDialog,"Replace with:","",function(text){text=parseString(text)
if(all){replaceAll(cm,query,text)}else{clearSearch(cm);var cursor=getSearchCursor(cm,query,cm.getCursor("from"));var advance=function(){var start=cursor.from(),match;if(!(match=cursor.findNext())){cursor=getSearchCursor(cm,query);if(!(match=cursor.findNext())||(start&&cursor.from().line==start.line&&cursor.from().ch==start.ch))return;}
cm.setSelection(cursor.from(),cursor.to());cm.scrollIntoView({from:cursor.from(),to:cursor.to()});confirmDialog(cm,doReplaceConfirm,"Replace?",[function(){doReplace(match);},advance,function(){replaceAll(cm,query,text)}]);};var doReplace=function(match){cursor.replace(typeof query=="string"?text:text.replace(/\$(\d)/g,function(_,i){return match[i];}));advance();};advance();}});});}
CodeMirror.commands.find=function(cm){clearSearch(cm);doSearch(cm);};CodeMirror.commands.findPersistent=function(cm){clearSearch(cm);doSearch(cm,false,true);};CodeMirror.commands.findPersistentNext=function(cm){doSearch(cm,false,true,true);};CodeMirror.commands.findPersistentPrev=function(cm){doSearch(cm,true,true,true);};CodeMirror.commands.findNext=doSearch;CodeMirror.commands.findPrev=function(cm){doSearch(cm,true);};CodeMirror.commands.clearSearch=clearSearch;CodeMirror.commands.replace=replace;CodeMirror.commands.replaceAll=function(cm){replace(cm,true);};});(function(mod){if(typeof exports=="object"&&typeof module=="object")
mod(require("../../lib/codemirror"))
else if(typeof define=="function"&&define.amd)
define(["../../lib/codemirror"],mod)
else
mod(CodeMirror)})(function(CodeMirror){"use strict"
var Pos=CodeMirror.Pos
function regexpFlags(regexp){var flags=regexp.flags
return flags!=null?flags:(regexp.ignoreCase?"i":"")
+(regexp.global?"g":"")
+(regexp.multiline?"m":"")}
function ensureGlobal(regexp){return regexp.global?regexp:new RegExp(regexp.source,regexpFlags(regexp)+"g")}
function maybeMultiline(regexp){return /\\s|\\n|\n|\\W|\\D|\[\^/.test(regexp.source)}
function searchRegexpForward(doc,regexp,start){regexp=ensureGlobal(regexp)
for(var line=start.line,ch=start.ch,last=doc.lastLine();line<=last;line++,ch=0){regexp.lastIndex=ch
var string=doc.getLine(line),match=regexp.exec(string)
if(match)
return{from:Pos(line,match.index),to:Pos(line,match.index+match[0].length),match:match}}}
function searchRegexpForwardMultiline(doc,regexp,start){if(!maybeMultiline(regexp))return searchRegexpForward(doc,regexp,start)
regexp=ensureGlobal(regexp)
var string,chunk=1
for(var line=start.line,last=doc.lastLine();line<=last;){for(var i=0;i<chunk;i++){var curLine=doc.getLine(line++)
string=string==null?curLine:string+"\n"+curLine}
chunk=chunk*2
regexp.lastIndex=start.ch
var match=regexp.exec(string)
if(match){var before=string.slice(0,match.index).split("\n"),inside=match[0].split("\n")
var startLine=start.line+before.length-1,startCh=before[before.length-1].length
return{from:Pos(startLine,startCh),to:Pos(startLine+inside.length-1,inside.length==1?startCh+inside[0].length:inside[inside.length-1].length),match:match}}}}
function lastMatchIn(string,regexp){var cutOff=0,match
for(;;){regexp.lastIndex=cutOff
var newMatch=regexp.exec(string)
if(!newMatch)return match
match=newMatch
cutOff=match.index+(match[0].length||1)
if(cutOff==string.length)return match}}
function searchRegexpBackward(doc,regexp,start){regexp=ensureGlobal(regexp)
for(var line=start.line,ch=start.ch,first=doc.firstLine();line>=first;line--,ch=-1){var string=doc.getLine(line)
if(ch>-1)string=string.slice(0,ch)
var match=lastMatchIn(string,regexp)
if(match)
return{from:Pos(line,match.index),to:Pos(line,match.index+match[0].length),match:match}}}
function searchRegexpBackwardMultiline(doc,regexp,start){regexp=ensureGlobal(regexp)
var string,chunk=1
for(var line=start.line,first=doc.firstLine();line>=first;){for(var i=0;i<chunk;i++){var curLine=doc.getLine(line--)
string=string==null?curLine.slice(0,start.ch):curLine+"\n"+string}
chunk*=2
var match=lastMatchIn(string,regexp)
if(match){var before=string.slice(0,match.index).split("\n"),inside=match[0].split("\n")
var startLine=line+before.length,startCh=before[before.length-1].length
return{from:Pos(startLine,startCh),to:Pos(startLine+inside.length-1,inside.length==1?startCh+inside[0].length:inside[inside.length-1].length),match:match}}}}
var doFold,noFold
if(String.prototype.normalize){doFold=function(str){return str.normalize("NFD").toLowerCase()}
noFold=function(str){return str.normalize("NFD")}}else{doFold=function(str){return str.toLowerCase()}
noFold=function(str){return str}}
function adjustPos(orig,folded,pos,foldFunc){if(orig.length==folded.length)return pos
for(var min=0,max=pos+Math.max(0,orig.length-folded.length);;){if(min==max)return min
var mid=(min+max)>>1
var len=foldFunc(orig.slice(0,mid)).length
if(len==pos)return mid
else if(len>pos)max=mid
else min=mid+1}}
function searchStringForward(doc,query,start,caseFold){if(!query.length)return null
var fold=caseFold?doFold:noFold
var lines=fold(query).split(/\r|\n\r?/)
search:for(var line=start.line,ch=start.ch,last=doc.lastLine()+1-lines.length;line<=last;line++,ch=0){var orig=doc.getLine(line).slice(ch),string=fold(orig)
if(lines.length==1){var found=string.indexOf(lines[0])
if(found==-1)continue search
var start=adjustPos(orig,string,found,fold)+ch
return{from:Pos(line,adjustPos(orig,string,found,fold)+ch),to:Pos(line,adjustPos(orig,string,found+lines[0].length,fold)+ch)}}else{var cutFrom=string.length-lines[0].length
if(string.slice(cutFrom)!=lines[0])continue search
for(var i=1;i<lines.length-1;i++)
if(fold(doc.getLine(line+i))!=lines[i])continue search
var end=doc.getLine(line+lines.length-1),endString=fold(end),lastLine=lines[lines.length-1]
if(endString.slice(0,lastLine.length)!=lastLine)continue search
return{from:Pos(line,adjustPos(orig,string,cutFrom,fold)+ch),to:Pos(line+lines.length-1,adjustPos(end,endString,lastLine.length,fold))}}}}
function searchStringBackward(doc,query,start,caseFold){if(!query.length)return null
var fold=caseFold?doFold:noFold
var lines=fold(query).split(/\r|\n\r?/)
search:for(var line=start.line,ch=start.ch,first=doc.firstLine()-1+lines.length;line>=first;line--,ch=-1){var orig=doc.getLine(line)
if(ch>-1)orig=orig.slice(0,ch)
var string=fold(orig)
if(lines.length==1){var found=string.lastIndexOf(lines[0])
if(found==-1)continue search
return{from:Pos(line,adjustPos(orig,string,found,fold)),to:Pos(line,adjustPos(orig,string,found+lines[0].length,fold))}}else{var lastLine=lines[lines.length-1]
if(string.slice(0,lastLine.length)!=lastLine)continue search
for(var i=1,start=line-lines.length+1;i<lines.length-1;i++)
if(fold(doc.getLine(start+i))!=lines[i])continue search
var top=doc.getLine(line+1-lines.length),topString=fold(top)
if(topString.slice(topString.length-lines[0].length)!=lines[0])continue search
return{from:Pos(line+1-lines.length,adjustPos(top,topString,top.length-lines[0].length,fold)),to:Pos(line,adjustPos(orig,string,lastLine.length,fold))}}}}
function SearchCursor(doc,query,pos,options){this.atOccurrence=false
this.doc=doc
pos=pos?doc.clipPos(pos):Pos(0,0)
this.pos={from:pos,to:pos}
var caseFold
if(typeof options=="object"){caseFold=options.caseFold}else{caseFold=options
options=null}
if(typeof query=="string"){if(caseFold==null)caseFold=false
this.matches=function(reverse,pos){return(reverse?searchStringBackward:searchStringForward)(doc,query,pos,caseFold)}}else{query=ensureGlobal(query)
if(!options||options.multiline!==false)
this.matches=function(reverse,pos){return(reverse?searchRegexpBackwardMultiline:searchRegexpForwardMultiline)(doc,query,pos)}
else
this.matches=function(reverse,pos){return(reverse?searchRegexpBackward:searchRegexpForward)(doc,query,pos)}}}
SearchCursor.prototype={findNext:function(){return this.find(false)},findPrevious:function(){return this.find(true)},find:function(reverse){var result=this.matches(reverse,this.doc.clipPos(reverse?this.pos.from:this.pos.to))
while(result&&CodeMirror.cmpPos(result.from,result.to)==0){if(reverse){if(result.from.ch)result.from=Pos(result.from.line,result.from.ch-1)
else if(result.from.line==this.doc.firstLine())result=null
else result=this.matches(reverse,this.doc.clipPos(Pos(result.from.line-1)))}else{if(result.to.ch<this.doc.getLine(result.to.line).length)result.to=Pos(result.to.line,result.to.ch+1)
else if(result.to.line==this.doc.lastLine())result=null
else result=this.matches(reverse,Pos(result.to.line+1,0))}}
if(result){this.pos=result
this.atOccurrence=true
return this.pos.match||true}else{var end=Pos(reverse?this.doc.firstLine():this.doc.lastLine()+1,0)
this.pos={from:end,to:end}
return this.atOccurrence=false}},from:function(){if(this.atOccurrence)return this.pos.from},to:function(){if(this.atOccurrence)return this.pos.to},replace:function(newText,origin){if(!this.atOccurrence)return
var lines=CodeMirror.splitLines(newText)
this.doc.replaceRange(lines,this.pos.from,this.pos.to,origin)
this.pos.to=Pos(this.pos.from.line+lines.length-1,lines[lines.length-1].length+(lines.length==1?this.pos.from.ch:0))}}
CodeMirror.defineExtension("getSearchCursor",function(query,pos,caseFold){return new SearchCursor(this.doc,query,pos,caseFold)})
CodeMirror.defineDocExtension("getSearchCursor",function(query,pos,caseFold){return new SearchCursor(this,query,pos,caseFold)})
CodeMirror.defineExtension("selectMatches",function(query,caseFold){var ranges=[]
var cur=this.getSearchCursor(query,this.getCursor("from"),caseFold)
while(cur.findNext()){if(CodeMirror.cmpPos(cur.to(),this.getCursor("to"))>0)break
ranges.push({anchor:cur.from(),head:cur.to()})}
if(ranges.length)
this.setSelections(ranges,0)})});