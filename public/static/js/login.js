$(document).ready(function() {
    var password = $("#password");
    var username = $("#username");
    var login = $("#login-button");
    var register = $("#register-button");

    login.on("click", function() {
        if (password.val() == '') {
            swal("错误", "密码不能为空", "error");
            error_password();
        } else pass_password();
        if (username.val() == '') {
            swal("错误", "用户名不能为空", "error");
            error_username();
        } else pass_username();
        if (password.val() != '' || username.val() != '') {
            $.post("/testpaper/public/index.php/api/User/login", {
                Username: username.val(),
                Password: password.val()
            }).done(function(result) {
                if (result.status == 1) {
                    $.cookie("userid", result.cookie, {
                        path: '/'
                    })
                    if (result.type == 1) {
                        swal("成功", "登录成功!", "success").then((ok) => {
                            window.location.href = "/testpaper/public/index.php/auditor"
                        })
                    } else if (result.type == 0) {
                        swal("成功", "登录成功!", "success").then((ok) => {
                            window.location.href = "/testpaper/public/index.php/uploader"
                        })
                    } else {
                        swal("成功", "登录成功!", "success").then((ok) => {
                            window.location.href = "/testpaper/public/index.php/admin"
                        })
                    }
                } else if (result.status == 0) {
                    swal("错误", "密码错误!", "error");
                    error_password();
                } else if (result.status == -1) {
                    swal("错误", "用户名不存在", "error");
                    error_username();
                }
            });
        }
    })
    register.on('click', function() {
        window.location.href = "/testpaper/public/index.php/index/Register/register";
    })
});

function error_username() {
    $("#form-username").addClass("has-error");
}

function pass_username() {
    $("#form-username").removeClass("hass-error");
}

function error_password() {
    $("#form-password").addClass("has-error");
}

function pass_password() {
    $("#form-password").removeClass("has-error");
}