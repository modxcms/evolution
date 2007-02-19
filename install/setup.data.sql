# MODx Database Script for New/Upgrade Installations
#
# Each sql command is separated by double lines


#
# Dumping data for table `keyword_xref`
#


REPLACE INTO `{PREFIX}keyword_xref` VALUES ('3','1');


REPLACE INTO `{PREFIX}keyword_xref` VALUES ('4','1');


#
# Dumping data for table `documentgroup_names`
#


REPLACE INTO `{PREFIX}document_groups` VALUES ('1','1','3');


REPLACE INTO `{PREFIX}documentgroup_names` VALUES ('1','Site Admin Pages','0','0');


#
# Dumping data for table `site_content`
#


REPLACE INTO `{PREFIX}site_content` VALUES (1, 'document', 'text/html', 'Home', 'Welcome to MODx', 'Introduction to MODx', 'index', '', 1, 0, 0, 0, 0, 'Create and do amazing things with MODx', '<h3>Install Successful!</h3>\r\n<p>You have successfully installed and configured MODx. We hope you find this site an adequate starting configuration for many small business, organization or personal websites; just change the template and content, and you''ll be good to go! This site is preconfigured with a variety of options we hope are helpful, relevant and just plain cool for many marketing or personal sites:</p>\r\n<ul>\r\n    <li><strong>Simple Blog.</strong> When logged into your site, you''ll be able to create new entries from the front end. This can also be turned into a News publishing or PR publishing system. <a href="[~2~]">View example blog</a></li>\r\n    <li><strong>Easy Comments.</strong> When logged into your site, your registered site users can comment on your posts. <a href="[~9~]">View example</a></li>\r\n    <li><strong>RSS Feeds.</strong> Your site visitors can stay up to date using your site feeds. <a href="feed.rss">View RSS feed</a></li>\r\n    <li><strong>Automatic User Registration.</strong> Those that wish to comment on blogs must first create an account. This comes pre-configured with a &quot;Captcha&quot; anti-robot registration feature. <a href="[~5~]">View registration form</a></li>\r\n    <li><strong>QuickEdit.</strong> When you''re logged into the manager, you can edit a page directly from the front end! <a href="[~14~]">More about CMS features</a></li>\r\n    <li><strong>Integrated Site Search.</strong> Allows visitors to search only the pages you wish them to search. Uses Ajax to display results without loading a new page. <a href="javascript:void(0)" onclick="new Effect.Highlight( ''ajaxSearch_input'' , {endcolor:''#FFFFFF'', duration: 2} );">Highlight feature</a></li>\r\n    <li><strong>Powerful Navigation Builder.</strong> Duplicate or build virtually any navigation system with our dynamic menu builder code. The menu above, for example. <a href="[~22~]">More about menu features</a></li>\r\n    <li><strong>Mootools enabled.</strong> You''re on your way to Web 2.0 and AJAX goodness. <a href="[~16~]">More about Ajax features</a></li>\r\n    <li><strong>Custom &quot;page not found (404)&quot; page.</strong> Help visitors who go astray to find what they''re looking for. <a href="[~7~]">View 404 page</a></li>\r\n    <li><strong>Contact Us form.</strong> A highly configurable contact form you <em>should</em> customize to point to the right email address. Comes pre-configured to prevent mail-form-injection so your site does <em>not</em> become a source for spam. <a href="[~6~]">View form</a></li>\r\n    <li><strong>Newest documents list.</strong> Shows your visitor the most recently added pages (configurable). <a href="javascript:void(0)" onclick="new Effect.Highlight( ''recentdocsctnr'' , {endcolor:''#E2E2E2'', duration: 2} );">Highlight example</a></li>\r\n</ul>\r\n<p><strong>To log into the MODx Control Panel and start customizing this site, point your browser to <a href="manager">[(site_url)]manager/</a>.</strong></p>', 1, 4, 1, 1, 1, 1, 1144904400, 1, 1160262629, 0, 0, 0, 0, 0, 'Home', 0, 0, 0, 0, 0, 0, 0);


REPLACE INTO `{PREFIX}site_content` VALUES (2, 'document', 'text/html', 'Blog', 'My Blog', '', 'blog', '', 1, 0, 0, 0, 1, '', '[[Ditto? &startID=`2` &summarize=`2` &removeChunk=`Comments` &tpl=`ditto_blog` &paginate=`1` &extenders=`date,summary,dateFilter` &paginateAlwaysShowLinks=`1` &tagData=`documentTags`]]\r\n\r\n<p>Showing <strong>[+start+]</strong> - <strong>[+stop+]</strong> of <strong>[+total+]</strong> Articles</p>\r\n\r\n<div id="ditto_pages"> [+previous+] [+pages+] [+next+] </div>\r\n\r\n<div id="ditto_pages">&nbsp;</div>\r\n\r\n[[Reflect? &dittoSnippetParameters=`startID:2` &groupByYears=`0` &showItems=0` &tplMonth=`reflect_month_tpl`]]', 1, 4, 2, 0, 0, 1, 1144904400, 1, 1159818696, 0, 0, 0, 0, 0, 'Blog', 0, 0, 0, 0, 0, 0, 0);


REPLACE INTO `{PREFIX}site_content` VALUES (3, 'document', 'text/html', 'Add a Blog Entry', 'Add a Blog Entry', '', 'add-a-blog-entry', '', 1, 0, 0, 2, 0, '', '[!NewsPublisher? &folder=`2` &canpost=`Site Admins` &formtpl=`FormBlog` &footertpl=`Comments` &makefolder=`1` &rtcontent=`tvblogContent`!]', 0, 4, 2, 0, 0, 1, 1144904400, 3, 1144904400, 0, 0, 0, 0, 0, 'Add Blog Entry', 1, 0, 0, 1, 0, 0, 0);


REPLACE INTO `{PREFIX}site_content` VALUES (4, 'document', 'text/html', '[*loginName*]', 'Login to Enable to Comments', '', 'login', '', 1, 0, 0, 0, 0, '', '<p>In order to comment on blog entries, you must be a registered user of [(site_name)]. If you haven''t already registered, you can  <a href="[~5~]">request an account</a>.</p>\r\n<div> [!WebLogin? &tpl=`FormLogin` &loginhomeid=`2`!] </div>', 1, 4, 11, 0, 0, 1, 1144904400, 1, 1144904400, 0, 0, 0, 0, 0, '[*loginName*]', 0, 0, 0, 0, 0, 0, 1);


REPLACE INTO `{PREFIX}site_content` VALUES (5, 'document', 'text/html', 'Request an Account', 'Sign Up for Full Site Privileges', '', 'request-an-account', '', 1, 0, 0, 0, 0, '', '[[WebSignup? &tpl=`FormSignup` &groups=`Registered Users`]]', 1, 4, 3, 0, 0, 1, 1144904400, 1, 1158320704, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 1);


REPLACE INTO `{PREFIX}site_content` VALUES (6, 'document', 'text/html', 'Contact Us', 'Contact [(site_name)]', '', 'contact-us', '', 1, 0, 0, 0, 0, '', '[!eForm? &formid=`ContactForm` &subject=`[+subject+]` &to=`[(email_sender)]` &ccsender=`1` &tpl=`ContactForm` &report=`ContactFormReport` &gotoid=`46`  !]\r\n', 0, 4, 14, 1, 0, 1, 1144904400, 1, 1159303922, 0, 0, 0, 0, 0, 'Contact us', 0, 0, 0, 0, 0, 0, 0);


REPLACE INTO `{PREFIX}site_content` VALUES (7, 'document', 'text/html', '404 - Document Not Found', 'Uh oh ... it''s a 404! (Page Not Found)', '', 'doc-not-found', '', 1, 0, 0, 0, 0, '', '<p>Looks like you tried to go somewhere that does not exist... perhaps you <a href="">need to login</a> or you''d like one of the following pages instead:</p>\r\n\r\n[[Wayfinder? &startId=`0` &showDescription=`1`]]\r\n\r\n<h3>Want to find it the old fashioned way? Use the site search at the top of this site to find what you seek.</h3>\r\n\r\n', 1, 4, 4, 0, 1, 1, 1144904400, 1, 1159301173, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 1);


REPLACE INTO `{PREFIX}site_content` VALUES (8, 'document', 'text/html', 'Search Results', 'Your Search Results', '', 'search-results', '', 1, 0, 0, 0, 0, '', '[!AjaxSearch? &AS_showForm=`0` &ajaxSearch=`0`!]', 0, 4, 5, 0, 0, 1, 1144904400, 1, 1158613055, 0, 0, 0, 0, 0, '', 1, 0, 0, 0, 0, 0, 1);


REPLACE INTO `{PREFIX}site_content` VALUES (9, 'document', 'text/html', 'Mini-Blog HOWTO', 'How to Start Posting with MODx Mini-Blogs', '', 'article-1126081344', '', 1, 0, 0, 2, 1, '', '<p>Setting up a mini-blog is relatively simple. Here''s what you need to do to get started with making new posts:</p>\r\n<ol>\r\n    <li>Login to the <a href="[(site_url)]manager/">MODx Control Panel</a>.</li>\r\n    <li>Create a new Webuser by clicking the <strong>Users &gt; Manage web users</strong> link.</li>\r\n    <li>Make sure to check the <strong>Site Admins</strong> Webuser Group at the bottom of the page.</li>\r\n    <!-- splitter -->\r\n    <li>Go to the <a href="[~4~]">login page</a> and login using this newly created Webuser information.</li>\r\n    <li>You should now notice a new <a href="[~3~]">Add a Blog Entry</a> menu item that automatically appeared under the Blog Page link in the right column.</li>\r\n</ol>\r\n{{Comments}}', 1, 4, 0, 1, 1, -1, 1144904400, 1, 1160171764, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 1);


REPLACE INTO `{PREFIX}site_content` VALUES (11, 'document', 'text/xml', 'RSS Feed', '[(site_name)] RSS Feed', '', 'feed.rss', '', 1, 0, 0, 0, 0, '', '[[Ditto? &startID=`2` &format=`rss` &summarize=`20` &total=`20` &commentschunk=`Comments`]]', 0, 0, 6, 0, 0, 1, 1144904400, 1, 1160062859, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 1);


REPLACE INTO `{PREFIX}site_content` VALUES (14, 'document', 'text/html', 'Content Management', 'Ways to manage content', '', 'cms', '', 1, 0, 0, 15, 0, '', '<h2>Manage your content in the backend</h2>\r\n<p>The Manager is a skinnable feature-packed tool for admin users. You can add extra users and limit what functions they can access. MODx''s Manager makes creating content and managing templates and reusable elements easy. Modules can be added to work with other datasets or make management tasks easier.</p>\r\n<h2>Manage your content in the frontend</h2>\r\n<p>The QuickEdit bar lets manager users edit content whilst browsing the site. Most content fields and template variables can be edited quickly and easily.</p>\r\n<h2>Enable web users to add content</h2>\r\n<p>Custom data entry is easy to code using the MODx API - so you can design forms and collect whatever information you need.</p>', 0, 4, 3, 1, 1, 1, 1144904400, 1, 1158331927, 0, 0, 0, 0, 0, 'Manage Content', 0, 0, 0, 0, 0, 0, 0);


