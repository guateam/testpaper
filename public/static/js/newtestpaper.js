var titlenum = 1;
var titlelist = [];
var input_id_group = ["name", "subject", "institute", "school", "score"];

//检测是否添加了大题目
var small_is_add = false;

//删除已经添加的大题
function removetitle(number) {
    if (titlelist.length == 1) small_is_add = false;
    for (i = 0; i < titlelist.length; i++) {
        if (titlelist[i]["ID"] == number) {
            titlelist.splice(i, 1);
            $("#" + number).remove()
        }
    }
}
$("#new-question").on('click', () => {
    if (check_base_fill() == 1) {
        $.post("/testpaper/public/index.php/uploader/newtestpaper/check_paper_reupload", {
            name: $("#name").val(),
            class: $("#subject").val(),
            subject: $("#institute").val(),
            school: $("#school").val(),
        }).done((data)=>{
            if(data == 0){
                swal("错误", "相同信息的试卷已经存在，请重新输入", 'error',{
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                }).then((ok)=>{
                    if(ok){
                        location.reload();
                    }
                });
            }
        })
    }else{
        swal("错误", "试卷基础信息还未填写完毕，请先填写试卷基础信息", 'error');
    }

})

$("#quit").on("click", function () {
    $.cookie("userid", "", { expires: -1, path: '/' });
    window.location.href = "/testpaper/public/index.php/index";
});
$('#add').click(function () {
    name = $("input[name='titlename']").val();
    type = $("#titletype").val();
    number = $("input[name='titlenumber']").val()
    if (name != "" && number != "" && check_base_fill()) {
        titlelist.push({ "ID": titlenum, "name": name, "type": type, "number": number });
        $("#titlelist").append('<li class="col-md-12" id="' + titlenum + '"><span class="col-md-11"><h4>' + name + '<small>' + type + ' 共' + number + '题</small></h4></span><span class="col-md-1"><button type="button" class="btn btn-link" onclick="removetitle(' + titlenum + ')"><span class="glyphicon glyphicon-remove"></span>清除</button></span></li>');
        titlenum++;
        small_is_add = true;
    } else {

    }
    $("input[name='titlename']").val("")
    $("#titletype").val("选择题")
    $("input[name='titlenumber']").val("")
})

$('#send').click(function () {
    var state = check_all_fill()
    if (state == -1) swal('添加试卷失败', '请添加至少一道大题', 'error');
    else if (state == 0) swal('添加试卷失败', '请填写完整试卷信息', 'error');
    else {
        $.post("/testpaper/public/index.php/uploader/newtestpaper/add", {
            name: $("#name").val(),
            class: $("#subject").val(),
            subject: $("#institute").val(),
            school: $("#school").val(),
            uploader: $.cookie("userid"),
            headquestion: titlelist,
            score: $("#score").val()
        }).done(function (data) {
            if (data.status == 1) {
                swal('成功', '试卷新建成功，请继续完善该份试卷！', 'success').then((ok) => {
                    window.location.href = '/testpaper/public/index.php/uploader/addtestpaper/index/id/' + data.id
                })
            } else if (data.status == -1) {
                swal("错误", "相同信息的试卷已经存在", 'error');
            }
        })
    }
})
//检测所有元素是否被填写
function check_all_fill() {
    fill_complete = true;
    for (i = 0; i < input_id_group.length; i++) {
        if ($("#" + input_id_group[i]).val() == '') {
            error_item(input_id_group[i]);
            fill_complete = false;
        } else pass_item(input_id_group[i]);
    }

    if (fill_complete) {
        if (!small_is_add) {
            fill_complete = false;
            return -1;
        } else {
            return 1;
        }

    }
    else return 0;
}
//检测基础试卷信息是否填写完整
function check_base_fill(){
    fill_complete = true;
    for (i = 0; i < input_id_group.length; i++) {
        if ($("#" + input_id_group[i]).val() == '') {
            error_item(input_id_group[i]);
            fill_complete = false;
        } else pass_item(input_id_group[i]);
    }
    if (fill_complete) {
        return 1;
    }else return 0;
}
function error_item(str) {
    $("#form-" + str).addClass("has-error");
}

function pass_item(str) {
    $("#form-" + str).removeClass("has-error");
}