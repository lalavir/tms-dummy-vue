import axios from 'axios';

import { createApp } from 'vue'
import Task from './components/Task.vue'

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const app = createApp();

app.component('task-component', Task)
app.mount('#app')