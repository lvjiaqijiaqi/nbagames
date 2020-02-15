<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" /> 
<title>你是一只猪啊啊啊啊</title>
<style>

.container {
  background:url(images/bg2.jpeg);
  background-size:cover;
}
.text_container {
  width:100%;
  height:20;
  font-size: 30px;
  color: white;
  position:absolute;
  bottom: 20%;
  text-align:center;
  z-index: 1000;
}

@keyframes text_container_rotate{
0%{
    transform:rotate(0deg);
  }
25%{
    transform:rotate(30deg);
  }
50%{
    transform:rotate(0deg);
  }
75%{
    transform:rotate(-30deg);
}
100%{
    transform:rotate(0deg);
  }
}

.text_container{
  animation: text_container_rotate 4s linear infinite
}

.zuida {
  width:100%;
  height:100%;
  background:#666;
  position:relative;
  perspective:800px;
  margin:0 auto;
}
.aa {
  width:40%;
  height:100px;
  position:absolute;
  left:35%;
  top:210px;
  transform-style:preserve-3d;
  transform-origin:center center;
  transform:rotateY(0deg) rotateX(0deg);
  transition:transform 4s linear;
}

@keyframes rotatete{
from{
    transform:rotateY(0deg) rotateX(0deg);
  }
to{
    transform:rotateY(360deg) rotateX(360deg);
  }
}
.aa:hover {
  animation: rotatete 6s linear infinite
}
.aa:active{
  animation: rotatete 6s linear infinite
}

.a1,.a2,.a3,.a4,.a5,.a6 {
  width:100px;
  height:100px;
  position:absolute;
  color:#fff;
  opacity:0.7;
}
.a1 {
  background:#f00;
  left:-50px;
  transform:rotateY(90deg);
  background:url(images/img12.jpeg);
  background-size:cover;
}
.a2 {
  background:#F60;
  left:50px;
  transform:rotateY(90deg);
  background:url(images/img11.jpeg);
  background-size:cover;
}
.a3 {
  background:#fff000;
  transform:translateZ(50px);
  background:url(images/img10.jpeg);
  background-size:cover;
}
.a4 {
  background:#0f0;
  transform:translateZ(-50px);
  background:url(images/img9.jpeg);
  background-size:cover;
}
.a5 {
  background:#00f;
  transform:rotateX(90deg);
  background:url(images/img8.jpeg);
  background-size:cover;
  top:-50px;
}
.a6 {
  background:#f0f;
  transform:rotateX(90deg);
  background:url(images/img7.jpeg);
  background-size:cover;
  top:50px;
}
.b1,.b2,.b3,.b4,.b5,.b6 {
  width:200px;
  height:200px;
  position:absolute;
  opacity:0.9;
}
.b1 {
  left:-150px;
  top:-50px;
  transform:rotateY(270deg);
  background:url(images/img6.jpeg);
  background-size:cover;
  transition:left 1.5s;
}
.aa:hover .b1 {
  left:-250px;
  transition:left 1.5s 0.5s linear;
}
.b2 {
  left:50px;
  top:-50px;
  transform:rotateY(270deg);
  background:url(images/img5.jpeg);
  background-size:cover;
  transition:left 1.5s;
}
.aa:hover .b2 {
  left:150px;
  transition:left 1.5s 0.5s linear;
}
.b3 {
  left:-50px;
  top:-50px;
  transform:translateZ(100px);
  background:url(images/img4.jpeg);
  background-size:cover;
  transition:transform 1.5s;
}
.aa:hover .b3 {
  transform:translateZ(200px);
  transition:transform 1.5s 0.5s linear;
}
.b4 {
  left:-50px;
  top:-50px;
  transform:translateZ(-100px);
  background:url(images/img3.jpeg);
  background-size:cover;
  transition:transform 1.5s;
}
.aa:hover .b4 {
  transform:translateZ(-200px);
  transition:transform 1.5s 0.5s linear;
}
.b5 {
  left:-50px;
  top:-150px;
  transform:rotateX(90deg);
  background:url(images/img2.jpeg);
  background-size:cover;
  transition:top 1.5s;
}
.aa:hover .b5 {
  top:-250px;
  transition:top 1.5s 0.5s linear;
}
.b6 {
  left:-50px;
  top:50px;
  transform:rotateX(90deg);
  background:url(images/img1.jpeg);
  background-size:cover;
  transition:top 1.5s linear;
}
.aa:hover .b6 {
  top:150px;
  transition:top 1.5s 0.5s linear;
}
</style>
</head>
<body class="container">
<div class="zuida">
    <div class="aa">
        <div class="bb"></div>
        <div class="a1"></div>
        <div class="a2"></div>
        <div class="a3"></div>
        <div class="a4"></div>
        <div class="a5"></div>
        <div class="a6"></div>
        <div class="b1"></div>
        <div class="b2"></div>
        <div class="b3"></div>
        <div class="b4"></div>
        <div class="b5"></div>
        <div class="b6"></div>
    </div>
</div>
<div id="text_container" class="text_container">
  啊啊啊啊啊啊啊啊
</div>
<script>

</script>
<script type="text/javascript">
  var textarr = ["你是一头猪","你是一头大笨猪","你是我的一头大笨猪","你永远是我的大笨猪","想和你睡觉","想和你吃饭","想和XXXX"];
  var conter = 0;
  function startTextPlay(){
    console.log(conter);
    var x =document.getElementById("text_container");
    x.innerHTML = textarr[conter%textarr.length];
    conter++;
  }
  document.body.addEventListener('touchstart', function (){
    setInterval(function(){startTextPlay()},4000);
     //startTextPlay();
  });
  window.onload = function(){
      setInterval(function(){startTextPlay()},4000);
  };
</script>
</body>
</html>
