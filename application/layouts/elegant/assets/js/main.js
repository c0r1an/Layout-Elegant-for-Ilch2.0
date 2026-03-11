document.addEventListener('DOMContentLoaded', function () {
    var navToggle = document.querySelector('[data-elegant-nav-toggle]');
    var nav = document.querySelector('[data-elegant-nav]');
    var header = document.querySelector('[data-elegant-header]');
    var backToTop = document.querySelector('[data-elegant-backtop]');
    var mobileQuery = window.matchMedia('(max-width: 991.98px)');

    if (navToggle && nav) {
        navToggle.addEventListener('click', function () {
            nav.classList.toggle('is-open');
        });

        document.querySelectorAll('.elegant-nav li.has-children > a').forEach(function (link) {
            link.addEventListener('click', function (event) {
                if (!mobileQuery.matches) {
                    return;
                }

                var parent = link.parentElement;
                if (!parent) {
                    return;
                }

                event.preventDefault();
                event.stopPropagation();

                if (parent.classList.contains('is-open')) {
                    parent.classList.remove('is-open');
                    parent.querySelectorAll('.is-open').forEach(function (openItem) {
                        openItem.classList.remove('is-open');
                    });
                } else {
                    Array.prototype.forEach.call(parent.parentElement ? parent.parentElement.children : [], function (sibling) {
                        if (sibling !== parent && sibling.classList) {
                            sibling.classList.remove('is-open');
                            sibling.querySelectorAll('.is-open').forEach(function (openItem) {
                                openItem.classList.remove('is-open');
                            });
                        }
                    });

                    parent.classList.add('is-open');
                }
            });
        });

        document.addEventListener('click', function (event) {
            if (!nav.contains(event.target) && !navToggle.contains(event.target)) {
                nav.classList.remove('is-open');
            }
        });
    }

    function handleScroll() {
        var isScrolled = window.scrollY > 24;

        if (header) {
            header.classList.toggle('is-scrolled', isScrolled);
        }

        if (backToTop) {
            backToTop.classList.toggle('is-visible', window.scrollY > 340);
        }
    }

    if (backToTop) {
        backToTop.addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
});
