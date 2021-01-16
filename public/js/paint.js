
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

//色変更　テスト
function buttonClick(color){
    console.log(images);
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
        for(i = 1; i <= 5; i++){
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

function canvasPlus(){
    var newImageNumber = Object.keys(images).length+1;
    $('#change_canvas_buttons').append('<input type="button" value="'+newImageNumber+'" onclick="changeCanvas(this.value,window.location.hash.slice(1))">');
    var image = canvas.toDataURL('image/jpeg', 1);
    images[window.location.hash.slice(1)] = image;
    con.fillStyle = 'rgb(255,255,255)';
    con.fillRect(0, 0, 1280, 720);
    images[newImageNumber] = canvas.toDataURL('image/jpeg', 1);
    window.location.hash = newImageNumber;
    if(newImageNumber == 50) {
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