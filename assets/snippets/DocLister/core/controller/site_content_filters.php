<?php
/**
 * site_content_filters controller for DocLister
 *
 * @category controller
 * @license GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @author kabachello <kabachnik@hotmail.com>
 *
 * Adds flexible filters to DocLister. Filter types can be easily added using filter extenders (see filter subfolder).
 * To use filtering via snippet call add the "filters" parameter to the DocLister call like " ... &filters=`tv:tags:like:your_tag`
 * All filters adhere to the following syntax:
 * <logic_operator>(<filter_type>:<field>:<comparator>:<value>, <filter_type>:<field>:<comparator>:<value>, ...)
 * <logic_operator> - AND, OR, etc. - applied to a comma separated list of filters enclosed in parenthesis
 * <filter_type> - name of the filter extender to use (tv, content, etc.)
 * <field> - the field to filter (must be supported by the respecitve filter_type)
 * <comparator> - comparison operator (must be supported by the respecitve filter_type) - is, gt, lt, like, etc.
 * <value> - value to compare with
 *
 * Examples:
 * AND(content:template:eq:5; tv:tags:like:my tag) - fetch all documents with template id 5 and the words "my tag" in the TV named "tags"
 *
 */

include_once(dirname(__FILE__) . "/site_content.php");

/**
 * Class site_content_filtersDocLister
 */
class site_content_filtersDocLister extends site_contentDocLister
{

}
