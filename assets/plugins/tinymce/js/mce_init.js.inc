<script language="javascript" type="text/javascript" src="[+mce_url+]jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript" src="[+mce_url+]js/xconfig.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
	theme                            : 'advanced',
	mode                             : 'exact',
	elements                         : '[+elmList+]',
	width                            : '[+width+]',
	height                           : '[+height+]',
	language                         : '[+language+]',
	document_base_url                : '[+document_base_url+]',
	relative_urls                    : [+relative_urls+],
	remove_script_host               : [+remove_script_host+],
	convert_urls                     : [+convert_urls+],
	forced_root_block                : '[+forced_root_block+]',
	force_p_newlines                 : [+force_p_newlines+],
	valid_elements                   : mce_valid_elements,
	extended_valid_elements          : mce_extended_valid_elements,
	invalid_elements                 : mce_invalid_elements,
	popup_css_add                    : '[+mce_url+]style/popup_add.css',
	accessibility_warnings : false,
	theme_advanced_toolbar_location  : 'top',
	theme_advanced_statusbar_location: 'bottom',
	theme_advanced_toolbar_align     : '[+toolbar_align+]',
	theme_advanced_font_sizes        : '80%,90%,100%,120%,140%,160%,180%,220%,260%,320%,400%,500%,700%',
	content_css                      : '[+content_css+]',
	formats : {
		alignleft   : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'justifyleft'},
		alignright  : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'justifyright'},
		alignfull   : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'justifyfull'}
	},
	apply_source_formatting          : true,
	remove_linebreaks                : false,
	convert_fonts_to_spans           : true,
	plugins                          : '[+plugins+]',
	theme_advanced_buttons1          : '[+buttons1+]',
	theme_advanced_buttons2          : '[+buttons2+]',
	theme_advanced_buttons3          : '[+buttons3+]',
	theme_advanced_buttons4          : '[+buttons4+]',
	theme_advanced_resize_horizontal :  false,
	external_link_list_url           : [+link_list+],
	theme_advanced_blockformats      : '[+blockFormats+]',
	theme_advanced_styles            : '[+css_selectors+]',
	theme_advanced_disable           : '[+disabledButtons+]',
	theme_advanced_resizing          : [+resizing+],
	fullscreen_settings : {
		theme_advanced_buttons1_add_before : 'save'
	},
	plugin_insertdate_dateFormat     : '[+date_format+]',
	plugin_insertdate_timeFormat     : '[+time_format+]',
	entity_encoding                  : '[+entity_encoding+]',
	file_browser_callback            : '[+file_browser_callback+]',
	onchange_callback                : [+onchange_callback+][+terminate+]
	[+customparams+]
})
</script>

<script language="javascript" type="text/javascript">
function myCustomOnChangeHandler() {
	documentDirty = true;
}
</script>
