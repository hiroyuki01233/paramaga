function playManga(){
    console.log(url);
    console.log(penName);
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
            "penName" : penName,
            "url" : url
        },
        dataType: 'json',
        timeout: 5000,
    })
    .done(function(result,textStatus,jqXHR) {    
        console.log(result);
        // images = result;
        // const defaultImage = result;
        // $.each(result,function(index,value){
        //     if(!typeof(result[index])) return false;
        //     if(index == 1) return true;
        //     $('#change_canvas_buttons').append('<input type="button" value="'+(index)+'" onclick="changeCanvas(this.value,window.location.hash.slice(1))">');
        // })
        // var img = new Image();
        // img.src = images[1];
        // img.onload = function(){
        //     con.drawImage(img, 0, 0, 1280, 720);
        // }
    })
    .fail(function(data1,textStatus,jqXHR) {
        var data2 = JSON.stringify(data1);
        console.log(data2);
    });

});