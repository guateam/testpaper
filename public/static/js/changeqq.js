$("#QQbutton").on('click', function () {
    if ($("#QQnumber").val() != '') {
        $.post("/testpaper/public/index.php/api/Qq/setQQnumber", {
            qqnumber: $("#QQnumber").val()
        }).done((result) => {
            swal('成功', "成功更新客服QQ号", "success").then((ok) => {
                if (ok) {
                    location.reload();
                }
            });
        })
    }
})

$('#pricebutton').on('click', () => {
    if ($('#uploaderprice').val() != '' && $('#uploaderprice').val() != '') {
        $.post("/testpaper/public/index.php/api/Defaultprice/setdefaultprice", {
            uploaderprice: $('#uploaderprice').val(),
            auditorprice: $('#auditorprice').val()
        }).done((result)=>{
            if(result == 1)
            {
                swal('成功','已设定新的试卷价格','success').then(()=>{
                    location.reload();
                });

            }
        })
    }

})