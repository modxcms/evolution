<?php
/**
 * @package modx
 * @subpackage phpthumb
 */
require_once MODX_BASE_PATH.'assets/snippets/phpthumbof/phpthumb.class.php';
/**
 * Helper class to extend phpThumb and simplify thumbnail generation process
 * since phpThumb class is overly convoluted and doesn't do enough.
 *
 * @package modx
 * @subpackage phpthumb
 */
class modPhpThumb extends phpThumb {

    function __construct(&$modx,array $config = array()) {
        $this->modx =& $modx;
        $this->config = array_merge(array(

        ),$config);
        parent::__construct();
    }

    /**
     * Setup some site-wide phpthumb options from modx config
     */
    public function initialize() {
        $cachePath = $this->modx->config['base_path'].'assets/cache/phpthumbof/';
        if (!is_dir($cachePath)) {
			mkdir($cachePath);
			@chmod($cachePath, 0755);
		}
		
        $this->setParameter('config_cache_directory',$cachePath);
        $this->setCacheDirectory();

        $this->setParameter('config_allow_src_above_docroot',false);
        $this->setParameter('config_cache_maxage', 0);
        $this->setParameter('config_cache_maxsize', 0);
        $this->setParameter('config_cache_maxfiles', 0);
        $this->setParameter('config_error_bgcolor','CCCCFF');
        $this->setParameter('config_error_textcolor','FF0000');
        $this->setParameter('config_error_fontsize',1);
        $this->setParameter('config_nohotlink_enabled',true);
        $this->setParameter('config_nohotlink_valid_domains', $this->modx->config['site_url']);
        $this->setParameter('config_nohotlink_erase_image',true);
        $this->setParameter('config_nohotlink_text_message','Off-server thumbnailing is not allowed');
        $this->setParameter('config_nooffsitelink_enabled',false);
        $this->setParameter('config_nooffsitelink_valid_domains',$this->modx->config['site_url']);
        $this->setParameter('config_nooffsitelink_require_refer',false);
        $this->setParameter('config_nooffsitelink_erase_image',true);
        $this->setParameter('config_nooffsitelink_watermark_src','');
        $this->setParameter('config_nooffsitelink_text_message','Off-server linking is not allowed');
        $this->setParameter('cache_source_enabled',false);
        $this->setParameter('cache_source_directory',$cachePath.'source/');
        $this->setParameter('allow_local_http_src',true);
        $this->setParameter('zc',0);
        $this->setParameter('far','C');
        $this->setParameter('cache_directory_depth',2);

        /* iterate through properties */
        foreach ($this->config as $property => $value) {
            $this->setParameter($property,$value);
        }
        return true;
    }

    /**
     * Sets the source image
     */
    public function set($src) {
        $src = str_replace('+','%27',urldecode($src));
        if (empty($src)) return '';
        return $this->setSourceFilename($src);
    }

    /**
     * Check to see if cached file already exists
     */
    public function checkForCachedFile() {
        $this->setCacheFilename();
        if (file_exists($this->cache_filename) && is_readable($this->cache_filename)) {
            return true;
        }
        return false;
    }

    /**
     * Load cached file
     */
    public function loadCache() {
        $this->RedirectToCachedFile();
    }

    /**
     * Cache the generated thumbnail.
     */
    public function cache() {
        phpthumb_functions::EnsureDirectoryExists(dirname($this->cache_filename));
        if ((file_exists($this->cache_filename) && is_writable($this->cache_filename)) || is_writable(dirname($this->cache_filename))) {
            $this->CleanUpCacheDirectory();
            if ($this->RenderToFile($this->cache_filename) && is_readable($this->cache_filename)) {
                chmod($this->cache_filename, 0644);
                $this->RedirectToCachedFile();
            }
        }
    }

    /**
     * Generate a thumbnail
     */
    public function generate() {
        if (!$this->GenerateThumbnail()) {
            //$this->modx->log(modX::LOG_LEVEL_ERROR,'phpThumb was unable to generate a thumbnail for: '.$this->cache_filename);
            return false;
        }
        return true;
    }

    /**
     * Output a thumbnail.
     */
    public function output() {
        $output = $this->OutputThumbnail();
        if (!$output) {
            //$this->modx->log(modx::LOG_LEVEL_ERROR,'Error outputting thumbnail:'."\n".$this->debugmessages[(count($this->debugmessages) - 1)]);
        }
        return $output;
    }


    /** PHPTHUMB HELPER METHODS **/

