var images;
var commentPage = 1;
var mangaPage = 1;
var imageAll;
function checkLogin() {
    if(!myPenName){
        location.href=HOST_NAME+"/login";
    }
    return true;
}

function getManga(page){
    setCSRF();
    $.ajax({
        url: HOST_NAME+'/v1/image/previewManga',
        type: 'GET',
        data: {
            "url" : url,
            "page" : page
        },
        dataType: 'json',
        timeout: 5000,
    })
    .done(function(result,textStatus,jqXHR) {  
        imageAll = result["imageAll"];
        max = Math.ceil(imageAll / 50)+1;
        if(page >= 2){
            Object.assign(images, result);
        }else{
            images = result;
        }
        mangaPage = mangaPage + 1;
    })
    .fail(function(data1,textStatus,jqXHR) {
        var data2 = JSON.stringify(data1);
        console.log(data2);
    });
}

function playManga(){
    var count = 1;
    var playScreen = function(){
        $('#playScreen').children('img').attr('src', images[count]);
        $("#flame").html(count  + "/" + imageAll);
        var id = setTimeout(playScreen, 100);
        if(typeof images[count + 1] == 'undefined'){
            clearTimeout(id);
        }
        count++;
        if((count == 10 || count == 60 || count == 110 || count == 160) && mangaPage < max) {
            getManga(mangaPage);
        }
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
    getManga(mangaPage);
});