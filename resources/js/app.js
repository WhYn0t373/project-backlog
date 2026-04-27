```js
/**
 * Main application entry point.
 * Dynamically imports rarely‑used components to enable code‑splitting.
 */
import Vue from 'vue';
import App from './App.vue';
import router from './router';

Vue.config.productionTip = false;

// Lazy‑load components that are not required on initial load
const LazyModal = () => import(/* webpackChunkName: "modal" */ './components/Modal.vue');
const LazyChart = () => import(/* webpackChunkName: "chart" */ './components/Chart.vue');

// Register lazy components globally
Vue.component('LazyModal', LazyModal);
Vue.component('LazyChart', LazyChart);

new Vue({
    router,
    render: h => h(App),
}).$mount('#app');
```