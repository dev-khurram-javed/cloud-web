// Import modules.
import Swiper from 'swiper/bundle';
import { setupAppearAnimations } from './animations';

const blocks = (name, callback) => {
    window.addEventListener('load', () => {
        const instances = document.querySelectorAll(`[data-block="${name}"]`);

        instances.forEach(i => {
            const blockId = i.dataset.blockId || '';
            const data = blockId && typeof window[blockId] !== 'undefined' ? window[blockId].data : null;

            if (callback) callback(i, data);
        });
    });
}

const components = (name, callback) => {
    window.addEventListener('load', () => {
        const instances = document.querySelectorAll(`[data-component="${name}"]`);
        instances.forEach(i => callback(i));
    });
}

window.coreTheme = {
    blocks,
    components
}

window.packages = { swiper: Swiper };
setupAppearAnimations();