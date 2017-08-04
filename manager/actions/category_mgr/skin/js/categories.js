
//window.addEvent('domready',function(){

/**
 * Tips
 */
//new Tips($$('.mootooltip'),{className:'custom'} );
//new MooTips($$('.mootooltip_dom'), {
//    className :'assigned',
//    showOnClick: true,
//    showOnMouseEnter: true,
//    showDelay: 200,
//    hideDelay: 200,
//    offsets: {'x': 20, 'y': 20},
//    fixed: true
//});

/**
 * Sort Categories
 */
evo.sortable('.table-sortable tbody > tr', {
    complete: function(a, b) {
        for(let i = 0; i < b.length; i++) {
            let item = b[i].querySelector('input.sort');
            b[i].querySelector('input.sort').value = i + 1;
            b[i].querySelector('span.sort').innerHTML = i + 1
        }
    }
});

/**
 * Categorization
 */
var reset_position = function( drag ) {
    drag.setStyles({ left:0+"px", top:0+"px"  });
};
          
var optDrop = {
    over: function(drag) {
        this.addClass('over');
        drag.addClass('ok');
    },
    leave: function(drag) {
        this.removeClass('over');
        drag.removeClass('ok');
    },
    drop: function(drag) {
        this.removeClass('over');      
        drag.injectInside(this);
        reset_position(drag);
    }
};

var optDrag = {
    onStart: function(drag) {
        drag.setOpacity(.5).setStyle('z-index', 10000);
    },
    onComplete: function(drag) {
        drag.setOpacity(1).setStyle('z-index', 1000);
        reset_position(drag);
    }
};
    
var init_drag = function() {

    optDrag.droppables = $$('div.drop').addEvents( optDrop );
    $$('div#categorize-workbench div.drag').makeDraggable( optDrag );
    $$('div#categorize-workbench div.drag').each(function(element){ reset_position(element) });

    /**
     * Make container uncategorized elements movable.... but for what
    
    if( $('categorize-category-0') !== null ){
        var first_click = true;
        var container_uncategorized = $('categorize-category-0');
        container_uncategorized.getElement('h2').addEvent('click',function(){
            if( first_click === true ) {
                this.getParent().setStyles({
                    'top' : '-50px',
                    'left': '0'
                });
                new Drag.Move(container_uncategorized, {
                    handle: container_uncategorized.getElement('h2')
                });
                first_click = false;
            }
        });
    }
    
    */
}

/**
 * collect the categorization in formfields
 * 
 * @TODO collect them within a object and send by jason-request.
 */    
$('categorize-submit').addEvent('mouseenter',function() {

    this.setProperty('disabled','disabled');
    this.setProperty('value','wait...');

    $('categorize-formfields').empty();

    $$('div.categorize_category').each(function(drop) {

        category_id   = drop.getProperty('id').split('-')[2];
        category_name = drop.getElement('h2').getText().trim();
        elements      = drop.getElements('div.drag');
        
        if( elements.length > 0 ) {
            elements.each(function(element,index) {
                var element_id             = element.getProperty('id').split('-')[2];
                var element_name           = element.getElement('h4').getText();
                var id_input_element       = 'input-element-'+element_id;
                var id_input_element_name  = 'input-element-name-'+element_id;
                var id_input_category_name = 'input-category-name-'+element_id;

                new Element( 'input', {
                    'type' : 'text',
                    'id'   : id_input_element_name,
                    'name' : request_key + '[categorize][elements]['+element_id+'][element_name]',
                    'value': element_name.trim()
                }).injectInside( $('categorize-formfields') );
                
                new Element( 'input', {
                    'type' : 'text',
                    'id'   : id_input_element,
                    'name' : request_key + '[categorize][elements]['+element_id+'][category_id]',
                    'value': category_id
                }).injectInside( $('categorize-formfields') );

                new Element( 'input', {
                    'type' : 'text',
                    'id'   : id_input_category_name,
                    'name' : request_key + '[categorize][elements]['+element_id+'][category_name]',
                    'value': category_name.trim()
                }).injectInside( $('categorize-formfields') );

            });
        }
    });

    this.removeProperty('disabled');
    this.setProperty('value','Save categorization');
});

//});
