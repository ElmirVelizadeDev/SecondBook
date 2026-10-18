import { createApp } from 'vue';

const modules = import.meta.glob('./pages/*.vue', {
    eager: true,
    import: 'default',
});

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
            .filter(([key]) => key !== 'vuePage' && key !== 'vueMounted')
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

export function mountVuePages() {
    document.querySelectorAll('[data-vue-page]').forEach((element) => {
        const pageName = element.dataset.vuePage;
        const component = pages[pageName];

        if (!component) {
            console.error(`No Vue page is registered for "${pageName}".`);
            return;
        }

        if (element.dataset.vueMounted === 'true') {
            return;
        }

        createApp(component, pageProps(element)).mount(element);
        element.dataset.vueMounted = 'true';
    });
}
