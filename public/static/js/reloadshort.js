var childnum = $.cookie("childrennumber");
var childidlist = [];
var editor = null;
var answer_editor = null;
var small_editor = new Array();
var small_answer_editor = new Array();

$(document).ready(()=>{
    initkindEditor();
})

function send_child_number(data, belongpaper) {
    var new_score = 0;
    swal("等待", "正在更新", "info");
    for (i = 0; i < childnum; i++) {
        new_score += parseInt($("#small-score-" + i).val());
    }

    $.post("/testpaper/public/index.php/api/Shortanswer/update", {
        id: getQueryString("id"),
        name: editor.txt.html(),
        answer: childnum > 0 ? "" : answer_editor.txt.html(),
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
                        name: small_editor[i].txt.html() ,//replace($("#small-name-" + i).val()),
                        answer: small_answer_editor[i].txt.html(),//$("#small-answer-" + i).val(),
                        score: $("#small-score-" + i).val()
                    }).done(function() {
                        swal("完成", "该道简答题修改成功", "success").then((ok)=>{
                            if(ok){
                                editor.txt.clear();
                                history.back(-1);
                            }
                        });
                    });
                }
            })
        } else {
            swal("完成", "该道简答题修改成功", "success").then((ok)=>{
                if(ok){
                    editor.txt.clear();
                    self.location = document.referrer;
                }
            });
        }

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
    //string = string.replace(/\n/g, "<br>")
    //return string.replace(/\s/g, '&nbsp;')
    return string;
}

function initkindEditor() {
    var E = window.wangEditor;
    editor = new E("#big-name");
    editor.customConfig.uploadImgShowBase64 = true
    editor.customConfig.zIndex = 1;
    editor.create();

    answer_editor = new E("#big-answer");
    answer_editor.customConfig.uploadImgShowBase64 = true
    answer_editor.customConfig.zIndex = 1;
    answer_editor.create();

    for(i = 0;i<childnum;i++){
        small_editor.push(new E('#small-name-'+i));
        small_editor[i].customConfig.uploadImgShowBase64 = true
        small_editor[i].customConfig.zIndex = 1;
        small_editor[i].create();
        small_answer_editor.push(new E('#small-answer-'+i));
        small_answer_editor[i].customConfig.uploadImgShowBase64 = true
        small_answer_editor[i].customConfig.zIndex = 1;
        small_answer_editor[i].create();
    }

}