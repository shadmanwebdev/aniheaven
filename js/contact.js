function validateContact(event) {
    event.preventDefault();
    var form = $('.contact-form')[0];
    var formData = new FormData(form);
    // Name
    var fnameValue = document.getElementById('fname').value;
    var fnameError = document.getElementById('fnameError');
    // Surname
    var lnameValue = document.getElementById('lname').value;
    var lnameError = document.getElementById('lnameError');
    // Email
    var emailValue = document.getElementById('email').value;
    var emailError = document.getElementById('emailError');
    // Message
    var msgValue = document.getElementById('msg').value;
    var msgError = document.getElementById('msgError');



    // console.log(emailValue, fnameValue, lnameValue);

    if(emailValue && emailValue.match(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/) && fnameValue && lnameValue) {
        emailError.innerHTML = '';
        fnameError.innerHTML = '';
        lnameError.innerHTML = '';
        $.ajax({
            url : $('#contactForm').attr('action'),
            type: 'POST', 
            data : formData,
            async: false,
            cache : false,
            contentType: false,
            processData: false,
            success: function(response, textStatus, jqXHR) {
                console.log(response);
                if($.trim(response) == '1') {
                    var alert = document.getElementById('msg-response');
                    alert.classList.add('alert') 
                    alert.innerHTML = "<div class='success'>Your message has been sent!</div>";
                } else {
                    var alert = document.getElementById('msg-response');
                    alert.classList.add('alert') 
                    alert.innerHTML = "<div class='error'>There was an error.</div>";
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
        } else {
            if(!emailValue) {
                event.preventDefault();
                emailError.innerHTML = '<div>* Required</div>';
            } else {
                event.preventDefault();
                emailError.innerHTML = '<div>Please enter a valid email</div>';
            }
        }

        if(fnameValue) {
            fnameError.innerHTML = '';
        } else {
            event.preventDefault();
            fnameError.innerHTML = '<div>* Required</div>';
        }
        if(lnameValue) {
            lnameError.innerHTML = '';
        } else {
            event.preventDefault();
            lnameError.innerHTML = '<div>* Required</div>';
        }
    }
}