<?php
include('session.php');
$_SESSION['pageStore'] = "draw.php";

if (!isset($_SESSION['login_id'])) {
    header("location: index.php"); // Redirecting To Home Page
}
?>

<!DOCTYPE html>
<html>

<head>
    <script src="js/fabric.min.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
        //UpDate table with new dateTime every minutes
        setInterval(() => {
            fetch('checkOnline/active.php?user_id=' + <?php echo $session_id ?>)
        }, 60000);

        $(function() { // For saving data on database
            $("#savBtn").click(function() {
                var dataURL = canvas.toDataURL();
                var dataMsg = document.getElementById('atchMsg').value;
                var frnSelect = $(".frnSelect:checked").val();
                if (dataMsg == '') {
                    alert("Please enter some message");
                    $("#atchMsg").focus();
                } else if (frnSelect == '') {
                    alert("Please select friend");
                } else {
                    $.ajax({
                        type: "POST",
                        url: "img/imgInsert.php",
                        data: {
                            dataImg: dataURL,
                            dataCont: dataMsg,
                            dataFrn: frnSelect
                        },
                        cache: true,
                        success: function(response) {
                            if (response == "Please select a friend") {
                                alert(response);
                                $("#searchFrn").focus();
                            } else {
                                alert("Sucessfuly Send");
                            }
                        }
                    });
                }
                return false;
            });

            // For searching friend
            $("#frnBtn").click(function() {
                var textcontent = $("#searchFrn").val();
                if (textcontent == '') {
                    alert("Enter your friend name");
                    $("#searchFrn").focus();
                } else {
                    $.ajax({
                        type: "POST",
                        url: "draw/searchFrn.php",
                        data: {
                            q: textcontent
                        },
                        cache: true,
                        success: function(response) {
                            document.getElementById("frnList").innerHTML = response;
                        }
                    });
                }
                return false;
            });
        });

        // Increase width of input
        function morewidth() {
            $("#atchMsg").animate({
                width: "1340px"
            }, 400);
            document.getElementById('SzIcon').className = "icon fa-arrow-right";
        }
        // Decrease width of input
        function lesswidth() {
            $("#atchMsg").animate({
                width: "170px"
            }, 400);
            document.getElementById('SzIcon').className = "icon fa-arrow-left";
        }
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/w3.css">
    <link rel="stylesheet" href="css/bootstrap.css">

    <style type="text/css">
        input[type="text"]:focus {
            box-shadow: 0 0 0 0.2rem rgba(134, 142, 150, .5);
        }

        .svg {
            display: none;
        }

        .l5,
        .l40,
        .l75,
        .l110,
        .l145 {
            position: absolute;
            width: 30px;
            height: 30px;
        }

        .l5 {
            left: 5px;
        }

        .l40 {
            left: 40px;
        }

        .l75 {
            left: 75px;
        }

        .l110 {
            left: 110px;
        }

        .l145 {
            left: 145px;
        }

        .t35 {
            top: 35px;
        }

        .t70 {
            top: 70px;
        }
    </style>
</head>

