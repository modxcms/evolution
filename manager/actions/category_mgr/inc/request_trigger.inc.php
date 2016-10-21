<?php
/**
 * Ajax Requests
 */
if( isset( $_REQUEST[$cm->get('request_key')]['ajax'] ) )
{
    $_data  = $_REQUEST[$cm->get('request_key')];
    $output = '';
    $task   = $_data['task'];
    switch( $task )
    {
        /**
         * get categories
         */
        case 'categorize_load_elements':
            $elements = $_data['elements'];
            
            if( $uncategorized_elements = $cm->getAssignedElements( 0, $_data['elements'] ) )
            { 
               $output .= $cm->renderView('chunks/categorize/uncategorized_elements', $uncategorized_elements);
            }
            
            foreach( $cm->getCategories() as $category )
            {
                $category['elements'] = $cm->getAssignedElements( $category['id'], $_data['elements'] );
                $output .= $cm->renderView('chunks/categorize/category', $category);
            }            
            break;
    }
    exit( $output );
}
/**
 * Categorize elements
 * 
 * @notice array [data] removed
 * @see /manager/includes/protect.inc.php ($limit)
 * @see http://modxcms.com/forums/index.php/topic,40430.msg251476.html#msg251476 
 * 
 */
if( isset( $_POST[$cm->get('request_key')]['categorize']['submit'] ) )
{  
    $_data = $_POST[$cm->get('request_key')]['categorize'];
    $_changes = 0;

    $cm->addMessage(
        sprintf( 
            $cm->txt('Categorize <span class="highlight">%s</span>'), 
            $cm->txt($_data['elementsgroup'])
        ),
        'categorize'
    );
    
    if( !isset( $_data['elements'] ) )
    {
        $cm->addMessage( $cm->txt('Something went wrong.'), 'categorize' );
        return;
    }

    foreach( $_data['elements'] as $element_id => $data )
    {
        if( $cm->updateElement( $_data['elementsgroup'], $element_id, $data['category_id'] ) )
        {
            $cm->addMessage(
                sprintf( 
                    $cm->txt('<span class="highlight">%s(%s)</span> has been assigned to category <span class="highlight">%s(%s)</span>'), 
                    $data['element_name'], 
                    $element_id,
                    $data['category_name'],
                    $data['category_id']
                ),
                'categorize'
            );
            $_changes++;
        }
    }
    
    if( $_changes === 0 )
    {
        $cm->addMessage( $cm->txt('No categorization made.'), 'categorize' );
        return;
    }
    else
    {
        $cm->addMessage(
            sprintf( 
                $cm->txt('<span class="highlight">%s</span> changes made'), 
                $_changes
            ),
            'categorize'
        );       
    }
}

/**
 * Add a new category
 */
if( isset( $_POST[$cm->get('request_key')]['add']['submit'] ) )
{
    $_data    = $_POST[$cm->get('request_key')]['add']['data'];
    $category = trim( html_entity_decode($_data['name']) );
    $rank     = (int) $_data['rank'];

    if( empty( $category ) )
    {
       $cm->addMessage( $cm->txt('Please enter a name for the new category.'), 'add' );
       return;
    }

    if( $cm->isCategoryExists( $category ) )
    {
       $cm->addMessage( sprintf( $cm->txt('Category <span class="highlight">%s</span> already exists.'), $category ), 'add' ); 
       return;
    }
    
    if( $cm->addCategory( $category, $rank ) !== 0 )
    {
        $cm->addMessage(
            sprintf( 
                $cm->txt( 'The new category <span class="highlight">%s</span> was saved at position <span class="highlight">%s</span>.' ),
                $category,
                $rank
            ),
            'add'
        );
    }
    else
    {
        $cm->addMessage( $cm->txt('Something went wrong on adding a category.'), 'add' );
    }    
}

/**
 * Sort categories
 */
