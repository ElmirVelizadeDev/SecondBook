import { createApp } from 'vue';

// Keep page components in separate Vite chunks so a page only downloads its
// own UI code. This uses the existing page discovery/mount mechanism.
const modules = import.meta.glob('./pages/*.vue');

const pages = Object.fromEntries(
    Object.entries(modules).map(([path, component]) => {
        const filename = path.split('/').pop().replace(/\.vue$/, '');
        const pageName = filename
            .replace(/([a-z0-9])([A-Z])/g, '$1-$2')
            .toLowerCase();

        return [pageName, component];
    })
);

pages.home = modules['./pages/HomePage.vue'];

function pageProps(element) {
    return Object.fromEntries(
        Object.entries(element.dataset)
            .filter(([key]) => !['vuePage', 'vueMounted', 'vueLoading'].includes(key))
            .map(([key, value]) => {
                const propValue = value === 'true'
                    ? true
                    : value === 'false'
                        ? false
                        : value;

                return [
                    key.replace(/-([a-z])/g, (_, letter) => letter.toUpperCase()),
                    propValue,
                ];
            })
    );
}

function setSellerMountStatus(element, className, message, role) {
    const status = document.createElement('div');
    status.className = className;
    status.setAttribute('role', role);
    status.textContent = message;
    element.replaceChildren(status);
}

export function mountVuePages() {
    document.querySelectorAll('[data-vue-page]').forEach((element) => {
        const pageName = element.dataset.vuePage;
        const loadComponent = pages[pageName];

        if (!loadComponent) {
            console.error(`No Vue page is registered for "${pageName}".`);
            return;
        }

        if (element.dataset.vueMounted === 'true' || element.dataset.vueLoading === 'true') {
            return;
        }

        element.dataset.vueLoading = 'true';
        if (pageName.startsWith('seller-')) {
            setSellerMountStatus(
                element,
                'seller-vue-mount-status is-loading',
                'Loading Seller page…',
                'status'
            );
        }

        loadComponent()
            .then((module) => {
                if (!element.isConnected || element.dataset.vueMounted === 'true') {
                    return;
                }

                createApp(module.default, pageProps(element)).mount(element);
                element.dataset.vueMounted = 'true';
            })
            .catch((error) => {
                console.error(`Could not load Vue page "${pageName}".`, error);
                if (pageName.startsWith('seller-')) {
                    setSellerMountStatus(
                        element,
                        'seller-vue-mount-status is-error',
                        'This Seller page could not be loaded. Reload the page to try again.',
                        'alert'
                    );
                }
            })
            .finally(() => {
                delete element.dataset.vueLoading;
            });
    });
}
