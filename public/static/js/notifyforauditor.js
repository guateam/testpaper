$(document).ready(() => {
    $.notify.addStyle("alert", {
        html: '<div><strong><span data-notify-text/></strong></div>',
        classes: {
            base: {
                "padding": "15px",
                "margin-bottom": "20px",
                "border": "1 px solid transparent",
                "border-radius": "4px",
            },
            success: {
                "color": "#3c763d",
                "background-color": "#dff0d8",
                "border-color": "#d6e9c6"
            },
            warning: {
                "color": "#8a6d3b",
                "background-color": "#fcf8e3",
                "border-color": "#faebcc"
            },
            error: {
                "color": "#a94442",
                "background-color": "#f2dede",
                "border-color": "#ebccd1"
            }
        }
    })
    setInterval(() => {
        $.post('/testpaper/public/index.php/api/log/getalert/id/' + $.cookie('userid')).done((data) => {
            if (data.status == 1) {
                data.data.forEach(element => {
                    $.notify(element.name, {
                        style: 'alert',
                        className: element.style,
                        position: 'right buttom',
                        showDuration: 600
                    })
                });
            }
        })
    }, 20000)

})

function confirmpay(id){
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