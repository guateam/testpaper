var childnum = 0;

function send_child_number(data) {
    var new_score = 0;
    childnum = data;
    if (childnum > 0) {
        swal("等待","正在更新","info");
        for (i = 0; i < childnum; i++) {
           new_score+=parseInt($("#big-score").val());
        }
        $.post("/testpaper/public/index.php/api/Shortanswer/update", {
            id: $("#big-id").val(),
            name: $("#big-name").val(),
            answer: $("#big-answer").val(),
            score: new_score
        });
        for (i = 0; i < childnum; i++) {
            $.post("/testpaper/public/index.php/api/Shortanswer/update", {
                id: $("#id-" + i).val(),
                name: $("#small-name-" + i).val(),
                answer: $("#small-answer-" + i).val(),
                score: $("#small-score-" + i).val()
            }).done(function(){
            });
        }
    }
    else {
        $.post("/testpaper/public/index.php/api/Shortanswer/update", {
            id: $("#big-id").val(),
            name: $("#big-name").val(),
            answer: $("#big-answer").val(),
            score: $("#big-score").val()
        });
    }
    swal("完成","修改成功","success");
}