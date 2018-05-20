function replace(string) {
    string = string.replace(/\n/g, "<br>")
    return string.replace(/\s/g, '&nbsp;')
}
$('#next').click(() => {
    name = replace($("textarea[name='name']").val())
    score = $("input[name='score']").val()
    if (name != '' && score != '') {
        list = name.split('__')
        answer = []
        namelist = []
        for (i = 0; i < list.length; i++) {
            if (i % 2 != 0) {
                answer.push(list[i])
            } else {
                namelist.push(list[i])
            }
        }
        $.post('/testpaper/public/index.php/uploader/reloadfill/edit', {
            id: $.cookie('reloadfillid'),
            name: namelist,
            answer: answer,
            score: score
        }).done((data) => {
            if (data.status == 1) {
                swal('成功', '修改成功！', 'success').then((ok) => {
                    self.location = document.referrer;
                })
            }
        })
    }
})
$("#quit").on("click", function() {
    $.cookie("userid", "", { expires: -1, path: '/' });
    window.location.href = "/testpaper/public/index.php/index";
});