function video_height_from_width(width) {
    height = width * .5625;
    console.log(height);
    return height;
}
// video_height_from_width(560);
// Navigation
var navNodelist = document.querySelectorAll('.navigation_list li');
var navBtn = document.getElementById('navBtn');
var mobList  = document.getElementById('mobList');
var bgOverlay = document.getElementById('bgOverlay');
var navSpansAll = document.querySelectorAll('#navBtn > span');

if(typeof(bgOverlay) != 'undefined' && bgOverlay != null) {
    bgOverlay.addEventListener('click', function() {
        if(mobList.classList.contains('show_list')) {
            mobList.classList.remove('show_list');
            mobList.classList.add('hide_list');
            bgOverlay.classList.remove('dark');
            bgOverlay.classList.add('light');

            navBtn.style.height = "19px";
            navSpansAll.forEach(element => {
                scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                if(scrollTop > 100) {
                    element.style.backgroundColor = "#000";
                } else {
                    element.style.backgroundColor = "#fff";
                }

                // Admin menu end

                if(element = navSpansAll[0]) {
                    navSpansAll[0].classList.remove('rotate-left');
                    navSpansAll[0].classList.add('rotate-left-rev');
                }
                if(element = navSpansAll[1]) {
                    navSpansAll[1].classList.remove('hide');
                    navSpansAll[1].classList.add('show');
                }
                if(element = navSpansAll[2]) {
                    navSpansAll[2].classList.remove('rotate-right');
                    navSpansAll[2].classList.add('rotate-right-rev');
                }
            });
            return;
        }
    });
}
function menu() {
  
    console.log(navNodelist); // NodeList(4) [li.list-item, li.list-item, li.list-item, li.list-item]
    for (let i = 0; i < navNodelist.length; i++) {
        navNodelist[i].addEventListener('mouseover', function() {
            for (let n = 0; n < navNodelist.length; n++) {
                var itemChildren = navNodelist[n].children;
                var childCount = navNodelist[n].children.length;
                if(childCount === 2) {
                    if(itemChildren[1].classList.contains('mega-menu')) {
                        itemChildren[1].classList.add('mega-menu-hide');
                        if(itemChildren[1].classList.contains('mega-menu-show')) {
                            itemChildren[1].classList.remove('mega-menu-show');
                        }
                    }
                }
            }
            var itemChildren = navNodelist[i].children;
            var childCount = navNodelist[i].children.length;
            if(childCount === 2) {
                if(itemChildren[1].classList.contains('mega-menu')) {
                    itemChildren[1].classList.add('mega-menu-show');
                    if(itemChildren[1].classList.contains('mega-menu-hide')) {
                        itemChildren[1].classList.remove('mega-menu-hide');
                    }
                }
            }
        });
        navNodelist[i].addEventListener('mouseout', function() {
            var itemChildren = navNodelist[i].children;
            var childCount = navNodelist[i].children.length;
            if(childCount === 2) {
                itemChildren[1].addEventListener('mouseout', function() {
                    if(itemChildren[1].classList.contains('mega-menu')) {
                        itemChildren[1].classList.add('mega-menu-hide');
                        if(itemChildren[1].classList.contains('mega-menu-show')) {
                            itemChildren[1].classList.remove('mega-menu-show');
                        }
                    }
                });
            }
        });
    }



    if(typeof(navBtn) != 'undefined' && navBtn != null) {
        navBtn.addEventListener('click', function() {
            if(mobList.classList.contains('show_list')) {
                mobList.classList.remove('show_list');
                mobList.classList.add('hide_list');
                bgOverlay.classList.remove('dark');
                bgOverlay.classList.add('light');

                navBtn.style.height = "19px";
                navSpansAll.forEach(element => {

                    if(element = navSpansAll[0]) {
                        navSpansAll[0].classList.remove('rotate-left');
                        navSpansAll[0].classList.add('rotate-left-rev');
                    }
                    if(element = navSpansAll[1]) {
                        navSpansAll[1].classList.remove('hide');
                        navSpansAll[1].classList.add('show');
                    }
                    if(element = navSpansAll[2]) {
                        navSpansAll[2].classList.remove('rotate-right');
                        navSpansAll[2].classList.add('rotate-right-rev');
                    }
                });

                return;
            }
            if(!mobList.classList.contains('show_list')) {
                mobList.classList.remove('hide_list');
                mobList.classList.add('show_list');
                bgOverlay.classList.remove('light');
                bgOverlay.classList.add('dark');

                navBtn.style.height = "0px";
                navSpansAll.forEach(element => {
                    scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    if(scrollTop > 100) {
                        element.style.backgroundColor = "#000";
                    } else {
                        element.style.backgroundColor = "#fff";
                    }


                    if(element = navSpansAll[0]) {
                        navSpansAll[0].classList.remove('rotate-left-rev');
                        navSpansAll[0].classList.add('rotate-left');
                    }
                    if(element = navSpansAll[1]) {
                        navSpansAll[1].classList.remove('show');
                        navSpansAll[1].classList.add('hide');
                    }
                    if(element = navSpansAll[2]) {
                        navSpansAll[2].classList.remove('rotate-right-rev');
                        navSpansAll[2].classList.add('rotate-right');
                    }
                });
                return;
            }
        });
    }
}
menu();

