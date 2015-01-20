<?php
/**
 * Created by PhpStorm.
 * User: morozov
 * Date: 19.01.2015
 * Time: 13:18
 */
try {
    if (!file_exists('libs/tmsPhotoManager.php') || !is_file('libs/tmsPhotoManager.php')) {
        error('Can`t start gallery. There is no main library');
        exit;
    }
    require_once 'libs/tmsPhotoManager.php';

    if (file_exists('config/config.php') && is_file('config/config.php')) {
        require_once('config/config.php');
        if (!isset($tmsConf) || !isValidConfig($tmsConf)) {
            error('There is no config data in config/config.php ');
            exit;
        }

        if (isset($tmsConf['ignore_folder']) && is_array($tmsConf['ignore_folder'])) {
            foreach ($tmsConf['ignore_folder'] as $ignore_folder) {
                tmsPhotoManager::setIgnoreFolderName($ignore_folder);
            }
        }
    } else {
        error('There is no config file config/config.php');
    }


    $act = (isset($_POST['act']) ? $_POST['act'] : (isset($_GET['act']) ? $_GET['act'] : ''));
    $path = (isset($_POST['path']) ? $_POST['path'] : (isset($_GET['path']) ? $_GET['path'] : '/'));

//$manager = new tmsPhotoManager();
    if (!tmsPhotoManager::setRootPath($tmsConf['root_path'])) {
        error('Wrong root path');
    }
    if ($path != '/') {
        $path = tmsPhotoManager::decode($path);
    }
    if (!tmsPhotoManager::setPath($path)) {
        error('Wrong path ' . $path);
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


} catch (\Exception $e) {
    die ('Critical error. Please notify your system administrator.');
}

function error($text = '')
{
    if (!file_exists('tpls/errorWindow.php') || !is_file('tpls/errorWindow.php')) {
        die ('Critical Error: Can`t find Error template');
        exit;
    }
    require_once 'tpls/errorWindow.php';
    exit;
}

function isValidConfig($conf = array())
{
    if (!is_array($conf)) error('wrong type in config. Config must be  valid array');

    if (!isset($conf['title'])) error('gallery title is not set');
    if (!isset($conf['root_path']) || (trim($conf['root_path']) == '')) error('root path is not set');
    return true;
}