/// <reference types="vite/client" />

import ziggyRoute, { Config as ZiggyConfig } from 'ziggy-js';

declare global {
  interface Window {
    axios: AxiosInstance;
  }

  var route: typeof ziggyRoute;
  var Ziggy: ZiggyConfig;
}

declare module 'vue' {
  interface ComponentCustomProperties {
    route: typeof ziggyRoute;
  }
}
