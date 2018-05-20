var childnum = 0;
var childidlist = [];

function send_child_number(data, belongpaper) {
    var new_score = 0;
    childnum = data;
    swal("等待", "正在更新", "info");
    for (i = 0; i < childnum; i++) {
        new_score += parseInt($("#small-score-" + i).val());
    }

    $.post("/testpaper/public/index.php/api/Shortanswer/update", {
        id: getQueryString("id"),
        name: replace($("#big-name").val()),
        answer: childnum > 0 ? "" : $("#big-answer").val(),
        score: childnum > 0 ? new_score : $("#big-score").val()
    }).done(function() {
        if (childnum > 0) {
            $.post("/testpaper/public/index.php/api/Shortanswer/getchildstring", {
                id: getQueryString("id")
            }).done(function(str) {
                str = str.split(",");
                for (i = 0; i < childnum; i++) {
                    var now_i = i;
                    $.post("/testpaper/public/index.php/api/Shortanswer/update", {
                        id: str[i],
                        name: replace($("#small-name-" + i).val()),
                        answer: $("#small-answer-" + i).val(),
                        score: $("#small-score-" + i).val()
                    }).done(function() {
                        swal("完成", "修改成功", "success");
                    });
                }
            })
        } else swal("完成", "修改成功", "success");
    });

}
//获取url参数
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var reg_rewrite = new RegExp("(^|/)" + name + "/([^/]*)(/|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    var q = window.location.pathname.substr(1).match(reg_rewrite);
    if (r != null) {
        return unescape(r[2]);
    } else if (q != null) {
        return unescape(q[2]);
    } else {
        return null;
    }
}

function replace(string) {
    string = string.replace(/\n/g, "<br>")
    return string.replace(/\s/g, '&nbsp;')
}