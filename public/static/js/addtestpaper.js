function commit(id) {
    var title = '是否确认提交？';
    switch ($.cookie('iscomplete')) {
        case "0":
            title = '未知试卷'
            break
        case "-1":
            title = '未完全录入，是否确认？'
            break
        case "-2":
            title = '录入题目分数与总分不符合，是否确认？'
            break
        default:
            title = '是否确认提交？';
    }
    swal('提示', title, 'info', {
        buttons: {
            yes: {
                text: "是",
                value: true,
            },
            no: {
                text: '否',
                value: false,
            }
        },
        closeOnClickOutside: false,
        closeOnEsc: false,
    }).then((ok) => {
        if (ok) {
            $.post('/testpaper/public/index.php/uploader/addtestpaper/commit/id/' + id).done((data) => {
                if (data.status == 1) {
                    swal('成功', '提交成功，等待审核。', 'success').then((ok) => {
                        self.location = document.referrer;
                    })
                } else {
                    swal('错误', '提交失败', 'error')
                }
            })
        }
    })
}
$("#quit").on("click", function() {
    $.cookie("userid", "", { expires: -1, path: '/' });
    window.location.href = "/testpaper/public/index.php/index";
});