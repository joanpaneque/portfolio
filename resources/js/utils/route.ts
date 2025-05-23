import { App } from 'vue';
import { route as ziggyRoute } from 'ziggy-js';
import type { RouteParams, Router, Config, ValidRouteName } from 'ziggy-js';

// Keep a reference to the original function for type safety
const originalRoute = window.route;

/**
 * Extended route function that logs routes when generated
 */
function extendedRoute(): Router;
function extendedRoute<T extends ValidRouteName>(name: T, params?: RouteParams<T>, absolute?: boolean, config?: Config): string;
function extendedRoute(name?: any, params?: any, absolute?: boolean, config?: Config): Router | string {
  console.log("[Route Debug]", { name, params });

  // Call the original Ziggy route function
  const result = ziggyRoute(name, params, absolute, config);

  // Return the original result
  return result;
}

// Create a Vue plugin that injects our custom route function
const RoutePlugin = {
  install: (app: App) => {
    // Override the global route function
    if (typeof window !== 'undefined') {
      window.route = extendedRoute;
    }

    // Override the Vue global property with type casting to avoid TS errors
    app.config.globalProperties.route = extendedRoute as typeof originalRoute;
  }
};

// Ensure route is available in global scope
declare global {
  interface Window {
    route: typeof ziggyRoute;
  }
}

export { RoutePlugin, extendedRoute as route };
