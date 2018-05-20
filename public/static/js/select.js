var titlenum = 1;
var titlelist = [];

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

function updateprogress() {
    $.get("/testpaper/public/index.php/uploader/select/getprogress", {
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

updateprogress();
$("#next").click(() => {
    $.post('/testpaper/public/index.php/uploader/select/add', {
        belong: $.cookie('belong'),
        belongid: $.cookie('belongid'),
        name: replace($("textarea[name='name']").val()),
        answerlist: titlelist,
        score: $("input[name='score']").val(),
    }).done((data) => {
        if (data.status == 1) {
            updateprogress()
            $("textarea[name='name']").val("")
            $("input[name='score']").val("")
            titlelist.forEach(element => {
                $("#" + element["ID"]).remove()
            });
            titlelist = [];
            titlenum = 1;
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