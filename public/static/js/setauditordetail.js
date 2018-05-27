$('#add').click(() => {
    swal('提示', '是否确认添加？', 'info', {
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
            var checkbox = $("input[name='userlist']:checked");
            var resourcelist = [];
            var list = ','
            checkbox.each(function() {
                resourcelist.push($(this).val());
                list += $(this).val() + ','
            })
            if (list != ',') {
                $.post('/testpaper/public/index.php/admin/setauditordetail/add/', {
                    id: $.cookie('paperid'),
                    auditorlist: list
                }).done((data) => {
                    if (data.status == 1) {
                        swal('成功', '添加成功！', 'success').then((ok) => {
                            self.location = document.referrer;
                        })
                    } else {
                        swal('错误', '添加失败！', 'error')
                    }
                })
            } else {
                swal('错误', '未选择添加的审核员', 'error')
            }
        }
    })
})