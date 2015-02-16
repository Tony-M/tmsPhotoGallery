<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <title><?php echo $tmsConf['title']; ?></title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/blueimp-gallery.min.css">
    <link rel="stylesheet" href="/css/bootstrap-image-gallery.min.css">
    <link rel="stylesheet" href="/css/tmsPhotoManager.css">
    <script src="/js/jquery-2.1.3.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/jquery.blueimp-gallery.min.js"></script>
    <script src="/js/bootstrap-image-gallery.min.js"></script>
    <script src="/js/tmsPhotoManager.js"></script>
</head>
<body>
<input type="hidden" value="/<?php echo tmsPhotoManager::getLocalPath(); ?>" id="path">
<?php $files = tmsPhotoManager::getFileList(); ?>
<script>
    var no_thumbs = [];
    var not_n = 0;
    var i_not_n = 0;

    <?php if(is_array($files) && count($files)):?><?php foreach($files as $file):?><?php if(!$file['thumb_exist']):?>no_thumbs.push({
        id: 'tmsThumb_<?php echo md5($file['path_local']);?>',
        path: "<?php echo tmsPhotoManager::encode($file['path_local']);?>"
    });
    <?php endif;?><?php endforeach;?><?php endif;?>

    $(document).ready(function () {
        not_n = no_thumbs.length;
        if (not_n) {
            tmsPhotoManager.createThumbnail(no_thumbs[i_not_n]);
        }
    });

</script>

<div id="wrapper">
    <nav class="navbar-default navbar-static-side tmsNavbar" role="navigation">
        <div class="tmsSidebar">
            <ul class="nav" id="side-menu">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php if ($path == '/'): ?>
                            <li class="active"><a href="?path=/">/</a></li>
                        <?php endif; ?>
                        <?php if ($path != '/'): ?>
                            <li class="active">
                                <a href="?path=<?php echo tmsPhotoManager::encode($path); ?>"><?php echo tmsPhotoManager::getCurrentDirNAme() ?>
                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"
                                          style="float: right; font-size: 12px;"></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($path != '/'): ?>
                    <li><a href="?path=/">/</a></li>
                <?php endif; ?>
                <?php if ($path != '/'): ?>
                    <li><a href="?path=<?php echo tmsPhotoManager::encode(tmsPhotoManager::getLevelUpPath()) ?>">../</a>
                    </li>


                <?php endif ?>


                <?php $dirs = tmsPhotoManager::getDirList(); ?>
                <?php if (is_array($dirs)): ?>
                    <?php foreach ($dirs as $dir): ?>
                        <?php if (!isset($dir['current']))   : ?>
                            <li>
                                <a href="?path=<?php echo tmsPhotoManager::encode($dir['path_local']); ?>"><span
                                        class="glyphicon glyphicon-folder-open" aria-hidden="true"
                                        style="margin-right: 8px"></span> <?php echo $dir['name']; ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>


            </ul>

        </div>
    </nav>

    <div class="container-fluid" style="width:100%; padding-left: 230px;">
        <div class="row" style="margin-left: -15px;">
            <div class="col-xs-12" style="padding-left: 0px; padding-right: 0px;">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Альбом:
                        <?php if ($path == '/'): ?>
                            <a href="?path=/">/</a>
                        <?php endif; ?>
                        <?php if ($path != '/'): ?>
                            <a href="?path=<?php echo tmsPhotoManager::encode($path); ?>"><?php echo tmsPhotoManager::getCurrentDirNAme() ?></a>
                        <?php endif; ?>
                        <small class="text-muted" style="float: right;">
                            Папок: <?php echo count($dirs); ?> шт.
                            Файлов:  <?php echo count($files); ?> шт.
                        </small>
                    </div>
                    <div class="panel-body">
                        <!--<pre>--><?php //print_r($files);?><!--</pre>-->
                        <?php if (is_array($files)): ?>
                            <div id="links">

                                <?php foreach ($files as $file): ?>
                                    <a class="tmsThumb"
                                       href="/storage<?php echo $file['path_local'] ?>"
                                       title="<?php echo $file['name']; ?>" data-gallery>
                                        <img class="tmsThumb" id="tmsThumb_<?php echo md5($file['path_local']); ?>"
                                             src="<?php if (tmsPhotoManager::isThumbExists($file['path_local'])): ?>?act=im&file=<?php echo tmsPhotoManager::encode($file['path_local']); ?><?php else: ?>/images/image.png<?php endif; ?>"
                                             title="<?php echo $file['name']; ?>"/>

                                        <span><?php echo tmsPhotoManager::encode($file['name']); ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>


            </div>
        </div>
    </div>

</div>
<!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery" data-use-bootstrap-modal="false">
    <!-- The container for the modal slides -->
    <div class="slides"></div>
    <!-- Controls for the borderless lightbox -->
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>

    <div class="tmsActions" style="">
        <a class="btn btn-default" onclick="tmsPhotoManager.rotateCCW();return false;"><span
                class="glyphicon glyphicon-share-alt" aria-hidden="true"
                style="-moz-transform: scale(-1, 1);-webkit-transform: scale(-1, 1);-o-transform: scale(-1, 1);-ms-transform: scale(-1, 1);transform: scale(-1, 1);"></span></a>
        <a class="btn btn-default" onclick="tmsPhotoManager.rotateCW();return false;"><span
                class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></a>
        <a class="btn btn-default" onclick="tmsPhotoManager.remove();return false;" style="margin-top: 20px;"><span
                class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
    </div>
    <ol class="indicator"></ol>
    <!-- The modal dialog, which will be used to wrap the lightbox content -->
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body next"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left prev">
                        <i class="glyphicon glyphicon-chevron-left"></i>
                        Previous
                    </button>
                    <button type="button" class="btn btn-primary next">
                        Next
                        <i class="glyphicon glyphicon-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
