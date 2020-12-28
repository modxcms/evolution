function addContentType(){
    var i,o,exists=false;
    var txt = document.settings.txt_custom_contenttype;
    var lst = document.settings.lst_custom_contenttype;
    for(i=0;i<lst.options.length;i++)
    {
        if(lst.options[i].value==txt.value) {
            exists=true;
            break;
        }
    }
    if (!exists) {
        o = new Option(txt.value,txt.value);
        lst.options[lst.options.length]= o;
        updateContentType();
    }
    txt.value='';
}
function removeContentType(){
    var i;
    var lst = document.settings.lst_custom_contenttype;
    for(i=0;i<lst.options.length;i++) {
        if(lst.options[i].selected) {
            lst.remove(i);
            break;
        }
    }
    updateContentType();
}
function updateContentType(){
    var i,o,ol=[];
    var lst = document.settings.lst_custom_contenttype;
    var ct = document.settings.custom_contenttype;
    while(lst.options.length) {
        ol[ol.length] = lst.options[0].value;
        lst.options[0]= null;
    }
    if(ol.sort) ol.sort();
    ct.value = ol.join(",");
    for(i=0;i<ol.length;i++) {
        o = new Option(ol[i],ol[i]);
        lst.options[lst.options.length]= o;
    }
    documentDirty = true;
}
/**
 * @param element el were language selection comes from
 * @param string lkey language key to look up
 * @param id elupd html element to update with results
 * @param string default_str default value of string for loaded manager language - allows some level of confirmation of change from default
 */
function confirmLangChange(el, lkey, elupd){
    lang_current = document.getElementById(elupd).value;
    lang_default = document.getElementById(lkey+'_hidden').value;
    changed = lang_current != lang_default;
    proceed = true;
    if(changed) {
        proceed = confirm(lang_chg);
    }
    if(proceed) {
        //document.getElementById(elupd).value = '';
        lang = el.options[el.selectedIndex].value;
        var myAjax = new Ajax('index.php?a=118', {
            method: 'post',
            data: 'action=get&lang='+lang+'&key='+lkey
        }).request();
        myAjax.addEvent('onComplete', function(resp){
            document.getElementById(elupd).value = resp;
        });
    }
}
