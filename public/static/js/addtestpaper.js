function commit(id) {
    swal('提示', '是否确认提交试卷？', 'info', {
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
                    swal('成功', '提交试卷成功，等待审核。', 'success').then((ok) => {
                        self.location = document.referrer;
                    })
                } else {
                    swal('错误', '提交试卷失败', 'error')
                }
            })
        }
    })
}
$("#quit").on("click", function() {
    $.cookie("userid", "", { expires: -1, path: '/' });
    window.location.href = "/testpaper/public/index.php/index";
});