let defaultTextDomain = 'default';

const identity = (text) => String(text ?? '');

const pluralIdentity = (single, plural, number) => (
    Number(number) === 1 ? identity(single) : identity(plural)
);

export const getWpI18n = () => globalThis?.wp?.i18n ?? {};

export const setDefaultTextDomain = (textDomain) => {
    defaultTextDomain = textDomain || 'default';
};

export const getDefaultTextDomain = () => defaultTextDomain;

export const createI18n = (textDomain = defaultTextDomain) => {
    const domain = textDomain || defaultTextDomain;
    const wpI18n = getWpI18n();

    return {
        textDomain: domain,
        __(text) {
            return wpI18n.__ ? wpI18n.__(text, domain) : identity(text);
        },
        _x(text, context) {
            return wpI18n._x ? wpI18n._x(text, context, domain) : identity(text);
        },
        _n(single, plural, number) {
            return wpI18n._n ? wpI18n._n(single, plural, number, domain) : pluralIdentity(single, plural, number);
        },
        _nx(single, plural, number, context) {
            return wpI18n._nx
                ? wpI18n._nx(single, plural, number, context, domain)
                : pluralIdentity(single, plural, number);
        },
        sprintf(format, ...args) {
            if (wpI18n.sprintf) {
                return wpI18n.sprintf(format, ...args);
            }

            let index = 0;
            return identity(format).replace(/%([sd])/g, () => String(args[index++] ?? ''));
        },
        isRTL() {
            return wpI18n.isRTL ? wpI18n.isRTL() : false;
        },
    };
};

const getDefaultI18n = () => createI18n(defaultTextDomain);

export const __ = (text) => getDefaultI18n().__(text);
export const _x = (text, context) => getDefaultI18n()._x(text, context);
export const _n = (single, plural, number) => getDefaultI18n()._n(single, plural, number);
export const _nx = (single, plural, number, context) => getDefaultI18n()._nx(single, plural, number, context);
export const sprintf = (format, ...args) => getDefaultI18n().sprintf(format, ...args);
export const isRTL = () => getDefaultI18n().isRTL();
