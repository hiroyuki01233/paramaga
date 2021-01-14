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

function getComments(id){
    $.ajax({
        url: 'http://localhost:8000/v1/comment',
        type: 'GET',
        data: {
            "url" : url,
            "page" : id
        },
        dataType: 'json',
        timeout: 5000,
    })
    .done(function(result,textStatus,jqXHR) {    
        var numberOfComments = result["total"];
        var comments = result['data'];

        $.each(comments,function(index,value){
            if(!typeof(result[index])) return false;
            $('#comments').append('<div class="user_comment"><p>'+value['pen_name']+'</p>'+'<p class="comment">'+value['comment']+'</p></div><br>');
        })

    })
    .fail(function(data1,textStatus,jqXHR) {
        var data2 = JSON.stringify(data1);
        console.log(data2);
    });
}

function addComment(){
    setCSRF();
    
    var comment = $('input[name="comment"]').val();

    $.ajax({
        url: 'http://localhost:8000/v1/comment',
        type: 'POST',
        data: {
            "url" : url,
            "comment" : comment
        },
        dataType: 'json',
        timeout: 5000,
    })
    .done(function(result,textStatus,jqXHR) {
        $('#comment_text').val("");
        $('#comments').prepend('<div class="user_comment"><p>'+myPenName+'</p>'+'<p class="comment">'+comment+'</p></div><br>');
    })
    .fail(function(data1,textStatus,jqXHR) {
        var data2 = JSON.stringify(data1);
        console.log(data2);
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

    getComments(1);
});