/**
 * 催单方法
 * @param {*} id 
 */
function warning(id) {
    $.post('/testpaper/public/index.php/api/log/warningauditor', {
        testpaperid: id,
        from: $.cookie('userid')
    }).done((data) => {
        if (data.status == 1) {
            swal('成功', '催单成功，审核员会尽快审核！', 'success')
            $('#' + id).hide()
        } else {
            swal('失败', '催单失败，请联系管理员汇报bug！', 'error')
        }
    })
}