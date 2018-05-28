function confirm(id) {
    swal('提示', '是否确认通过该份试卷？', 'info', {
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
            $.post('/testpaper/public/index.php/auditor/auditdetail/confirm/id/' + id + '/auditorid/' + $.cookie('userid')).done((data) => {
                if (data.status == 1) {
                    swal('成功', '审核操作成功，该试卷已通过审核', 'success').then((ok) => {
                        window.location.href = '/testpaper/public/index.php/auditor/audit/'
                    })
                } else {
                    swal('错误', '由于未知原因，审核操作失败', 'error')
                }
            })
        }
    })
}

function cancel(id) {
    swal('提示', '是否确认打回该份试卷？', 'info', {
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
            $.post('/testpaper/public/index.php/auditor/auditdetail/cancel/', {
                id: id,
                auditorid: $.cookie('userid'),
                note: $("textarea[name='reason']").val()
            }).done((data) => {
                if (data.status == 1) {
                    swal('成功', '审核操作成功，该份试卷已经被退回', 'success').then((ok) => {
                        window.location.href = '/testpaper/public/index.php/auditor/audit/'
                    })
                } else {
                    swal('错误', '由于未知原因，审核操作失败', 'error')
                }
            })
        }
    })
}