REPLACE INTO `{PREFIX}site_content` VALUES (15, 'document', 'text/html', 'MODx Features', 'MODx Features', '', 'features', '', 1, 0, 0, 0, 1, '', '[!Wayfinder?startId=`[*id*]` &outerClass=`topnav`!]', 1, 4, 7, 1, 1, 1, 1144904400, 1, 1158452722, 0, 0, 0, 1144777367, 1, 'Features', 0, 0, 0, 0, 0, 0, 0);


REPLACE INTO `{PREFIX}site_content` VALUES (16, 'document', 'text/html', 'Ajax', 'Ajax and Web 2.0 ready', '', 'ajax', '', 1, 1159264800, 0, 15, 0, '', '<b>Ajax ready out-of-the-box</b>\r\n<p>MODx empowers users to build engaging sites today, with its pre-integrated <a href="http://mootools.net/" target="_blank">Mootools</a> javascript library.</p>\r\n\r\n<p>Check out the Ajax-powered search in this example site. The libraries are also used with QuickEdit, our front-end editing tool.</p>\r\n\r\n<p>Smart integration means the scripts are only included in the document head when needed - no unnecessary bloat on simple pages!</p>\r\n\r\n<b>Web 2.0 today</b>\r\n<p>MODx makes child''s play of building content managed sites with validating, accessible CSS layouts - so web standards compliance is easy. (You can create a site with excessively nested tables too, if you really want to).</p>\r\n', 1, 4, 1, 1, 1, 1, 1144904400, 1, 1159307504, 0, 0, 0, 0, 0, 'Ajax', 0, 0, 0, 0, 0, 0, 0);


REPLACE INTO `{PREFIX}site_content` VALUES (18, 'document', 'text/html', 'Just a pretend, older post', 'This post should in fact be archived', '', 'article-1128398162', '', 1, 0, 0, 2, 0, '', '<p>Not so exciting, after all, eh?<br /></p>\r\n', 1, 4, 2, 1, 1, -1, 1144904400, 1, 1159306886, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 1);


REPLACE INTO `{PREFIX}site_content` VALUES (22, 'document', 'text/html', 'Menus and Lists', 'Flexible Menus and Lists', '', 'menus', '', 1, 1159178400, 0, 15, 0, '', '<h2>Your documents - listed how you want them</h2>\r\n<p>MODx''s document data structure has been designed to allow many different routines to redisplay the information in ways that suit your needs, such as a dynamic menu in your template.</p>\r\n<p>Since the last release of MODx, the community has produced many great snippets - reusable functions that you can call in your content or template. Two of the most widely useful are Ditto and Wayfinder.</p>\r\n<h2>Wayfinder - the menu builder</h2>\r\n<p>Allows you to template every part of the menu. On this site, Wayfinder is being used to generate the drop-down menus, but many types of menus and sitemaps are possible. <a href="http://www.modxcms.com/Wayfinder-868.html">Wayfinder updates and support</a>.</p>\r\n<h2>Ditto - the document lister</h2>\r\n<p>Uses include listing the most recent blog posts, producing a site map, listing related documents (using a TV filter) and generating an RSS feed. You could even write a menu with it. On this site, Ditto is being used for the blog posts list on the Blog page, and the list on the right of some templates. <a href="http://modxcms.com/Ditto-487.html">Ditto updates and support</a>.</p>\r\n<h2>Unlimited Customization</h2>\r\n<p>If you can''t quite get your desired effect using templating and the many options of Ditto and Wayfinder, you can write your own routine, or look for other snippets in <a href="http://modxcms.com/downloads.html">the MODx repository</a>. MODx''s fields for Menu Title, summaries, menu position etc can be used via the API to produce anything you can imagine.</p>', 1, 4, 2, 1, 1, 1, 1144904400, 1, 1160148522, 0, 0, 0, 0, 0, 'Menus and Lists', 0, 0, 0, 0, 0, 0, 0);


REPLACE INTO `{PREFIX}site_content` VALUES (24, 'document', 'text/html', 'Extendable by design', 'Extendable by design', '', 'extendable', '', 1, 1159092732, 0, 15, 0, '', '<p>The MODx community has created many add-ons which can be found in the <a href="http://modxcms.com/downloads.html">Repository</a>, from image galleries and e-commerce to smaller utilities.</p>\r\n\r\n<h2>Template Variables with Bindings</h2>\r\n<p>TVs - Template Variables - are powerful extra fields that you can use with your documents. As an example of an advanced template element that returns a different thing dependent on code or data, we created an @BINDING for the name of the Login menu item. This changes the menu name from Login to Logout based on your logged in state. The @BINDING as follows was placed in the default value as:\r\n<code>@EVAL if ($modx->getLoginUserID()) return ''Logout''; else return ''Login'';</code></p>\r\n\r\n<h2>Using Scriptaculous</h2>\r\n<p>We used some simple effects to highlight various things on the front/home page to demonstrate how easy it is to create a useful way  to draw attention to things. To see them in action on the home page, click the Integrated Site Search, Related Links or Newest Documents headers.</p>\r\n\r\n<h2>Custom Forms</h2>\r\n<p>To demonstrate how to link to custom forms, we customized the calls to the Webuser Registration system and the Login system.</p>\r\n\r\n<h2>And more</h2>\r\n<p><strong>Rich Text Editor for blog entries.</strong> To make it easier to format blog posts with simple text formatting, we modified the blog to use a custom RTE-enabled Template Variable (TV).</p>\r\n\r\n<p><strong>Smart-Summary logic.</strong> When splitting the full blog/news posts you simply insert a "&lt;!-- splitter -->" where you want the break to occur. In addition, if that leaves any important tags open, it will try to match them and close them so it doesn''t mess up your site layout with unclosed OL, UL or DIV tags.</p>', 1, 4, 4, 1, 1, 2, 1144904400, 1, 1159309971, 0, 0, 0, 0, 0, 'Extendability', 0, 0, 0, 0, 0, 0, 0);


REPLACE INTO `{PREFIX}site_content` VALUES (32, 'document', 'text/html', 'Design', 'Site Design', '', 'design', '', 1, 0, 0, 0, 0, '', '<h3>Credits</h3>\r\n<p>The default site''s themes are based off of validating XHTML/CSS designs by <a href="http://andreasviklund.com/">Andreas     Viklund</a>, <a title="Complete web design solutions" href="http://ziworks.com/">ziworks | Web Solutions</a> and <a href="http://www.modxhost.com">MODxHost</a>. \r\n\r\n<h3>Style examples</h3>\r\n<p>This page was created to show some of the styles built into this template. It also shows the alternative content page layout, where the left sidebar has   been removed to let the content fill the entire width of the layout.</p>\r\n<h3 onclick="new Effect.toggle( ''styles'' , ''blind'');" href="javascript:void(0)" style="cursor: pointer;">Style List (<a>show/hide</a>)</h3>\r\n<div id="styles" style="display: none;" class="stylebox">   <blockquote>\r\n<p>&quot;This is a blockquote. Use this for quotes and references to texts from other places. You can also use the class .box to create a similar boxed effect...&quot;</p>\r\n</blockquote>\r\n<h1>Heading 1 Consect etuer adipisci ngon.</h1>\r\n<h2>Heading 2 Consect etuer adipisci ngon.</h2>\r\n<h3>Heading 3 Consect etuer adipisci ngon.</h3>\r\n<h4>Heading 4 Consect etuer adipisci ngon.</h4>\r\n<h5>Heading 5 Consect etuer adipisci ngon.</h5>\r\n<h6>Heading 6 Consect etuer adipisci ngon.</h6>\r\n<ul>\r\n    <li>Unordered list, option 1</li>\r\n    <li>Unordered list, option 2</li>\r\n    <li>Unordered list, option 3\r\n    <ul>\r\n        <li>Sub-option 3:1</li>\r\n        <li>Sub-option 3:2</li>\r\n        <li>Sub-option 3:3</li>\r\n    </ul>\r\n    </li>\r\n    <li>Unordered list, option 4</li>\r\n</ul>\r\n<ol>\r\n    <li>Ordered list, option 1</li>\r\n    <li>Ordered list, option 2</li>\r\n    <li>Ordered list, option 3\r\n    <ol>\r\n        <li>Sub-option 3:1</li>\r\n        <li>Sub-option 3:2</li>\r\n        <li>Sub-option 3:3</li>\r\n    </ol>\r\n    </li>\r\n    <li>Unordered list, option 4</li>\r\n</ol>\r\n<p><a href="#">This is a regular link.</a></p>\r\n<p><strong>This is strong text, left-aligned.</strong></p>\r\n<p class="important center">This is text with the class .important, centered.</p>\r\n<p class="textright"><em>This is emphasized text, right-aligned.</em></p>\r\n<p class="big">This is text styled with the class .big.</p>\r\n<p class="small">This is small text, using the class .small. Also available     is the class <span class="green">.green</span>.</p>\r\n</div>', 1, 4, 10, 1, 1, 2, 1144904400, 1, 1160112322, 0, 0, 0, 1144912754, 1, 'Design', 0, 0, 0, 0, 0, 0, 0);


REPLACE INTO `{PREFIX}site_content` VALUES (33, 'document', 'text/html', 'Getting Help', 'Getting Help with MODx', '', 'geting-help', '', 1, 0, 0, 0, 0, '', '<p>The <a href="http://modxcms.com/modx-team.html" target="_blank">team behind MODx</a> strives to contantly add to and refine the documentation to help you get up to speed with MODx:</p>\r\n<ul>\r\n    <li>For basic instructions on integrating custom templates into MODx, please see the <a target="_blank" href="http://modxcms.com/designer-guide.html">Designer''s Guide</a>. </li>\r\n    <li>For an introduction to working in MODx from the content editors perspectve, see the <a target="_blank" href="http://modxcms.com/editor-guide.html">Content Editor''s Guide</a>. </li>\r\n    <li>For a detailed overview of the backend &quot;manager&quot; and setting up Users and Groups, please peruse the <a target="_blank" href="http://modxcms.com/developers-guide.html">Administration Guide</a>.</li>\r\n    <li>For developers, architecture and API documentation can be found in the <a target="_blank" href="http://modxcms.com/administration-guide.html">Developer''s Guide</a>.</li>\r\n    <li>And if someone has installed this site for you, but you''re curious as to the steps they went through, please see the <a target="_blank" href="http://modxcms.com/getting-started.html">Getting Started Guide</a>.</li>\r\n</ul>\r\n\r\n<p>And don''t forget, you can always learn and ask questions at the <a href="http://www.modxcms.com/forums" target="_blank">MODx forums</a>. \r\n', 1, 4, 8, 1, 1, 2, 1144904400, 2, 1144904400, 0, 0, 0, 0, 0, 'Getting Help', 0, 0, 0, 0, 0, 0, 0);


