/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import moment from 'moment';
import Vue from 'vue';
import VueRouter from 'vue-router'
import ACL from './ACL'
//
import VueHtmlToPaper from 'vue-html-to-paper';
Vue.use(VueHtmlToPaper);
//
Vue.prototype.$acl = new ACL(window.user)
Vue.use(VueRouter)

const routes = [
    { path: '/home', component: require('./components/Home.vue').default },
    { path: '/emloyee', component: require('./components/Emloyee.vue').default },
    { path: '/admin', component: require('./components/Admin.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/payroll', component: require('./components/Payroll.vue').default },
    { path: '*', component: require('./components/404Page.vue').default }
  ]

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
  })  
////////////////////
// Filter
Vue.filter('upText', function(text){
    return text.charAt(0).toUpperCase() + text.slice(1)
});

Vue.filter('myDate',function(created){
    // return moment(created).format('MMMM Do YYYY');
    return moment(created).format('MMMM Do YYYY, h:mm:ss a');
});
import Swal from 'sweetalert2'
import { _ } from 'core-js';
window.Swal = Swal;
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
window.Toast = Toast;
//
window.Fire = new Vue();

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('pagination', require('laravel-vue-pagination'));

// Vue.component('404-page', require('./components/404Page.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    data:{
      search: ''
    },
    methods:{
      searchEmloyee: _.debounce( ()=>{
        Fire.$emit('startSearch');
      },1000)
    }
});
