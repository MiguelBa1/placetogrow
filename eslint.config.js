import globals from 'globals';
import pluginJs from '@eslint/js';
import tseslint from '@typescript-eslint/eslint-plugin';
import tsParser from '@typescript-eslint/parser';
import pluginVue from 'eslint-plugin-vue';
import pluginPrettier from 'eslint-plugin-prettier';
import configPrettier from 'eslint-config-prettier';

export default [
    {
        files: ['*.ts', '*.tsx', '*.vue', '*.js'],
        languageOptions: {
            globals: globals.browser,
            parser: tsParser,
            parserOptions: {
                ecmaVersion: 2021,
                sourceType: 'module',
            },
        },
        plugins: {
            '@typescript-eslint': tseslint,
            vue: pluginVue,
            prettier: pluginPrettier,
        },
        rules: {
            ...pluginJs.configs.recommended.rules,
            ...tseslint.configs.recommended.rules,
            ...pluginVue.configs['flat/essential'].rules,
            ...pluginPrettier.configs.recommended.rules,
            ...configPrettier.rules,
            'prettier/prettier': ['error', { endOfLine: 'auto', semi: true }],
            '@typescript-eslint/explicit-module-boundary-types': 'off',
            semi: ['error', 'always'],
        },
    },
];