REPLACE INTO `{PREFIX}site_content` VALUES (37, 'document', 'text/html', '[*loginName*]', 'The page you''re trying to reach requires a login', '', 'blog-login', '', 1, 0, 0, 0, 0, '', '<p>In order to add a blog entry, you must be logged in as a Site Admin webuser. Also, commenting on posts requires a login. <a href="[~6~]">Contact the site owner</a> for permissions to create new post, or <a href="[~5~]">create a web user account</a> to automatically receive commenting privileges. If you already have an account, please login below.</p>\r\n\r\n[!WebLogin? &tpl=`FormLogin` &loginhomeid=`3`!]', 1, 4, 12, 0, 0, 1, 1144904400, 1, 1158599931, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 1);


REPLACE INTO `{PREFIX}site_content` VALUES (39, 'document', 'text/html', 'Template Examples', 'Templates', 'Templates', 'templates', '', 1, 0, 0, 0, 1, '', '<p>This page provides a simple way to explore alternate layouts and stylesheets. To change the overall template, use the first set of links. To alter the stylesheets, use the second set of options.</p>\r\n\r\n<h4>Change Layout Templates:</h4>\r\n[!Wayfinder?startId=`[*id*]` &outerClass=`topnav`!]<br />\r\n\r\n<h4>Switch CSS Themes:</h4>\r\n{{styles}}', 1, 4, 9, 1, 0, 1, 1144904400, 1, 1159978559, 0, 0, 0, 1144628721, 1, 'Templates', 0, 0, 0, 0, 0, 0, 0);


REPLACE INTO `{PREFIX}site_content` VALUES (42, 'document', 'text/html', 'MODxCSS Wide', 'MODxCSS Wide', 'MODxCSS Wide', 'modxcss_wide', '', 1, 0, 0, 39, 0, '', '<p>This page provides a simple way to explore alternate layouts and stylesheets. To change the overall template, use the first set of links. To alter the stylesheets, use the second set of options.</p>\r\n\r\n<h4>Change Layout Templates:</h4>\r\n[!Wayfinder?startId=`[*parent*]` &outerClass=`topnav`!]<br />\r\n\r\n<h4>Switch CSS Themes:</h4>\r\n{{styles}}', 1, 3, 9, 1, 0, 1, 1144904400, 1, 1159978559, 0, 0, 0, 1144628721, 1, 'MODxCSS Wide', 0, 0, 0, 0, 0, 0, 0);


REPLACE INTO `{PREFIX}site_content` VALUES (43, 'document', 'text/html', 'MODxCSS', 'MODxCSS', 'MODxCSS', 'modxcss', '', 1, 0, 0, 39, 0, '', '<p>This page provides a simple way to explore alternate layouts and stylesheets. To change the overall template, use the first set of links. To alter the stylesheets, use the second set of options.</p>\r\n\r\n<h4>Change Layout Templates:</h4>\r\n[!Wayfinder?startId=`[*parent*]` &outerClass=`topnav`!]<br />\r\n\r\n<h4>Switch CSS Themes:</h4>\r\n{{styles}}', 1, 1, 9, 1, 0, 1, 1144904400, 1, 1159978559, 0, 0, 0, 1144628721, 1, 'MODxCSS', 0, 0, 0, 0, 0, 0, 0);


REPLACE INTO `{PREFIX}site_content` VALUES (44, 'reference', 'text/html', 'MODxHost', 'MODxHost', 'MODxHost', 'modxhost_tpl', '', 1, 0, 0, 39, 0, '', 'index.php?id=39', 0, 0, 1, 1, 0, 1, 1144904400, 1, 1158505455, 0, 0, 0, 1144967650, 1, 'MODxHost', 0, 0, 0, 0, 0, 0, 0);


REPLACE INTO `{PREFIX}site_content` VALUES (46, 'document', 'text/html', 'Thank You', '', '', 'thank-you', '', 1, 0, 0, 0, 0, '', '<h3>Thank You!</h3>\r\n<p>We do appreciate your feedback. Your comments have been submitted to our office and hopefully someone will bother to actually read it. You should also receive a copy of the message in your inbox.</p>\r\n<p>Please be assured that we will do our best not to ignore you, but if today''s a Monday please try again in a few days.</p>\r\n', 1, 4, 13, 1, 1, 1, 1159302141, 1, 1159302892, 0, 0, 0, 1159302182, 1, '', 0, 0, 0, 0, 0, 0, 1);


#
# Dumping data for table `site_htmlsnippets`
#


REPLACE INTO `{PREFIX}site_htmlsnippets` VALUES (1, 'WebLoginSideBar', 'WebLogin Sidebar Template', 0, 2, 0, '<!-- #declare:separator <hr> --> \r\n<!-- login form section-->\r\n<form method="post" name="loginfrm" action="[+action+]" style="margin: 0px; padding: 0px;"> \r\n<input type="hidden" value="[+rememberme+]" name="rememberme" /> \r\n<table border="0" cellspacing="0" cellpadding="0">\r\n<tr>\r\n<td>\r\n<table border="0" cellspacing="0" cellpadding="0">\r\n  <tr>\r\n	<td><b>User:</b></td>\r\n	<td><input type="text" name="username" tabindex="1" onkeypress="return webLoginEnter(document.loginfrm.password);" size="5" style="width: 100px;" value="[+username+]" /></td>\r\n  </tr>\r\n  <tr>\r\n	<td><b>Password:</b></td>\r\n	<td><input type="password" name="password" tabindex="2" onkeypress="return webLoginEnter(document.loginfrm.cmdweblogin);" size="5" style="width: 100px;" value="" /></td>\r\n  </tr>\r\n  <tr>\r\n	<td><label for="chkbox" style="cursor:pointer">Remember me:&nbsp; </label></td>\r\n	<td>\r\n	<table width="100%"  border="0" cellspacing="0" cellpadding="0">\r\n	  <tr>\r\n		<td valign="top"><input type="checkbox" id="chkbox" name="chkbox" tabindex="4" size="1" value="" [+checkbox+] onClick="webLoginCheckRemember()" /></td>\r\n		<td align="right">									\r\n		<input type="submit" value="[+logintext+]" name="cmdweblogin" /></td>\r\n	  </tr>\r\n	</table>\r\n	</td>\r\n  </tr>\r\n  <tr>\r\n	<td colspan="2"><a href="#" onclick="webLoginShowForm(2);return false;">Forget Password?</a></td>\r\n  </tr>\r\n</table>\r\n</td>\r\n</tr>\r\n</table>\r\n</form>\r\n<hr>\r\n<!-- log out hyperlink section -->\r\n<a href=''[+action+]''>[+logouttext+]</a>\r\n<hr>\r\n<!-- Password reminder form section -->\r\n<form name="loginreminder" method="post" action="[+action+]" style="margin: 0px; padding: 0px;">\r\n<input type="hidden" name="txtpwdrem" value="0" />\r\n<table border="0">\r\n	<tr>\r\n	  <td>Enter the email address of your account <br />below to receive your password:</td>\r\n	</tr>\r\n	<tr>\r\n	  <td><input type="text" name="txtwebemail" size="24" /></td>\r\n	</tr>\r\n	<tr>\r\n	  <td align="right"><input type="submit" value="Submit" name="cmdweblogin" />\r\n	  <input type="reset" value="Cancel" name="cmdcancel" onclick="webLoginShowForm(1);" /></td>\r\n	</tr>\r\n  </table>\r\n</form>\r\n\r\n', 0);


