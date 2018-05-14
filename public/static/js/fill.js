function updateprogress() {
    $.get("/testpaper/public/index.php/uploader/fill/getprogress", {
        belong: $.cookie('belong'),
        belongid: $.cookie('belongid')
    }).done((data) => {
        if (data.status == 1) {
            $('#progress').css('width', data.progress + "%")
            $('#now').text(data.now);
            if (data.progress > 100) {
                swal('完成', '完成本大题录入', 'success').then((ok) => {
                    window.location.href = '/testpaper/public/index.php/uploader/addtestpaper/index/id/' + $.cookie('belong')
                })
            }
        }
    })
}
updateprogress()
$('#next').click(() => {
    name = $("textarea[name='name']").val()
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
        $.post('/testpaper/public/index.php/uploader/fill/add', {
            belong: $.cookie('belong'),
            belongid: $.cookie('belongid'),
            name: namelist,
            answer: answer,
            score: score
        }).done((data) => {
            if (data.status == 1) {
                updateprogress()
                $("textarea[name='name']").val("")
                $("input[name='score']").val("")
                answer = [];
                namelist = [];
            }
        })
    }
})