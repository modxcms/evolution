<?php
 /*
 *  MODx Manager Home Page Implmentation by pixelchutes (www.pixelchutes.com)
 *  Based on kudo's kRSS Module v1.0.72
 *
 *  Written by: kudo, based on MagpieRSS
 *  Contact: kudo@kudolink.com
 *  Created: 11/05/2006 (November 5)
 *  For: MODx cms (modxcms.com)
 *  Name: kRSS
 *  Version (MODx Module): 1.0.72
 *  Version (Magpie): 0.72
 *  Description: A simple module to read RSS feeds: good to parse feeds from MODx Forums
 *                      Based on MagpieRSS (http://sourceforge.net/projects/magpierss/).
 *                      Item descriptions are limited to 200 chars.
 *  Installation: extract zip content to assets/modules/kRSS
 *                     Add/edit Feeds in Configuration (some lines under this).
 */


/* Configuration
---------------------------------------------- */
// Here you can set the urls to retrieve the RSS from. Simply add a $urls line following the numbering progress in the square brakets.

$urls['modx_news_content'] ="http://feeds.feedburner.com/modx-announce";
$urls['modx_security_notices_content'] = "http://feeds.feedburner.com/modxsecurity";

// How many items per Feed?
$itemsNumber = '10';

/* End of configuration
NO NEED TO EDIT BELOW THIS LINE
---------------------------------------------- */

#$output = '';
#$output .= '<html>';
#$output .= '<head>';
#$output .= '<title>MODx</title>';
#$output .= '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />';
#$output .= '<link rel="stylesheet" type="text/css" href="media/style/MODxLight/style.css?" />';
#$output .= '<script type="text/javascript">var MODX_MEDIA_PATH = "media";</script>';
#$output .= '<script type="text/javascript" language="JavaScript" src="media/script/modx.js"></script>';
#$output .= '<div class="subTitle">';
#$output .= '<span class="right">';
#$output .= '<img src="media/style/MODx/images/_tx_.gif" width="1" height="5"><br />kRSS - RSS from MODx Forums';
#$output .= '</span>';
#$output .= '</div>';

// include MagPieRSS
$basePath = $modx->config['base_path'];
require_once($basePath.'manager/media/rss/rss_fetch.inc');

$feedData = array(); // pixelchutes

// create Feed
foreach ($urls as $section=>$url) {
	$output = '';
    $rss = @fetch_rss($url);
    if( !$rss ){
    	$feedData[$section] = 'Failed to retrieve ' . $url;
    	continue;
	}
    #$output .= '<div class="sectionHeader">';
    #$output .= '<img src="media/style/MODx/images/misc/dot.gif" alt="" />&nbsp;'.$rss->channel['title'];
    #$output .= '</div>';
    #$output .= '<div class="sectionBody" style="font-size: 11px;"><ul>';
    $output .= '<ul>'; // pixelchutes

    $items = array_slice($rss->items, 0, $itemsNumber);
    foreach ($items as $item) {
        $href = $item['link'];
        $title = $item['title'];
        $pubdate = $item['pubdate'];
        $description = strip_tags($item['description']);
        if (strlen($description) > 199) {
            $description = substr($description, 0, 200);
            $description .= '...<br />Read <a href="'.$href.'">more</a>.';
        }
        $output .= '<li><a href="'.$href.'">'.$title.'</a> - <b>'.$pubdate.'</b><br />'.$description.'</li>';
    }

    #$output .= '</ul></div>';
    $output .= '</ul>'; // pixelchutes
	$feedData[$section] = $output;
}

#$output .= '</div></body></html>';
#echo $output;

?>