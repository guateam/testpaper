$(document).ready(function () {
    var password = $("#password");
    var repassword = $("#repassword");
    var username = $("#username");
    var register = $("#register-button");
    var phonenumber = $("#phonenumber");
    var alipay =  $("#alipay");
    var back = $("#back-button");
    var captcha = false
    jigsaw.init(document.getElementById('captcha'), function () {
        captcha = true
    })
    register.on("click", function () {

        if (!captcha) {
            swal('错误', '请先完成验证识别', 'error');
        }

        if ($("input:radio[name='type']:checked").val() == null) {
            swal("错误", "请选择注册的身份", "error");
            error_item("form-type");
        } else pass_item("form-type");


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
        if (alipay.val() == '') {
            swal("错误", "支付宝账号不能为空", "error");
            error_item("form-alipay")
        } else {
            pass_item("form-alipay");
        }

        if (phonenumber.val() == '') {
            swal("错误", "手机号不能为空", "error");
            error_item("form-phonenumber")
        } else {
            pass_item("form-phonenumber");
        }
        
        if (username.val() == '') {
            swal("错误", "用户名不能为空", "error");
            error_item("form-username");
        } else pass_item("form-username");

        if (captcha && password.val() != '' && username.val() != '' && alipay.val() != '' && phonenumber.val() != '' && $("input[name='type']:checked").val() != '') {
            $.post("/testpaper/public/index.php/api/user/register", {
                Username: username.val(),
                Password: password.val(),
                PhoneNumber: phonenumber.val(),
                Alipay:alipay.val(),
                Type: $("input[name='type']:checked").val(),
                Num: 0
            }).done(function (result) {
                if (result.status == 1) {
                    swal("成功", "注册成功!", "success").then((ok) => {
                        if (ok) {
                            window.location.href = "/testpaper/public/index.php/index/Index/index";
                        }
                    })
                } else if (result.status == -1) {
                    swal("注册失败", "该用户名或手机号码已经被注册", "error");
                } else {
                    swal("注册失败", "未知原因", "error");
                }
            });
        }
    })
    back.on("click", function () {
        window.location.href = "/testpaper/public/index.php/index/Index/index";
    })
});

function error_item(str) {
    $("#" + str).addClass("has-error");
}

function pass_item(str) {
    $("#" + str).removeClass("has-error");
}