function scroll_to_element(id, event) {
    event.preventDefault();
    var element = document.getElementById(id);
    if(window.innerWidth > 900) {

        element.scrollIntoView({ behavior: 'smooth', block: "center", inline: "nearest"});
    } else {

        element.scrollIntoView({ behavior: 'smooth', block: "start", inline: "nearest"});
    }
}
// window.addEventListener('scroll', function(e) {
//     const target = document.querySelectorAll('.scroll');
//     scrolled = window.pageYOffset;
//     // console.log(scrolled);
//     target.forEach(element => {
//         var pos = window.pageYOffset * element.dataset.rate;
//         if(element.dataset.direction == 'vertical') {
//             element.style.transform = "translate3d(0px, "+pos+"px, 0px)";
//         } else {
//             var posX = window.pageYOffset * element.dataset.ratex;
//             var posY = window.pageYOffset * element.dataset.ratey;
//             element.style.transform = "translate3d("+posX +"px,"+posY+"px, 0px)";
//         }
//     });
// });

// contact_scroll();
function nav_bg() {
    window.onscroll = function() {
        scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        var navOuterNodelist = document.querySelectorAll('.nav-outer');
        if(scrollTop > 100) {
            navOuterNodelist.forEach(el => {
                el.classList.remove('d-bg');
                el.classList.add('w-bg');
            });                
            navSpansAll.forEach(element => {
                element.style.backgroundColor = "#000";
            });
        } else {
            navOuterNodelist.forEach(el => {
                el.classList.remove('w-bg');
                el.classList.add('d-bg');

                navSpansAll.forEach(element => {
                    element.style.backgroundColor = "#fff";
                });
            });
        }
    };
}

nav_bg();




abtSections = document.querySelectorAll('.about-section .txt-col');
options = {
    root: null, // it is the viewport
    threshold: .2,
    rootMargin: '0px'
}
const observer = new IntersectionObserver(function(entries, observer) {
    entries.forEach(entry => {
        if(!entry.isIntersecting) {
            return;
        }
        console.log(entry.target);
        entry.target.classList.toggle("inverse");
        observer.unobserve(entry.target);
    })
},options);

abtSections.forEach(section => {
    observer.observe(section);
});



abtSections = document.querySelectorAll('.blog-section .thumbnail');
options = {
    root: null, // it is the viewport
    threshold: .1,
    rootMargin: '0px'
}
const observer2 = new IntersectionObserver(function(entries, observer2) {
    entries.forEach(entry => {
        if(!entry.isIntersecting) {
            return;
        }
        console.log(entry.target);
        entry.target.classList.toggle("show-thumb");
        observer2.unobserve(entry.target);
    })
},options);

abtSections.forEach(section => {
    observer2.observe(section);
});

