
// 描画用フラグ  true: 描画中   false: 描画中でない
var flgDraw = false;

// 座標
var gX = 0;
var gY = 0;

// 描画色
var gColor = 'black';
var gWidth = 0;

// '2dコンテキスト'を取得
var canvas = document.getElementById('canvas');
var con = canvas.getContext('2d');

con.fillStyle = 'rgb(255,255,255)';
con.fillRect(0, 0, 1920, 1080);

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

function buttonClick(color){
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
        con.lineCap = "butt";
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

    // Ajax通信を開始
    $.ajax({
      url: 'http://localhost:8000/v1/image',
      type: 'POST',
      data: {
          "title" : $('#title').val(),
          "image_1" : images[1],
          "image_2" : images[2],
          "image_3" : images[3],
          "image_4" : images[4],
          "image_5" : images[5],
          "image_6" : images[6],
          "image_7" : images[7],
          "image_8" : images[8],
          "image_9" : images[9],
          "image_10" : images[10],
        },
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
    if(newImageNumber == 10) {
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