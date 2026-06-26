import React, {createContext, useContext, useMemo} from 'react';
import {createI18n} from './index.js';

const I18nContext = createContext(createI18n());

export const I18nProvider = ({children, textDomain, i18n}) => {
    const value = useMemo(
        () => i18n || createI18n(textDomain),
        [i18n, textDomain],
    );

    return React.createElement(I18nContext.Provider, {value}, children);
};

export const useI18n = () => useContext(I18nContext);

export const withI18n = (Component) => {
    const Wrapped = (props) => React.createElement(Component, {
        ...props,
        i18n: useI18n(),
    });

    Wrapped.displayName = `withI18n(${Component.displayName || Component.name || 'Component'})`;

    return Wrapped;
};

