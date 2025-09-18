import axios from 'axios';
import collapse from '@alpinejs/collapse';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Configure Alpine.js when it's initialized by Livewire
document.addEventListener('livewire:init', () => {
    // Register collapse plugin with Livewire's Alpine instance
    if (window.Alpine) {
        window.Alpine.plugin(collapse);

        // Define scrollGallery data function
        window.Alpine.data('scrollGallery', () => ({
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
        }));
    }
});
