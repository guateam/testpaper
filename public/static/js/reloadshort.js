var childnum = 0;

function send_child_number(data,belongpaper) {
    var new_score = 0;
    childnum = data;
    swal("等待", "正在更新", "info");
    if (childnum > 0) {
        for (i = 0; i < childnum; i++) {
            new_score += parseInt($("#small-score-" + i).val());
        }
        $.post("/testpaper/public/index.php/api/Shortanswer/update", {
            id: $("#big-id").val(),
            name: $("#big-name").val(),
            answer: "",
            score: new_score
        }).done(function () {
            for (i = 0; i < childnum; i++) {
                $.post("/testpaper/public/index.php/api/Shortanswer/update", {
                    id: $("#id-" + i).val(),
                    name: $("#small-name-" + i).val(),
                    answer: $("#small-answer-" + i).val(),
                    score: $("#small-score-" + i).val()
                }).done(function () {
                });
            }
            swal("完成", "修改成功", "success");
        });

    }
    else {
        $.post("/testpaper/public/index.php/api/Shortanswer/update", {
            id: $("#big-id").val(),
            name: $("#big-name").val(),
            answer: $("#big-answer").val(),
            score: $("#big-score").val()
        }).done(function(){
            swal("完成", "修改成功", "success").then((ok)=>{
                window.location.href = "/testpaper/public/index.php/uploader/overview/index/id/"+belongpaper;
            });
        });
    }

}