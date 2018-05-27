function confirm(id) {
    swal('提示', '是否确认？', 'info', {
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
                    swal('成功', '审核成功', 'success').then((ok) => {
                        window.location.href = '/testpaper/public/index.php/auditor/audit/'
                    })
                } else {
                    swal('错误', '审核失败', 'error')
                }
            })
        }
    })
}

function cancel(id) {
    swal('提示', '是否确认？', 'info', {
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
                    swal('成功', '退回成功', 'success').then((ok) => {
                        window.location.href = '/testpaper/public/index.php/auditor/audit/'
                    })
                } else {
                    swal('错误', '审核失败', 'error')
                }
            })
        }
    })
}