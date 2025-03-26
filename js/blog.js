function addPost(event) {
    event.preventDefault();
    var form = $('.add-post-form')[0];
    var formData = new FormData(form);
    
    $.ajax({
        url : '../controllers/post-handler',
        type: 'POST', 
        data : formData,
        async: false,
        cache : false,
        contentType: false,
        processData: false,
        success: function(response, textStatus, jqXHR) {
            console.log($.trim(response));
            if($.trim(response) == '1') {
                var alert = document.getElementById('msg-response');
                alert.classList.add('alert') 
                alert.innerHTML = "<div class='success'>New Post Created.</div>";
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
}
function updatePost(event) {
    event.preventDefault();
    var form = $('.edit-post-form')[0];
    var formData = new FormData(form);
    $.ajax({
        url : '../controllers/post-handler',
        type: 'POST', 
        data : formData,
        async: false,
        cache : false,
        contentType: false,
        processData: false,
        success: function(response, textStatus, jqXHR) {
            console.log($.trim(response));
            if($.trim(response) == '1') {
                var alert = document.getElementById('msg-response');
                alert.classList.add('alert') 
                alert.innerHTML = "<div class='success'>Post updated.</div>";
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
}

function readmore(id) {
    var rms = document.querySelectorAll('[id="'+id+'"]> span');

    var postContent = document.getElementById('post-full-'+id);
    var postBrief = document.getElementById('post-brief-'+id);

    if(postContent.classList.contains('show')) {
        postContent.classList.remove('show');
        postBrief.classList.add('show');
        rms.forEach(function(elm) {
            elm.textContent = 'Read More';
        });
        return;
    } else {
        postContent.classList.add('show');
        postBrief.classList.remove('show');
        rms.forEach(function(elm) {
            elm.textContent = 'Read Less';
        });
        return;
    }
}