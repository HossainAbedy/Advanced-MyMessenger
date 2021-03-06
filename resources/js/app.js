
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue'
//imported for scroll
import VueChatScroll from 'vue-chat-scroll'
Vue.use(VueChatScroll)
//imported for notification
import Toaster from 'v-toaster'
import 'v-toaster/dist/v-toaster.css'
Vue.use(Toaster, {timeout:5000})
//events
Vue.prototype.$eventBus = new Vue()
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('chat-app', require('./components/ChatApp.vue').default);
Vue.component('profile', require('./components/Profile.vue').default);
Vue.component('Friendship', require('./components/Friendship.vue').default);
Vue.component('message', require('./components/Message.vue').default);
Vue.component('notification', require('./components/Notification.vue').default);
Vue.component('incoming', require('./components/Incoming.vue').default);
Vue.component('pagination', require('laravel-vue-pagination'));


//file upload component
const VueUploadComponent = require('vue-upload-component')
Vue.component('file-upload', VueUploadComponent);


import { Form, HasError, AlertError } from 'vform' //vform
window.Form=Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError) //vform

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});
