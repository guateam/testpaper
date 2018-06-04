function confirmpayuploader(id,uid){
    swal("确认", "是否确实收到了该试卷的结算？", 'info', {
        buttons: {
            yes: {
                text: "是",
                value: true,
            },
            cancel:"否"
        },
        closeOnClickOutside: false,
        closeOnEsc: false,
    }).then((ok)=>{
        if(ok){
            $.post("/testpaper/public/index.php/api/Testpaper/confirmpaying",{
                id: id
            }).done((result)=>{
                if(result == 1){
                    swal("完成",'已经确认收款','success').then((ok)=>{
                        location.reload();
                    });
                }
            })
        }
    })
}

function confirmpayauditor(id,uid){
    swal("确认", "是否确实收到了该试卷的结算？", 'info', {
        buttons: {
            yes: {
                text: "是",
                value: true,
            },
            cancel:"否"
        },
        closeOnClickOutside: false,
        closeOnEsc: false,
    }).then((ok)=>{
        if(ok){
            $.post("/testpaper/public/index.php/api/Testpaper/auditorconfirmpaying",{
                id: id
            }).done((result)=>{
                if(result == 1){
                    swal("完成",'已经确认收款','success').then((ok)=>{
                        location.reload();
                    });
                }
            })
        }
    })
}