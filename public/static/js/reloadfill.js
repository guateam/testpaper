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
        $.post('/testpaper/public/index.php/uploader/reloadfill/edit', {
            id: $.cookie('reloadfillid'),
            name: namelist,
            answer: answer,
            score: score
        }).done((data) => {
            if (data.status == 1) {
                swal('成功', '该道填空题修改成功！', 'success').then((ok) => {
                    self.location = document.referrer;
                })
            }
        })
    }
})
$("#quit").on("click", function() {
    $.cookie("userid", "", { expires: -1, path: '/' });
    window.location.href = "/testpaper/public/index.php/index";
});