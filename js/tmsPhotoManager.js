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

        if (!confirm('Do you really want to remove image?'))return false;
        data = {};
        data.file = file;

        $.ajax({
            type: "POST",
            url: '/?act=rmimg',
            data: data,
            dataType: 'json',
            statusCode: {
                404: function () {
                    alert("Error [page 404]");
                }, 500: function () {
                    alert("Error [page 500]");
                }
            }, success: function (response) {
                if (response.success !== undefined && response.success) {
                    var file_name = $('#blueimp-gallery').find('h3.title').text();

                    var thumbs = $('img.tmsThumb');
                    var n = thumbs.length;
                    for (var i = 0; i < n; i++) {
                        if (thumbs[i].title == file_name) {
                            $('img.tmsThumb:eq(' + i + ')').remove();
                        }

                    }
                    if (!n || n == 1) {
                        $('#blueimp-gallery > a.close:first').click();
                    } else {
                        if ($('a.next:first').css('display') != 'none')
                            $('#blueimp-gallery > a.next:first').click();
                        else {
                            if ($('a.prev:first').css('display') != 'none') {
                                $('#blueimp-gallery > a.prev:first').click();
                            } else {
                                $('#blueimp-gallery > a.close:first').click();

                            }
                        }

                    }

                }
                else {

                }
            }, complite: function () {
            }
        });


    }
    , rotateCW: function () {
        var file = tmsPhotoManager.getSelectedFile();
        tmsPhotoManager.rotate(file, 'cw');
    }

    , rotateCCW: function () {
        var file = tmsPhotoManager.getSelectedFile();
        tmsPhotoManager.rotate(file, 'ccw');

    }

    , rotate: function (file, direction) {
        data = {};
        data.direction = direction;
        data.file = file;

        $.ajax({
            type: "POST",
            url: '/?act=rotate',
            data: data,
            dataType: 'json',
            statusCode: {
                404: function () {
                    alert("Error [page 404]");
                }, 500: function () {
                    alert("Error [page 500]");
                }
            }, success: function (response) {
                if (response.success !== undefined && response.success) {
                    var slides = $('img.slide-content');
                    var n = slides.length;
                    var file_name = $('#blueimp-gallery').find('h3.title').text();
                    for (var i = 0; i < n; i++) {
                        if (slides[i].title == file_name) {
                            var path = '?act=src&file=' + tmsPhotoManager.getSelectedFile() + '&_t=' + Math.random();
                            $('img.slide-content:eq(' + i + ')').attr('src', path);
                        }

                    }

                    var thumbs = $('img.tmsThumb');
                    var n = thumbs.length;
                    for (var i = 0; i < n; i++) {
                        if (thumbs[i].title == file_name) {
                            var path = '?act=im&file=' + tmsPhotoManager.getSelectedFile() + '&_t=' + Math.random();
                            $('img.tmsThumb:eq(' + i + ')').attr('src', path);
                        }

                    }
                }
                else {

                }
            }, complite: function () {
            }
        });

    }

    , createThumbnail: function (params) {
        if (params === undefined || params === null)return false;
        if (params.id == undefined || params.id === null)return false;
        if (params.path == undefined || params.path === null)return false;

        data = {};
        data.file = params.path;

        $.ajax({
            type: "POST",
            url: '/?act=mkthumb',
            data: data,
            dataType: 'json',
            statusCode: {
                404: function () {
                    alert("Error [page 404]");
                }, 500: function () {
                    alert("Error [page 500]");
                }
            }, success: function (response) {
                if (response.success !== undefined && response.success) {


                    var thumb = $('#' + params.id);
                    var path = '?act=im&file=' + params.path + '&_t=' + Math.random();
                    thumb.attr('src', path);
                    i_not_n++;
                    tmsPhotoManager.createThumbnail(no_thumbs[i_not_n]);
                }
                else {

                }
            }, complite: function () {

            }
        });
    }
}