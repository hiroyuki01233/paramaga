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

var max = 1;
function getManga(page){
    setCSRF();
    $.ajax({
        url: HOST_NAME+'/v1/image/publicMangaByFlameNumber',
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
        mangaPage++;
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
        $("#flame").html(count + "/" + imageAll);
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

function getComments(id){
    $.ajax({
        url: HOST_NAME+'/v1/comment',
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
        commentPage = commentPage + 1;
        $.each(comments,function(index,value){
            if(!typeof(result[index])) return false;
            if(myPenName == value['pen_name']){
                $('#comments').append('<div class="user_comment" id="'+value['id']+'"><p>'+value['pen_name']+'</p>'+'<p class="comment">'+value['comment']+'</p><input type="button" class="delete_btn" value="削除" onclick="deleteComment('+value['id']+')"></div>');
            }else{
                $('#comments').append('<div class="user_comment" id="'+value['id']+'"><p>'+value['pen_name']+'</p>'+'<p class="comment">'+value['comment']+'</p></div>');
            }
        })
    })
    .fail(function(data1,textStatus,jqXHR) {
        var data2 = JSON.stringify(data1);
        console.log(data2);
    });
}

$(window).on('scroll', function () {
    var doch = $(document).innerHeight(); //ページ全体の高さ
    var winh = $(window).innerHeight(); //ウィンドウの高さ
    var bottom = doch - winh; //ページ全体の高さ - ウィンドウの高さ = ページの最下部位置
    if (bottom <= $(window).scrollTop()) {
        getComments(commentPage);
    }
});

function deleteComment(id){
    $.ajax({
        url: HOST_NAME+'/v1/comment/'+id,
        type: 'DELETE',
        dataType: 'json',
        timeout: 5000,
    })
    .done(function(result,textStatus,jqXHR) { 
        $("#"+id).remove();
    })
    .fail(function(data1,textStatus,jqXHR) {
        var data2 = JSON.stringify(data1);
        console.log(data2);
    });
}

function addComment(){
    checkLogin()
    setCSRF();
    $("#add_comment_btn").val("通信中...");
    
    var comment = $('input[name="comment"]').val();

    $.ajax({
        url: HOST_NAME+'/v1/comment',
        type: 'POST',
        data: {
            "url" : url,
            "comment" : comment
        },
        dataType: 'json',
        timeout: 5000,
    })
    .done(function(result,textStatus,jqXHR) {
        $("#add_comment_btn").val("投稿");
        $('#comment_text').val("");
        $('#comments').prepend('<div class="user_comment"><p>'+myPenName+'</p>'+'<p class="comment">'+comment+'</p></div><br>');
    })
    .fail(function(data1,textStatus,jqXHR) {
        var data2 = JSON.stringify(data1);
        console.log(data2);
    });
}

function addLike(){
    checkLogin()
    setCSRF();
    
    $.ajax({
        url: HOST_NAME+'/v1/like',
        type: 'POST',
        data: {
            "url" : url,
        },
        dataType: 'json',
        timeout: 5000,
    })
    .done(function(result,textStatus,jqXHR) {
        $('#like_btn').val('いいね取り消し');
        liked = 1;
    })
    .fail(function(data1,textStatus,jqXHR) {
        var data2 = JSON.stringify(data1);
        console.log(data2);
    });
}


function deleteLike(){
    checkLogin()
    setCSRF();
    $.ajax({
        url: HOST_NAME+'/v1/like/'+url,
        type: 'DELETE',
        dataType: 'json',
        timeout: 5000,
    })
    .done(function(result,textStatus,jqXHR) {
        $('#like_btn').val('いいね');
        liked = 0;
    })
    .fail(function(data1,textStatus,jqXHR) {
        var data2 = JSON.stringify(data1);
        console.log(data2);
    });
}

function changeLike(){
    $('#like_btn').val('通信中...');
    if(liked){
        deleteLike()
    }else{
        addLike()
    }
}

$(document).ready( function(){
    getManga(mangaPage);
    getComments(commentPage);
});