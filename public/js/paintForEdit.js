
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
var changedImages = [];
var postImages = {};
var mangaPage = 1;

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

function createBtn(){
    $.each(images,function(index,value){
        if(!typeof(images[index])) return false;
        if(index == 1) return true;
        $('#change_canvas_buttons').append('<input type="button" value="'+(index)+'" onclick="changeCanvas(this.value,window.location.hash.slice(1))">');
        if(index == 200) $("#plus_button").remove();
    })
    var img = new Image();
    img.src = images[1];
    img.onload = function(){
        con.drawImage(img, 0, 0, 1280, 720);
    }
}

function getManga(page){
    setCSRF();
    $.ajax({
        url: HOST_NAME+'/v1/image/'+id+'/edit',
        type: 'GET',
        data: {
            "page" : page
        },
        dataType: 'json',
        timeout: 5000,
    })
    .done(function(result,textStatus,jqXHR) {  
        if(page >= 2){
            Object.assign(images, result);
        }else{
            images = result;
        }
        mangaPage = mangaPage + 1;
        if(mangaPage < 5){
            getManga(mangaPage);
        }else{
            createBtn();
        }
    })
    .fail(function(data1,textStatus,jqXHR) {
        var data2 = JSON.stringify(data1);
        console.log(data2);
    });
}

$(document).ready( function(){
    
    // イベント登録
    // マウス
    const canvas = document.getElementById('canvas');
   
    canvas.addEventListener('mousedown', startDraw, false);
    canvas.addEventListener('mousemove', Draw, false);
    canvas.addEventListener('mouseup', endDraw, false);

    window.location.hash = "1" ;

    var image = canvas.toDataURL('image/jpeg', 0.5);
    getManga(mangaPage);

});
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

function buttonClick(color){
    console.log(changedImages);
    gColor = color;
    console.log(color);
}

var volume = document.getElementById('volume');

volume.addEventListener('change', function () {
  gWidth = volume.value.length*3;
}, false);

function bigLine(){
    gWidth = 100;
}

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
    if(changedImages.indexOf(window.location.hash.slice(1)) == -1) changedImages.push(window.location.hash.slice(1));

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
    setCSRF();

    $.each(changedImages, function(index, value){
        postImages["image_" + value] = images[value];
      })
    postImages["title"] = $('#title').val();

    $('#test_text').text('通信中...');

    // Ajax通信を開始
    $.ajax({
      url: HOST_NAME+'/v1/image/'+id,
      type: 'PATCH',
      data: postImages,
      dataType: 'json',
      timeout: 5000,
    })
    .done(function(data1,textStatus,jqXHR) {
        var data2 = JSON.stringify(data1);
        $('#test_text').text("成功");
        console.log(data2);
        window.location.href = '/manga';
    })
    .fail(function(data1,textStatus,jqXHR) {
        $('#test_text').text(JSON.stringify(data1));
    });
};

function canvasPlus(){
    var newImageNumber = Object.keys(images).length+1;
    $('#change_canvas_buttons').append('<input type="button" value="'+newImageNumber+'" onclick="changeCanvas(this.value,window.location.hash.slice(1))">');
    var image = canvas.toDataURL('image/jpeg', 1);
    images[window.location.hash.slice(1)] = image;
    con.fillStyle = 'rgb(255,255,255)';
    con.fillRect(0, 0, 1280, 720);
    images[newImageNumber] = canvas.toDataURL('image/jpeg', 1);
    window.location.hash = newImageNumber;
    if(newImageNumber == 200) {
        $("#plus_button").remove();
    }
}

function changeCanvas(id,hash){
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
    window.location.hash = id;
}