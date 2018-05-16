$(document).ready(function(){
    page_init();
    $("#quit").on("click",function(){
        $.cookie("userid", "", { expires: -1 ,path: '/'});
        window.location.href = "/testpaper/public/index.php/index";

    });
})

//改变导航栏元素的选中情况
function change_nav(str){
    ids = ["#paper","#settings"];
    for(i = 0;i<ids.length;i++)
    {
        if(str == ids[i])   
        {
            //-nav 表示 str相关的导航栏
            //-page 表示 str相关的界面显示
            $(str+"-nav").addClass('active');
            $(str+"-page").show();
        }
        else 
        {
            $(ids[i] +"-nav").removeClass('active');
            $(ids[i] + "-page").hide();
        }
    }
}

//页面初始化
function page_init(){
    $("#settings-page").hide();
}