REPLACE INTO `{PREFIX}site_htmlsnippets` VALUES (2, 'FormBlog', 'Input Form for creating new blog entries', 0, 3, 0, '<form name="NewsPublisher" method="post" action="[~[*id*]~]">\r\n    <fieldset>\r\n        <h3>Publishing Details</h3>\r\n        <p>Note: Leaving the Publish Date empty will immediately publish your blog entry.</p>\r\n        <input name="NewsPublisherForm" type="hidden" value="on" />\r\n    	<label for="pagetitle">Page title <abbr title="The title used on the browser window">?</abbr>: <input name="pagetitle" id="pagetitle" type="text" size="40" value="[+pagetitle+]" /></label><br />\r\n    	<label for="longtitle">Headline <abbr title="The title used on the article">?</abbr>: <input name="longtitle" id="longtitle" type="text" size="40" value="[+longtitle+]" /></label><br />\r\n\r\n    	<label for="pub_date">Published date: <input name="pub_date" id="pub_date" type="text" value="[+pub_date+]" size="40" readonly="readonly" />\r\n    	<a onclick="nwpub_cal1.popup();" onmouseover="window.status=''Select date''; return true;" onmouseout="window.status=''''; return true;"><img src="manager/media/style/MODxLight/images/icons/cal.gif" width="16" height="16" alt="Select date" /></a>\r\n    	<a onclick="document.NewsPublisher.pub_date.value=''''; return true;" onmouseover="window.status=''Remove date''; return true;" onmouseout="window.status=''''; return true;"><img src="manager/media/style/MODxLight/images/icons/cal_nodate.gif" width="16" height="16" alt="Remove date" /></a></label>\r\n	</fieldset>\r\n	\r\n	<fieldset>\r\n    	<h3>The Content</h3>\r\n    	<p>The Summary field is optional, but is used as a short version for RSS feeds and summary views on the main blog page.</p>\r\n    	<label for="introtext">Summary (optional, but encouraged):<textarea name="introtext" cols="50" rows="5">[+introtext+]</textarea></label><br />\r\n    	<label for="content">Content:[*blogContent*]</label>\r\n	</fieldset>\r\n	\r\n	<fieldset>\r\n    	<h3>You''re Done</h3>\r\n		<label>Now... wasn''t that easy?</label>\r\n    	<input name="send" type="submit" value="Blog it!" class="button" />\r\n	</fieldset>	\r\n</form>\r\n<script language="JavaScript" src="manager/media/script/datefunctions.js"></script>\r\n<script type="text/javascript">\r\n		var elm_txt = {}; // dummy\r\n		var pub = document.forms["NewsPublisher"].elements["pub_date"];\r\n		var nwpub_cal1 = new calendar1(pub,elm_txt);\r\n		nwpub_cal1.path="manager/media/";\r\n		nwpub_cal1.year_scroll = true;\r\n		nwpub_cal1.time_comp = true;	\r\n</script>', 0);


REPLACE INTO `{PREFIX}site_htmlsnippets` VALUES (3, 'FormLogin', 'Custom login form for Weblogins', 0, 2, 0, '<!-- #declare:separator <hr> --> \r\n<!-- login form section-->\r\n<form method="post" name="loginfrm" action="[+action+]"> \r\n    <input type="hidden" value="[+rememberme+]" name="rememberme" /> \r\n    <fieldset>\r\n        <h3>Your Login Details</h3>\r\n        <label for="username">User: <input type="text" name="username" id="username" tabindex="1" onkeypress="return webLoginEnter(document.loginfrm.password);" value="[+username+]" /></label>\r\n    	<label for="password">Password: <input type="password" name="password" id="password" tabindex="2" onkeypress="return webLoginEnter(document.loginfrm.cmdweblogin);" value="" /></label>\r\n    	<input type="checkbox" id="checkbox_1" name="checkbox_1" tabindex="3" size="1" value="" [+checkbox+] onclick="webLoginCheckRemember()" /><label for="checkbox_1" class="checkbox">Remember me</label>\r\n    	<input type="submit" value="[+logintext+]" name="cmdweblogin" class="button" />\r\n	<a href="#" onclick="webLoginShowForm(2);return false;" id="forgotpsswd">Forget Your Password?</a>\r\n	</fieldset>\r\n</form>\r\n<hr>\r\n<!-- log out hyperlink section -->\r\n<h4>You''re already logged in</h4>\r\nDo you wish to <a href="[+action+]" class="button">[+logouttext+]</a>?\r\n<hr>\r\n<!-- Password reminder form section -->\r\n<form name="loginreminder" method="post" action="[+action+]">\r\n    <fieldset>\r\n        <h3>It happens to everyone...</h3>\r\n        <input type="hidden" name="txtpwdrem" value="0" />\r\n        <label for="txtwebemail">Enter the email address of your account to reset your password: <input type="text" name="txtwebemail" id="txtwebemail" size="24" /></label>\r\n        <label>To return to the login form, press the cancel button.</label>\r\n    	<input type="submit" value="Submit" name="cmdweblogin" class="button" /> <input type="reset" value="Cancel" name="cmdcancel" onclick="webLoginShowForm(1);" class="button" style="clear:none;display:inline" />\r\n    </fieldset>\r\n</form>\r\n', 0);


REPLACE INTO `{PREFIX}site_htmlsnippets` VALUES (4, 'FormSignup', 'For the weblogin signup', 0, 2, 0, '<!-- #declare:separator <hr> --> \r\n<!-- login form section-->\r\n<form method=\"post\" name=\"websignupfrm\" action=\"[+action+]\">\r\n    <fieldset>\r\n        <h3>User Details</h3>\r\n        <p>Items marked by * are required</p>\r\n		<label for=\"wsu_username\">User name:* <input type=\"text\" name=\"wsu_username\" id=\"wsu_username\" class=\"inputBox\" size=\"20\" maxlength=\"30\" value=\"[+username+]\" /></label>\r\n        <label for=\"fullname\">Full name: <input type=\"text\" name=\"fullname\" id=\"fullname\" class=\"inputBox\" size=\"20\" maxlength=\"100\" value=\"[+fullname+]\" /></label>\r\n		<label for=\"wsu_email\">Email address:* <input type=\"text\" name=\"wsu_email\" id=\"wsu_email\" class=\"inputBox\" size=\"20\" value=\"[+email+]\" /></label>\r\n	</fieldset>\r\n	\r\n	<fieldset>\r\n	    <h3>Password</h3>\r\n	    <label for=\"wsu_password\">Password:* <input type=\"password\" name=\"wsu_password\" id=\"wsu_password\" class=\"inputBox\" size=\"20\" /></label>\r\n	    <label for=\"confirmpassword\">Confirm password:* <input type=\"password\" name=\"confirmpassword\" id=\"confirmpassword\" class=\"inputBox\" size=\"20\" /></label>\r\n	</fieldset>\r\n	\r\n	<fieldset>\r\n		<h3>Optional Account Profile Info</h3>\r\n		<label for=\"country\">Country:</label>\r\n		<select size=\"1\" name=\"country\" id=\"country\">\r\n			<option value=\"\" selected=\"selected\">&nbsp;</option>\r\n			<option value=\"1\">Afghanistan</option>\r\n			<option value=\"2\">Albania</option>\r\n			<option value=\"3\">Algeria</option>\r\n			<option value=\"4\">American Samoa</option>\r\n			<option value=\"5\">Andorra</option>\r\n			<option value=\"6\">Angola</option>\r\n			<option value=\"7\">Anguilla</option>\r\n			<option value=\"8\">Antarctica</option>\r\n			<option value=\"9\">Antigua and Barbuda</option>\r\n			<option value=\"10\">Argentina</option>\r\n			<option value=\"11\">Armenia</option>\r\n			<option value=\"12\">Aruba</option>\r\n			<option value=\"13\">Australia</option>\r\n			<option value=\"14\">Austria</option>\r\n			<option value=\"15\">Azerbaijan</option>\r\n			<option value=\"16\">Bahamas</option>\r\n			<option value=\"17\">Bahrain</option>\r\n			<option value=\"18\">Bangladesh</option>\r\n			<option value=\"19\">Barbados</option>\r\n			<option value=\"20\">Belarus</option>\r\n			<option value=\"21\">Belgium</option>\r\n			<option value=\"22\">Belize</option>\r\n			<option value=\"23\">Benin</option>\r\n			<option value=\"24\">Bermuda</option>\r\n			<option value=\"25\">Bhutan</option>\r\n			<option value=\"26\">Bolivia</option>\r\n			<option value=\"27\">Bosnia and Herzegowina</option>\r\n			<option value=\"28\">Botswana</option>\r\n			<option value=\"29\">Bouvet Island</option>\r\n			<option value=\"30\">Brazil</option>\r\n			<option value=\"31\">British Indian Ocean Territory</option>\r\n			<option value=\"32\">Brunei Darussalam</option>\r\n			<option value=\"33\">Bulgaria</option>\r\n			<option value=\"34\">Burkina Faso</option>\r\n			<option value=\"35\">Burundi</option>\r\n			<option value=\"36\">Cambodia</option>\r\n			<option value=\"37\">Cameroon</option>\r\n			<option value=\"38\">Canada</option>\r\n			<option value=\"39\">Cape Verde</option>\r\n			<option value=\"40\">Cayman Islands</option>\r\n			<option value=\"41\">Central African Republic</option>\r\n			<option value=\"42\">Chad</option>\r\n			<option value=\"43\">Chile</option>\r\n			<option value=\"44\">China</option>\r\n			<option value=\"45\">Christmas Island</option>\r\n			<option value=\"46\">Cocos (Keeling) Islands</option>\r\n			<option value=\"47\">Colombia</option>\r\n			<option value=\"48\">Comoros</option>\r\n			<option value=\"49\">Congo</option>\r\n			<option value=\"50\">Cook Islands</option>\r\n			<option value=\"51\">Costa Rica</option>\r\n			<option value=\"52\">Cote D&#39;Ivoire</option>\r\n			<option value=\"53\">Croatia</option>\r\n			<option value=\"54\">Cuba</option>\r\n			<option value=\"55\">Cyprus</option>\r\n			<option value=\"56\">Czech Republic</option>\r\n			<option value=\"57\">Denmark</option>\r\n			<option value=\"58\">Djibouti</option>\r\n			<option value=\"59\">Dominica</option>\r\n			<option value=\"60\">Dominican Republic</option>\r\n			<option value=\"61\">East Timor</option>\r\n			<option value=\"62\">Ecuador</option>\r\n			<option value=\"63\">Egypt</option>\r\n			<option value=\"64\">El Salvador</option>\r\n			<option value=\"65\">Equatorial Guinea</option>\r\n			<option value=\"66\">Eritrea</option>\r\n			<option value=\"67\">Estonia</option>\r\n			<option value=\"68\">Ethiopia</option>\r\n			<option value=\"69\">Falkland Islands (Malvinas)</option>\r\n			<option value=\"70\">Faroe Islands</option>\r\n			<option value=\"71\">Fiji</option>\r\n			<option value=\"72\">Finland</option>\r\n			<option value=\"73\">France</option>\r\n			<option value=\"74\">France, Metropolitan</option>\r\n			<option value=\"75\">French Guiana</option>\r\n			<option value=\"76\">French Polynesia</option>\r\n			<option value=\"77\">French Southern Territories</option>\r\n			<option value=\"78\">Gabon</option>\r\n			<option value=\"79\">Gambia</option>\r\n			<option value=\"80\">Georgia</option>\r\n			<option value=\"81\">Germany</option>\r\n			<option value=\"82\">Ghana</option>\r\n			<option value=\"83\">Gibraltar</option>\r\n			<option value=\"84\">Greece</option>\r\n			<option value=\"85\">Greenland</option>\r\n			<option value=\"86\">Grenada</option>\r\n			<option value=\"87\">Guadeloupe</option>\r\n			<option value=\"88\">Guam</option>\r\n			<option value=\"89\">Guatemala</option>\r\n			<option value=\"90\">Guinea</option>\r\n			<option value=\"91\">Guinea-bissau</option>\r\n			<option value=\"92\">Guyana</option>\r\n			<option value=\"93\">Haiti</option>\r\n			<option value=\"94\">Heard and Mc Donald Islands</option>\r\n			<option value=\"95\">Honduras</option>\r\n			<option value=\"96\">Hong Kong</option>\r\n			<option value=\"97\">Hungary</option>\r\n			<option value=\"98\">Iceland</option>\r\n			<option value=\"99\">India</option>\r\n			<option value=\"100\">Indonesia</option>\r\n			<option value=\"101\">Iran (Islamic Republic of)</option>\r\n			<option value=\"102\">Iraq</option>\r\n			<option value=\"103\">Ireland</option>\r\n			<option value=\"104\">Israel</option>\r\n			<option value=\"105\">Italy</option>\r\n			<option value=\"106\">Jamaica</option>\r\n			<option value=\"107\">Japan</option>\r\n			<option value=\"108\">Jordan</option>\r\n			<option value=\"109\">Kazakhstan</option>\r\n			<option value=\"110\">Kenya</option>\r\n			<option value=\"111\">Kiribati</option>\r\n			<option value=\"112\">Korea, Democratic People&#39;s Republic of</option>\r\n			<option value=\"113\">Korea, Republic of</option>\r\n			<option value=\"114\">Kuwait</option>\r\n			<option value=\"115\">Kyrgyzstan</option>\r\n			<option value=\"116\">Lao People&#39;s Democratic Republic</option>\r\n			<option value=\"117\">Latvia</option>\r\n			<option value=\"118\">Lebanon</option>\r\n			<option value=\"119\">Lesotho</option>\r\n			<option value=\"120\">Liberia</option>\r\n			<option value=\"121\">Libyan Arab Jamahiriya</option>\r\n			<option value=\"122\">Liechtenstein</option>\r\n			<option value=\"123\">Lithuania</option>\r\n			<option value=\"124\">Luxembourg</option>\r\n			<option value=\"125\">Macau</option>\r\n			<option value=\"126\">Macedonia, The Former Yugoslav Republic of</option>\r\n			<option value=\"127\">Madagascar</option>\r\n			<option value=\"128\">Malawi</option>\r\n			<option value=\"129\">Malaysia</option>\r\n			<option value=\"130\">Maldives</option>\r\n			<option value=\"131\">Mali</option>\r\n			<option value=\"132\">Malta</option>\r\n			<option value=\"133\">Marshall Islands</option>\r\n			<option value=\"134\">Martinique</option>\r\n			<option value=\"135\">Mauritania</option>\r\n			<option value=\"136\">Mauritius</option>\r\n			<option value=\"137\">Mayotte</option>\r\n			<option value=\"138\">Mexico</option>\r\n			<option value=\"139\">Micronesia, Federated States of</option>\r\n			<option value=\"140\">Moldova, Republic of</option>\r\n			<option value=\"141\">Monaco</option>\r\n			<option value=\"142\">Mongolia</option>\r\n			<option value=\"143\">Montserrat</option>\r\n			<option value=\"144\">Morocco</option>\r\n			<option value=\"145\">Mozambique</option>\r\n			<option value=\"146\">Myanmar</option>\r\n			<option value=\"147\">Namibia</option>\r\n			<option value=\"148\">Nauru</option>\r\n			<option value=\"149\">Nepal</option>\r\n			<option value=\"150\">Netherlands</option>\r\n			<option value=\"151\">Netherlands Antilles</option>\r\n			<option value=\"152\">New Caledonia</option>\r\n			<option value=\"153\">New Zealand</option>\r\n			<option value=\"154\">Nicaragua</option>\r\n			<option value=\"155\">Niger</option>\r\n			<option value=\"156\">Nigeria</option>\r\n			<option value=\"157\">Niue</option>\r\n			<option value=\"158\">Norfolk Island</option>\r\n			<option value=\"159\">Northern Mariana Islands</option>\r\n			<option value=\"160\">Norway</option>\r\n			<option value=\"161\">Oman</option>\r\n			<option value=\"162\">Pakistan</option>\r\n			<option value=\"163\">Palau</option>\r\n			<option value=\"164\">Panama</option>\r\n			<option value=\"165\">Papua New Guinea</option>\r\n			<option value=\"166\">Paraguay</option>\r\n			<option value=\"167\">Peru</option>\r\n			<option value=\"168\">Philippines</option>\r\n			<option value=\"169\">Pitcairn</option>\r\n			<option value=\"170\">Poland</option>\r\n			<option value=\"171\">Portugal</option>\r\n			<option value=\"172\">Puerto Rico</option>\r\n			<option value=\"173\">Qatar</option>\r\n			<option value=\"174\">Reunion</option>\r\n			<option value=\"175\">Romania</option>\r\n			<option value=\"176\">Russian Federation</option>\r\n			<option value=\"177\">Rwanda</option>\r\n			<option value=\"178\">Saint Kitts and Nevis</option>\r\n			<option value=\"179\">Saint Lucia</option>\r\n			<option value=\"180\">Saint Vincent and the Grenadines</option>\r\n			<option value=\"181\">Samoa</option>\r\n			<option value=\"182\">San Marino</option>\r\n			<option value=\"183\">Sao Tome and Principe</option>\r\n			<option value=\"184\">Saudi Arabia</option>\r\n			<option value=\"185\">Senegal</option>\r\n			<option value=\"186\">Seychelles</option>\r\n			<option value=\"187\">Sierra Leone</option>\r\n			<option value=\"188\">Singapore</option>\r\n			<option value=\"189\">Slovakia (Slovak Republic)</option>\r\n			<option value=\"190\">Slovenia</option>\r\n			<option value=\"191\">Solomon Islands</option>\r\n			<option value=\"192\">Somalia</option>\r\n			<option value=\"193\">South Africa</option>\r\n			<option value=\"194\">South Georgia and the South Sandwich Islands</option>\r\n			<option value=\"195\">Spain</option>\r\n			<option value=\"196\">Sri Lanka</option>\r\n			<option value=\"197\">St. Helena</option>\r\n			<option value=\"198\">St. Pierre and Miquelon</option>\r\n			<option value=\"199\">Sudan</option>\r\n			<option value=\"200\">Suriname</option>\r\n			<option value=\"201\">Svalbard and Jan Mayen Islands</option>\r\n			<option value=\"202\">Swaziland</option>\r\n			<option value=\"203\">Sweden</option>\r\n			<option value=\"204\">Switzerland</option>\r\n			<option value=\"205\">Syrian Arab Republic</option>\r\n			<option value=\"206\">Taiwan</option>\r\n			<option value=\"207\">Tajikistan</option>\r\n			<option value=\"208\">Tanzania, United Republic of</option>\r\n			<option value=\"209\">Thailand</option>\r\n			<option value=\"210\">Togo</option>\r\n			<option value=\"211\">Tokelau</option>\r\n			<option value=\"212\">Tonga</option>\r\n			<option value=\"213\">Trinidad and Tobago</option>\r\n			<option value=\"214\">Tunisia</option>\r\n			<option value=\"215\">Turkey</option>\r\n			<option value=\"216\">Turkmenistan</option>\r\n			<option value=\"217\">Turks and Caicos Islands</option>\r\n			<option value=\"218\">Tuvalu</option>\r\n			<option value=\"219\">Uganda</option>\r\n			<option value=\"220\">Ukraine</option>\r\n			<option value=\"221\">United Arab Emirates</option>\r\n			<option value=\"222\">United Kingdom</option>\r\n			<option value=\"223\">United States</option>\r\n			<option value=\"224\">United States Minor Outlying Islands</option>\r\n			<option value=\"225\">Uruguay</option>\r\n			<option value=\"226\">Uzbekistan</option>\r\n			<option value=\"227\">Vanuatu</option>\r\n			<option value=\"228\">Vatican City State (Holy See)</option>\r\n			<option value=\"229\">Venezuela</option>\r\n			<option value=\"230\">Viet Nam</option>\r\n			<option value=\"231\">Virgin Islands (British)</option>\r\n			<option value=\"232\">Virgin Islands (U.S.)</option>\r\n			<option value=\"233\">Wallis and Futuna Islands</option>\r\n			<option value=\"234\">Western Sahara</option>\r\n			<option value=\"235\">Yemen</option>\r\n			<option value=\"236\">Yugoslavia</option>\r\n			<option value=\"237\">Zaire</option>\r\n			<option value=\"238\">Zambia</option>\r\n			<option value=\"239\">Zimbabwe</option>\r\n			</select>\r\n        </fieldset>\r\n        \r\n        <fieldset>\r\n            <h3>Bot-Patrol</h3>\r\n            <p>Enter the word/number combination shown in the image below.</p>\r\n            <label>Form code:* \r\n            <input type=\"text\" name=\"formcode\" class=\"inputBox\" size=\"20\" /></label>\r\n            <a href=\"[+action+]\"><img align=\"top\" src=\"manager/includes/veriword.php\" width=\"148\" height=\"60\" alt=\"If you have trouble reading the code, click on the code itself to generate a new random code.\" style=\"border: 1px solid #039\" /></a>\r\n        </fieldset>\r\n        \r\n        <fieldset>\r\n            <input type=\"submit\" value=\"Submit\" name=\"cmdwebsignup\" />\r\n	</fieldset>\r\n</form>\r\n\r\n<script language=\"javascript\" type=\"text/javascript\"> \r\n	var id = \"[+country+]\";\r\n	var f = document.websignupfrm;\r\n	var i = parseInt(id);	\r\n	if (!isNaN(i)) f.country.options[i].selected = true;\r\n</script>\r\n<hr>\r\n<!-- notification section -->\r\n<p class=\"message\">Signup completed successfully!<br />\r\nYour account was created. A copy of your signup information was sent to your email address.</p>\r\n', 0);


REPLACE INTO `{PREFIX}site_htmlsnippets` VALUES (5, 'FormBlogComments', 'Comment to show up beneath a blog for registered user comments', 0, 3, 0, '<a name="comments"></a>\r\n<p style="margin-top: 1em;font-weight:bold">Enter your comments in the space below (registered site users only):</p>\r\n[!UserComments? &canpost=`Registered Users, Site Admins` &makefolder=`0` &postcss=`comment` &titlecss=`commentTitle` &numbercss=`commentNum` &altrowcss=`commentAlt` &authorcss=`commentAuthor` &ownercss=`commentMe` &sortorder=`0`!]', 0);


REPLACE INTO `{PREFIX}site_htmlsnippets` VALUES (6, 'nl_sidebar', 'Default Template TPL for Ditto', 0, 3, 0, '<strong><a href="[~[+id+]~]" title="[+title+]">[+title+]</a></strong><br />\r\n[+longtitle+]<br /><br />', 0);


REPLACE INTO `{PREFIX}site_htmlsnippets` VALUES (7, 'styles', 'Stylesheet switcher list', 0, 1, 0, '<div id="modxhost">The CSS Themes can only be used on the MODxCSS and MODxCSSW Layouts</div>\r\n<script type="text/javascript">Element.hide(\'modxhost\');</script>\r\n<ul class="links">\r\n<li><a href="#" onclick="setActiveStyleSheet(''Trend''); return false;">Trend (Default)</a></li>\r\n<li><a href="#" onclick="setActiveStyleSheet(''Trend (Alternate)''); return false;" >Trend (Alternate)</a></li>\r\n<li><a href="#" onclick="setActiveStyleSheet(''ZiX''); return false;" >ZiX (Clean)</a></li>\r\n<li><a href="#" onclick="setActiveStyleSheet(''ZiX Background''); return false;" >ZiX (Background)</a></li>\r\n<li><a href="#" onclick="setActiveStyleSheet(''Light''); return false;" >Light</a></li>\r\n<li><a href="#" onclick="setActiveStyleSheet(''Light Green''); return false;" >Light Green</a></li>\r\n<li><a href="#" onclick="setActiveStyleSheet(''Dark''); return false;" >Dark</a></li>\r\n    </ul>', 0);


REPLACE INTO `{PREFIX}site_htmlsnippets` VALUES (8, 'ditto_blog', 'Blog Template', 0, 3, 0, '<div class="ditto_summaryPost">\r\n\  <h3><a href="[~[+id+]~]" title="[+title+]">[+title+]</a></h3>\r\n  <div class="ditto_info" >By <strong>[+author+]</strong> on [+date+]. <a  href="[~[+id+]~]#commentsAnchor">Comments\r\n  ([!Jot?&docid=`[+id+]`&action=`count-comments`!])</a></div><div class="ditto_tags">Tags: [+tagLinks+]</div>\r\n  [+summary+]\r\n  <p class="ditto_link">[+link+]</p>\r\n</div>', 0);


REPLACE INTO `{PREFIX}site_htmlsnippets` VALUES (9, 'footer', 'Site Template Footer', 0, 1, 0, '[(site_name)] is powered by <a href="http://modxcms.com/" title="Powered by MODx, Do more with less.">MODx CMS</a> |\r\n      <span id="andreas">Design by <a href="http://andreasviklund.com/">Andreas Viklund</a></span>\r\n<span id="zi" style="display: none">Designed by <a href="http://ziworks.com/" target="_blank" title="E-Business &amp; webdesign solutions">ziworks</a></span>\r\n\r\n<!-- the modx icon -->\r\n\r\n<div id="modxicon"><h6><a href="http://modxcms.com" title="MODx - The XHTML, CSS and Ajax CMS and PHP Application Framework" id="modxicon32">MODx - The XHTML, CSS and Ajax CMS and PHP Application Framework</a></h6></div>', 0);


REPLACE INTO `{PREFIX}site_htmlsnippets` VALUES (10, 'meta', 'Site Template Meta', 0, 1, 0, '<p><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></p>                	<p><a href="http://jigsaw.w3.org/css-validator/check/referer" title="This page uses valid Cascading Stylesheets" rel="external">Valid <abbr title="W3C Cascading Stylesheets">css</abbr></a></p>				    <p><a href="http://modxcms.com/" title="Powered by MODx, Do more with less.">MOD<strong>x</strong></a></p>', 0);


REPLACE INTO `{PREFIX}site_htmlsnippets` VALUES (11, 'mh.InnerRowTpl', 'Inner row template for ModxHost top menu', 0, 8, 0, '<li[+wf.classes+]><a href="[+wf.link+]" title="[+wf.title+]">[+wf.linktext+]</a>[+wf.wrapper+]</li>', 0);


REPLACE INTO `{PREFIX}site_htmlsnippets` VALUES (12, 'mh.InnerTpl', 'Inner nesting template for ModxHost top menu', 0, 8, 0, '<ul style="display:none">\r\n  [+wf.wrapper+]\r\n</ul>', 0);


REPLACE INTO `{PREFIX}site_htmlsnippets` VALUES (13, 'mh.OuterTpl', 'Outer nesting template for ModxHost top menu', 0, 8, 0, '  <ul id="myajaxmenu">\r\n    [+wf.wrapper+]\r\n  </ul>', 0);


REPLACE INTO `{PREFIX}site_htmlsnippets` VALUES (14, 'mh.RowTpl', 'Row template for ModxHost top menu', 0, 8, 0, '<li class="category [+wf.classnames+]"><a href="[+wf.link+]" title="[+wf.title+]">[+wf.linktext+]</a>[+wf.wrapper+]</li>', 0);


REPLACE INTO `{PREFIX}site_htmlsnippets` VALUES (15, 'Comments', 'Comments (Jot) showing beneath a blog entry.', 0, 3, 0, '<div id="commentsAnchor">\r\n[!Jot? &customfields=`name,email` &subscribe=`1` &pagination=`4` &badwords=`dotNet` &canmoderate=`Site Admins` !]\r\n</div>', 0);


REPLACE INTO `{PREFIX}site_htmlsnippets` VALUES (16, 'ContactForm', '', 0, 5, 0, '<p class="error">[+validationmessage+]</p>\r\n\r\n<form method="post" action="[~[*id*]~]" id="EmailForm" name="EmailForm">\r\n\r\n	<fieldset>\r\n		<h3> Contact Form</h3>\r\n\r\n		<input name="formid" type="hidden" value="ContactForm" />\r\n\r\n		<label for="cfName">Your name:\r\n		<input name="name" id="cfName" class="text" type="text" eform="Your Name::1:" /> </label>\r\n\r\n		<label for="cfEmail">Your Email Address:\r\n		<input name="email" id="cfEmail" class="text" type="text" eform="Email Address:email:1" /> </label>\r\n\r\n		<label for="cfRegarding">Regarding:</label>\r\n		<select name="subject" id="cfRegarding" eform="Form Subject::1">\r\n			<option value="General Inquiries">General Inquiries</option>\r\n			<option value="Press">Press or Interview Request</option>\r\n			<option value="Partnering">Partnering Opportunities</option>\r\n		</select>\r\n\r\n		<label for="cfMessage">Message: \r\n		<textarea name="message" id="cfMessage" rows="4" cols="20" eform="Message:textarea:1"></textarea>\r\n		</label>\r\n\r\n		<label>&nbsp;</label><input type="submit" name="contact" id="cfContact" class="button" value="Send This Message" />\r\n\r\n	</fieldset>\r\n\r\n</form>\r\n', 0);


REPLACE INTO `{PREFIX}site_htmlsnippets` VALUES (17, 'ContactFormReport', '', 0, 5, 0, '<p>This is a response sent by <b>[+Name+]</b> using the feedaback form on the website. The details of the mesage follow below:</p>\r\n\r\n\r\n<p>Name: [+name+]</p>\r\n<p>Email: [+email+]</p>\r\n<p>Regarding: [+subject+]</p>\r\n<p>comments:<br />[+message+]</p>\r\n\r\n<p>You can use this link to reply: <a href="mailto:[+email+]?subject=RE: [+subject+]">[+email+]</a></p>\r\n', 0);


REPLACE INTO `{PREFIX}site_htmlsnippets` VALUES (18, 'reflect_month_tpl', 'For the yearly archive. Use with Ditto.', 0, 3, 0, '<a href="[+url+]" title="[+month+] [+year+]" class="reflect_month_link">[+month+] [+year+]</a>', 0);


#
# Dumping data for table `site_keywords`
#


REPLACE INTO `{PREFIX}site_keywords` VALUES ('1','MODx');


REPLACE INTO `{PREFIX}site_keywords` VALUES ('2','content management system');


REPLACE INTO `{PREFIX}site_keywords` VALUES ('3','Front End Editing');


REPLACE INTO `{PREFIX}site_keywords` VALUES ('4','login');


#
# Dumping data for table `site_templates`
#


REPLACE INTO `{PREFIX}site_templates` VALUES ('1','MODxCSS','MODx CSS template','0','1','','0','<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">\r\n<head>\r\n<meta http-equiv=\"content-type\" content=\"text/html; charset=iso-8859-1\" />\r\n<base href=\"[(site_url)]\" />\r\n<link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS 2.0\" href=\"[(site_url)][~11~]\" />\r\n<link title=\"Trend\" rel=\"stylesheet\" type=\"text/css\" href=\"[(base_url)]assets/templates/default/fashion-modx-clear.css\" />\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"[(base_url)]assets/templates/default/modx.css\" />\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"[(base_url)]assets/templates/default/print.css\" media=\"print\" />\r\n<link title=\"ZiX\" rel=\"alternate stylesheet\" type=\"text/css\" href=\"[(base_url)]assets/templates/default/zi-modx-1.css\" />\r\n<link title=\"ZiX Background\" rel=\"alternate stylesheet\" type=\"text/css\" href=\"[(base_url)]assets/templates/default/zi-modx-2.css\" />\r\n<link title=\"Trend (Alternate)\" rel=\"alternate stylesheet\" type=\"text/css\" href=\"[(base_url)]assets/templates/default/default.css\" />\r\n<link title=\"Light\" rel=\"alternate stylesheet\" type=\"text/css\" href=\"[(base_url)]assets/templates/default/light_green.css\" />\r\n<link title=\"Light Green\" rel=\"alternate stylesheet\" type=\"text/css\" href=\"[(base_url)]assets/templates/default/light.css\" />\r\n<link title=\"Dark\" rel=\"alternate stylesheet\" type=\"text/css\" href=\"[(base_url)]assets/templates/default/dark.css\" />\r\n<script src=\"[(base_url)]assets/templates/default/styleswitcher.js\" type=\"text/javascript\"></script>\r\n<title>[(site_name)] | [*pagetitle*]</title>\r\n<script src=\"manager/media/script/scriptaculous/prototype.js\" type=\"text/javascript\"></script>\r\n<script src=\"manager/media/script/scriptaculous/scriptaculous.js\" type=\"text/javascript\"></script>\r\n</head>\r\n<body>\r\n<div id=\"wrap\">\r\n  <div id=\"header\">\r\n    <div id=\"title\">\r\n      <h1><a href=\"[~[(site_start)]~]\">[(site_name)]</a></h1>\r\n      <p id=\"slogan\">{{slogan}}</p>\r\n    </div>\r\n    <h2 class=\"hide\">Main menu</h2>\r\n    <div id=\"mainmenu\">[!Wayfinder?startId=`0` &hereClass=`current` &level=`1` &outerClass=`topnav`!]</div>\r\n    <div class=\"clear\"></div>\r\n  </div>\r\n  <div id=\"leftside\">\r\n    <div id=\"lmenu\" style=\"display: none;\">\r\n      <h2>Menu</h2>\r\n      [!Wayfinder?startId=`0` &hereClass=`` &selfClass=`current` &outerClass=`sidemenu`!]\r\n    </div>\r\n    <h2>Search</h2>\r\n    [[AjaxSearch? &AS_landing=`8` &moreResultsPage=`8` &showMoreResults=`1` &addJscript=`0` &extract=`0` &AS_showResults=`0`]]\r\n    <h2>News</h2>\r\n    [[Ditto? &startID=`2` &summarize=`1` &total=`1` &commentschunk=`Comments` &tpl=`nl_sidebar` &showarch=`0` &truncLen=`100` &truncSplit=`0`]]\r\n    <h2>Login</h2>\r\n    <div id=\"sidebarlogin\">[!WebLogin? &tpl=`FormLogin` &loginhomeid=`[(site_start)]`!]</div>\r\n    <h2>Styles</h2>\r\n    {{styles}} </div>\r\n  <div id=\"contentwide\">\r\n    <h2>[*longtitle*]</h2>\r\n    [*#content*] </div>\r\n  <div id=\"footer\">\r\n    <p> {{footer}}</p>\r\n    <p>MySQL: [^qt^], [^q^] request(s), PHP: [^p^], total: [^t^], document retrieved\r\n      from [^s^].</p>\r\n  </div>\r\n</div>\r\n</body>\r\n</html>\r\n','0');


REPLACE INTO `{PREFIX}site_templates` VALUES ('3','MODxCSS Wide','Wide Template','0','1','','0','<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">\r\n<head>\r\n<meta http-equiv=\"content-type\" content=\"text/html; charset=iso-8859-1\" />\r\n<base href=\"[(site_url)]\" />\r\n<link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS 2.0\" href=\"[(site_url)][~11~]\" />\r\n<link title=\"Trend\" rel=\"stylesheet\" type=\"text/css\" href=\"[(base_url)]assets/templates/default/fashion-modx-clear.css\" />\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"[(base_url)]assets/templates/default/modx.css\" />\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"[(base_url)]assets/templates/default/print.css\" media=\"print\" />\r\n<link title=\"ZiX\" rel=\"alternate stylesheet\" type=\"text/css\" href=\"[(base_url)]assets/templates/default/zi-modx-1.css\" />\r\n<link title=\"ZiX Background\" rel=\"alternate stylesheet\" type=\"text/css\" href=\"[(base_url)]assets/templates/default/zi-modx-2.css\" />\r\n<link title=\"Trend (Alternate)\" rel=\"alternate stylesheet\" type=\"text/css\" href=\"[(base_url)]assets/templates/default/default.css\" />\r\n<link title=\"Light\" rel=\"alternate stylesheet\" type=\"text/css\" href=\"[(base_url)]assets/templates/default/light_green.css\" />\r\n<link title=\"Light Green\" rel=\"alternate stylesheet\" type=\"text/css\" href=\"[(base_url)]assets/templates/default/light.css\" />\r\n<link title=\"Dark\" rel=\"alternate stylesheet\" type=\"text/css\" href=\"[(base_url)]assets/templates/default/dark.css\" />\r\n<script src=\"[(base_url)]assets/templates/default/styleswitcher.js\" type=\"text/javascript\"></script>\r\n<title>[(site_name)] | [*pagetitle*]</title>\r\n<script src=\"manager/media/script/scriptaculous/prototype.js\" type=\"text/javascript\"></script>\r\n<script src=\"manager/media/script/scriptaculous/scriptaculous.js\" type=\"text/javascript\"></script>\r\n</head>\r\n<body>\r\n<div id=\"wrap\">\r\n  <div id=\"header\">\r\n    <div id=\"title\">\r\n      <h1><a href=\"[~[(site_start)]~]\">[(site_name)]</a></h1>\r\n      <p id=\"slogan\">{{slogan}}</p>\r\n    </div>\r\n    <h2 class=\"hide\">Main menu</h2>\r\n    <div id=\"mainmenu\">[!Wayfinder?startId=`0` &hereClass=`current` &level=`1` &outerClass=`topnav`!]</div>\r\n    <div class=\"clear\"></div>\r\n  </div>\r\n  <div id=\"contentfull\">\r\n    <h2>[*longtitle*]</h2>\r\n    [*#content*] </div>\r\n  <div id=\"footer\">\r\n    <p> {{footer}}</p>\r\n    <p>MySQL: [^qt^], [^q^] request(s), PHP: [^p^], total: [^t^], document retrieved\r\n      from [^s^].</p>\r\n  </div>\r\n</div>\r\n</body>\r\n</html>\r\n','0');


REPLACE INTO `{PREFIX}site_templates` VALUES ('4','MODxHost','MODxHost Template','0','1','','0','<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n\r\n<head>\r\n  <title>[(site_name)] | [*pagetitle*]</title>\r\n  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=[(modx_charset)]\" />\r\n  <base href=\"[(site_url)]\"></base>\r\n  <link rel=\"stylesheet\" href=\"assets/templates/modxhost/layout.css\" type=\"text/css\" media=\"screen\" />\r\n  <link rel=\"stylesheet\" href=\"assets/templates/modxhost/modxmenu.css\" type=\"text/css\" media=\"screen\" />\r\n  <link rel=\"stylesheet\" href=\"assets/templates/modxhost/form.css\" type=\"text/css\" media=\"screen\" />\r\n  <link rel=\"stylesheet\" href=\"assets/templates/modxhost/modx.css\" type=\"text/css\" media=\"screen\" />\r\n  <link rel=\"stylesheet\" href=\"assets/templates/modxhost/print.css\" type=\"text/css\" media=\"print\" />\r\n  <link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS 2.0\" href=\"[(site_url)][~11~]\" />\r\n  <script src=\"manager/media/script/mootools/mootools.js\" type=\"text/javascript\"></script>\r\n  <script src=\"assets/templates/modxhost/drop_down_menu.js\" type=\"text/javascript\"></script>\r\n</head>\r\n\r\n<body>\r\n<div id=\"wrapper\">\r\n  <div id=\"minHeight\"></div>\r\n  <div id=\"outer\">\r\n    <div id=\"inner\">\r\n      <div id=\"right\">\r\n        <div id=\"right-inner\">\r\n          <h1 style=\"text-indent: -5000px;padding: 0px; margin:0px; font-size: 1px;\">[(site_name)]</h1>\r\n          <div id=\"sidebar\">\r\n            <h2>News:</h2>\r\n            [[Ditto? &startID=`2` &summarize=`2` &total=`20` &commentschunk=`Comments` &tpl=`nl_sidebar` &showarch=`0` &truncLen=`100` &truncSplit=`0`]]\r\n            <div id=\"recentdocsctnr\">\r\n              <h2>Most Recent:</h2>\r\n              <a name=\"recentdocs\"></a>[[ListIndexer?LIn_root=0]] </div>\r\n            <h2>Login:</h2>\r\n            <div id=\"sidebarlogin\">[!WebLogin? &tpl=`FormLogin` &loginhomeid=`[(site_start)]`!]</div>\r\n            <h2>Meta:</h2>\r\n            <p><a href=\"http://validator.w3.org/check/referer\" title=\"This page validates as XHTML 1.0 Transitional\">Valid <abbr title=\"eXtensible HyperText Markup Language\">XHTML</abbr></a></p>\r\n            <p><a href=\"http://jigsaw.w3.org/css-validator/check/referer\" title=\"This page uses valid Cascading Stylesheets\" rel=\"external\">Valid <abbr title=\"W3C Cascading Stylesheets\">css</abbr></a></p>\r\n            <p><a href=\"http://modxcms.com\" title=\"Ajax CMS and PHP Application Framework\">MODx</a></p>\r\n          </div>\r\n          <!-- close #sidebar -->\r\n        </div>\r\n        <!-- end right inner-->\r\n      </div>\r\n      <!-- end right -->\r\n      <div id=\"left\">\r\n        <div id=\"left-inner\">\r\n          <div id=\"content\">\r\n            <div class=\"post\">\r\n              <h2>[*longtitle*]</h2>\r\n              [*#content*] </div>\r\n            <!-- close .post (main column content) -->\r\n          </div>\r\n          <!-- close #content -->\r\n        </div>\r\n        <!-- end left-inner -->\r\n      </div>\r\n      <!-- end left -->\r\n    </div>\r\n    <!-- end inner -->\r\n    <div id=\"clearfooter\"></div>\r\n    <div id=\"header\">\r\n      <h1><a id=\"logo\" href=\"[~[(site_start)]~]\" title=\"[(site_name)]\">[(site_name)]</a></h1>\r\n      <div id=\"search\"><!--search_terms--><span id=\"search-txt\">SEARCH</span><a name=\"search\"></a>[!AjaxSearch? ajaxSearch=`1` &AS_landing=`8` &moreResultsPage=`8` &showMoreResults=`1` &addJscript=`0` &extract=`0` &AS_showResults=`0`!]</div>\r\n      <div id=\"ajaxmenu\"> [[Wayfinder?startId=`0` &outerTpl=`mh.OuterTpl` &innerTpl=`mh.InnerTpl` &rowTpl=`mh.RowTpl` &innerRowTpl=`mh.InnerRowTpl` &firstClass=`first` &hereClass=``]] </div>\r\n      <!-- end topmenu -->\r\n    </div>\r\n    <!-- end header -->\r\n    <br style=\"clear:both;height:0;font-size: 1px\" />\r\n    <div id=\"footer\">\r\n      <p> <a href=\"http://modxcms.com\" title=\"Ajax CMS and PHP Application Framework\">Powered\r\n          by MODx</a> &nbsp;<a href=\"http://www.modxhost.com/\" title=\"Template Designed by modXhost.com\">Template &copy; 2006\r\n          modXhost.com</a><br />\r\n        MySQL: [^qt^], [^q^] request(s), PHP: [^p^], total: [^t^], document retrieved\r\n        from [^s^]. </p>\r\n    </div>\r\n    <!-- end footer -->\r\n  </div>\r\n  <!-- end outer div -->\r\n</div>\r\n<!-- end wrapper -->\r\n</body>\r\n</html>','0');


REPLACE INTO `{PREFIX}site_templates` VALUES ('5','MODxHostWithComments','MODxHost Template with comments','0','3','','0','<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n\r\n<head>\r\n  <title>[(site_name)] | [*pagetitle*]</title>\r\n  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=[(modx_charset)]\" />\r\n  <base href=\"[(site_url)]\"></base>\r\n  <link rel=\"stylesheet\" href=\"assets/templates/modxhost/layout.css\" type=\"text/css\" media=\"screen\" />\r\n  <link rel=\"stylesheet\" href=\"assets/templates/modxhost/modxmenu.css\" type=\"text/css\" media=\"screen\" />\r\n  <link rel=\"stylesheet\" href=\"assets/templates/modxhost/form.css\" type=\"text/css\" media=\"screen\" />\r\n  <link rel=\"stylesheet\" href=\"assets/templates/modxhost/modx.css\" type=\"text/css\" media=\"screen\" />\r\n  <link rel=\"stylesheet\" href=\"assets/templates/modxhost/print.css\" type=\"text/css\" media=\"print\" />\r\n  <link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS 2.0\" href=\"[(site_url)][~11~]\" />\r\n  <script src=\"manager/media/script/mootools/mootools.js\" type=\"text/javascript\"></script>\r\n  <script src=\"assets/templates/modxhost/drop_down_menu.js\" type=\"text/javascript\"></script>\r\n</head>\r\n\r\n<body>\r\n<div id=\"wrapper\">\r\n  <div id=\"minHeight\"></div>\r\n  <div id=\"outer\">\r\n    <div id=\"inner\">\r\n      <div id=\"right\">\r\n        <div id=\"right-inner\">\r\n          <h1 style=\"text-indent: -5000px;padding: 0px; margin:0px; font-size: 1px;\">[(site_name)]</h1>\r\n          <div id=\"sidebar\">\r\n            <h2>News:</h2>\r\n            [[Ditto? &startID=`2` &summarize=`2` &total=`20` &commentschunk=`Comments` &tpl=`nl_sidebar` &showarch=`0` &truncLen=`100` &truncSplit=`0`]]\r\n            <div id=\"recentdocsctnr\">\r\n              <h2>Most Recent:</h2>\r\n              <a name=\"recentdocs\"></a>[[ListIndexer?LIn_root=0]] </div>\r\n            <h2>Login:</h2>\r\n            <div id=\"sidebarlogin\">[!WebLogin? &tpl=`FormLogin` &loginhomeid=`[(site_start)]`!]</div>\r\n            <h2>Meta:</h2>\r\n            <p><a href=\"http://validator.w3.org/check/referer\" title=\"This page validates as XHTML 1.0 Transitional\">Valid <abbr title=\"eXtensible HyperText Markup Language\">XHTML</abbr></a></p>\r\n            <p><a href=\"http://jigsaw.w3.org/css-validator/check/referer\" title=\"This page uses valid Cascading Stylesheets\" rel=\"external\">Valid <abbr title=\"W3C Cascading Stylesheets\">css</abbr></a></p>\r\n            <p><a href=\"http://modxcms.com\" title=\"Ajax CMS and PHP Application Framework\">MODx</a></p>\r\n          </div>\r\n          <!-- close #sidebar -->\r\n        </div>\r\n        <!-- end right inner-->\r\n      </div>\r\n      <!-- end right -->\r\n      <div id=\"left\">\r\n        <div id=\"left-inner\">\r\n          <div id=\"content\">\r\n            <div class=\"post\">\r\n              <h2>[*longtitle*]</h2>\r\n              [*#content*]\r\n            </div>\r\n            <!-- close .post (main column content) -->\r\n[!Jot? &customfields=`name,email` &subscribe=`1` &pagination=`10`!]\r\n          </div>\r\n          <!-- close #content -->\r\n        </div>\r\n        <!-- end left-inner -->\r\n      </div>\r\n      <!-- end left -->\r\n    </div>\r\n    <!-- end inner -->\r\n    <div id=\"clearfooter\"></div>\r\n    <div id=\"header\">\r\n      <h1><a id=\"logo\" href=\"[~[(site_start)]~]\" title=\"[(site_name)]\">[(site_name)]</a></h1>\r\n      <div id=\"search\"><!--search_terms--><span id=\"search-txt\">SEARCH</span><a name=\"search\"></a>[!AjaxSearch? ajaxSearch=`1` &AS_landing=`8` &moreResultsPage=`8` &showMoreResults=`1` &addJscript=`0` &extract=`0` &AS_showResults=`0`!]</div>\r\n      <div id=\"ajaxmenu\"> [[Wayfinder?startId=`0` &outerTpl=`mh.OuterTpl` &innerTpl=`mh.InnerTpl` &rowTpl=`mh.RowTpl` &innerRowTpl=`mh.InnerRowTpl` &firstClass=`first` &hereClass=``]] </div>\r\n      <!-- end topmenu -->\r\n    </div>\r\n    <!-- end header -->\r\n    <br style=\"clear:both;height:0;font-size: 1px\" />\r\n    <div id=\"footer\">\r\n      <p> <a href=\"http://modxcms.com\" title=\"Ajax CMS and PHP Application Framework\">Powered\r\n          by MODx</a> &nbsp;<a href=\"http://www.modxhost.com/\" title=\"Template Designed by modXhost.com\">Template &copy; 2006\r\n          modXhost.com</a><br />\r\n        MySQL: [^qt^], [^q^] request(s), PHP: [^p^], total: [^t^], document retrieved\r\n        from [^s^]. </p>\r\n    </div>\r\n    <!-- end footer -->\r\n  </div>\r\n  <!-- end outer div -->\r\n</div>\r\n<!-- end wrapper -->\r\n</body>\r\n</html>','0');


#
# Dumping data for table `site_tmplvars`
#


REPLACE INTO `{PREFIX}site_tmplvars` VALUES ('1','richtext','blogContent','blogContent','RTE for the new blog entries','0','0','0','','0','richtext','&w=383px&h=450px&edt=TinyMCE','');


REPLACE INTO `{PREFIX}site_tmplvars` VALUES ('2','text','loginName','loginName','Conditional name for the Login menu item','0','0','0','','0','','','@EVAL if ($modx->getLoginUserID()) return \'Logout\'; else return \'Login\';');


REPLACE INTO `{PREFIX}site_tmplvars` VALUES ('3','text','documentTags','Tags','Space delimited tags for the current document','0','3','0','','0','','','');


#
# Dumping data for table `modx2352_site_tmplvar_contentvalues`
#


REPLACE INTO `{PREFIX}site_tmplvar_contentvalues` VALUES ('1','3','9','demo miniblog howto tutorial posting');


REPLACE INTO `{PREFIX}site_tmplvar_contentvalues` VALUES ('2','3','18','demo older posting');


#
# Dumping data for table `site_tmplvar_templates`
#


REPLACE INTO `{PREFIX}site_tmplvar_templates` VALUES ('1','1','1');


REPLACE INTO `{PREFIX}site_tmplvar_templates` VALUES ('1','3','2');


REPLACE INTO `{PREFIX}site_tmplvar_templates` VALUES ('1','4','3');


REPLACE INTO `{PREFIX}site_tmplvar_templates` VALUES ('2','1','1');


REPLACE INTO `{PREFIX}site_tmplvar_templates` VALUES ('2','3','2');


REPLACE INTO `{PREFIX}site_tmplvar_templates` VALUES ('2','4','3');


REPLACE INTO `{PREFIX}site_tmplvar_templates` VALUES ('3','3','0');


REPLACE INTO `{PREFIX}site_tmplvar_templates` VALUES ('3','4','0');


REPLACE INTO `{PREFIX}site_tmplvar_templates` VALUES ('3','1','0');


REPLACE INTO `{PREFIX}site_tmplvar_templates` VALUES ('3','5','0');


#
# Dumping data for table `system_settings`
#


REPLACE INTO `{PREFIX}system_settings` VALUES('error_page', '7');


REPLACE INTO `{PREFIX}system_settings` VALUES('unauthorized_page', '4');


#
# Dumping data for table `web_groups`
#


REPLACE INTO `{PREFIX}web_groups` VALUES ('1','1','1');


#
# Dumping data for table `web_user_attributes`
#


REPLACE INTO `{PREFIX}web_user_attributes` VALUES ('1','1','Site Admin','0','you@yourdomain.com','','','0','0','0','25','1129049624','1129063123','0','f426f3209310abfddf2ee00e929774b4','0','0','','','','','','');


#
# Dumping data for table `web_users`
#


REPLACE INTO `{PREFIX}web_users` VALUES ('1','siteadmin','5f4dcc3b5aa765d61d8327deb882cf99','');


#
# Dumping data for table `webgroup_access`
#


REPLACE INTO `{PREFIX}webgroup_access` VALUES ('1','1','1');


#
# Dumping data for table `webgroup_names`
#


REPLACE INTO `{PREFIX}webgroup_names` VALUES ('1','Site Admins');


REPLACE INTO `{PREFIX}webgroup_names` VALUES ('2','Registered Users');


#
# Dumping data for table `categories`
#


REPLACE INTO `{PREFIX}categories` VALUES ('1','MODx default templates');


REPLACE INTO `{PREFIX}categories` VALUES ('2','User Management');


REPLACE INTO `{PREFIX}categories` VALUES ('3','News, Blogs and Catalogs');


REPLACE INTO `{PREFIX}categories` VALUES ('4','Navigation');


REPLACE INTO `{PREFIX}categories` VALUES ('5','Forms and Mail');


REPLACE INTO `{PREFIX}categories` VALUES ('6','Core and Manager');


REPLACE INTO `{PREFIX}categories` VALUES ('7','Frontend');


REPLACE INTO `{PREFIX}categories` VALUES ('8','MODxHost Menu');


REPLACE INTO `{PREFIX}categories` VALUES ('9','Demo Content');


REPLACE INTO `{PREFIX}categories` VALUES ('10','Search');


#
# Table structure for table `jot_content`
#


CREATE TABLE IF NOT EXISTS `{PREFIX}jot_content` (`id` int(10) NOT NULL auto_increment, `title` varchar(255) default NULL, `tagid` varchar(50) default NULL, `published` int(1) NOT NULL default '0', `uparent` int(10) NOT NULL default '0', `parent` int(10) NOT NULL default '0', `flags` varchar(25) default NULL, `secip` varchar(32) default NULL, `sechash` varchar(32) default NULL, `content` mediumtext, `customfields` mediumtext, `mode` int(1) NOT NULL default '1', `createdby` int(10) NOT NULL default '0', `createdon` int(20) NOT NULL default '0', `editedby` int(10) NOT NULL default '0', `editedon` int(20) NOT NULL default '0', `deleted` int(1) NOT NULL default '0', `deletedon` int(20) NOT NULL default '0', `deletedby` int(10) NOT NULL default '0', `publishedon` int(20) NOT NULL default '0', `publishedby` int(10) NOT NULL default '0', PRIMARY KEY  (`id`), KEY `parent` (`parent`), KEY `secip` (`secip`), KEY `tagidx` (`tagid`), KEY `uparent` (`uparent`)) TYPE=MyISAM;


#
# Dumping data for table `jot_content`
#


REPLACE INTO `{PREFIX}jot_content` VALUES ('9','The first comment','','1','9','0','','87.211.130.14','edb75dab198ff302efbf2f60e548c0b3','This is the first comment.','<custom><name></name><email></email></custom>','0','0','1160420310','0','0','0','0','0','0','0');


REPLACE INTO `{PREFIX}jot_content` VALUES ('10','Second comment','','1','9','0','','87.211.130.14','edb75dab198ff302efbf2f60e548c0b3','This is the second comment and uses an alternate row color. I also supplied a name, but i\'m not logged in.','<custom><name>Armand</name><email></email></custom>','0','0','1160420453','0','0','0','0','0','0','0');


REPLACE INTO `{PREFIX}jot_content` VALUES ('11','No abuse','','1','9','0','','87.211.130.14','edb75dab198ff302efbf2f60e548c0b3','Notice that I can\'t abuse <b>html</b>, ,  or [+placeholder+] tags.\r\n\r\nA new line also doesn\'t come unnoticed.','<custom><name>Armand</name><email></email></custom>','0','0','1160420681','0','0','0','0','0','0','0');


REPLACE INTO `{PREFIX}jot_content` VALUES ('12','Posting when logged in','','1','9','0','','87.211.130.14','58fade927c1df50ba6131f2b0e53c120','When you are logged in your own posts have a special color so you can easily spot them from the comment view. \r\n\r\nThe form also does not display any guest fields when logged in.','<custom></custom>','0','-1','1160421310','0','0','0','0','0','0','0');


REPLACE INTO `{PREFIX}jot_content` VALUES ('13','Managers','','1','9','0','','87.211.130.14','91e230cf219e3ade10f32d6a41d0bd4d','Comments posted when only logged in as a manager user will use your manager name.\r\n\r\nModerators options are always shown when you are logged in as manager user.','<custom></custom>','0','1','1160421487','0','0','0','0','0','0','0');


REPLACE INTO `{PREFIX}jot_content` VALUES ('14','Moderation','','1','9','0','','87.211.130.14','58fade927c1df50ba6131f2b0e53c120','In this setup the Site Admins group is defined as being the moderator for this particular comment view. These users will have extra moderation options \r\n\r\nManager users, Moderators or Trusted users can post bad words like: dotNet.','<custom></custom>','0','-1','1160422081','0','0','0','0','0','0','0');


REPLACE INTO `{PREFIX}jot_content` VALUES ('15','I\'m untrusted','','0','9','0','','87.211.130.14','edb75dab198ff302efbf2f60e548c0b3','Untrusted users however can NOT post bad words like: dotNet. When they do the posts will be unpublished.','<custom><name></name><email></email></custom>','0','0','1160422167','0','0','0','0','0','0','0');


#
# Table structure for table `jot_subscriptions`
#


CREATE TABLE IF NOT EXISTS `{PREFIX}jot_subscriptions` (`id` mediumint(10) NOT NULL auto_increment, `uparent` mediumint(10) NOT NULL default '0', `tagid` varchar(50) NOT NULL default '', `userid` mediumint(10) NOT NULL default '0', PRIMARY KEY  (`id`), KEY `uparent` (`uparent`), KEY `tagid` (`tagid`), KEY `userid` (`userid`)) TYPE=MyISAM;


