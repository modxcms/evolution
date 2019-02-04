<?php
if (! function_exists('fetchCacheableRss')) {
    function fetchCacheableRss($url, $xpath = null, Closure $callback = null)
    {
        $items = [];
        $file = evolutionCMS()->getCachePath() . 'rss/' . md5($url);
        $loadPath = file_exists($file) ? $file : $url;
        $content = empty($loadPath) ? '' : file_get_contents($loadPath);

        if (! empty($content)) {
            $xml = new SimpleXmlElement($content);

            if (! empty($xpath)) {
                $xml = $xml->xpath($xpath);
            }

            if ($callback !== null) {
                foreach ($xml as $entry) {
                    if ($entry instanceof SimpleXMLElement) {
                        $props = $callback($entry);
                        if (!empty($props)) {
                            $items[] = $props;
                        }
                    }
                }
            } else {
                $items = $xml;
            }

            if (!empty($items) && $loadPath !== $file) {
                if(! is_dir(dirname($file))) {
                    mkdir(dirname($file));
                }
                file_put_contents($file, $content);
            }
        }

        return $items;
    }
}

if (! function_exists('rel2abs')) {
    /**
     * Convert relative path into absolute url
     *
     * @param string $rel
     * @param string $base
     * @return string
     */
    function rel2abs($rel, $base)
    {
        // parse base URL  and convert to local variables: $scheme, $host,  $path
        $tmp = parse_url($base);
        extract($tmp);
        if (strpos($rel, "//") === 0) {
            return $scheme . ':' . $rel;
        }
        // return if already absolute URL
        if (parse_url($rel, PHP_URL_SCHEME) != '') {
            return $rel;
        }
        // queries and anchors
        if ($rel[0] == '#' || $rel[0] == '?') {
            return $base . $rel;
        }
        // remove non-directory element from path
        $path = preg_replace('#/[^/]*$#', '', $path);
        // destroy path if relative url points to root
        if ($rel[0] == '/') {
            $path = '';
        }
        // dirty absolute URL
        $abs = $host . $path . "/" . $rel;
        // replace '//' or  '/./' or '/foo/../' with '/'
        $abs = preg_replace("/(\/\.?\/)/", "/", $abs);
        $abs = preg_replace("/\/(?!\.\.)[^\/]+\/\.\.\//", "/", $abs);

        // absolute URL is ready!
        return $scheme . '://' . $abs;
    }
}
