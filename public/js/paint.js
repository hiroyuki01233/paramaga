
// 描画用フラグ  true: 描画中   false: 描画中でない
var flgDraw = false;

// 座標
var gX = 0;
var gY = 0;

// 描画色
var gColor = 'black';
var gWidth = 10;

// '2dコンテキスト'を取得
var canvas = document.getElementById('canvas');
var con = canvas.getContext('2d');

con.fillStyle = 'rgb(255,255,255)';
con.fillRect(0, 0, 1280, 720);

var nowImage = 0;
var images = {};

window.onload = function() {
    // イベント登録
    // マウス
    const canvas = document.getElementById('canvas');
   
    canvas.addEventListener('mousedown', startDraw, false);
    canvas.addEventListener('mousemove', Draw, false);
    canvas.addEventListener('mouseup', endDraw, false);

    window.location.hash = "1" ;

    var image = canvas.toDataURL('image/jpeg', 0.5);
    images["1"] = image;
    
} 
// セレクトボックス変更時に色を変更する
function changeColor(){

    gColor = document.getElementById('color').value;
    console.log(gColor);
    
}
// 描画開始
function startDraw(e){
    
    flgDraw = true;
    gX = e.offsetX;
    gY = e.offsetY;
    
}

//色変更　テスト
function buttonClick(color){
    gColor = color;
}

$(function(){
    $('[name="big_check_box"]').change(function(){
        var aryCmp = [];
        var checkedFlg = false;
        $('[name="big_check_box"]:checked').each(function(index, element){
            checkedFlg = true;
            gWidth = 100;
        });
        if(checkedFlg) return true;
        gWidth = $("#size").val();
    });
});


var size = document.getElementById('size');

size.addEventListener('change', function () {
  gWidth = $("#size").val();
}, false);

// 描画
function Draw(e){
    
    if (flgDraw == true){
        
        var x = e.offsetX;
        var y = e.offsetY;

        // 線のスタイルを設定
        con.lineWidth = gWidth;
        // 色設定
        con.strokeStyle = gColor;

        // 描画開始
        con.beginPath();
        con.moveTo(gX, gY);
        con.lineTo(x, y);
        con.lineJoin = "round";
        con.lineCap = "round";
        con.closePath();
        con.stroke();

        // 次の描画開始点
        gX = x;
        gY = y;
        
    }
}

// 描画終了
function endDraw(){
    
    flgDraw = false;
    
}

function saveCanvas()
{
	var canvas = document.getElementById("canvas");
	//アンカータグを作成
	var a = document.createElement('a');
	//canvasをJPEG変換し、そのBase64文字列をhrefへセット
	a.href = canvas.toDataURL('image/jpeg', 0.5);
	//ダウンロード時のファイル名を指定
	a.download = 'download.jpeg';
	//クリックイベントを発生させる
	a.click();
}

function saveNow(){
    var image = canvas.toDataURL('image/jpeg', 1);
    var hash = window.location.hash.slice(1);
    images[hash] = image;
}

function saveImages(){
    $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
        if (!options.crossDomain) {
            const token = $('meta[name="csrf-token"]').attr('content');
            if (token) {
                return jqXHR.setRequestHeader('X-CSRF-Token', token);
            }
        }
    });
    
    $('#test_text').text('通信中...');
    if($('#title').val() == "") {
        $('#test_text').text('タイトルを入力してください');
        return false;
    }

    i = 0;
    var postImages = {};
    $.each(images, function(index, value){
        postImages["image_" + index] = value;
        postImages["title"] = $('#title').val();
        if(i == 10) return false;
        i++;
    })

    // Ajax通信を開始
    $.ajax({
        url: HOST_NAME+'/v1/image',
        type: 'POST',
        data: postImages,
        dataType: 'json',
        timeout: 5000,
        })
        .done(function(data1,textStatus,jqXHR) {
            var mangaId = JSON.stringify(data1);
            $('#test_text').text("初回作成成功");
            create10over(mangaId);
        })
        .fail(function(data1,textStatus,jqXHR) {
            console.log(JSON.stringify(data1));
            $('#test_text').text("保存に失敗しました。やり直してください");
        });

    function create10over(mangaId){
        postImages = {};
        for(i = 1; i <= 20; i++){
            number = i * 10;
            $.each(images, function(index, value){
                if(index >= number && index <= (number + 9)){
                    postImages["image_" + index] = value;
                }
            })
            if(postImages.length == 0) break;
            postImages["title"] = $('#title').val();
            $.ajax({
                url: HOST_NAME+'/v1/image/'+mangaId,
                type: 'PATCH',
                data: postImages,
                dataType: 'json',
                timeout: 5000,
            })
            .done(function(data1,textStatus,jqXHR) {
                var data2 = JSON.stringify(data1);
                $('#test_text').text("保存しました");
            })
            .fail(function(data1,textStatus,jqXHR) {
                $('#test_text').text("保存に失敗しました。やり直してください");
            });
            postImages = {};
        }
    }
};

