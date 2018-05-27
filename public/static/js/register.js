$(document).ready(function() {
    var password = $("#password");
    var repassword = $("#repassword");
    var username = $("#username");
    var register = $("#register-button");
    var phonenumber = $("#phonenumber");
    var back = $("#back-button");
    register.on("click", function() {
        if (username.val() == '') {
            swal("错误", "用户名不能为空", "error");
            error_item("form-username");
        } else pass_item("form-username");

        if (password.val() == '') {
            swal("错误", "密码不能为空", "error");
            error_item("form-password");
        } else {
            pass_item("form-password");
            if (repassword.val() != password.val()) {
                swal("错误", "两次输入的密码不一致", "error");
                error_item("form-repassword")
            } else pass_item("form-repassword");
        }
        if(phonenumber.val()==''){
            swal("错误", "手机号不能为空", "error");
                error_item("form-phonenumber")
        }else{
            pass_item("form-phonenumber");
        }

        if ($("input:radio[name='type']:checked").val() == null) {
            swal("错误", "请选择注册的身份", "error");
            error_item("form-type");
        } else pass_item("form-type");
        if (password.val() != '' && username.val() != '' && phonenumber.val()!='' && $("input[name='type']:checked").val() != '') {
            $.post("/testpaper/public/index.php/api/user/register", {
                Username: username.val(),
                Password: password.val(),
                PhoneNumber:phonenumber.val(),
                Type: $("input[name='type']:checked").val(),
                Num: 0
            }).done(function(result) {
                if (result.status == 1) {
                    swal("成功", "注册成功!", "success").then((ok) => {
                        if (ok) {
                            window.location.href = "/testpaper/public/index.php/index/Index/index";
                        }
                    })
                } else {
                    swal("失败", "注册失败!", "error");
                }
            });
        }
    })
    back.on("click", function() {
        window.location.href = "/testpaper/public/index.php/index/Index/index";
    })
});

function error_item(str) {
    $("#" + str).addClass("has-error");
}

function pass_item(str) {
    $("#" + str).removeClass("has-error");
}