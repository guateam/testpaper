$(document).ready(() => {
    /**
     * 添加提示信息css样式
     */
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
    });
    /**
     * 显示提示信息
     */
    $.post('/testpaper/public/index.php/api/log/getalert/id/' + $.cookie('userid')).done((data) => {
        if (data.status == 1) {
            data.data.forEach(element => {
                $.notify(element.name, {
                    style: 'alert',
                    className: element.style,
                    position: 'top center',
                    showDuration: 600
                })
            });
        }
    })


})