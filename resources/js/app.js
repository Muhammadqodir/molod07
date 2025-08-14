import './bootstrap';

window.scrollGallery = function () {
    return {
        start() {
            const el = this.$refs.container;
            let scrollSpeed = 1;

            setInterval(() => {
                if (el.scrollLeft >= el.scrollWidth - el.clientWidth) {
                    el.scrollLeft = 0;
                } else {
                    el.scrollLeft += scrollSpeed;
                }
            }, 20);
        }
    }
}