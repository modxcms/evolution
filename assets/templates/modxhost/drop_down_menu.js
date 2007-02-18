/**
 * Light Menu System v0.1
 * @author Jonathan Schemoul
 * @copyright 2006-2007 Holdiland
 * @license GPL V2 or newer
 */
var menuEffects = new Class({
        initialize: function(selector, options) {
                this.options = Object.extend({
                        subElement: false,
                        subElementSelector: 'a'
                }, options || {})
                this.selector = selector;
                this.currTimer = 500;
                $ES(selector + ' li ul').each(function(el) {
                        el.setStyles({
                                'display': 'block'
                        });
                        normalHeight = el.offsetHeight;
                        el.setStyles({
                                'height': 0,
                                'overflow': 'hidden'
                        });
                        elParent = $(el.parentNode);
                        
                        currentMenu = new Fx.Style(el, 'height');
                        elParent.addEvents({
                                'mouseover': function(submenu, myParent, targetValue) {
                                        myParent.addClass('hover');
                                        submenu.clearTimer();
                                        submenu.custom(targetValue);
                                }.pass([currentMenu, elParent, normalHeight]),
                                'mouseout': function(submenu, myParent, targetValue) {
                                        myParent.removeClass('hover');
                                        submenu.clearTimer();
                                        submenu.custom(targetValue);
                                }.pass([currentMenu, elParent, 0])
                        })
                }.bind(this));
        }
});
function processMenuEffects (){
        var myMenus = new menuEffects('#myajaxmenu', {
                subElement: true
        });
}
window.onDomReady(processMenuEffects);