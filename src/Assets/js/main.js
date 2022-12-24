/** Feather **/
feather.replace({
    'stroke-width': 2,
    'height': 17
});

$("#menu-toggle").click(function (e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});

function showSubMenu(t)
{
    var toggle = $(t).data('toggle');
    if(toggle === 0) {
        $(t).next("ul").show();
        $(t).data("toggle", 1);
        $(t).attr("data-toggle", 1);
    } else {
        $(t).next("ul").hide();
        $(t).data("toggle", 0);
    }
}

$(function() {
    function summerNoteUploadImage(image) {
        var data = new FormData();
        data.append("image", image);
        $.ajax({
            url: adminURL+"file-management/upload-image",
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            type: "POST",
            success: function(url) {
                $('.editor').summernote("insertImage", url);
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

    function summerNoteDeleteImage(src) {
        $.ajax({
            data: {src : src},
            type: "POST",
            url: adminURL+"file-management/delete-image",
            cache: false,
            success: function(response) {
                console.log(response);
            }
        });
    }
    $('.editor').summernote({
        height: 150,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['insert',['picture','link','video']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ],
        callbacks: {
            onImageUpload: function(image) {
                summerNoteUploadImage(image[0]);
            },
            summerNoteDeleteImage : function(target) {
                summerNoteDeleteImage(target[0].src);
            }
        }
    });

    $('.select2').select2();
})