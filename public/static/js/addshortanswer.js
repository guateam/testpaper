var switch_button = $("#switch-button");
//保存小题信息的数组
var small = Array();
$(document).ready(function () {
    $("input:radio[name='type']").on("click", function () {
        var value = $("input:radio[name='type']:checked").val()
        switch_button.attr("href", "#" + which_case(value));
    });
    $('#confirm-answer').on('click',function(){
        $("#new-ans").hide();
        var txt = "<p>答案："+$('textarea[id="answer"]').val()+"</p>"
        $('#flag').append(txt);
    })
    $("#confirm-small").on("click", function () {
        if (!small_is_empty()) {
            $.post("/testpaper/public/index.php/uploader/shortanswer/add", {
                belong: $.cookie('belong'),
                belongid: $.cookie('belongid'),
                name: $('#small-title').val(),
                ans:$('#small-answer').val(),
                score: $("input[name='score']").val(),
            })
        }
    })
});

function which_case(cs) {
    if (cs == 0) 
    {
        return "#add-answer";
    }
    else return "#add-small";
}

function small_is_empty() {
    if ($('#small-answer').val() == '' || $('#small-title').val() == '') return false;
    else {
        return true;
    }
}