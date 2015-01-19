<?php

/**
 * Created by PhpStorm.
 * User: morozov
 * Date: 19.01.2015
 * Time: 13:31
 */
class tmsPhotoManager
{
    public static $prod = false;

    /**
     * path to root folder of gallery
     * @var string
     */
    protected $ROOT_PATH = '/';

    /**
     * current path
     * @var string
     */
    protected $PATH = '';

    protected $DIRS = array();
    protected $FILES = array();

    protected $CURRENT_DIR = '';

    protected $thumbs = '.thmb';

    protected static $exts = array('.jpg', '.jpeg', '.gif', '.png');

    /**
     * set path to root folder
     * @param $path string
     * @return bool
     */
    public function setRootPath($path = '')
    {
        $path = trim($path);
        $path = preg_replace('/([\/]{2,})/', '/', $path);
        if (!preg_match('/([\/]{1})$/', $path)) $path .= '/';

        if ($path != '' && file_exists($path) && is_dir($path)) {
            $this->ROOT_PATH = $path;
            return true;
        } else {
            return false;
        }
    }

    /**
     * set local path part
     * @param string $path
     * @return bool
     */
    public function setPath($path = '')
    {
        $path = trim($path);
        $path = preg_replace('/^([\/]{1,})/', '', $path);
        $path = preg_replace('/([\.]{2,})/', '', $path);
        $tmp_path = $this->ROOT_PATH . $path;
        if ($path == '') {
            $this->PATH = '';
            return true;
        }
        if (file_exists($tmp_path) && is_dir($tmp_path)) {
            $this->PATH = $path;
            return true;
        } else {
            return false;
        }

    }

    /**
     * return path
     * @return string
     */
    public function getPath()
    {
        return $this->ROOT_PATH . $this->PATH;
    }

    public function scanPath()
    {
        $this->CURRENT_DIR_NAME == '/';
        if ($this->PATH != '/') {
            $p = explode('/', $this->PATH);
            if (is_array($p)) {
                $this->CURRENT_DIR = $p[count($p) - 1];
            }
        }


        $path = $this->getPath();
        $cmd = 'ls -lahgG --time-style=full-iso ' . $path;
//        echo $cmd;
        exec($cmd, $out);
        if (is_array($out)) {
            foreach ($out as $row) {
                if (preg_match('/^([a-z\-]{1})([a-z\-]{9})( [0-9\-]{1,})([ ]{1,})([0-9a-zA-Z\-]{1,})( )([0-9]{4}\-[0-9]{2}\-[0-9]{2} [0-9]{2}\:[0-9]{2}\:[0-9]{2})(.{17})(.{1,})$/', $row, $matches)) {
                    $tmp = array();
                    $tmp['type'] = $matches[1];
                    $tmp['size'] = $matches[5];
                    $tmp['mode'] = $matches[2];
                    $tmp['date'] = $matches[7];
                    $tmp['name'] = $matches[9];
                    $tmp['path'] = $path . $tmp['name'];
                    $tmp['path_local'] = '/' . ($this->PATH != '' ? $this->PATH . '/' : '') . $tmp['name'];


                    if (in_array($tmp['name'], array('.', '..'))) continue;

                    if ($tmp['type'] == 'd') {
                        $this->DIRS[] = $tmp;
                    } else {
                        $this->FILES[] = $tmp;
                    }
                }
            }

        }

//        echo '<pre>';
//        print_r($out);
//        print_r($this->DIRS);
//        echo '</pre>';
    }


    public function getDirList()
    {
        return $this->DIRS;
    }

    public function getCurrentDirNAme()
    {
        return $this->CURRENT_DIR;
    }

    public function getLevelUpPath()
    {
        $path = $this->PATH;
        $path = explode('/', $path);
        if (is_array($path) && count($path > 1)) {
            unset($path[count($path) - 1]);
            return '/' . implode('/', $path);
        }
        return '/';
    }

    public static function encode($text = '')
    {
        if (self::$prod) return base64_encode($text);
        return $text;
    }

    public static function decode($text = '')
    {
        if (self::$prod) return base64_decode($text);
        return $text;
    }

    public static function getThumb($file = '')
    {
        $file = trim($file);
        if (file_exists($file) && is_file($file)) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);

            if (!in_array($ext, self::$exts)) {
                return false;
            }
        } else {
            return false;
        }

        $maxWidth = 100;
        $maxHeight = 100;
        $image = file_get_contents($file);
        if ($image) {
            $im = new Imagick();
            $im->readImageBlob($image);
            $im->setImageFormat("png24");
            $geo = $im->getImageGeometry();
            //print_r($geo);
            $width = $geo['width'];
            $height = $geo['height'];
            if ($width > $height) {
                $scale = ($width > $maxWidth) ? $maxWidth / $width : 1;
            } else {
                $scale = ($height > $maxHeight) ? $maxHeight / $height : 1;
            }
            $newWidth = $scale * $width;
            $newHeight = $scale * $height;
            $im->setImageCompressionQuality(85);
            $im->resizeImage($newWidth, $newHeight, Imagick::FILTER_LANCZOS, 1.1);
            header("Content-type: image/png");
            echo $im;
            $im->clear();
            $im->destroy();
        }
    }

}