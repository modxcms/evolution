HTMLElement.prototype.innerHTML setter = function (str) {
	var r = this.ownerDocument.createRange();
	r.selectNodeContents(this);
	r.deleteContents();
	var df = r.createContextualFragment(str);
	this.appendChild(df);
	
	return str;
}
  
HTMLElement.prototype.outerHTML setter = function (str) {
	var r = this.ownerDocument.createRange();
	r.setStartBefore(this);
	var df = r.createContextualFragment(str);
	this.parentNode.replaceChild(df, this);
	return str;
}


HTMLElement.prototype.innerHTML getter = function () {
	return getInnerHTML(this);
}

function getInnerHTML(node) {
	var str = "";
	for (var i=0; i<node.childNodes.length; i++)
		str += getOuterHTML(node.childNodes.item(i));
	return str;
}

HTMLElement.prototype.outerHTML getter = function () {
	return getOuterHTML(this)
}

function getOuterHTML(node) {
	var str = "";
	
	switch (node.nodeType) {
		case 1: // ELEMENT_NODE
			str += "<" + node.nodeName;
			for (var i=0; i<node.attributes.length; i++) {
				if (node.attributes.item(i).nodeValue != null) {
					str += " "
					str += node.attributes.item(i).nodeName;
					str += "=\"";
					str += node.attributes.item(i).nodeValue;
					str += "\"";
				}
			}

			if (node.childNodes.length == 0 && leafElems[node.nodeName])
				str += ">";
			else {
				str += ">";
				str += getInnerHTML(node);
				str += "<" + node.nodeName + ">"
			}
			break;
				
		case 3:	//TEXT_NODE
			str += node.nodeValue;
			break;
			
		case 4: // CDATA_SECTION_NODE
			str += "<![CDATA[" + node.nodeValue + "]]>";
			break;
					
		case 5: // ENTITY_REFERENCE_NODE
			str += "&" + node.nodeName + ";"
			break;

		case 8: // COMMENT_NODE
			str += "<!--" + node.nodeValue + "-->"
			break;
	}

	return str;
}


var _leafElems = ["IMG", "HR", "BR", "INPUT"];
var leafElems = {};
for (var i=0; i<_leafElems.length; i++)
	leafElems[_leafElems[i]] = true;