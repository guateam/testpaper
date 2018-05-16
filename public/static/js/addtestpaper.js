function commit(id) {
    $.post('/testpaper/public/index.php/uploader/addtestpaper/commit/id/' + id).done((data) => {
        if (data.status == 1) {
            swal('成功', '提交成功，等待审核。', 'success').then((ok) => {
                window.location.href = '/testpaper/public/index.php/uploader/workingproject'
            })
        } else {
            swal('错误', '提交失败', 'error')
        }
    })
}