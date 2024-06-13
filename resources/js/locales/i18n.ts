import { createI18n } from 'vue-i18n';

import en from './en';
import es from './es';

const messages = {
    en,
    es,
};

console.log(messages);

export const createI18nInstance = (locale: string) => {
    return createI18n({
        locale,
        legacy: false,
        fallbackLocale: 'en',
        messages,
    });
}
