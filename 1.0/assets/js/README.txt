csshover3 and frankenslight notes:
------------------------------------------------------------------------------

csshover3.htc -- enable hovering on any element in IE6 and earlier (supports :hover, :active and :focus)
http://www.xs4all.nl/~peterned/csshover.html

frankensleight.js -- support transparent .png for foreground and background images in IE6 and earlier
http://wiredance.blogspot.com/2007/09/bgsleight-sleight-meet-frankensleight.html

Add these to your templates by using an IE Conditional Comment like the following:

    <!--[if lt IE 7]>
        <style type="text/css">
            body { behavior: url(assets/js/csshover3.htc) }
        </style>
        <script type="text/javascript" src="assets/js/frankensleight.js"></sript>
    <![endif]-->

Also note that serving the htc files via the proper mime-type is critical. See the csshover3.htc link above for details.