var admNavBtn = document.getElementById('nav-btn-admin');
var mobList  = document.getElementById('mobList');
var bgOverlay  = document.getElementById('bgOverlay');


var admSpansAll = document.querySelectorAll('#nav-btn-admin > div');
console.log(admSpansAll);

if(window.innerWidth < 1280) {
    mobList.classList.add('hide_menu');
} else {
    mobList.classList.remove('hide_menu');
    admNavBtn.style.display = 'none';
}

admNavBtn.addEventListener('click', function() {
    mobList.classList.remove('hide_menu');
    console.log('clicked!');
    if(mobList.classList.contains('show_list')) {
        mobList.classList.remove('show_list');
        mobList.classList.add('hide_list');
        bgOverlay.classList.remove('dark');
        bgOverlay.classList.add('light');

        admSpansAll.forEach(element => {
            if(element = admSpansAll[0]) {
                admSpansAll[0].classList.remove('rtt-left');
                admSpansAll[0].classList.add('rtt-left-rev');
            }
            if(element = admSpansAll[1]) {
                admSpansAll[1].classList.remove('hide');
                admSpansAll[1].classList.add('show');
            }
            if(element = admSpansAll[2]) {
                admSpansAll[2].classList.remove('rtt-right');
                admSpansAll[2].classList.add('rtt-right-rev');
            }
        });

        return;
    }
    if(!mobList.classList.contains('show_list')) {
        mobList.classList.remove('hide_menu');
        mobList.classList.remove('hide_list');
        mobList.classList.add('show_list');
        bgOverlay.classList.remove('light');
        bgOverlay.classList.add('dark');

        admSpansAll.forEach(element => {
            if(element = admSpansAll[0]) {
                admSpansAll[0].classList.remove('rtt-left-rev');
                admSpansAll[0].classList.add('rtt-left');
            }
            if(element = admSpansAll[1]) {
                admSpansAll[1].classList.remove('show');
                admSpansAll[1].classList.add('hide');
            }
            if(element = admSpansAll[2]) {
                admSpansAll[2].classList.remove('rtt-right-rev');
                admSpansAll[2].classList.add('rtt-right');
            }
        });
        return;
    }
});

bgOverlay.addEventListener('click', function() {
    if(mobList.classList.contains('show_list')) {
        mobList.classList.remove('show_list');
        mobList.classList.add('hide_list');
        bgOverlay.classList.remove('dark');
        bgOverlay.classList.add('light');

        admSpansAll.forEach(element => {
            if(element = admSpansAll[0]) {
                admSpansAll[0].classList.remove('rtt-left');
                admSpansAll[0].classList.add('rtt-left-rev');
            }
            if(element = admSpansAll[1]) {
                admSpansAll[1].classList.remove('hide');
                admSpansAll[1].classList.add('show');
            }
            if(element = admSpansAll[2]) {
                admSpansAll[2].classList.remove('rtt-right');
                admSpansAll[2].classList.add('rtt-right-rev');
            }
        });
        return;
    }
});



var listItem = document.querySelectorAll('.item-group a');

listItem.forEach(el => {
    el.addEventListener('click', function(event) {
        var sibling = el.nextElementSibling;
        if(sibling.classList.contains("child-list")) {
            event.preventDefault();
            $(sibling).slideToggle();
        }
    });
});

function pop(node) {
    return confirm("Are you sure you want to delete this? Click OK to continue or CANCEL to quit.");
}   