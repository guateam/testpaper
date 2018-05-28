var titlenum = 1;
var titlelist = [];
var input_id_group = ["name", "subject", "institute", "school"];

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
$('#add').click(function() {
    name = $("input[name='titlename']").val();
    type = $("#titletype").val();
    number = $("input[name='titlenumber']").val()
    if (name != "" && number != "") {
        titlelist.push({ "ID": titlenum, "name": name, "type": type, "number": number });
        $("#titlelist").append('<li class="col-md-12" id="' + titlenum + '"><span class="col-md-11"><h4>' + name + '<small>' + type + ' 共' + number + '题</small></h4></span><span class="col-md-1"><button type="button" class="btn btn-link" onclick="removetitle(' + titlenum + ')"><span class="glyphicon glyphicon-remove"></span>清除</button></span></li>');
        titlenum++;
    } else {

    }
    $("input[name='titlename']").val("")
    $("#titletype").val("选择题")
    $("input[name='titlenumber']").val("")
})
$('#send').click(function() {
    fill_complete = true;
    for (i = 0; i < input_id_group.length; i++) {
        if ($("#" + input_id_group[i]).val() == '') {
            error_item(input_id_group[i]);
            fill_complete = false;
        } else pass_item(input_id_group[i]);
    }
    if (fill_complete) {
        $.post("/testpaper/public/index.php/uploader/newtestpaper/add", {
            name: $("#name").val(),
            class: $("#subject").val(),
            subject: $("#institute").val(),
            school: $("#school").val(),
            uploader: $.cookie("userid"),
            headquestion: titlelist,
            score: $("#score").val()
        }).done(function(data) {
            if (data.status == 1) {
                swal('成功', '添加成功！', 'success').then((ok) => {
                    window.location.href = '/testpaper/public/index.php/uploader/addtestpaper/index/id/' + data.id
                })
            } else if (data.status == -1) {
                swal("错误", "试卷已经存在", 'error');
            }
        })
    }

})

function error_item(str) {
    $("#form-" + str).addClass("has-error");
}

function pass_item(str) {
    $("#form-" + str).removeClass("has-error");
}