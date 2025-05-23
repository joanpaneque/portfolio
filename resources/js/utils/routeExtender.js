/**
 * Route Extender Plugin
 *
 * This plugin extends the Ziggy route function to add console logging
 * without the TypeScript complexities.
 */

export const RouteExtenderPlugin = {
  install(app) {
    if (typeof window !== 'undefined') {
      const originalRoute = window.route;

      window.route = function() {

        const userLocale = navigator.language.split('-')[0]
        arguments[1] = { ...arguments[1], locale: userLocale }

        return originalRoute.apply(this, arguments);
      };

      if (app.config && app.config.globalProperties) {
        app.config.globalProperties.route = window.route;
      }
    }
  }
};
