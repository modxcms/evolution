function initQuicksearch(inputId, listId) {
    jQuery('#'+inputId).quicksearch('#'+listId+' ul li', {
        selector: '.man_el_name',
        'show': function () { jQuery(this).removeClass('hide'); },
        'hide': function () { jQuery(this).addClass('hide'); },
        'bind':'keyup',
        'onAfter': function() {
            jQuery('#'+listId).find('> li ul').each( function() {
                var parentLI = jQuery(this).closest('li');
                var totalLI  = jQuery(this).children('li').length;
                var hiddenLI = jQuery(this).children('li.hide').length;
                if (hiddenLI == totalLI) { parentLI.addClass('hide'); }
                else { parentLI.removeClass('hide'); }
            });
        }
    });
    jQuery('.filterElements-form').keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
    });
}

function unlockElement(type, id, domEl) {
    var msg = trans.msg.replace('[+id+]',id).replace('[+element_type+]',trans['type'+type]);
    if(confirm(msg)==true) {
        jQuery.get( 'index.php?a=67&type='+type+'&id='+id, function( data ) {
            if(data == 1) {
                jQuery(domEl).fadeOut();
            }
            else alert( data );
        });
    }
}
function initViews(pre, helppre, target) {
    jQuery( "#"+helppre+"-help" ).click(function() {
        jQuery( '#'+helppre+'-info').toggle();
    });
}

function setColumnCount(targetEl, count) {
    targetEl.find('li ul').css({
        '-moz-column-count': count,
        '-webkit-column-count': count,
        'column-count': count,
    });
}

// Add switch-view functionality
jQuery( document ).ready(function() {
    jQuery(".switchForm").each(function() {
        var target = jQuery(this).data('target');
        var targetEl = jQuery('#'+target);
        var version = 1;
        
        jQuery(this).change(function() {
            var viewOpts = {};
            // Options
            if(jQuery(this).find("input:checkbox[name=cb_buttons]").is(':checked')) {
                targetEl.find('.elements_buttonbar').show();
                viewOpts.cb_buttons = 1;
            } else {
                targetEl.find('.elements_buttonbar').hide();
                viewOpts.cb_buttons = 0;
            }
            if(jQuery(this).find("input:checkbox[name=cb_description]").is(':checked')) {
                targetEl.find('span.elements_descr').show();
                viewOpts.cb_description = 1;
            } else {
                targetEl.find('span.elements_descr').hide();
                viewOpts.cb_description = 0;
            }
            if(jQuery(this).find("input:checkbox[name=cb_icons]").is(':checked')) {
                targetEl.removeClass('noicons');
                viewOpts.cb_icons = 1;
            } else {
                targetEl.addClass('noicons');
                viewOpts.cb_icons = 0;
            }
            if(jQuery(this).find("input:checkbox[name=cb_small]").is(':checked')) {
                targetEl.addClass('small');
                viewOpts.cb_small = 1;
            } else {
                targetEl.removeClass('small');
                viewOpts.cb_small = 0;
            }
        
            // Views
            var columns = jQuery(this).find("input[name=columns]").val();
            viewOpts.columns = columns;
            switch(this.view.value) {
                case 'inline':
                    targetEl.removeClass('flex list');
                    targetEl.addClass('inline');
                    viewOpts.view = 'inline';
                    setColumnCount(targetEl, 1);
                    break;
                case 'flex':
                    targetEl.removeClass('inline list');
                    targetEl.addClass('flex');
                    viewOpts.view = 'flex';
                    setColumnCount(targetEl, columns);
                    break;
                case 'list':
                default:
                    targetEl.removeClass('flex inline');
                    targetEl.addClass('list');
                    viewOpts.view = 'list';
                    setColumnCount(targetEl, 1);
                    break;
            }
            
            // Save to localhost
            viewOpts.version = version;
            localStorage.setItem('MODX_mgrResources_'+target, JSON.stringify(viewOpts));

            console.log('save', viewOpts);
        });

        // Get parameters from localStorage
        var viewOpts = JSON.parse(localStorage.getItem('MODX_mgrResources_'+target));
        
        // console.log('load', viewOpts.version, '==', version);
        console.log(viewOpts);
        
        if(viewOpts && viewOpts.version == version) {
            // Recover from localStorage
            if(viewOpts.cb_buttons)     jQuery(this).find("input:checkbox[name=cb_buttons]").attr('checked', true).prop("checked", true);
            if(viewOpts.cb_description) jQuery(this).find("input:checkbox[name=cb_description]").attr('checked', true).prop("checked", true);
            if(viewOpts.cb_icons)       jQuery(this).find("input:checkbox[name=cb_icons]").attr('checked', true).prop("checked", true);
            if(viewOpts.cb_small)       jQuery(this).find("input:checkbox[name=cb_small]").attr('checked', true).prop("checked", true);
            if(viewOpts.view)           jQuery(this).find("input:radio[name=view][value="+viewOpts.view+"]").attr('checked', true).prop("checked", true);
            if(viewOpts.columns)        jQuery(this).find("input[name=columns]").val(viewOpts.columns);
        } else {
            // Set defaults
            jQuery(this).find("input:checkbox[name=cb_buttons]").attr('checked', true).prop("checked", true);
            jQuery(this).find("input:checkbox[name=cb_description]").attr('checked', true).prop("checked", true);
            jQuery(this).find("input:checkbox[name=cb_icons]").attr('checked', true).prop("checked", true);
            jQuery(this).find("input:checkbox[name=cb_small]").attr('checked', false).prop("checked", false);
            jQuery(this).find("input:radio[name=view]:first").attr('checked', true).prop("checked", true);
            jQuery(this).find("input[name=columns]").val(3);
        }
        jQuery(this).trigger('change');
        
        jQuery(this).submit(function(e){
            e.preventDefault();
        });
    });
});