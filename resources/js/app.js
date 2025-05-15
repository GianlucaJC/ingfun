import { createApp } from 'vue'


import makeApp from './components/makeApp.vue';
const app = createApp()

app.component('makeApp', makeApp);
app.mount('#app')