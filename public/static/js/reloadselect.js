/**
 * 初始化富文本
 */
var titlenum = 1;
var titlelist = [];
var E = window.wangEditor
var editor1 = new E('#editor1')
var $text1 = $("#name")
editor1.customConfig.onchange = function(html) {
    // 监控变化，同步更新到 textarea
    $text1.val(html)
}
editor1.customConfig.uploadImgShowBase64 = true
editor1.customConfig.zIndex = 1
editor1.create()
    // 初始化 textarea 的值
$text1.val(editor1.txt.html())
var editor2 = new E('#editor2')
var $text2 = $("#answer")
editor2.customConfig.onchange = function(html) {
    // 监控变化，同步更新到 textarea
    $text2.val(html)
}
editor2.customConfig.uploadImgShowBase64 = true
editor2.customConfig.menus = [
    'head', // 标题
    'bold', // 粗体
    'fontSize', // 字号
    'fontName', // 字体
    'italic', // 斜体
    'underline', // 下划线
    'strikeThrough', // 删除线
    'foreColor', // 文字颜色
    'link', // 插入链接
    'quote', // 引用
    'image', // 插入图片
    'table', // 表格
    'video', // 插入视频
    'code', // 插入代码
]
editor2.create()
    // 初始化 textarea 的值
$text2.val(editor2.txt.html());
/**
 * 获取选项信息
 */
$.post('/testpaper/public/index.php/uploader/reloadselect/getoption/id/' + $.cookie('reloadselectid')).done((data) => {
    if (data.status == 1) {
        titlelist = data.data
        titlenum = data.data.length
        titlelist.forEach(element => {
            if (element['type']) {
                type = "是"
            } else {
                type = '否'
            }
            $("#titlelist").append('<li class="col-md-12" id="' + element['ID'] + '"><span class="col-md-11"><h4>' + element['answer'] + '<small>是否答案：' + type + '</small></h4></span><span class="col-md-1"><button type="button" class="btn btn-link" onclick="removetitle(' + element['ID'] + ')"><span class="glyphicon glyphicon-remove"></span>清除</button></span></li>');
        });
    }
});
/**
 * 移除选项方法
 * @param {*} number 
 */
function removetitle(number) {
    for (i = 0; i < titlelist.length; i++) {
        if (titlelist[i]["ID"] == number) {
            titlelist.splice(i, 1);
            $("#" + number).remove()
        }
    }
}
/**
 * 退出方法
 * 弃用
 */
$("#quit").on("click", function() {
    $.cookie("userid", "", { expires: -1, path: '/' });
    window.location.href = "/testpaper/public/index.php/index";
});
/**
 * 更新编辑的选择题
 */
$("#next").click(() => {
    $.post('/testpaper/public/index.php/uploader/reloadselect/edit', {
        name: $("textarea[name='name']").val(),
        answerlist: titlelist,
        score: $("input[name='score']").val(),
        id: $.cookie('reloadselectid')
    }).done((data) => {
        if (data.status == 1) {
            swal('成功', '该道选择题修改成功！', 'success').then((ok) => {
                self.location = document.referrer;
            })
        }
    })
});
/**
 * 添加新的选项
 */
$('#add').click(() => {
    if ($("textarea[name='answer']").val() != '') {
        titlelist.push({ "ID": titlenum, "answer": $("textarea[name='answer']").val(), "type": $("#toggle").bootstrapSwitch('state') });
        if ($("#toggle").bootstrapSwitch('state')) {
            type = "是"
        } else {
            type = '否'
        }
        $("#titlelist").append('<li class="col-md-12" id="' + titlenum + '"><span class="col-md-11"><h4>' + $("textarea[name='answer']").val() + '<small>是否答案：' + type + '</small></h4></span><span class="col-md-1"><button type="button" class="btn btn-link" onclick="removetitle(' + titlenum + ')"><span class="glyphicon glyphicon-remove"></span>清除</button></span></li>');
        titlenum++;
    }
    $("textarea[name='answer']").val("")
    editor2.txt.html("")
    $("#toggle").bootstrapSwitch('state', false)
})