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
$text2.val(editor2.txt.html())

/*function replace(string) {
    string = string.replace(/\n/g, "<br>")
    return string.replace(/\s/g, '&nbsp;')
}*/

function removetitle(number) {
    for (i = 0; i < titlelist.length; i++) {
        if (titlelist[i]["ID"] == number) {
            titlelist.splice(i, 1);
            $("#" + number).remove()
        }
    }
}
$("#quit").on("click", function() {
    $.cookie("userid", "", { expires: -1, path: '/' });
    window.location.href = "/testpaper/public/index.php/index";
});

function updateprogress() {
    $.get("/testpaper/public/index.php/uploader/select/getprogress", {
        belong: $.cookie('belong'),
        belongid: $.cookie('belongid')
    }).done((data) => {
        if (data.status == 1) {
            $('#progress').css('width', data.progress + "%")
            $('#now').text(data.now);
            if (data.progress > 100) {
                swal('完成', '完成本大题录入', 'success').then((ok) => {
                    window.location.href = '/testpaper/public/index.php/uploader/addtestpaper/index/id/' + $.cookie('belong')
                })
            }
        }
    })
}

updateprogress();
$("#next").click(() => {
    $.post('/testpaper/public/index.php/uploader/select/add', {
        belong: $.cookie('belong'),
        belongid: $.cookie('belongid'),
        name: $("textarea[name='name']").val(),
        answerlist: titlelist,
        score: $("input[name='score']").val(),
    }).done((data) => {
        if (data.status == 1) {
            updateprogress()
            $("textarea[name='name']").val("")
            $("input[name='score']").val("")
            editor1.txt.html("")
            titlelist.forEach(element => {
                $("#" + element["ID"]).remove()
            });
            titlelist = [];
            titlenum = 1;
        }
    })
})

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