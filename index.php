<?php
/**
 * Created by PhpStorm.
 * User: morozov
 * Date: 19.01.2015
 * Time: 13:18
 */

require_once 'libs/tmsPhotoManager.php';

if (file_exists('config/config.php') && is_file('config/config.php')) {
    require_once('config/config.php');
    if (!isset($tmsConf) || !isValidConfig($tmsConf)) {
        error('no config data');
        exit;
    }

    if (isset($tmsConf['ignore_folder']) && is_array($tmsConf['ignore_folder'])) {
        foreach ($tmsConf['ignore_folder'] as $ignore_folder) {
            tmsPhotoManager::setIgnoreFolderName($ignore_folder);
        }
    }
} else {
    error('there is no config file config/config.php');
}


$act = (isset($_POST['act']) ? $_POST['act'] : (isset($_GET['act']) ? $_GET['act'] : ''));
$path = (isset($_POST['path']) ? $_POST['path'] : (isset($_GET['path']) ? $_GET['path'] : '/'));

//$manager = new tmsPhotoManager();
if (!tmsPhotoManager::setRootPath($tmsConf['root_path'])) {
    die('wrong root path');
}
if ($path != '/') {
    $path = tmsPhotoManager::decode($path);
}
if (!tmsPhotoManager::setPath($path)) {
    die('wrong path ' . $path);
}

switch ($act) {
    default:
        tmsPhotoManager::scanPath();

        require_once 'tpls/indexSuccess.php';
        break;
    case 'im':
        $file = (isset($_POST['file']) ? $_POST['file'] : (isset($_GET['file']) ? $_GET['file'] : '/'));
        tmsPhotoManager::getThumb($file);
        exit;
        break;
    case 'src':
        $file = (isset($_POST['file']) ? $_POST['file'] : (isset($_GET['file']) ? $_GET['file'] : '/'));
        tmsPhotoManager::getImage($file);
        exit;
        break;
}

function error($text = '')
{
    echo $text;
    exit;
}

function isValidConfig($conf = array())
{
    if (!is_array($conf)) error('wrong type in config. Config must be  valid array');

    if (!isset($conf['title'])) error('gallery title is not set');
    if (!isset($conf['root_path']) || (trim($conf['root_path']) == '')) error('root path is not set');
    return true;
}
