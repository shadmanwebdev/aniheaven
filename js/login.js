function login(event) {
    event.preventDefault();

    // Email    
    var emailInp = document.getElementById('email');
    var emailValue = document.getElementById('email').value;
    var emailError = document.getElementById('emailError');

    // Password
    var pwdInp = document.getElementById('password');
    var pwdValue = document.getElementById('password').value;
    var pwdError = document.getElementById('pwdError');

    var loginBtn = document.getElementById('login-btn');
    var form = $('.login-form')[0];
    var formData = new FormData(form);


    if(
        emailValue && emailValue.match(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/) && pwdValue 
        // &&
        // loginBtn.classList.contains('ready') &&
        // loginBtn.classList.contains('pwd-ready')
        
    ) {
        $.ajax({
            url : $('#loginForm').attr('action'),
            type: 'POST', 
            data : formData,
            async: false,
            cache : false,
            contentType: false,
            processData: false,
            success: function(response, textStatus, jqXHR) {
                console.log(response);
                if($.trim(response) == '1') {
                    window.location.href = './admin/index.php';
                    return;
                } else if($.trim(response) == '2') {
                    console.log(response);
                } else {
                    var alert = document.getElementById('msg-alert');
                    alert.classList.add('alert') 
                    alert.innerHTML = "<div class='alert-danger'>That username and/or password appears to be incorrect</div>";
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    } else {
        if(emailValue && emailValue.match(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
            emailError.innerHTML = '';
            if(emailInp.classList.contains('invalid')) {
                emailInp.classList.remove('invalid');
            }
            // if(!loginBtn.classList.contains('ready')) {
            //     loginBtn.classList.add('ready');
            // }
        } else {
            if(!emailInp.classList.contains('invalid')) {
                emailInp.classList.add('invalid');
            }
            if(loginBtn.classList.contains('ready')) {
                loginBtn.classList.remove('ready');
            }
            if(emailValue) {
                emailError.innerHTML = '<div>Please enter a valid email</div>';
            } else {
                emailError.innerHTML = "<div>Email cannot be blank</div>";
            }
        }
        if(pwdValue) {
            pwdError.innerHTML = '';
            if(pwdInp.classList.contains('invalid')) {
                pwdInp.classList.remove('invalid');
            }
            // if(!loginBtn.classList.contains('pwd-ready')) {
            //     loginBtn.classList.add('pwd-ready');
            // }
        } else {
            if(!pwdInp.classList.contains('invalid')) {
                pwdInp.classList.add('invalid');
            }
            // if(loginBtn.classList.contains('pwd-ready')) {
            //     loginBtn.classList.remove('pwd-ready');
            // }
            pwdError.innerHTML = '<div>Password cannot be blank</div>';
        }
    }
}