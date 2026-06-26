import {inject} from 'vue';
import {createI18n} from './index.js';

export const WpZylosI18nKey = Symbol('wpzylos-i18n');

export const createVueI18nPlugin = (options = {}) => {
    const i18n = createI18n(options.textDomain);

    return {
        install(app) {
            app.provide(WpZylosI18nKey, i18n);
            app.config.globalProperties.$i18n = i18n;
            app.config.globalProperties.$__ = i18n.__;
            app.config.globalProperties.$_x = i18n._x;
            app.config.globalProperties.$_n = i18n._n;
            app.config.globalProperties.$_nx = i18n._nx;
            app.config.globalProperties.$sprintf = i18n.sprintf;
            app.config.globalProperties.$isRTL = i18n.isRTL;
            app.config.globalProperties.__ = i18n.__;
            app.config.globalProperties._x = i18n._x;
            app.config.globalProperties._n = i18n._n;
            app.config.globalProperties._nx = i18n._nx;
            app.config.globalProperties.sprintf = i18n.sprintf;
            app.config.globalProperties.isRTL = i18n.isRTL;
        },
    };
};

export const useWpZylosI18n = () => inject(WpZylosI18nKey, createI18n());
