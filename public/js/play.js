var images;

function playManga(){
    var count = 1;
    var playScreen = function(){
        $('#playScreen').children('img').attr('src', images[count]);
        var id = setTimeout(playScreen, 100);
        if(typeof images[count + 1] == 'undefined'){
            clearTimeout(id);
        }
        count++;
    }
    playScreen();
}

function setCSRF(){
    $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
        if (!options.crossDomain) {
            const token = $('meta[name="csrf-token"]').attr('content');
            if (token) {
                return jqXHR.setRequestHeader('X-CSRF-Token', token);
            }
        }
    });
}

$(document).ready( function(){

    setCSRF();
    
    $.ajax({
        url: 'http://localhost:8000/v1/image/publicMangaByFlameNumber',
        type: 'GET',
        data: {
            "url" : url
        },
        dataType: 'json',
        timeout: 5000,
    })
    .done(function(result,textStatus,jqXHR) {    
        images = result;
    })
    .fail(function(data1,textStatus,jqXHR) {
        var data2 = JSON.stringify(data1);
        console.log(data2);
    });

});