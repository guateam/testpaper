$(document).ready(function() {
    /**
     * 退出方法
     */
    $("#quit").on("click", function() {
        $.cookie("userid", "", { expires: -1, path: '/' });
        window.location.href = "/testpaper/public/index.php/index/index";
    });
});
/**
 * 页面跳转方法
 */
function turn_to_detail(id) {
    window.location.href = "/testpaper/public/index.php/uploader/Historypaperdetail/index/id/" + id;
}