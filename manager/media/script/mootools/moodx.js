var Event = function(e) {
    this._event = e || window.event;
}
Object.extend(Event.prototype, {
    event: function() {
        return this._event;
    },
    type: function() {
        return this._event.type || undefined;
    },
    target: function() {
        if (this._event.target) var target = this._event.target;
        else if (this._event.srcElement) var target = this._event.srcElement;
        if (target.nodeType == 3) target = target.parentNode; // Safari
        return $(target);
    },
    relatedTarget: function() {
        if (this.type() == 'mouseover') return $(this._event.relatedTarget || this._event.fromElement);
        if (this.type() == 'mouseout') return $(this._event.relatedTarget || this._event.toElement);
        return false;
    },
    modifier: function () {
        var e = this._event;
        return {alt: e.altKey,
            ctrl: e.ctrlKey,
            meta: e.metaKey || false,
            shift: e.shiftKey, 
            any: e.altKey || e.ctrlKey || e.metaKey || e.shiftKey
        };
    },
    key: function() {
        var k = {}, e = this._event;
        if (e.keyCode) k.code = e.keyCode;
        else if (e.which) k.code = e.which;
        k.string = String.fromCharCode(k.code);
        return k;
    },
    isRightClick: function() {
        var e = this._event;
        return (((e.which) && (e.which == 3)) || ((e.button) && (e.button == 2)));
    },
    pointerX: function() {
        var e = this._event;
        return e.pageX || (e.clientX + (document.documentElement.scrollLeft || document.body.scrollLeft));
    },
    pointerY: function() {
        var e = this._event;
        return e.pageY || (e.clientY + (document.documentElement.scrollTop || document.body.scrollTop));
    },
    stop: function() {
        this.stopPropagation();
        this.preventDefault();
    },
    stopPropagation: function() {
        if (this._event.stopPropagation) this._event.stopPropagation();
        else this._event.cancelBubble = true;
    },
    preventDefault: function() {
        if (this._event.preventDefault) this._event.preventDefault();
        else this._event.returnValue = false;
    },
    findParentTag: function(tagName) {
        var el = this.target();
        while (el.parentNode && (!el.tagName || (el.tagName.toUpperCase() != tagName.toUpperCase())))
            el = el.parentNode;
        return $(el);
    },
    findParentId: function(id) {
        var el = this.target();
        while (el.parentNode && (!el.id || (el.id != id)))
            el = el.parentNode;
        return $(el);
    }
});