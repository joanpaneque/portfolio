import { createI18n } from 'vue-i18n';
import es from './locales/es';
import ca from './locales/ca';
import en from './locales/en';
import fr from './locales/fr';
import de from './locales/de';

// Detectar el idioma preferido del navegador
function detectBrowserLanguage() {
  const navigatorLang = navigator.language.split('-')[0];
  const supportedLocales = ['es', 'ca', 'en', 'fr', 'de'];
  
  // Check if localStorage has a saved language
  const savedLanguage = localStorage.getItem('language');
  if (savedLanguage && supportedLocales.includes(savedLanguage)) {
    return savedLanguage;
  }
  
  // Check if browser language is supported
  if (supportedLocales.includes(navigatorLang)) {
    return navigatorLang;
  }
  
  // Default to Spanish
  return 'es';
}

export default createI18n({
  legacy: false, // Use Composition API
  globalInjection: true, // Add $t, $tc, etc. to global properties
  locale: detectBrowserLanguage(),
  fallbackLocale: 'es',
  missingWarn: false, // Disable warnings for missing translations
  messages: {
    es,
    ca,
    en,
    fr,
    de,
  },
}); 