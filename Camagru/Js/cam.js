// Grab elements, create settings, etc.
var video = document.getElementById('video');
var canvasdata;
var sym = 0;
var imgSrc;
if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    // Not adding `{ audio: true }` since we only want video now
    navigator.mediaDevices.getUserMedia({ video: true }).then(function (stream) {
        if ("srcObject" in video)
            video.srcObject = stream;
        else
            video.src = window.URL.createObjectURL(stream);
        video.onloadedmetadata = function (e) {
            video.play();
        };
    }).catch(function (e) {
        alert("Please make sure your camera is properly working or give access in order to capture pictures!");
    });
}
// Elements for taking the snapshot
var canvas = document.getElementById('canvas');
var context = canvas.getContext('2d');
// Trigger photo take
document.getElementById("snap").addEventListener("click", function () {
    context.drawImage(video, 0, 0, 640, 480);
    sym = 1;
    canvasdata = canvas.toDataURL('image/jpeg');
});
function handle_upload(e) {
    var dataToSend;
    var extension = '0';
    var willRedirect = true;
    var comp;
    if ("srcElement" in e) comp = e.srcElement;
    else comp = e.target;
    if (comp.name == "upload_snap") {
        if (canvasdata) {
            dataToSend = canvasdata;
            willRedirect = false;
        }
        else {
            alert("Please take a picture first!");
            sym = 0;
        }
    }
    else if (comp.name == "upload_file") {
        dataToSend = imgSrc;
        var fileObj = document.getElementById("file");
        if (fileObj.files.length > 0) extension = fileObj.files[0].name.match(/[^.]+$/g);
        else alert("Please upload a file first");
    }
    e1 = 0;
    if (document.getElementById('sticker'))
        e1 = document.getElementById('sticker').className;
    var params = `imgBase64=${dataToSend}&sticker=${e1}&extension=${extension}`;
    var xhttp = new XMLHttpRequest();
    xhttp.open('POST', '/camera.php');
    xhttp.withCredentials = true;
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            //console.log(this.responseText);
        }
    }
    if (sym == 1) {
        xhttp.send(params);
        if (willRedirect) {
            window.location.href = "/";
        }
        else location.reload();
    }
}

var uploadButtons = [document.getElementById("up_file-1"), document.getElementById("up_file-2")];
uploadButtons.forEach(function (el) {
    el.addEventListener('click', handle_upload);
})
var imageLoader = document.getElementById('file');
imageLoader.addEventListener('change', handleImage, false);
function handleImage(e) {
    var reader = new FileReader();
    var flag = false;
    reader.onload = function (event) {
        var img = new Image();
        img.onload = function () {
            canvas.width = 640;
            canvas.height = 480;
            context.drawImage(img, 0, 0, 640, 480);
        }
        if (flag) {
            if (event.target.result.length > 7) {
                img.src = event.target.result;
                imgSrc = event.target.result;
                sym = 1;
            }
            else
                uploadError();
        }
    }
    if (e.target.files[0] && isImage(e.target.files[0])) {
        flag = true;
        reader.readAsDataURL(e.target.files[0]);
    }
    else
        uploadError();
}
function isImage(file) {
    if ("type" in file) {
        var regex = /image\/(jpeg|jpg|png)/g;
        if (file.type.match(regex))
            return true;
    }
    return false;
}
function uploadError() {
    document.getElementById("file").value = "";
    alert("Please insert a valid image!");
}