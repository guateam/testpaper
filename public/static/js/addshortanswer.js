var switch_button = $("#switch-button");
//保存小题信息的数组
var Mysmall = new Array();
//保存上传后返回的小题的id
var small_id = new Array();
//大题的分值
var total_score = 0;
//是否是单一大题
var single = true;
//cookie相关
var belong = $.cookie('belong');
var belongid = $.cookie('belongid');
//目前录了几题了
//初始化函数会初始化该变量
var now_num = 1;
//总共多少题目
var max_num = 0;
var editor = null;


$(document).ready(function () {
    init();

    $("input:radio[name='type']").on("click", function() {
        var value = $("input:radio[name='type']:checked").val()
        switch_button.attr("href", "#" + which_case(value));
    });
    $('#confirm-answer').on('click', function() {
        $("#new-ans").hide()
        var txt = "<p>答案：" + $('textarea[id="answer"]').val() + "</p>"
        $('#flag').append(txt);
    })
    $("#confirm-small").on("click", function() {
        if (!small_is_empty()) {
            var tp = new Array();
            //存入
            Mysmall.push({
                "belong": $.cookie('belong'),
                "belongid": $.cookie('belongid'),
                "name": $('#small-title').val(),
                "answer": $('#small-answer').val(),
                "score": $("input[name='small-score']").val()
            });
            total_score += parseInt($("input[name='small-score']").val());
            var txt = "<p>小题：" + $('#small-title').val() + "  分值:" + $("input[name='small-score']").val() + "分</p>"
            $('#flag').html($('#flag').html() + txt);
            //清空上次输入的内容
            $('#small-title').val("");
            $('#small-answer').val("");

            single = false;
            $("#single").hide();
        }
    })
    $("#show-text").on("click",function(){
        alert($("#name").val());
    })
    $("#send").on("click", function() {
        if (single) {
            $.post("/testpaper/public/index.php/api/shortanswer/add", {
                belong: belong,
                belongid: belongid,
                name: replace($("#name").val()),
                ans: $("#answer").val(),
                score: $("input[name='score']").val(),
                child: 0,
            }).done(function(result) {
                if (result == 0) swal("错误", "添加失败", "error");
                else {
                    now_num = now_num + 1;
                    $("#bar").css("width", (now_num / max_num) * 100 + "%")
                    if (now_num > max_num) {
                        swal("完成", "已完成本大题的录入", "success", {
                            closeOnClickOutside: false,
                            closeOnEsc: false,
                        }).then((ok) => {
                            if (ok) {
                                window.location.href = '/testpaper/public/index.php/uploader/addtestpaper/index/id/' + belong;
                            }
                        })
                    } else $('#now-num').html(now_num);
                    allClear();
                }
            })
        } else {
            $.post("/testpaper/public/index.php/api/shortanswer/addchild", {
                belong: belong,
                belongid: belongid,
                smallData: Mysmall,
            }).done(function(ids) {
                for (i = 0; i < ids.length; i++) small_id.push(ids[i]);
                $.post("/testpaper/public/index.php/api/shortanswer/add", {
                    belong: belong,
                    belongid: belongid,
                    name: replace($("#name").val()),
                    ans: $("#answer").val(),
                    score: total_score,
                    child: small_id.join(","),
                }).done(function(result) {
                    if (result == 0) swal("错误", "添加失败", "error");
                    else {
                        now_num++;
                        $("#bar").css("width", (now_num / max_num) * 100 + "%")
                        if (now_num > max_num) {
                            swal("完成", "已完成本大题的录入", "success", {
                                closeOnClickOutside: false,
                                closeOnEsc: false,
                            }).then((ok) => {
                                if (ok) {
                                    window.location.href = '/testpaper/public/index.php/uploader/addtestpaper/index/id/' + belong;
                                }
                            })
                        } else $('#now-num').html(now_num);
                    }
                    allClear();
                    editor.html('');
                })
            })
        }
    })
   
});

function which_case(cs) {
    if (cs == 0) {
        return "#add-answer";
    } else return "#add-small";
}

function small_is_empty() {
    if ($('#small-answer').val() == '' || $('#small-title').val() == '') return true;
    else {
        return false;
    }
}

function replace(string) {
    //string = string.replace(/\n/g, "<br>")
    //return string.replace(/\s/g, '&nbsp;')
    return string
}

function init() {
    swal("等待", "正在初始化页面", "info", {
        button: false,
        closeOnClickOutside: false,
        closeOnEsc: false,
    });
    initkindEditor();
    $.post("/testpaper/public/index.php/api/Testpaper/getTitle", {
        id: belong,
        belongid: belongid
    }).done(function(result) {
        max_num = result["number"]
        $.post("/testpaper/public/index.php/api/shortanswer/count", {
            belong: belong,
            belongid: belongid
        }).done(function(num) {
            now_num = num;
            if (now_num == max_num) {
                swal("完成", "已完成本大题的录入", "success", {
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                }).then((ok) => {
                    if (ok) {
                        window.location.href = '/testpaper/public/index.php/uploader/addtestpaper/index/id/' + belong;
                    }
                })
            } else {
                now_num++;
                $("#bar").css("width", now_num / max_num * 100 + "%");
                swal.close();
            }
            $('#now-num').html(now_num);
        });

    })
}
function initFileInput(ctrlName, uploadUrl) {    
    var control = $('#' + ctrlName); 

    control.fileinput({
        language: 'zh', //设置语言
        uploadUrl: uploadUrl, //上传的地址
        allowedFileExtensions : ['jpg', 'png','gif'],//接收的文件后缀
        showUpload: false, //是否显示上传按钮
        showCaption: false,//是否显示标题
        browseClass: "btn btn-primary", //按钮样式             
        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>", 
    });
}

function allClear(){
    single = true;
    $("#single").show();
    $('#flag').html("");
    $("#new-ans").show();
    $('#small-title').val("");
    $('#small-answer').val("");
    $('#answer').val("");
    $('#name').val("");
}

function initkindEditor() {
    
         editor = KindEditor.create('#name', {
            themeType: "simple",
            uploadJson: '/testpaper/public/static/vendor/kindeditor/php/upload_json.php',
            resizeType: 1,
            pasteType: 2,
            syncType: "",
            filterMode: true,
            allowPreviewEmoticons: false,
            items: [
                   'source', 'undo', 'redo', 'plainpaste', 'wordpaste', 'clearhtml', 'quickformat',
                   'selectall', 'fullscreen', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor',
                   'bold', 'italic', 'underline', 'hr', 'removeformat', '|', 'justifyleft', 'justifycenter',
                   'justifyright', 'insertorderedlist', 'insertunorderedlist', '|', 'link', 'image',
                   'unlink', 'emoticons'
               ],//, 'baidumap'
            afterCreate: function () {
                this.sync();
            },
            afterBlur: function () {
                this.sync();
            },
            afterChange: function () {
               //富文本输入区域的改变事件，一般用来编写统计字数等判断
            },
            afterUpload:function(url){
              //上传图片后的代码
            },
            allowFileManager: false,
            allowFlashUpload: false,
            allowMediaUpload: false,
            allowFileUpload: false
        });
}