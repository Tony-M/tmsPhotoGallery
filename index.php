<?php
/**
 * Created by PhpStorm.
 * User: morozov
 * Date: 19.01.2015
 * Time: 13:18
 */

require_once 'libs/tmsPhotoManager.php';


$act = (isset($_POST['act'])?$_POST['act']:(isset($_GET['act'])?$_GET['act']:''));
$path = (isset($_POST['path'])?$_POST['path']:(isset($_GET['path'])?$_GET['path']:'/'));

$manager = new tmsPhotoManager();
if (!$manager->setRootPath('/home/morozov/www/photo/dir')) {
    die('wrong root path');
}
if($path!='/'){
    $path=tmsPhotoManager::decode($path);
}
if (!$manager->setPath($path)) {
    die('wrong path '. $path);
}

switch ($act){
    default:
        $manager->scanPath();

        require_once 'tpls/indexSuccess.php';
        break;
    case 'im':
        tmsPhotoManager::getThumb($file);
        break;
}

