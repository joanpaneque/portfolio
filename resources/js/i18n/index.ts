import { createI18n } from 'vue-i18n';
import es from './locales/es';
import ca from './locales/ca';
import en from './locales/en';
import fr from './locales/fr';
import de from './locales/de';

// Verificar si estamos en un entorno de navegador
const isBrowser = typeof window !== 'undefined';

// Detectar el idioma preferido
function detectPreferredLanguage() {
  const supportedLocales = ['es', 'ca', 'en', 'fr', 'de'];
  
  // Solo intentamos acceder a localStorage y navigator en el navegador
  if (isBrowser) {
    // Primero intentamos obtener el idioma guardado en el localStorage
    const savedLanguage = localStorage.getItem('language');
    if (savedLanguage && supportedLocales.includes(savedLanguage)) {
      return savedLanguage;
    }
    
    // Si no hay idioma guardado, intentamos usar el idioma del navegador
    if (navigator) {
      const navigatorLang = navigator.language.split('-')[0];
      if (supportedLocales.includes(navigatorLang)) {
        return navigatorLang;
      }
    }
  }
  
  // Por defecto, español (tanto para SSR como cuando no se encuentra preferencia)
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