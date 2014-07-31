# Breadcrumbs
 
### Configurable breadcrumb page-trail navigation
 
This snippet shows the path through the various levels of site structure. It
is NOT necessarily the path the user took to arrive at a given page.


## Parameters

This section contains brief explanations of the available parameters.

### General

Name | Description | Default value
-----|-------------|--------------
maxCrumbs | Max number of elements to have in a breadcrumb path. The default 100 is an arbitrarily high number that will essentially include everything. If you were to set it to 2, and you were 5 levels deep, it would appear like: HOME > ... > Level 3 > Level 4 > CURRENT PAGE It should be noted that the "home" link, and the current page do not count as they are managed by their own configuration settings. | 100
pathThruUnPub | When your path includes an unpublished folder, setting this to 1 (true) will show all documents in path EXCEPT the unpublished. When set to 0 (false), the path will not go "through" that unpublished folder and will stop there. | 1
respectHidemenu | Setting this to 1 (true) will respect the hidemenu setting of the document and not include it in trail. | 1
showCurrentCrumb | Include the current page at the end of the trail. On by default. | 1
currentAsLink | If the current page is included, this parameter will show it as a link (1) or just plain text (0). | 0
linkTextField | Prioritized list of fields to use as link text. Options are: pagetitle, longtitle, description, menutitle. The first of these fields that has a value will be the title. | `menutitle,pagetitle,longtitle`
linkDescField | Prioritized list of fields to use as link title text. Options are: pagetitle, longtitle, description, menutitle. The first of these fields that has a value will be the title. | `description,longtitle,pagetitle,menutitle`
showCrumbsAsLinks | If for some reason you want breadcrumbs to be text and not links, set to 0 (false). | 1
templateSet | The set of templates you'd like to use. (Templates are defined below.) It will default to defaultString which replicates the output of previous versions. | `defaultString`
crumbGap | String to be shown to represent gap if there are more crumbs in trail than can be shown. Note: if you would like to use an image, the entire image tag must be provided. When making a snippet call, you cannot use "=", so use "&#124;&#124;" instead and it will be converted for you. | `...`
stylePrefix | Breadcrumbs will add style classes to various parts of the trail. To avoid class name conflicts, you can determine your own prefix. The following classes will be attached: crumbBox: Span that surrounds all crumb output; hideCrumb: Span that surrounds the "..." if there are more crumbs than will be shown; currentCrumb: Span or A tag surrounding the current crumb; firstCrumb: Span that will be applied to first crumb, whether it is "home" or not; lastCrumb: Span surrounding last crumb, whether it is the current page or not; crumb: Class given to each A tag surrounding the intermediate crumbs (not "home", "current", or "hide"); homeCrumb: Class given to the home crumb | `B_`

### Home link 

The home link is unique. It is a link that can be placed at the head of the
breadcrumb trail, even if it is not truly in the hierarchy.

Name | Description | Default value
-----|-------------|--------------
showHomeCrumb | This toggles the "home" crumb to be added to the beginning of your trail. | 1
homeId | Usually the page designated as "site start" in MODx configuration is considered the home page. But if you would like to use some other document, you may explicitly define it. | `[(site_start)]`
homeCrumbTitle | If you'd like to use something other than the menutitle (or pagetitle) for the home link. | -
homeCrumbDescription | If you'd like to use a custom description (link title) on the home link. If left blank, the title will follow the title order set in titleField. | -

### Behaviors

The following parameters will alter the behavior of the Breadcrumbs based on
the page it is on.

Name | Description | Default value
-----|-------------|--------------
showCrumbsAtHome |  You can turn off Breadcrumbs all together on the home page by setting this to 1 (true); | 0
hideOn | Comma separated list of documents you don't want Breadcrumbs on at all. If you have a LOT of pages like this, you might try hideUnder or use another template. This parameter is best for those rare odd balls - otherwise it will become a pain to manage. | -
hideUnder | Comma separated list of parent documents, whose CHILDREN you don't want Breadcrumbs to appear on at all. This enables you to hide Breadcrumbs on a whole folders worth of documents by specifying the parent only. The PARENT will not have Breadcrumbs hidden however. If you wanted to hide the parent and the children, put the parent ID in hideUnder AND hideOn. | -
stopIds | Comma separated list of document IDs that when reached, stops Breadcrumbs from going any further. This is useful in situations like where you have language branches, and you don't want the Breadcrumbs going past the "home" of the language you're in. | -
ignoreIds | Comma separated list of document IDs to explicitly ignore. | -
crumbSeparator | The set of templates you'd like to use for crumbSeparator. | `&raquo;`

## Templates

In an effort to keep the MODx chunks manager from getting mired down in lots of templates, Breadcrumbs templates are included here. Two sets are provided prefixed with defaultString, and defaultList. You can create as many more as you like, each set with it's own prefix

```
templates = array(
    'defaultString' => array(
        'crumb' => '[+crumb+]',
        'separator' => ' '.crumbSeparator.' ',
        'crumbContainer' => '<span class="[+crumbBoxClass+]">[+crumbs+]</span>',
        'lastCrumbWrapper' => '<span class="[+lastCrumbClass+]">[+lastCrumbSpanA+]</span>',
        'firstCrumbWrapper' => '<span class="[+firstCrumbClass+]">[+firstCrumbSpanA+]</span>'
    ),
    'defaultList' => array(
        'crumb' => '<li>[+crumb+]</li>',
        'separator' => '',
        'crumbContainer' => '<ul class="[+crumbBoxClass+]">[+crumbs+]</ul>',
        'lastCrumbWrapper' => '<span class="[+lastCrumbClass+]">[+lastCrumbSpanA+]</span>',
        'firstCrumbWrapper' => '<span class="[+firstCrumbClass+]">[+firstCrumbSpanA+]</span>'
    ),
);
```

