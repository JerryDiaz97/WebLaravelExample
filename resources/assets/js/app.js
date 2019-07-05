/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

import Category from './components/Category'
import Client from './components/Client'
import Entry from './components/Entry'
import Product from './components/Product'
import Provider from './components/Provider'
import Role from './components/Role'
import User from './components/User'

Vue.component('category', Category);
Vue.component('client', Client);
Vue.component('entry', Entry);
Vue.component('product', Product);
Vue.component('provider', Provider);
Vue.component('role', Role);
Vue.component('user', User);

const app = new Vue({
    el: '#app',
    data :{
        menu : 0
    }

});
