$(document).ready(function(){
    $("#quit").on("click",function(){
        $.cookie("userid", "", { expires: -1 ,path: '/'});
        window.location.href = "/testpaper/public/index.php/index/index";
    });
})

function turn_to_detail(id){
    window.location.href = "/testpaper/public/index.php/uploader/Historypaperdetail/index/id/"+id;
}