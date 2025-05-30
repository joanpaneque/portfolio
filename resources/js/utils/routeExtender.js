/**
 * Route Extender Plugin
 *
 * This plugin extends the Ziggy route function to add locale parameter
 * and console logging for debugging.
 */

export const RouteExtenderPlugin = {
  install(app) {
    if (typeof window !== 'undefined') {
      // Guardar la función original antes de cualquier modificación
      const originalRoute = window.route;

      // Reemplazar la función route con nuestra versión extendida
      window.route = function() {
        // Convertir arguments a un array para poder manipularlo más fácilmente
        const args = Array.from(arguments);
        const routeName = args[0];
        // const userLocale = "ca";


        const availableLocales = ['es', 'en', 'ca'];

        // get the first segment of the path
        const path = window.location.pathname;
        const firstSegment = path.split('/')[1];
        let userLocale = firstSegment;
        if (!availableLocales.includes(userLocale)) {
          userLocale = 'undefined';
        }


        // Añadir o actualizar el parámetro locale
        if (args.length < 2 || !args[1]) {
          // Si no hay parámetros, crear un objeto con locale
          args[1] = { locale: userLocale };
        } else if (typeof args[1] === 'object') {
          // Si hay parámetros, asegurarse de que incluyan locale
          if (!args[1].locale) {
            args[1] = { ...args[1], locale: userLocale };
          }
        }

        // Llamar a la función original con los argumentos modificados
        try {
          return originalRoute.apply(this, args);
        } catch (error) {
          throw error;
        }
      };

      // Asegurarse de que la función route esté disponible en las propiedades globales de Vue
      if (app.config && app.config.globalProperties) {
        app.config.globalProperties.route = window.route;
      }
    }
  }
};
