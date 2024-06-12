import { createI18n } from 'vue-i18n';

import { en, es } from './index';

export const createI18nInstance = (locale: string) => {
    return createI18n({
        locale,
        fallbackLocale: 'en',
        messages: {
            en,
            es,
        },
    });
}