    public function RedirectToCachedFile() {

        $nice_cachefile = str_replace(DIRECTORY_SEPARATOR, '/', $this->cache_filename);
        $nice_docroot   = str_replace(DIRECTORY_SEPARATOR, '/', rtrim($this->config_document_root, '/\\'));

        $parsed_url = phpthumb_functions::ParseURLbetter(@$_SERVER['HTTP_REFERER']);

        $nModified  = filemtime($this->cache_filename);

        if ($this->config_nooffsitelink_enabled && @$_SERVER['HTTP_REFERER'] && !in_array(@$parsed_url['host'], $this->config_nooffsitelink_valid_domains)) {

            $this->DebugMessage('Would have used cached (image/'.$this->thumbnailFormat.') file "'.$this->cache_filename.'" (Last-Modified: '.gmdate('D, d M Y H:i:s', $nModified).' GMT), but skipping because $_SERVER[HTTP_REFERER] ('.@$_SERVER['HTTP_REFERER'].') is not in $this->config_nooffsitelink_valid_domains ('.implode(';', $this->config_nooffsitelink_valid_domains).')', __FILE__, __LINE__);

        } elseif ($this->phpThumbDebug) {

            $this->DebugTimingMessage('skipped using cached image', __FILE__, __LINE__);
            $this->DebugMessage('Would have used cached file, but skipping due to phpThumbDebug', __FILE__, __LINE__);
            $this->DebugMessage('* Would have sent headers (1): Last-Modified: '.gmdate('D, d M Y H:i:s', $nModified).' GMT', __FILE__, __LINE__);
            $getimagesize = @GetImageSize($this->cache_filename);
            if ($getimagesize) {
                $this->DebugMessage('* Would have sent headers (2): Content-Type: '.phpthumb_functions::ImageTypeToMIMEtype($getimagesize[2]), __FILE__, __LINE__);
            }
            if (ereg('^'.preg_quote($nice_docroot).'(.*)$', $nice_cachefile, $matches)) {
                $this->DebugMessage('* Would have sent headers (3): Location: '.dirname($matches[1]).'/'.urlencode(basename($matches[1])), __FILE__, __LINE__);
            } else {
                $this->DebugMessage('* Would have sent data: readfile('.$this->cache_filename.')', __FILE__, __LINE__);
            }

        } else {

            if (headers_sent()) {
                $this->ErrorImage('Headers already sent ('.basename(__FILE__).' line '.__LINE__.')');
                exit;
            }
            $this->SendSaveAsFileHeaderIfNeeded();

            header('Last-Modified: '.gmdate('D, d M Y H:i:s', $nModified).' GMT');
            if (@$_SERVER['HTTP_IF_MODIFIED_SINCE'] && ($nModified == strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])) && @$_SERVER['SERVER_PROTOCOL']) {
                header($_SERVER['SERVER_PROTOCOL'].' 304 Not Modified');
                exit;
            }

            $getimagesize = @GetImageSize($this->cache_filename);
            if ($getimagesize) {
                header('Content-Type: '.phpthumb_functions::ImageTypeToMIMEtype($getimagesize[2]));
            } elseif (eregi('\.ico$', $this->cache_filename)) {
                header('Content-Type: image/x-icon');
            }
            if (!$this->config_cache_force_passthru && ereg('^'.preg_quote($nice_docroot).'(.*)$', $nice_cachefile, $matches)) {
                header('Location: '.dirname($matches[1]).'/'.urlencode(basename($matches[1])));
            } else {
                @readfile($this->cache_filename);
            }
            exit;

        }
        return true;
    }
    public function SendSaveAsFileHeaderIfNeeded() {
        if (headers_sent()) {
            return false;
        }
        $downloadfilename = phpthumb_functions::SanitizeFilename(@$_GET['sia'] ? $_GET['sia'] : (@$_GET['down'] ? $_GET['down'] : 'phpThumb_generated_thumbnail'.(@$_GET['f'] ? $_GET['f'] : 'jpg')));
        if (@$downloadfilename) {
            $this->DebugMessage('SendSaveAsFileHeaderIfNeeded() sending header: Content-Disposition: '.(@$_GET['down'] ? 'attachment' : 'inline').'; filename="'.$downloadfilename.'"', __FILE__, __LINE__);
            header('Content-Disposition: '.(@$_GET['down'] ? 'attachment' : 'inline').'; filename="'.$downloadfilename.'"');
        }
        return true;
    }
}