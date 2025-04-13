import { createI18n } from 'vue-i18n';
import es from './locales/es';
import ca from './locales/ca';
import en from './locales/en';
import fr from './locales/fr';
import de from './locales/de';

// Detectar el idioma preferido
function detectPreferredLanguage() {
  // Primero intentamos obtener el idioma guardado en el localStorage
  const savedLanguage = localStorage.getItem('language');
  const supportedLocales = ['es', 'ca', 'en', 'fr', 'de'];
  
  if (savedLanguage && supportedLocales.includes(savedLanguage)) {
    return savedLanguage;
  }
  
  // Si no hay idioma guardado, intentamos usar el idioma del navegador
  if (typeof navigator !== 'undefined') {
    const navigatorLang = navigator.language.split('-')[0];
    if (supportedLocales.includes(navigatorLang)) {
      return navigatorLang;
    }
  }
  
  // Por defecto, español
  return 'es';
}

export default createI18n({
  legacy: false, // Use Composition API
  globalInjection: true, // Add $t, $tc, etc. to global properties
  locale: detectPreferredLanguage(),
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