function copyPage(){
    if((Number(window.location.hash.slice(1)) <= 1)) return true;
    image = images[(Number(window.location.hash.slice(1)))-1];
    var img = new Image();
    img.src = image;
    img.onload = function(){
        con.drawImage(img, 0, 0, 1280, 720);
    }
}

function deletePage(){
    var now = (Number(window.location.hash.slice(1)));
    if(now <= 1) return true;
    var changedNumber = 1;
    var nowIndex = 1;
    $.each(images, function(index, value){
        if(index == now){
            changedNumber = nowIndex;
            return false;
        }
        nowIndex = index;
    });
    delete images[now];
    image = images[changedNumber];
    var img = new Image();
    img.src = image;
    img.onload = function(){
        con.drawImage(img, 0, 0, 1280, 720);
    }
    $("#"+now).remove();
    window.location.hash = changedNumber;
    $('#'+changedNumber).css('background-color', 'yellow');
}

function canvasPlus(){
    $('#1').css('background-color', 'transparent');
    var now = (Number(window.location.hash.slice(1)));
    // var newImageNumber = now+1;
    // if(typeof images[newImageNumber] !== "undefined") return true;
    startNumber = now;
    while(true){
        if(typeof images[startNumber] == "undefined"){
            newImageNumber = startNumber;
            break
        }
        startNumber++;
    }
    var image = canvas.toDataURL('image/jpeg', 1);
    images[now] = image;
    con.fillStyle = 'rgb(255,255,255)';
    con.fillRect(0, 0, 1280, 720);
    images[newImageNumber] = canvas.toDataURL('image/jpeg', 1);
    window.location.hash = newImageNumber;
    if(newImageNumber == 200) {
        $("#plus_button").remove();
    }
    $("#change_canvas_buttons").empty();
    $.each(images, function(index, value){
        if(index == 1) return true;
        if(index == newImageNumber){
            $('#change_canvas_buttons').append('<input class="manga_btn" style="background-color: yellow" id="'+index+'" type="button" value="'+index+'" onclick="changeCanvas(this.value,window.location.hash.slice(1))">');
        }else{
            $('#change_canvas_buttons').append('<input class="manga_btn" id="'+index+'" type="button" value="'+index+'" onclick="changeCanvas(this.value,window.location.hash.slice(1))">');
        }
    })
}

function changeCanvas(id,hash){
    $('#'+hash).css('background-color', 'transparent');
    var image = canvas.toDataURL('image/jpeg', 1);
    images[hash] = image;
    if(images[id]){
        var img = new Image();
        img.src = images[id];
        img.onload = function(){
            con.drawImage(img, 0, 0, 1280, 720);
        }
    }else{
        con.fillStyle = 'rgb(255,255,255)';
        con.fillRect(0, 0, 1280, 720);
    }
    $('#'+id).css('background-color', 'yellow');
    window.location.hash = id;
}

function play(){
    var image = canvas.toDataURL('image/jpeg', 1);
    images[Number(window.location.hash.slice(1))] = image;
    var count = 1;
    var playScreen = function(){
        var img = new Image();
        img.src = images[count];
        img.onload = function(){
            con.drawImage(img, 0, 0, 1280, 720);
        }
        $('#'+(count - 1)).css('background-color', 'transparent');
        $('#'+count).css('background-color', 'yellow');
        // $("#flame").html(count);
        var id = setTimeout(playScreen, 100);
        if(typeof images[count + 1] == 'undefined'){
            clearTimeout(id);
        }
        count++;
    }
    playScreen();
}