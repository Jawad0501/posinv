import { Livewire, Alpine } from '../../../vendor/livewire/livewire/dist/livewire.esm';
import flatpickr from "flatpickr";
import PerfectScrollbar from "perfect-scrollbar";
import Swiper from 'swiper';
import feather from 'feather-icons'
import { Notyf } from 'notyf';
import.meta.glob(["../../assets/images/**"]);

document.addEventListener('livewire:init', () => {
    Livewire.on('alert', (e) => toastNotyf(e[0], typeof e[1] !== 'undefined' ? e[1] : 'success'));
    const toastNotyf = (message, type = 'success') => {
        const notyf = new Notyf({
            position: {x:'right',y:'top'}
        });
        if(type == 'success') {
            notyf.success(message);
        }
        else {
            notyf.error(message);
        }
    }

    if(typeof alertNotyf !== 'undefined') toastNotyf(alertNotyf)

    let myWindow;
    Livewire.on('checkoutDone', (data) => {
        location.href = data[0]
        // const w = 500;
        // const h = 400;
        // const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
        // const dualScreenTop = window.screenTop !==  undefined   ? window.screenTop  : window.screenY;

        // const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        // const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        // const systemZoom = width / window.screen.availWidth;
        // const left = (width - w) / 2 / systemZoom + dualScreenLeft
        // const top = (height - h) / 2 / systemZoom + dualScreenTop
        // myWindow = window.open(data[0], 'Payment', `scrollbars=yes,width=${w / systemZoom},height=${h / systemZoom},top=${top},left=${left}`)
        // if (window.focus) myWindow.focus();
    });
})

Alpine.data('categories', (element) => ({
    categories: {},
    init() {
        this.categories =  new Swiper(element, {
            speed: 400,
            enabled: true,
            spaceBetween: 8,
            loop: true,
            breakpoints: {
                0: {
                    slidesPerView: 2,
                },
                576: {
                    slidesPerView: 6,
                },
                992: {
                    slidesPerView: 8,
                },
                1400: {
                    slidesPerView: 12,
                },
            }
        })
    },
    slidePrev(el) {
        this.categories.slidePrev();
    },
    slideNext(el) {
        this.categories.slideNext()
    },
}))

Alpine.data('ads', (element) => ({
    ads: {},
    init() {
        this.ads =  new Swiper(element, {
            speed: 400,
            loop: true,
            spaceBetween: 8,
            breakpoints: {
                320: {
                  slidesPerView: 1,
                },
                480: {
                  slidesPerView: 2,
                },
                768: {
                  slidesPerView: 4,
                }
            }
        })
    },
    slidePrev(el) {
        this.ads.slidePrev();
    },
    slideNext(el) {
        this.ads.slideNext()
    },
}))

Livewire.hook('component.init', ({ component }) => {
    feather.replace()

    if(component.name == 'frontend.pages.menu') {
        new PerfectScrollbar('#menuCategories', {
            useBothWheelAxes: true,
            suppressScrollX: false,
            suppressScrollY: false
        });

        // Category menu active inactive
        const sections = document.querySelectorAll('.sections');
        const navLi = document.querySelectorAll('.category-item');
        window.onscroll = () => {
            var current = "";
            sections.forEach((section) => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 192) {
                    current = section.getAttribute("id");
                }
            });
            navLi.forEach((li) => {
                li.classList.remove("active");
                if (li.classList.contains(current)) {
                    li.classList.add("active");
                }
            });
        };
    }

    if (component.name.indexOf('pages.') > -1) {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
        try {
            new PerfectScrollbar(".asidescroll");
        } catch (error) {

        }
    }
});

Livewire.hook('request', ({ fail }) => {
    fail(({ status, preventDefault }) => {
        if (status === 419) {
            preventDefault()
            // confirm('Your custom page expiration behavior...')
            location.reload()
        }
    })
})

Livewire.start()
