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
    protected static $ROOT_PATH = '/';

    /**
     * current path
     * @var string
     */
    protected static $PATH = '';

    protected static $DIRS = array();
    protected static $FILES = array();

    protected static $CURRENT_DIR = '';

    protected static $thumbs = '.thmb';

    protected static $exts = array('jpg', 'jpeg', 'gif', 'png');

    protected static $ignore_folders = array();

    /**
     * set path to root folder
     * @param $path string
     * @return bool
     */
    public static function setRootPath($path = '')
    {
        $path = trim($path);
        $path = preg_replace('/([\/]{2,})/', '/', $path);
        if (!preg_match('/([\/]{1})$/', $path)) $path .= '/';

        if ($path != '' && file_exists($path) && is_dir($path)) {
            self::$ROOT_PATH = $path;
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
    public static function setPath($path = '')
    {
        $path = trim($path);
        $path = preg_replace('/^([\/]{1,})/', '', $path);
        $path = preg_replace('/([\.]{2,})/', '', $path);
        $tmp_path = self::$ROOT_PATH . $path;
        if ($path == '') {
            self::$PATH = '';
            return true;
        }
        if (file_exists($tmp_path) && is_dir($tmp_path)) {
            self::$PATH = $path;
            return true;
        } else {
            return false;
        }

    }

    /**
     * return absolute path
     * @return string
     */
    public static function getPath()
    {
        return self::$ROOT_PATH . self::$PATH;
    }
    public static function getLocalPath()
{
    return self::$PATH;
}

    public static function scanPath()
    {
        self::$CURRENT_DIR == '/';
        if (self::$PATH != '/') {
            $p = explode('/', self::$PATH);
            if (is_array($p)) {
                self::$CURRENT_DIR = $p[count($p) - 1];
            }
        }

//        echo '<pre>';
        $path = self::getPath();
//        print_r($path);
        $cmd = 'ls -lahgG --time-style=full-iso ' . $path;
//        echo $cmd;
        exec($cmd, $out);
//        print_r($out);
        if (is_array($out)) {
            foreach ($out as $row) {
               $row=trim($row);
//                echo '|'.$row.'|'.'<br/>';
                if (preg_match('/^([a-z\-]{1})([a-z\-]{9})([ ]{1,})([0-9\-]{1,})([ ]{1,})([0-9a-zA-Z\-]{1,})([ ]{1,})([0-9]{4}\-[0-9]{2}\-[0-9]{2} [0-9]{2}\:[0-9]{2}\:[0-9]{2})(.{16})([ ]{1,})(.{1,})$/', $row, $matches)) {
                    $tmp = array();
                    $tmp['type'] = $matches[1];
                    $tmp['size'] = $matches[6];
                    $tmp['mode'] = $matches[2];
                    $tmp['date'] = $matches[8];
                    $tmp['name'] = $matches[11];
                    $tmp['path'] = $path . $tmp['name'];
                    $tmp['path_local'] = '/' . (self::$PATH != '' ? self::$PATH . '/' : '') . $tmp['name'];
//echo '<br>';
//print_r($tmp);
                    $ignored_folders = self::$ignore_folders;
                    $ignored_folders[] = '.';
                    $ignored_folders[] = '..';
                    $ignored_folders[] = '.thumb';

                    if (in_array($tmp['name'], $ignored_folders)) continue;

                    if ($tmp['type'] == 'd') {
                        self::$DIRS[] = $tmp;
                    } else {
                        self::$FILES[] = $tmp;
                    }
                }
            }

        }


//        print_r($out);
//        print_r(self::$FILES);
//        echo '</pre>';
    }


    public static function getDirList()
    {
        return self::$DIRS;
    }


    public static function getFileList()
    {
        return self::$FILES;
    }

    public static function getCurrentDirNAme()
    {
        return self::$CURRENT_DIR;
    }

    public static function setIgnoreFolderName($folder_name=''){
        $folder_name = trim($folder_name);
        if(!preg_match('/[<>\/]{1,}/',$folder_name) && !in_array($folder_name, self::$ignore_folders)){
            array_push(self::$ignore_folders, $folder_name);
        }
    }

    public static function getLevelUpPath()
    {
        $path = self::$PATH;
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
        $file = preg_replace('/([\.]{2,})/', '', $file);
        $file = preg_replace('/([\/]{2,})/', '', $file);
        $file = preg_replace('/^([\/]{1,})/', '', $file);

        $file = self::$ROOT_PATH . $file;
        if (file_exists($file) && is_file($file)) {
            $ext = pathinfo(strtolower($file), PATHINFO_EXTENSION);

            if (!in_array($ext, self::$exts)) {
                echo 1;
                return false;
            }
        } else {
            echo 2;
            return false;
        }

        $filemtime = '';//filemtime($file);
        $hash = md5($file . $filemtime);

        $dir = dirname($file) . '/' . '.thumb';
        if (!file_exists($dir) || !is_dir($dir)) {
            if (!mkdir($dir, 0777)) {
            }
            chmod($dir, 0777);
        }
        $thumb_file = $dir . '/' . $hash . '.png';
//        echo $thumb_file;exit;
        if (!file_exists($thumb_file) || !is_file($thumb_file)) {


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
                file_put_contents($thumb_file, $im);
                echo $im;
                $im->clear();
                $im->destroy();
            }
        } else {
            $im = file_get_contents($thumb_file);
            header("Content-type: image/png");
            echo $im;
        }
    }

    public static function getImage($file = '')
    {
        $file = trim($file);
        $file = preg_replace('/([\.]{2,})/', '', $file);
        $file = preg_replace('/([\/]{2,})/', '', $file);
        $file = preg_replace('/^([\/]{1,})/', '', $file);

        $file = self::$ROOT_PATH . $file;
        if (file_exists($file) && is_file($file)) {
            $ext = pathinfo(strtolower($file), PATHINFO_EXTENSION);

            if (!in_array($ext, self::$exts)) {
                echo 1;
                return false;
            }
        } else {
            echo 2;
            return false;
        }


        $im = file_get_contents($file);
        header("Content-type: image/".$ext);
        echo $im;
    }

}