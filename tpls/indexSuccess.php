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
</head>
<body>
<?php $files = tmsPhotoManager::getFileList(); ?>
<div id="wrapper">
    <nav class="navbar-default navbar-static-side tmsNavbar" role="navigation">
        <div class="tmsSidebar">
            <ul class="nav" id="side-menu">
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
                                <a href="?path=<?php echo tmsPhotoManager::encode($dir['path_local']); ?>"><?php echo $dir['name']; ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>


            </ul>

        </div>
    </nav>

    <div class="container-fluid" style="left: 220px; position: relative">
        <div class="row">
            <div class="col-xs-12">

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

                        <?php if (is_array($files)): ?>
                            <div id="links">

                                <?php foreach ($files as $file): ?>
                                    <a href="?act=src&file=<?php echo tmsPhotoManager::encode($file['path_local']); ?>"
                                       title="<?php echo $file['name']; ?>" data-gallery>
                                        <img
                                            src="?act=im&file=<?php echo tmsPhotoManager::encode($file['path_local']); ?>"/>
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