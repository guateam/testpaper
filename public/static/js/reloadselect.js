var titlenum = 1;
var titlelist = [];

$.post('/testpaper/public/index.php/uploader/reloadselect/getoption/id/' + $.cookie('reloadselectid')).done((data) => {
    if (data.status == 1) {
        titlelist = data.data
        titlenum = data.data.length
        titlelist.forEach(element => {
            if (element['type']) {
                type = "是"
            } else {
                type = '否'
            }
            $("#titlelist").append('<li class="col-md-12" id="' + element['ID'] + '"><span class="col-md-11"><h4>' + element['answer'] + '<small>是否答案：' + type + '</small></h4></span><span class="col-md-1"><button type="button" class="btn btn-link" onclick="removetitle(' + element['ID'] + ')"><span class="glyphicon glyphicon-remove"></span>清除</button></span></li>');
        });
    }
})

function replace(string) {
    string = string.replace(/\n/g, "<br>")
    return string.replace(/\s/g, '&nbsp;')
}

function removetitle(number) {
    for (i = 0; i < titlelist.length; i++) {
        if (titlelist[i]["ID"] == number) {
            titlelist.splice(i, 1);
            $("#" + number).remove()
        }
    }
}
$("#quit").on("click", function() {
    $.cookie("userid", "", { expires: -1, path: '/' });
    window.location.href = "/testpaper/public/index.php/index";
});
$("#next").click(() => {
    $.post('/testpaper/public/index.php/uploader/reloadselect/edit', {
        name: replace($("textarea[name='name']").val()),
        answerlist: titlelist,
        score: $("input[name='score']").val(),
        id: $.cookie('reloadselectid')
    }).done((data) => {
        if (data.status == 1) {
            swal('成功', '修改成功！', 'success').then((ok) => {
                self.location = document.referrer;
            })
        }
    })
})

$('#add').click(() => {
    if ($("textarea[name='answer']").val() != '') {
        titlelist.push({ "ID": titlenum, "answer": replace($("textarea[name='answer']").val()), "type": $("#toggle").bootstrapSwitch('state') });
        if ($("#toggle").bootstrapSwitch('state')) {
            type = "是"
        } else {
            type = '否'
        }
        $("#titlelist").append('<li class="col-md-12" id="' + titlenum + '"><span class="col-md-11"><h4>' + $("textarea[name='answer']").val() + '<small>是否答案：' + type + '</small></h4></span><span class="col-md-1"><button type="button" class="btn btn-link" onclick="removetitle(' + titlenum + ')"><span class="glyphicon glyphicon-remove"></span>清除</button></span></li>');
        titlenum++;
    }
    $("textarea[name='answer']").val("")
    $("#toggle").bootstrapSwitch('state', false)
})