if( isset( $_POST[$cm->get('request_key')]['sort']['submit'] ) )
{
    $categories = $_POST[$cm->get('request_key')]['sort']['data'];
    $_changes   = 0;
    
    foreach( $categories as $category_id => $_data  ) 
    {
        $data = array(
            'category' => urldecode( $_data['category'] ),
            'rank'     => $_data['rank']
        );
        
        if( $cm->updateCategory( $category_id, $data ) )
        {
            $cm->addMessage(
                sprintf( 
                    $cm->txt('Category <span class="highlight">%s</span> was moved to position <span class="highlight">%s</span>'),
                    $data['category'],
                    $data['rank'] 
                ),
                'sort'
            );
            $_changes++;
        }
    }
        
    if( $_changes === 0 )
    {
        $cm->addMessage( $cm->txt( 'Nothing to change, so no changes made.' ), 'sort');
    }
    else
    {
        $cm->addMessage(
            sprintf( 
                $cm->txt('<span class="highlight">%s</span> changes made'), 
                $_changes
            ),
            'sort'
        );       
    }
}

/**
 * Edit categories
 */
if( isset( $_POST[$cm->get('request_key')]['edit']['submit'] ) )
{
    $categories = $_POST[$cm->get('request_key')]['edit']['data'];
    $_changes   = 0;

    foreach( $categories as $category_id => $_data  ) 
    {
        if( isset( $_data['delete'] ) )
        {
            if( $cm->deleteCategory( $category_id ) )
            {
                $cm->addMessage(
                    sprintf(
                        $cm->txt('Category <span class="highlight">%s</span> has been deleted'),
                        urldecode( $_data['origin'] )
                    ),
                    'edit'
                );
            }
            $_changes++;
            continue;
        }
        
        $data = array(
            'category' => trim( html_entity_decode( $_data['category'] ) ),
            'rank'     => $_data['rank']
        );
        
        if( $cm->updateCategory( $category_id, $data ) )
        {
            $cm->addMessage(
                sprintf( 
                    $cm->txt('Category <span class="highlight">%s</span> was renamed to <span class="highlight">%s</span>'),
                    urldecode( $_data['origin'] ),
                    $data['category']
                ),
                'edit'
            );
            $_changes++;
        }
    }

    if( $_changes === 0 )
    {
        $cm->addMessage( $cm->txt( 'Nothing to change, so no changes made.' ), 'edit');
    }
}

/**
 * Delete singel category by $_GET
 */
if( isset( $_GET[$cm->get('request_key')]['delete'] ) 
    && !empty( $_GET[$cm->get('request_key')]['delete'] ) )
{
    $category_id = (int)$_GET[$cm->get('request_key')]['delete'];
    
    if( $cm->deleteCategory( $category_id ) )
    {
        $cm->addMessage(
            sprintf(
                $cm->txt('Category <span class="highlight">%s</span> has been deleted'), 
                urldecode( $_GET[$cm->get('request_key')]['category'] )
            ), 
            'edit'
        );
    }
}
/**
 * Translate phrases
 */
if( isset( $_POST[$cm->get('request_key')]['translate']['submit'] ) )
{ 
    $translations = $_POST[$cm->get('request_key')]['translate']['data'];

    foreach( $translations as $native_phrase => $translation )
    {
        $native_phrase = urldecode( $native_phrase );

        if( empty( $translation ) )
        {
            $translation = $native_phrase;
            
            $cm->addMessage(
                sprintf(
                    $cm->txt('Translation for <span class="highlight">%s</span> was empty'), 
                    $native_phrase
                ), 
                'translate'
            );
        }

        //$cm->c('Translator')->setType('phrase');
        $cm->c('Translator')->addTranslation( $native_phrase, $translation, 'phrase' );
        
        $cm->addMessage(
            sprintf(
                $cm->txt('Translation for <span class="highlight">%s</span> to <span class="highlight">%s</span> successfully saved'), 
                $native_phrase,
                $translation
            ), 
            'translate'
        );
    }

    if( empty( $cm->new_translations ) )
    {   
       //$_REQUEST['webfxtab_manage-categories-pane'] = 0;
       // unset( $_COOKIE['webfxtab_manage-categories-pane'] );
       // setcookie('webfxtab_manage-categories-pane', 0);
    }
}