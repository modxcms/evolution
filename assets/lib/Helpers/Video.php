<?php
namespace Helpers;

/**
 * По мотивам videoThumb https://gist.github.com/bezumkin/4243590
 */
class Video {
    // Video details
    protected $url = null;
    protected $id = null;
    protected $service = "";

    // Settings embed
    protected $autoplay = false;
    protected $width = 420;
    protected $height = 315;

    public function __construct($link) {
        $this->process($link);
    }

    protected function process($video) {
        $out = $service = null;

        if (!preg_match('/^(http|https)\:\/\//i', $video)) {
            $video = 'http://' . $video;
        }

        switch(true){
            // YouTube
            case preg_match('/[http|https]+:\/\/(?:www\.|)youtube\.com\/watch\?(?:.*)?v=([a-zA-Z0-9_\-]+)/i', $video, $matches):
            case preg_match('/[http|https]+:\/\/(?:www\.|)youtube\.com\/embed\/([a-zA-Z0-9_\-]+)/i', $video, $matches):
            case preg_match('/[http|https]+:\/\/(?:www\.|)youtu\.be\/([a-zA-Z0-9_\-]+)/i', $video, $matches):{
                $this->id = $matches[1];
                $this->service = 'YouTube';
                break;
            }
            // Vimeo
            case preg_match('/[http|https]+:\/\/(?:www\.|)vimeo\.com\/([a-zA-Z0-9_\-]+)(&.+)?/i', $video, $matches):
            case preg_match('/[http|https]+:\/\/player\.vimeo\.com\/video\/([a-zA-Z0-9_\-]+)(&.+)?/i', $video, $matches):{
                $this->id = $matches[1];
                $this->service = 'Vimeo';
                break;
            }
            // ruTube
            case preg_match('/[http|https]+:\/\/(?:www\.|)rutube\.ru\/video\/embed\/([a-zA-Z0-9_\-]+)/i', $video, $matches):
            case preg_match('/[http|https]+:\/\/(?:www\.|)rutube\.ru\/tracks\/([a-zA-Z0-9_\-]+)(&.+)?/i', $video, $matches):{
                $this->id = $matches[1];
                $this->service = 'ruTube';
                break;
            }
            case preg_match('/[http|https]+:\/\/(?:www\.|)rutube\.ru\/video\/([a-zA-Z0-9_\-]+)\//i', $video, $matches):{
                $this->id = $matches[0];
                $this->service = 'ruTube';
                break;
            }
        }

        return $this;
    }

    // Generate embed code
    // Pass video_id
    // options (or leave blank for defaults / constructor options
    public function getEmbed($options = array()) {
        // Settings
        $autoplay = isset($options["autoplay"]) ? $options["autoplay"] : $this->autoplay;
        $width = isset($options["width"]) ? (int)$options["width"] : $this->width;
        $height = isset($options["height"]) ? (int)$options["height"] : $this->height;

        // Generate the correct embed code

        $f = $this->service . "Embed";
        if(!empty($this->service) && method_exists($this, $f)) {
            return $this->$f($autoplay, $width, $height);
        }
    }

    // Generate YouTube embed code
    protected function youtubeEmbed($autoplay, $width, $height) {
        return '<iframe src="//www.youtube.com/embed/' . $this->id . ''  . ($autoplay ? "&autoplay=1" : "") . '" width="' . $width . '" height="' . $height . '" frameborder="0" allowfullscreen></iframe>';
    }

    // Generate Vimeo embed code
    protected function vimeoEmbed($autoplay, $width, $height) {
        return '<iframe src="//player.vimeo.com/video/' . $this->id . ($autoplay ? "?autoplay=1" : "") . '" width="' . $width . '" height="' . $height . '" frameborder="0" allowFullScreen></iframe>';
    }
    protected function rutubeEmbed($autoplay, $width, $height){
         return '<iframe src="//rutube.ru/video/embed/' . $this->id . ($autoplay ? "?autoplay=1" : "") . '" width="' . $width . '" height="' . $height . '" frameborder="0" allowFullScreen></iframe>';
    }
}