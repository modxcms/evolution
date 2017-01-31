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

// Switch Views
var version = 1;

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

function getViewOpts(form) {
    viewOpts = {};
    // Options
    viewOpts.cb_buttons     = form.find("input:checkbox[name=cb_buttons]").is(':checked');
    viewOpts.cb_description = form.find("input:checkbox[name=cb_description]").is(':checked');
    viewOpts.cb_icons       = form.find("input:checkbox[name=cb_icons]").is(':checked');

    // Views
    viewOpts.view = form.find("input[name=view]:checked").val();
    viewOpts.columns = form.find("input[name=columns]").val();

    viewOpts.fontsize = form.find("input[name=fontsize]").val();
    
    return viewOpts;
}

function setView(viewOpts, targetEl, target) {
    // Options
    if(viewOpts.cb_buttons) {
        targetEl.find('.elements_buttonbar').show();
    } else {
        targetEl.find('.elements_buttonbar').hide();
    }
    if(viewOpts.cb_description) {
        targetEl.find('span.elements_descr').show();
    } else {
        targetEl.find('span.elements_descr').hide();
    }
    if(viewOpts.cb_icons) {
        targetEl.removeClass('noicons');
    } else {
        targetEl.addClass('noicons');
    }
    
    // Views
    switch(viewOpts.view) {
        case 'inline':
            targetEl.removeClass('flex list');
            targetEl.addClass('inline');
            setColumnCount(targetEl, 1);
            break;
        case 'flex':
            targetEl.removeClass('inline list');
            targetEl.addClass('flex');
            setColumnCount(targetEl, viewOpts.columns);
            break;
        case 'list':
        default:
            targetEl.removeClass('flex inline');
            targetEl.addClass('list');
            setColumnCount(targetEl, 1);
            break;
    }
    
    // Set font-size
    targetEl.css('font-size', viewOpts.fontsize/10 + 'em');

    // Save view-options to localStorage
    viewOpts.version = version; // Provides version of options-obj to allow easy resetting of localStorage on future updates
    localStorage.setItem('MODX_mgrResources_'+target, JSON.stringify(viewOpts));

    // console.log('save', viewOpts);
}

function setAllViews(viewOpts) {
    jQuery(".switchForm").each(function() {
        var form = jQuery(this);
        var target = form.data('target');
        var targetEl = jQuery('#'+target);
        setView(viewOpts, targetEl, target);
        setViewOptions(form, viewOpts);
    });
}

function setViewOptions(form, viewOpts) {
    form.find("input:checkbox[name=cb_buttons]")    .attr('checked', viewOpts.cb_buttons).prop("checked", viewOpts.cb_buttons);
    form.find("input:checkbox[name=cb_description]").attr('checked', viewOpts.cb_description).prop("checked", viewOpts.cb_description);
    form.find("input:checkbox[name=cb_icons]")      .attr('checked', viewOpts.cb_icons).prop("checked", viewOpts.cb_icons);
    form.find("input:radio[name=view][value="+viewOpts.view+"]").attr('checked', true).prop("checked", true);
    form.find("input[name=columns]").val(viewOpts.columns);
    form.find("input[name=fontsize]").val(viewOpts.fontsize);    
}

function setViewDefaultOptions(form) {
    var viewOpts = {};
    viewOpts.cb_buttons = 1;
    viewOpts.cb_description = 1;
    viewOpts.cb_icons = 1;
    viewOpts.view = 'list';
    viewOpts.columns = 3;
    viewOpts.fontsize = 10;
    setViewOptions(form, viewOpts);
}

// Add switch-view functionality
jQuery( document ).ready(function() {
    jQuery(".switchForm").each(function() {
        var form = jQuery(this);
        var target = form.data('target');
        var targetEl = jQuery('#'+target);
   
        form.change(function() {
            var viewOpts = getViewOpts(form);
            if(form.find("input:checkbox[name=cb_all]").is(':checked')) {
                // Set view in all tabs 
                setAllViews(viewOpts);
            } else {
                // Set view in single tab
                setView(viewOpts, targetEl, target);
            }
        });

        // Get parameters from localStorage
        var viewOpts = JSON.parse(localStorage.getItem('MODX_mgrResources_'+target));
        
        // console.log('load', viewOpts.version, '==', version);
        // console.log(viewOpts);
        
        // Set views - if version is different, defaults will be set up
        if(viewOpts && viewOpts.version == version) {
            setViewOptions(form, viewOpts);
        } else {
            setViewDefaultOptions(form);
        }

        // Now restore settings
        form.trigger('change');
        
        // Add reset-button
        form.find(".btn_reset").click(function(e) {
            e.preventDefault();
            setViewDefaultOptions(form);
            form.trigger('change');
        });
        
        // Prevent sending form
        form.submit(function(e){
            e.preventDefault();
        });
    });
    
    // Add switchForm-toggle
    jQuery('.switchform-btn').each(function() {
        jQuery(this).click(function() {
            var target = jQuery(this).data('target');
            jQuery('#'+target).toggle();
        });
    });
});