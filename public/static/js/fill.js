var E = window.wangEditor
var editor1 = new E('#editor1')
var $text1 = $("textarea[name='name']")
editor1.customConfig.onchange = function(html) {
    // 监控变化，同步更新到 textarea
    $text1.val(html)
}
editor1.customConfig.uploadImgShowBase64 = true
editor1.customConfig.zIndex = 1
editor1.create()
    // 初始化 textarea 的值
$text1.val(editor1.txt.html())
$("#addunderline").click(() => {
    editor1.cmd.do('insertHTML', '__');
})

function updateprogress() {
    $.get("/testpaper/public/index.php/uploader/fill/getprogress", {
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
updateprogress()
$('#next').click(() => {
    name = $("textarea[name='name']").val()
    score = $("input[name='score']").val()
    if (name != '' && score != '') {
        list = name.split('__')
        answer = []
        namelist = []
        for (i = 0; i < list.length; i++) {
            if (i % 2 != 0) {
                answer.push(list[i])
            } else {
                namelist.push(list[i])
            }
        }
        $.post('/testpaper/public/index.php/uploader/fill/add', {
            belong: $.cookie('belong'),
            belongid: $.cookie('belongid'),
            name: namelist,
            answer: answer,
            score: score
        }).done((data) => {
            if (data.status == 1) {
                updateprogress()
                $("textarea[name='name']").val("")
                $("input[name='score']").val("")
                editor1.txt.html("")
                answer = [];
                namelist = [];
            }
        })
    }
})
$("#quit").on("click", function() {
    $.cookie("userid", "", { expires: -1, path: '/' });
    window.location.href = "/testpaper/public/index.php/index";
});