<body class="bg-light">

    <!Menu Heading>
        <?php include 'menuHead.php'; ?>

        <canvas id="c" width="850" height="500" style="border:1px solid black;" class="c"></canvas>
        <div class="modal" style="width:30px;height:30px;background:#A7A9AC;"></div>
        <!Collection of colors>


            <div class="dark" onclick="chooseColor()" style="position:absolute;top:52px;right: 60px;">Choose Colors <i id="colorPen" class="icon fa-pencil" style="color:#A7A9AC;"></i></div>
            <div id="colorCollection" style="position:absolute;top:75px;right: 180px;">
                <div class="rounded l5" style="background:#A7A9AC;" id="#A7A9AC" onclick="color(this)"></div>
                <div class="rounded l40" style="background:#00AACC;" id="#00AACC" onclick="color(this)"></div>
                <div class="rounded l75" style="background:#004DE6;" id="#004DE6" onclick="color(this)"></div>
                <div class="rounded l110" style="background:#3D00B8;" id="#3D00B8" onclick="color(this)"></div>
                <div class="rounded l145" style="background:#600080;" id="#600080" onclick="color(this)"></div>
                <div class="rounded l5 t35" style="background:#FFE600;" id="#FFE600" onclick="color(this)"></div>
                <div class="rounded l40 t35" style="background:#FFAA00;" id="#FFAA00" onclick="color(this)"></div>
                <div class="rounded l75 t35" style="background:#FF5500;" id="#FF5500" onclick="color(this)"></div>
                <div class="rounded l110 t35" style="background:#E61B1B;" id="#E61B1B" onclick="color(this)"></div>
                <div class="rounded l145 t35" style="background:#B31564;" id="#B31564" onclick="color(this)"></div>
                <div class="rounded l5 t70" style="background:#A2E61B;" id="#A2E61B" onclick="color(this)"></div>
                <div class="rounded l40 t70" style="background:#26E600;" id="#26E600" onclick="color(this)"></div>
                <div class="rounded l75 t70" style="background:#008055;" id="#008055" onclick="color(this)"></div>
                <div class="rounded l110 t70" style="background:#58595B;" id="#58595B" onclick="color(this)"></div>
                <div class="rounded l145 t70" style="background:#613D30;" id="#613D30" onclick="color(this)"></div>
            </div>

            <!div>
                <!Erasear>
                    <!div style="position:absolute;top:187px;left: 5px;">
                        <!Eraser <i id="EraserIcon" class="icon fa-eraser">
                            <! /i>
                                <! /div>
                                    <!div class="rounded" style="position:absolute;top:185px;left: 80px;width:31px;height:31px;background:white;border:3px solid;" id="white" onclick="color(this)">
                                        <! /div>
                                            <! /div>

                                                <!Adjust width of pen with Range>
                                                    <canvas id="toolCan" width="60" height="35" style="position: absolute;top:185px;right: 5px;"></canvas>
                                                    <input type="range" id="colorWidth" min="1" max="30" value="25" oninput="range(this)" style="position: absolute;top:191px;right:70px;width:105px;">

                                                    <!Change Drawing mode>
                                                        <button class="btn btn-secondary" id="selObj" onclick="selectObject()" style="position: absolute;top:220px;right:5px;width:170px;">Enter drawing mode</button>

                                                        <! Connecting lines enabled if checked>
                                                            <div style="position: absolute;top:260px;right:44px;width:133px;">
                                                                <input type="radio" name="wire" id="wire" value="Connect">Enable Wiring<br>
                                                                <!-- <input type="radio" name="wire" id="no_wire" value="DisConnect">Disable Wiring<br> -->
                                                                <button type="button" onclick="wiring()">
                                                                    Submit
                                                                </button>
                                                            </div>

                                                            <!Add delete predefine object>
                                                                <select style="position: absolute;top:350px;right:44px;width:133px;" class="custom-select" id="paintOption">
                                                                    <option value="rectangle">Rectangle</option>
                                                                    <option value="triangle">Triangle</option>
                                                                    <option value="circle">Circle</option>
                                                                    <option value="line">Line</option>
                                                                    <option value="text">Textbox</option>
                                                                </select>
                                                                <button class="btn btn-primary" onclick="add()" value="add" id="add" style="position:absolute;top:350px;right:42px;"><i id="AddIcon" class="icon fa-plus"></i></button>
                                                                <button class="btn btn-danger" onclick="deleteObjects()" value="delete" id="delete" style="position:absolute;top:350px;right:5px;"><i id="DeleteIcon" class="icon fa-times"></i></button>

                                                                <!Search friend>
                                                                    <form method="post">
                                                                        <input type="search" id="searchFrn" placeholder="Search your friend" style="position: fixed;top:390px;right:5px;width:170px;">
                                                                        <input type="submit" name="msg" id="frnBtn" style="display: none;">
                                                                        <div id="frnList" style="position: absolute;top:410px;right:5px;width:170px;height:190px;overflow:auto;"></div>
                                                                    </form>

                                                                    <form method="post">
                                                                        <!Attach message>
                                                                            <div style="position: absolute;top:532px;right:15px;">Attach your message <i id="SzIcon" class="icon fa-arrow-left"></i></div>
                                                                            <input type="text" name="atchMsg" id="atchMsg" onblur="lesswidth()" onclick="morewidth()" class="form-control" style="position: absolute;top:555px;right:5px;width:170px;" required>

                                                                            <!Save erase button>
                                                                                <button class="btn btn-secondary" value="Save" id="savBtn" size="23" style="position:absolute;top:598px;right:98px;"><i id="SendIcon" class="icon fa-send"></i> Send</button>
                                                                    </form>
                                                                    <button class="btn btn-danger" value="Clear" id="clr" size="23" onclick="cleanUp()" style="position:absolute;top:598px;right:10px;"><i id="ClearIcon" class="icon fa-times"></i> Clear</button>


                                                                    <!-- *********   START ******** -->
                                                                    <!-- <div>
                                                                        <button id="svg-load">Load SVG || Load Circuit</button>
                                                                        <select id="option" size="1">
                                                                            <option value="group">Group</option>
                                                                            <option value="wgroup">Without Group</option>
                                                                        </select>
                                                                    </div>


                                                                    <button id="json">Group</button>
                                                                    <button id="ugroup">Ungroup</button>



                                                                    <div id="svg3" class="svg" data-url="mics.svg">
                                                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="700" height="300">
                                                                            <defs />
                                                                            <g>
                                                                                <rect fill="#FFFFFF" stroke="none" x="0" y="0" width="500" height="240" />
                                                                                <path fill="white" stroke="black" paint-order="fill stroke markers" d=" M 410.5 150.5 L 450.5 150.5 L 450.5 190.5 L 410.5 190.5 L 410.5 150.5 Z" stroke-miterlimit="10" stroke-width="3" stroke-dasharray="" />
                                                                                <path fill="none" stroke="none" /><text fill="green" stroke="none" font-family="Raleway" font-size="20px" font-style="normal" font-weight="normal" text-decoration="normal" x="430" y="175" text-anchor="middle" dominant-baseline="alphabetic">1</text>
                                                                                <path fill="none" stroke="black" paint-order="fill stroke markers" d=" M 410.5 175.5 L 415.5 180.5 L 410.5 185.5" stroke-miterlimit="10" stroke-width="3" stroke-dasharray="" />
                                                                                <path fill="white" stroke="black" paint-order="fill stroke markers" d=" M 50.5 150.5 L 90.5 150.5 L 90.5 190.5 L 50.5 190.5 L 50.5 150.5 Z" stroke-miterlimit="10" stroke-width="3" stroke-dasharray="" />
                                                                                <path fill="none" stroke="none" /><text fill="green" stroke="none" font-family="Raleway" font-size="20px" font-style="normal" font-weight="normal" text-decoration="normal" x="70" y="175" text-anchor="middle" dominant-baseline="alphabetic">0</text>
                                                                                <path fill="white" stroke="rgb(0,0,0)" paint-order="fill stroke markers" d=" M 170.5 50.5 L 160.5 60.5 L 180.5 60.5 L 170.5 50.5 Z M 170.5 60.5 L 170.5 70.5" stroke-opacity="1" stroke-miterlimit="10" stroke-width="3" stroke-dasharray="" />
                                                                                <path fill="none" stroke="lightgreen" paint-order="fill stroke markers" d=" M 170.5 70 L 170.5 170" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" stroke-dasharray="" />
                                                                                <path fill="none" stroke="lightgreen" paint-order="fill stroke markers" d=" M 170 70.5 L 320 70.5" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" stroke-dasharray="" />
                                                                                <path fill="none" stroke="black" paint-order="fill stroke markers" d=" M 90 170.5 L 170 170.5" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" stroke-dasharray="" />
                                                                                <path fill="none" stroke="lightgreen" paint-order="fill stroke markers" d=" M 320.5 70 L 320.5 180" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" stroke-dasharray="" />
                                                                                <path fill="none" stroke="lightgreen" paint-order="fill stroke markers" d=" M 410 180.5 L 320 180.5" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" stroke-dasharray="" />
                                                                                <path fill="green" stroke="none" paint-order="stroke fill markers" d=" M 173 70 A 3 3 0 1 1 172.99999850000012 69.9970000005 Z" />
                                                                                <path fill="none" stroke="green" paint-order="fill stroke markers" d=" M 178.5 70.5 A 8 8 0 1 1 178.49999600000032 70.49200000133332 Z" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" stroke-dasharray="" />
                                                                                <path fill="green" stroke="none" paint-order="stroke fill markers" d=" M 93 170 A 3 3 0 1 1 92.99999850000013 169.9970000005 Z" />
                                                                                <path fill="none" stroke="green" paint-order="fill stroke markers" d=" M 98.5 170.5 A 8 8 0 1 1 98.49999600000034 170.49200000133334 Z" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" stroke-dasharray="" />
                                                                                <path fill="green" stroke="none" paint-order="stroke fill markers" d=" M 413 160 A 3 3 0 1 1 412.9999985000001 159.9970000005 Z" />
                                                                                <path fill="green" stroke="none" paint-order="stroke fill markers" d=" M 413 180 A 3 3 0 1 1 412.9999985000001 179.9970000005 Z" />
                                                                                <path fill="green" stroke="none" paint-order="stroke fill markers" d=" M 433 190 A 3 3 0 1 1 432.9999985000001 189.9970000005 Z" />
                                                                                <path fill="green" stroke="none" paint-order="stroke fill markers" d=" M 453 160 A 3 3 0 1 1 452.9999985000001 159.9970000005 Z" />
                                                                                <path fill="green" stroke="none" paint-order="stroke fill markers" d=" M 453 180 A 3 3 0 1 1 452.9999985000001 179.9970000005 Z" />
                                                                                <path fill="black" stroke="none" paint-order="stroke fill markers" d=" M 173 170 A 3 3 0 1 1 172.99999850000012 169.9970000005 Z" />
                                                                                <path fill="none" stroke="green" paint-order="fill stroke markers" d=" M 178.5 170.5 A 8 8 0 1 1 178.49999600000032 170.49200000133334 Z" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" stroke-dasharray="" />
                                                                                <path fill="lightgreen" stroke="none" paint-order="stroke fill markers" d=" M 323 70 A 3 3 0 1 1 322.9999985000001 69.9970000005 Z" />
                                                                                <path fill="lightgreen" stroke="none" paint-order="stroke fill markers" d=" M 323 180 A 3 3 0 1 1 322.9999985000001 179.9970000005 Z" />
                                                                                <path fill="none" stroke="green" paint-order="fill stroke markers" d=" M 328.5 180.5 A 8 8 0 1 1 328.49999600000035 180.49200000133334 Z" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" stroke-dasharray="" />
                                                                            </g>
                                                                        </svg>

                                                                    </div>
                                                                    <br />

                                                                    <button id="inp-btn">Input Element</button>
                                                                    <div id="inp-ele" class="svg" data-url="mics.svg">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30">
                                                                            <path fill="#fff" stroke="#000" paint-order="fill stroke markers" d="M5 5h20v20H5V5z" stroke-miterlimit="10" stroke-width="3" /><text fill="green" font-family="Georgia" font-size="20" text-decoration="normal" x="15" y="20" text-anchor="middle" dominant-baseline="alphabetic">1</text>
                                                                            <path fill="green" paint-order="stroke fill markers" d="M28 15a3 3 0 110-.003z" />
                                                                        </svg>
                                                                    </div>


                                                                    <button id="btn-btn">Button Element</button>
                                                                    <div id="button-ele" class="svg" data-url="mics.svg">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60">
                                                                            <path fill="none" stroke="#353535" paint-order="fill stroke markers" d="M30 30h20" stroke-miterlimit="10" stroke-width="5" />
                                                                            <path fill="#ddd" stroke="#353535" paint-order="stroke fill markers" d="M32 30a12 12 0 110-.012" stroke-miterlimit="10" stroke-width="5" />
                                                                            <path fill="green" paint-order="stroke fill markers" d="M53 30a3 3 0 110-.003z" />
                                                                        </svg>
                                                                    </div>

                                                                    <button id="opt-btn">Output Element</button>
                                                                    <div id="opt-ele" class="svg" data-url="mics.svg">

                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30">
                                                                            <path fill="#fff" stroke="red" paint-order="fill stroke markers" d="M5 5h20v20H5V5z" stroke-miterlimit="10" stroke-width="3" /><text fill="green" font-family="Georgia" font-size="20" text-decoration="normal" x="15" y="20" text-anchor="middle" dominant-baseline="alphabetic">x</text>
                                                                            <path fill="green" paint-order="stroke fill markers" d="M8 15a3 3 0 110-.003z" />
                                                                        </svg>
                                                                    </div>
 -->

                                                                    <!-- *********   END   ********* -->




                                                                    <script>
                                                                        $(document).ready(function() {
                                                                            $("#selObj").click(function() {
                                                                                if ($("#selObj").text() == 'Enter drawing mode') {
                                                                                    $("#selObj").text('Cancel drawing mode');
                                                                                    $("#paintOption").hide(400);
                                                                                    $("#add").hide(400);
                                                                                    $("#delete").hide(400);
                                                                                } else if ($("#selObj").text() == 'Cancel drawing mode') {
                                                                                    $("#selObj").text('Enter drawing mode');
                                                                                    $("#paintOption").show(400);
                                                                                    $("#add").show(400);
                                                                                    $("#delete").show(400);
                                                                                    $("#searchFrn").animate({
                                                                                        top: "320px"
                                                                                    }, 400);
                                                                                    $("#frnList").animate({
                                                                                        top: "330px",
                                                                                        height: "190px"
                                                                                    }, 400);
                                                                                }
                                                                            });
                                                                        });

                                                                        var x = "#A7A9AC",
                                                                            y = 24;
                                                                        tcan = document.getElementById('toolCan');
                                                                        tctx = tcan.getContext("2d");
                                                                        tctx.clearRect(0, 0, 60, 35);
                                                                        tctx.beginPath();
                                                                        tctx.moveTo(12, 35 / 2);
                                                                        tctx.lineTo(48, 35 / 2);
                                                                        tctx.strokeStyle = document.getElementById('colorPen').style.color;
                                                                        tctx.lineWidth = 24;
                                                                        tctx.lineCap = 'round';
                                                                        tctx.stroke();

                                                                        function color(obj) {
                                                                            x = obj.id
                                                                            y = document.getElementById('colorWidth').value;
                                                                            document.getElementById('colorPen').style.color = x;
                                                                            document.getElementById('colorWidth').style.display = "inline";

                                                                            canvas.freeDrawingBrush.color = x;
                                                                            var checkObj = canvas.getActiveObject();
                                                                            if (checkObj) {
                                                                                if (checkObj.get('type') != 'path') checkObj.set("fill", x);
                                                                                checkObj.set("stroke", x);
                                                                                canvas.renderAll();
                                                                            }

                                                                            tctx.clearRect(0, 0, 60, 35);
                                                                            tctx.beginPath();
                                                                            tctx.moveTo(y / 2, 35 / 2);
                                                                            tctx.lineTo(60 - y / 2, 35 / 2);
                                                                            tctx.strokeStyle = x;
                                                                            tctx.lineWidth = y;
                                                                            tctx.lineCap = 'round';
                                                                            tctx.stroke();
                                                                        }

                                                                        // create a wrapper around native canvas element (with id="c")

                                                                        function add() {
                                                                            var paintOpt = $("#paintOption").val();
                                                                            var cordi = can.getBoundingClientRect();
                                                                            switch (paintOpt) {
                                                                                case 'rectangle':
                                                                                    var rectangle = new fabric.Rect({
                                                                                        width: 10,
                                                                                        height: 10,
                                                                                        fill: x,
                                                                                        left: cordi.left + Math.floor(Math.random() * (canvas.width)),
                                                                                        top: cordi.top + Math.floor(Math.random() * (canvas.height))
                                                                                    });
                                                                                    canvas.add(rectangle);
                                                                                    break;
                                                                                case 'triangle':
                                                                                    var triangle = new fabric.Triangle({
                                                                                        width: 10,
                                                                                        height: 7,
                                                                                        fill: x,
                                                                                        left: cordi.left + Math.floor(Math.random() * (canvas.width)),
                                                                                        top: cordi.top + Math.floor(Math.random() * (canvas.height))
                                                                                    });
                                                                                    canvas.add(triangle);
                                                                                    break;
                                                                                case 'circle':
                                                                                    var circle = new fabric.Circle({
                                                                                        radius: 10,
                                                                                        fill: x,
                                                                                        left: cordi.left + Math.floor(Math.random() * (canvas.width)),
                                                                                        top: cordi.top + Math.floor(Math.random() * (canvas.height))
                                                                                    });
                                                                                    canvas.add(circle);
                                                                                    break;
                                                                                case 'line':
                                                                                    var line = new fabric.Line([50, 100, 200, 100], {
                                                                                        left: 650,
                                                                                        top: 75,
                                                                                        stroke: x,
                                                                                        strokeWidth: 8
                                                                                    });
                                                                                    canvas.add(line);
                                                                                    break;
                                                                                case 'text':
                                                                                    var addtext = new fabric.Textbox('Edit this text', {
                                                                                        left: 400,
                                                                                        top: 200,
                                                                                        fill: x,
                                                                                        strokeWidth: 2,
                                                                                        fontFamily: 'Arial'
                                                                                    });
                                                                                    canvas.add(addtext);
                                                                                    break;
                                                                                default:
                                                                                    alert('No');
                                                                            }
                                                                        }

                                                                        function selectObject() {
                                                                            canvas.isDrawingMode = !canvas.isDrawingMode;
                                                                        }

                                                                        function deleteObjects() {
                                                                            var active = canvas.getActiveObjects();
                                                                            if (active) {
                                                                                canvas.discardActiveObject();
                                                                                canvas.remove(...active);
                                                                            }
                                                                        }

                                                                        var canvas = this.__canvas = new fabric.Canvas('c');
                                                                        var can = document.querySelector('.upper-canvas');
                                                                        var ctx = can.getContext("2d");

                                                                        if (canvas.freeDrawingBrush) {
                                                                            canvas.freeDrawingBrush.color = x;
                                                                            canvas.freeDrawingBrush.width = y;
                                                                        }
                                                                        // *************************
                                                                        // var can = document.querySelector('.upper-canvas');
                                                                        // var ctx = can.getContext("2d");

                                                                        // ctx.beginPath();

                                                                        // ctx.moveTo(0, 0);
                                                                        // ctx.lineTo(300, 150);

                                                                        // ctx.stroke();
                                                                        function wiring() {
                                                                            if (document.getElementById('wire').checked) {
                                                                                var clicks = 0;
                                                                                var lastClick = [0, 0];

                                                                                can.addEventListener('click', drawLine, false);

                                                                                function getCursorPosition(e) {
                                                                                    var x;
                                                                                    var y;
                                                                                    var rectc;
                                                                                    rectc = can.getBoundingClientRect();

                                                                                    if (e.pageX != undefined && e.pageY != undefined) {
                                                                                        // console.log(rectc);
                                                                                        x = e.pageX - rectc.left;
                                                                                        y = e.pageY - rectc.top;
                                                                                    } else {
                                                                                        // console.log(rectc);
                                                                                        x = e.clientX - rectc.left;
                                                                                        y = e.clientY - rectc.top;
                                                                                    }

                                                                                    return [x, y];
                                                                                }

                                                                                function drawLine(e) {
                                                                                    // console.log('clicked');
                                                                                    x = getCursorPosition(e)[0] - this.offsetLeft;
                                                                                    y = getCursorPosition(e)[1] - this.offsetTop;

                                                                                    if (clicks != 1) {
                                                                                        clicks++;
                                                                                    } else {
                                                                                        ctx.beginPath();
                                                                                        ctx.moveTo(lastClick[0], lastClick[1]);
                                                                                        ctx.lineTo(x, y, 6);

                                                                                        ctx.strokeStyle = '#000000';
                                                                                        ctx.stroke();

                                                                                        clicks = 0;
                                                                                    }

                                                                                    lastClick = [x, y];
                                                                                }

                                                                                can.addEventListener('touchstart', drawLine_touch, false);

                                                                                function getCursorPosition_touch(e) {
                                                                                    var x1;
                                                                                    var y1;
                                                                                    var rectc1;
                                                                                    rectc1 = can.getBoundingClientRect();

                                                                                    if (e.pageX != undefined && e.pageY != undefined) {
                                                                                        x1 = e.pageX - rectc1.left;
                                                                                        y1 = e.pageY - rectc1.top;
                                                                                    } else {
                                                                                        x1 = e.targetTouches[0].pageX - rectc1.left;
                                                                                        y1 = e.targetTouches[0].pageY - rectc1.top;
                                                                                    }
                                                                                    console.log(x1, y1);
                                                                                    return [x1, y1];
                                                                                }

                                                                                function drawLine_touch(e) {
                                                                                    console.log('touched');

                                                                                    x1 = getCursorPosition_touch(e)[0] - this.offsetLeft;
                                                                                    y1 = getCursorPosition_touch(e)[1] - this.offsetTop;

                                                                                    if (clicks != 1) {
                                                                                        clicks++;
                                                                                    } else {
                                                                                        ctx.beginPath();
                                                                                        ctx.moveTo(lastClick[0], lastClick[1]);
                                                                                        ctx.lineTo(x, y, 6);

                                                                                        ctx.strokeStyle = '#000000';
                                                                                        ctx.stroke();

                                                                                        clicks = 0;
                                                                                    }

                                                                                    lastClick = [x, y];
                                                                                }
                                                                            } else {
                                                                                console.log('select radio button for wirinf')
                                                                            }
                                                                        }

                                                                        // *********************                                                                       
                                                                        function range(tobj) {
                                                                            y = tobj.value;
                                                                            tctx.clearRect(0, 0, 60, 35);
                                                                            tctx.beginPath();
                                                                            tctx.moveTo(y / 2, 35 / 2);
                                                                            tctx.lineTo(60 - y / 2, 35 / 2);
                                                                            tctx.lineWidth = y;
                                                                            tctx.lineCap = 'round';
                                                                            tctx.stroke();
                                                                            canvas.freeDrawingBrush.width = y;
                                                                        }

                                                                        // ************************ Added some of the elements


                                                                        function loadSVG(id) {
                                                                            var elem = document.getElementById(id),
                                                                                svgStr = elem.innerHTML;

                                                                            fabric.loadSVGFromString(svgStr, function(objects, options) {
                                                                                // Group elements to fabric.PathGroup (more than 1 elements) or
                                                                                // to fabric.Path
                                                                                var loadedObject = fabric.util.groupSVGElements(objects, options);
                                                                                // Set sourcePath
                                                                                loadedObject.set('sourcePath', elem.getAttribute('data-url'));

                                                                                canvas.add(loadedObject);
                                                                                console.log(loadedObject);
                                                                                loadedObject.center().setCoords();
                                                                                canvas.renderAll();
                                                                            });
                                                                        }

                                                                        var loadSVGWithoutGrouping = function(id) {
                                                                            var elem = document.getElementById(id),
                                                                                svgStr = elem.innerHTML;

                                                                            fabric.loadSVGFromString(svgStr, function(objects) {
                                                                                canvas.add.apply(canvas, objects);
                                                                                canvas.renderAll();
                                                                            });
                                                                        };

                                                                        var groupObjects = function() {
                                                                            var activeGroup = canvas.getActiveGroup();
                                                                            if (activeGroup) {
                                                                                var objectsInGroup = activeGroup.getObjects();
                                                                                var objects = objectsInGroup;
                                                                                var left = activeGroup.getLeft();
                                                                                var top = activeGroup.getTop();
                                                                                var originLeft = activeGroup._originalLeft;
                                                                                var originTop = activeGroup._originalLeft;
                                                                                var coords = activeGroup.oCoords;
                                                                                console.log(activeGroup);
                                                                                var group = new fabric.Group(objects);
                                                                                group.set({
                                                                                    _originalLeft: originLeft,
                                                                                    _originalTop: originTop,
                                                                                    left: left,
                                                                                    top: top,
                                                                                    oCoords: coords,
                                                                                    type: "group"
                                                                                });
                                                                                console.log(group);
                                                                                canvas.discardActiveGroup();
                                                                                objectsInGroup.forEach(function(object) {
                                                                                    canvas.remove(object);
                                                                                });
                                                                            }
                                                                            canvas.add(group);
                                                                            canvas.renderAll();

                                                                        }

                                                                        var otherGroup = function() {

                                                                            var activegroup = canvas.getActiveGroup();
                                                                            var objectsInGroup = activegroup.getObjects();
                                                                            console.log(activegroup);
                                                                            activegroup.clone(function(newgroup) {
                                                                                canvas.discardActiveGroup();
                                                                                objectsInGroup.forEach(function(object) {
                                                                                    canvas.remove(object);
                                                                                });
                                                                                newgroup.set({
                                                                                    fill: "",

                                                                                });
                                                                                canvas.add(newgroup);
                                                                                canvas.renderAll();
                                                                                console.log(newgroup);
                                                                            });
                                                                        }

                                                                        var unGroup = function() {
                                                                            var activeObject = canvas.getActiveObject();
                                                                            if (activeObject.type == "group") {
                                                                                var items = activeObject._objects;
                                                                                alert(items);
                                                                                activeObject._restoreObjectsState();
                                                                                canvas.remove(activeObject);
                                                                                for (var i = 0; i < items.length; i++) {
                                                                                    canvas.add(items[i]);
                                                                                    canvas.item(canvas.size() - 1).hasControls = true;
                                                                                }
                                                                                canvas.renderAll();
                                                                            }
                                                                        }

                                                                        document.getElementById('svg-load').addEventListener('click', function() {
                                                                            canvas.clear();
                                                                            var elem = document.getElementById('option'),
                                                                                value = elem.options[elem.selectedIndex].value;
                                                                            switch (value) {
                                                                                case 'group':
                                                                                    loadSVG("svg3");
                                                                                    break;

                                                                                case 'wgroup':
                                                                                    loadSVGWithoutGrouping("svg3");
                                                                                    break;
                                                                            }

                                                                        });

                                                                        document.getElementById('json').addEventListener('click', function() {
                                                                            groupObjects();
                                                                        });

                                                                        document.getElementById('ugroup').addEventListener('click', function() {
                                                                            unGroup();
                                                                        });



                                                                        document.getElementById('inp-btn').addEventListener('click', function() {
                                                                            loadSVG("inp-ele");
                                                                        });


                                                                        document.getElementById('btn-btn').addEventListener('click', function() {
                                                                            loadSVG("button-ele");
                                                                        });

                                                                        document.getElementById('opt-btn').addEventListener('click', function() {
                                                                            loadSVG("opt-ele");
                                                                        });
                                                                    </script>
</body>

</html>