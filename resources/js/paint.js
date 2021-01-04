
// 描画用フラグ  true: 描画中   false: 描画中でない
var flgDraw = false;

// 座標
var gX = 0;
var gY = 0;

// 描画色
var gColor = 'white';

window.onload = function() {
    
    // イベント登録
    // マウス
    var canvas = document.getElementById('canvas');
   
    canvas.addEventListener('mousedown', startDraw, false);
    canvas.addEventListener('mousemove', Draw, false);
    canvas.addEventListener('mouseup', endDraw, false);
    
    // セレクトボックス
    var s = document.getElementById('color');
    s.addEventListener('change', changeColor, false);
    
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

// 描画
function Draw(e){
    
    if (flgDraw == true){
        
        // '2dコンテキスト'を取得
        var canvas = document.getElementById('canvas');
        var con = canvas.getContext('2d');

        var x = e.offsetX;
        var y = e.offsetY;

        // 線のスタイルを設定
        con.lineWidth = 3;
        // 色設定
        con.strokeStyle = gColor;

        // 描画開始
        con.beginPath();
        con.moveTo(gX, gY);
        con.lineTo(x, y);
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