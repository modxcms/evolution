<?php

// MM rules can go in here, instead of a chunk.
// If you want to put your rules in a chunk, create one and specify its name in the plugin's configuration tab
// If you copy them to a chunk, don't include the opening and closing PHP tags

// ------------------------ INSERT YOUR RULES HERE --------------------
// Insert your rules here. You can also use a chunk; see the plugin configuration and documentation.
// For example rules, also see the default.mm_rules.inc.php and the documentation. PHP is allowed.

// Hide templates for non admin users
mm_hideTemplates('6', '!1');

// Make news stories published on today's date by default
mm_default('pub_date', '', '', '9');

// Hide some fields
mm_hideFields('link_attributes');

mm_renameField('longtitle', 'Page heading');
mm_renameField('description', 'Search engine description');


// ------------------------ END OF RULES --------------------
?>