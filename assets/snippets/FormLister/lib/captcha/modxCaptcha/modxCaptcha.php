<?php

/**
 * Created by PhpStorm.
 * User: Pathologic
 * Date: 06.02.2016
 * Time: 0:11
 */
class ModxCaptcha
{
    protected $modx = null;

    /* path to font directory*/
    protected $dir_font = "/ttf/";
    /* path to background image directory*/
    protected $dir_noise = "/noises/";
    public $word = "";
    protected $im = null;
    protected $im_width = 0;
    protected $im_height = 0;

    /**
     * ModxCaptcha constructor.
     * @param DocumentParser $modx
     * @param int $width
     * @param int $height
     */
    public function __construct(DocumentParser $modx, $width = 200, $height = 160)
    {
        $this->modx = $modx;
        $this->dir_font = __DIR__ . $this->dir_font;
        $this->dir_noise = __DIR__ . $this->dir_noise;
        $this->im_width = $width;
        $this->im_height = $height;
        $this->word = $this->pickWord();
    }

    /**
     * @param bool $inline
     * @return string
     */
    public function outputImage($inline = false)
    {
        /* output the image as jpeg */
        $this->drawImage();
        ob_clean();
        if ($inline) {
            ob_start();
            imagejpeg($this->im);
            $image = ob_get_contents();
            ob_end_clean();

            return 'data:image/jpeg;base64,' . base64_encode($image);
        }
        header("Content-type: image/jpeg");
        imagejpeg($this->im);
        imagedestroy($this->im);
    }

    /**
     * @return string
     */
    public function pickWord()
    {
        // set default words
        $words = "MODX,Access,Better,BitCode,Chunk,Cache,Desc,Design,Excell,Enjoy,URLs,TechView,Gerald,Griff,Humphrey,Holiday,Intel,Integration,Joystick,Join(),Oscope,Genetic,Light,Likeness,Marit,Maaike,Niche,Netherlands,Ordinance,Oscillo,Parser,Phusion,Query,Question,Regalia,Righteous,Snippet,Sentinel,Template,Thespian,Unity,Enterprise,Verily,Veri,Website,WideWeb,Yap,Yellow,Zebra,Zygote";
        $words = $this->modx->getConfig('captcha_words') ? $this->modx->getConfig('captcha_words') : $words;
        $words = str_replace(array(' ', ',,'), array('', ','), $words);
        $arr_words = explode(',', $words);

        /* pick one randomly for text verification */

        return (string)$arr_words[array_rand($arr_words)] . rand(10, 999);
    }

    /**
     * @return resource
     */
    private function drawText()
    {
        $dir = dir($this->dir_font);
        $fontstmp = array();
        while (false !== ($file = $dir->read())) {
            if (substr($file, -4) == '.ttf') {
                $fontstmp[] = $this->dir_font . $file;
            }
        }
        $dir->close();
        $text_font = (string)$fontstmp[array_rand($fontstmp)];
        $chars = str_split($this->word);
        $_chars = array();
        $maxWidth = $this->im_width / count($chars);
        $text_size = round(max($maxWidth, $this->im_height) * 3 /4.5 );
        $maxHeight = 0;
        $totalWidth = 0;
        foreach ($chars as $index => $value) {
            $text_angle = rand(-20, 20);
            $size = $text_size;
            $box = imagettfbbox($size, $text_angle, $text_font, $value);
            $charWidth = $box[2] - $box[0];
            $charHeight = abs($box[5] - $box[3]);
            $_chars[] = array(
                'angle' => $text_angle,
                'size' => $size,
                'width' =>  $charWidth,
                'height' => $charHeight
            );
            if ($charHeight > $maxHeight) $maxHeight = $charHeight;
            $totalWidth += $charWidth;
        }

        $minRatio = min(1, $this->im_width / $totalWidth, $this->im_height / $maxHeight);
        $size = round($text_size * $minRatio * 0.9);

        $totalWidth = 0;
        foreach ($_chars as $index => &$data) {
            $data['size'] = $size;
            $box = imagettfbbox($data['size'], $data['angle'], $text_font, $chars[$index]);
            $charWidth = $box[2] - $box[0];
            $charHeight = abs($box[5] - $box[3]);
            $data['width'] = $charWidth;
            $data['height'] = $charHeight;
            $totalWidth += $charWidth;
        }
        /* create canvas for text drawing */
        $im_text = imagecreate($this->im_width, $this->im_height);
        $bg_color = imagecolorallocate($im_text, 255, 255, 255);
        $text_x = ($this->im_width - $totalWidth) / 2;
        foreach ($_chars as $index => $data) {
            /* calculate center position of text */
            $text_y = ($this->im_height + $data['height'])/2;
            /* pick color for text */
            $text_color = imagecolorallocate($im_text, rand(10, 200), rand(10, 200), rand(10, 200));

            /* draw text into canvas */
            imagettftext(
                $im_text,
                $data['size'],
                $data['angle'],
                $text_x,
                $text_y,
                $text_color,
                $text_font,
                $chars[$index]);
            $text_x += $data['width'];
        }
        /* remove background color */
        imagecolortransparent($im_text, $bg_color);

        return $im_text;
    }


    /**
     * @return null|resource
     */
    private function drawImage()
    {

        /* pick one background image randomly from image directory */
        $img_file = $this->dir_noise . "noise" . rand(1, 4) . ".jpg";

        /* create "noise" background image from your image stock*/

        $noise_img = imagecreatefromjpeg($img_file);
        $noise_width = imagesx($noise_img);
        $noise_height = imagesy($noise_img);

        /* resize the background image to fit the size of image output */
        $this->im = imagecreatetruecolor($this->im_width, $this->im_height);
        imagecopyresampled(
            $this->im,
            $noise_img,
            0, 0, 0, 0,
            $this->im_width,
            $this->im_height,
            $noise_width,
            $noise_height
        );

        /* put text image into background image */
        imagecopymerge(
            $this->im,
            $this->drawText(),
            0, 0, 0, 0,
            $this->im_width,
            $this->im_height,
            70
        );

        return $this->im;
    }
}
