var captcha = false
var username_pass = false, password_pass = false, repassword_pass = false;
var alipay_pass = false, phone_pass = false, radio_pass = false;

$(document).ready(function () {
    var password = $("#password");
    var repassword = $("#repassword");
    var username = $("#username");
    var register = $("#register-button");
    var phonenumber = $("#phonenumber");
    var alipay = $("#alipay");
    var back = $("#back-button");
    jigsaw.init(document.getElementById('captcha'), function () {
        captcha = true
    })
    alipay.on("change", function () {
        if (alipay.val().length <= 0) {
            error_item("form-alipay");
            $("#error-alipay").html("支付宝账号不能为空");
            alipay_pass = false;
        } else {
            alipay_pass = true;
            $("#error-alipay").html("");
            pass_item("form-alipay");
        }
    })
    password.on("change", function () {
        if (password.val().length < 6) {
            error_item("form-password");
            $("#error-password").html("密码长度不能小于6位");
            password_pass = false;
        } else {
            pass_item("form-password");
            $("#error-password").html("");
            password_pass = true;
        }
    })

    repassword.on("change", function () {
        if (repassword.val() != password.val()) {
            error_item("form-repassword");
            $("#error-repassword").html("两次输入的密码不一致");
            repassword_pass = false;
        } else {
            pass_item("form-repassword");
            $("#error-repassword").html("");
            repassword_pass = true;
        }
    })

    username.on("change", function () {
        if (username.val().length <= 0) {
            error_item("form-username");
            $("#error-username").html("用户名不能为空");
            username_pass = false;
        } else {
            $.post("/testpaper/public/index.php/api/user/checkrepeatname", {
                new_name: username.val()
            }).done((result) => {
                if (result == false) {
                    error_item("form-username");
                    $("#error-username").html("用户名已重复");
                    username_pass = false;
                } else {
                    pass_item("form-username");
                    $("#error-username").html("");
                    username_pass = true;
                }
            })
        }

    })

    phonenumber.on("change", function () {
        if (phonenumber.val().length <= 0) {
            error_item("form-phonenumber");
            $("#error-phonenumber").html("手机号不能为空");
            phone_pass = false;
        } else {
            $.post("/testpaper/public/index.php/api/user/checkrepeatphone", {
                new_phone: phonenumber.val()
            }).done((result) => {
                if (result == false) {
                    error_item("form-phonenumber");
                    $("#error-phonenumber").html("手机号已重复");
                    phone_pass = false;
                } else {
                    pass_item("form-phonenumber");
                    $("#error-phonenumber").html("");
                    phone_pass = true;
                }
            })
        }

    })

    register.on("click", function () {
        if (!captcha) {
            swal('错误', '请先完成验证识别', 'error');
        }

        if ($("input:radio[name='type']:checked").val() == null) {
            swal("错误", "请选择注册的身份", "error");
            error_item("form-type");
            radio_pass = false;
        } else {
            pass_item("form-type");
            radio_pass = true;
        }


        if (password.val() == '') {
            swal("错误", "密码不能为空", "error");
            error_item("form-password");
            password_pass = false;
        }

        if (repassword.val() == '') {
            swal("错误", "重复密码不能为空", "error");
            error_item("form-repassword");
            repassword_pass = false;
        }

        if (alipay.val() == '') {
            swal("错误", "支付宝账号不能为空", "error");
            error_item("form-alipay")
            alipay_pass = false;
        }

        if (phonenumber.val() == '') {
            swal("错误", "手机号不能为空", "error");
            error_item("form-phonenumber")
            phone_pass = false;
        }

        if (username.val() == '') {
            swal("错误", "用户名不能为空", "error");
            error_item("form-username");
            username_pass = false;
        }

        if (isPass()) {
            $.post("/testpaper/public/index.php/api/user/register", {
                Username: username.val(),
                Password: password.val(),
                PhoneNumber: phonenumber.val(),
                Alipay: alipay.val(),
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
function isPass() {
    if (username_pass && password_pass && repassword_pass && alipay_pass && phone_pass && radio_pass && captcha) return true;
    else return false;
}
function error_item(str) {
    $("#" + str).addClass("has-error");
}

function pass_item(str) {
    $("#" + str).removeClass("has-error");
}