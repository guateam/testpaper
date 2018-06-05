


function confirmpayuploader(uid,id){
    swal("确认", "是否确认结算工资？", 'info', {
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
                id: id.split(','),
                uid:uid
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

function confirmpayauditor(uid,id){
    swal("确认", "是否确认结算工资？", 'info', {
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
                id: id.split(','),
                uid:uid
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