import './bootstrap';

window.addEventListener('scroll', () => {
    document.querySelectorAll('.animate-on-scroll').forEach((el) => {
        const position = el.getBoundingClientRect().top;

        if (position < window.innerHeight - 100) {
            el.classList.add('show');
        }
    });
});