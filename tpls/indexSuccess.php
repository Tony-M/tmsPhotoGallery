<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/tmsPhotoManager.css">
    <script src="/js/jquery-2.1.3.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
</head>
<body>

<div id="wrapper">
    <nav class="navbar-default navbar-static-side tmsNavbar" role="navigation">
        <div class="tmsSidebar">
            <ul class="nav" id="side-menu">
                <?php if ($path == '/'): ?>
                    <li class="active"><a href="?path=/">/</a></li>
                <?php endif;?>
                <?php if ($path != '/'): ?>
                    <li class="active">
                        <a href="?path=<?php echo tmsPhotoManager::encode($path);?>"><?php echo $manager->getCurrentDirNAme() ?>
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true" style="float: right; font-size: 12px;"></span>
                        </a>
                    </li>
                    <li ><a href="?path=/">/</a></li>
                <?php endif;?>

                <?php if ($path != '/'): ?>
                    <li><a href="?path=<?php echo tmsPhotoManager::encode($manager->getLevelUpPath())  ?>">../</a></li>


                <?php endif ?>


                <?php $dirs = $manager->getDirList(); ?>
                <?php if (is_array($dirs)): ?>
                    <?php foreach ($dirs as $dir): ?>
                        <?php if (!isset($dir['current']))   : ?>
                            <li><a href="?path=<?php echo tmsPhotoManager::encode($dir['path_local']); ?>"><?php echo $dir['name']; ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>


            </ul>

        </div>
    </nav>

    <div class="container-fluid" style="left: 220px; position: relative">
        <div class="row">
            <div class="col-xs-12">11</div>
        </div>
    </div>

</div>
</body>
</html>