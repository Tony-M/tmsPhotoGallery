tmsPhotoManager = {
    getSelectedFile: function () {
        var file_name = $('#blueimp-gallery').find('h3.title').text();
        var path = $('#path').val();
        if (path != '/')path += '/';
        path += file_name;
        return path;
    }

    , remove: function () {
        var file = tmsPhotoManager.getSelectedFile();
    }
    , rotateCW: function () {
        var file = tmsPhotoManager.getSelectedFile();

    }

    , rotateCCW: function () {
        var file = tmsPhotoManager.getSelectedFile();

    }
}