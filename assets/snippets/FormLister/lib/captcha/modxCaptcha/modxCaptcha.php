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
    protected $dir_font = "ttf/";
    /* path to background image directory*/
    protected $dir_noise = "noises/";
    public $word = "";
    protected $im = null;
    protected $im_width = 0;
    protected $im_height = 0;

    public function __construct(DocumentParser $modx, $width = 200, $height = 160)
    {
        $this->modx = $modx;
        $this->dir_font = MODX_MANAGER_PATH . 'includes/' . $this->dir_font;
        $this->dir_noise = MODX_MANAGER_PATH . 'includes/' . $this->dir_noise;
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
        $words = $this->modx->config['captcha_words'] ? $this->modx->config['captcha_words'] : $words;
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
        /* angle for text inclination */
        /* initial text size */
        /* create canvas for text drawing */
        $im_text = imagecreate($this->im_width, $this->im_height);
        $bg_color = imagecolorallocate($im_text, 255, 255, 255);

        $len = count($chars);
        foreach ($chars as $index => $value) {
            $text_angle = rand(-30, 30);
            /* initial text size */
            $text_size = 30;
            /* calculate text width and height */
            $box = imagettfbbox($text_size, $text_angle, $text_font, $this->word);
            $text_width = $box[2] - $box[0]; //text width
            /* adjust text size */
            $text_size = round((30 * $this->im_width) / $text_width);
            /* recalculate text width and height */
            $box = imagettfbbox($text_size, $text_angle, $text_font, $this->word);
            $text_width = ($box[2] - $box[0]) / $len; //text width
            $text_height = $box[5] - $box[3]; //text height

            /* calculate center position of text */
            $text_x = ($this->im_width - $len * $text_width) / 2 + $index * $text_width;
            $text_y = ($this->im_height - $text_height) / 2;


            /* pick color for text */
            $text_color = imagecolorallocate($im_text, rand(10, 200), rand(10, 200), rand(10, 200));

            /* draw text into canvas */
            imagettftext(
                $im_text,
                $text_size,
                $text_angle,
                $text_x,
                $text_y,
                $text_color,
                $text_font,
                $value);
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
