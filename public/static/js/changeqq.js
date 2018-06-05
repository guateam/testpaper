/**
 * 修改qq方法
 */
$("#QQbutton").on('click', function() {
    if ($("#QQnumber").val() != '' && $("#QQnumber").val() == $("#QQnumber1").val()) {
        $.post("/testpaper/public/index.php/api/Qq/setQQnumber", {
            qqnumber: $("#QQnumber").val()
        }).done((result) => {
            swal('成功', "成功更新客服QQ号", "success").then((ok) => {
                if (ok) {
                    location.reload();
                }
            });
        })
    } else {
        swal('错误', '请确认两次输入无误！', 'error')
    }
});
/**
 * 修改价格方法
 */
$('#pricebutton').on('click', () => {
    if ($('#uploaderprice').val() != '' && $('#uploaderprice').val() != '' && $('#uploaderprice').val() > 0 && $('#uploaderprice').val() > 0) {
        $.post("/testpaper/public/index.php/api/Defaultprice/setdefaultprice", {
            uploaderprice: $('#uploaderprice').val(),
            auditorprice: $('#auditorprice').val()
        }).done((result) => {
            if (result == 1) {
                swal('成功', '已设定新的试卷价格', 'success').then(() => {
                    location.reload();
                });

            }
        })
    }

});