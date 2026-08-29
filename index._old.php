<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TOLOBA QUTBI MOHALLA</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial, Helvetica, sans-serif;
}

body{
background:#e8f5e9;
}

/* Header */

header{
background:#0b5d1e;
color:white;
text-align:center;
padding:25px;
font-size:36px;
font-weight:bold;
letter-spacing:2px;
box-shadow:0 4px 10px rgba(0,0,0,.3);
}

/* Grid */

.container{
width:90%;
margin:40px auto;
display:grid;
grid-template-columns:repeat(4,1fr);
gap:25px;
}

/* Panels */

.panel{
background:white;
border-radius:15px;
padding:45px 20px;
text-align:center;
border-top:8px solid #0b5d1e;
box-shadow:0 6px 15px rgba(0,0,0,.15);
transition:.3s;
cursor:pointer;
min-height:200px;
display:flex;
justify-content:center;
align-items:center;
}

.panel:hover{
background:#dff5e3;
transform:translateY(-8px);
}

.panel a{
text-decoration:none;
color:inherit;
display:flex;
flex-direction:column;
justify-content:center;
align-items:center;
width:100%;
height:100%;
}

.panel h2{
color:#0b5d1e;
font-size:20px;
margin-bottom:12px;
line-height:1.3;
min-height:52px;
display:flex;
justify-content:center;
align-items:center;
text-align:center;
}

.panel p{
color:#555;
font-size:15px;
}

.admin{
width:90%;
margin:0 auto 40px;
}

.admin button{
width:100%;
padding:18px;
font-size:22px;
font-weight:bold;
background:#083d14;
color:white;
border:none;
border-radius:12px;
cursor:pointer;
transition:.3s;
}

.admin button:hover{
background:#0b5d1e;
}

footer{
background:#0b5d1e;
color:white;
text-align:center;
padding:18px;
font-size:15px;
}

.modal{
display:none;
position:fixed;
left:0;
top:0;
width:100%;
height:100%;
background:rgba(0,0,0,.6);
justify-content:center;
align-items:center;
}

.login-box{
background:white;
width:350px;
padding:30px;
border-radius:15px;
text-align:center;
box-shadow:0 10px 20px rgba(0,0,0,.3);
}

.login-box h2{
color:#0b5d1e;
margin-bottom:20px;
}

.login-box input{
width:100%;
padding:12px;
margin:10px 0;
border-radius:8px;
border:1px solid #ccc;
font-size:15px;
}

.login-box button{
width:100%;
padding:12px;
margin-top:10px;
border:none;
border-radius:8px;
background:#0b5d1e;
color:white;
cursor:pointer;
font-size:16px;
}

.close{
background:#d32f2f !important;
}

@media(max-width:1000px){
.container{
grid-template-columns:repeat(2,1fr);
}
}

@media(max-width:600px){
.container{
grid-template-columns:1fr;
}
}

</style>

</head>

<body>

<header>
TOLOBA QUTBI MOHALLA
</header>

<div class="container">

<div class="panel">
<a href="miqat.php">
<h2>MIQAT</h2>
<p>View Upcoming Miqat</p>
</a>
</div>

<div class="panel">
<a href="postholders.php">
<h2>POST HOLDERS</h2>
<p>Click to View Post Holders</p>
</a>
</div>

<div class="panel">
<a href="photos.php">
<h2>PHOTOS</h2>
<p>Open Photo Gallery</p>
</a>
</div>

<div class="panel">
<a href="contact.php">
<h2>CONTACT</h2>
<p>Contact Us</p>
</a>
</div>

<div class="panel">
<a href="members.php">
<h2>MEMBERS</h2>
<p>Click to View Your Colleagues</p>
</a>
</div>

<div class="panel">
<a href="ashara1449.html">
<h2>ASHARA 1449</h2>
<p>View Ashara 1449 Information</p>
</a>
</div>

<div class="panel">
<a href="miqatkhidmat.html">
<h2>MIQAT KHIDMAT<br>UPDATES</h2>
<p>View Latest Khidmat Updates</p>
</a>
</div>

</div>

<div class="admin">
<a href="login.php">

<button>

ADMIN ACCESS

</button>

</a>
</div>

<footer>
All Rights Reserved © TOLOBA QUTBI MOHALLA DAHOD
</footer>



</body>
</html>