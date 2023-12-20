import { createApp } from 'vue';
import App from './App.vue';

//css
import './assets/main.css';

//Router
import router from './routes';

//Vuetify
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

const vuetify = createVuetify({
  components,
  directives,
})

//Font awesome
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

import 
{
  faXmark
} from '@fortawesome/free-solid-svg-icons';

library.add(faXmark)

createApp(App)
  .use(vuetify)
  .use(router)
  .component('font-awesome-icon', FontAwesomeIcon)
  .mount('#app')
