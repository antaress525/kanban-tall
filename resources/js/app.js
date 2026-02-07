import './bootstrap';
import EmblaCarousel from 'embla-carousel';

const wrapper = document.querySelector('.embla')
const viewport = wrapper.querySelector('.embla__viewport')
new EmblaCarousel(viewport, {
    loop: false,
    align: 'start',
    dragFree: false,
})
