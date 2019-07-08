/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.$ =  window.jQuery = require('jquery');

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
import Dashboard from './components/Dashboard'
import Entry from './components/Entry'
import Notification from './components/Notification'
import Product from './components/Product'
import Provider from './components/Provider'
import QueryIncome from './components/QueryIncome'
import QuerySale from './components/QuerySale'
import Role from './components/Role'
import Sale from './components/Sale'
import User from './components/User'
import Axios from 'axios';
import Echo from 'laravel-echo';

Vue.component('category', Category);
Vue.component('client', Client);
Vue.component('dashboard', Dashboard);
Vue.component('entry', Entry);
Vue.component('notification', Notification);
Vue.component('product', Product);
Vue.component('provider', Provider);
Vue.component('queryincome', QueryIncome);
Vue.component('querysale', QuerySale);
Vue.component('role', Role);
Vue.component('sale', Sale);
Vue.component('user', User);

const app = new Vue({
    el: '#app',
    data :{
        menu : 0,
        notifications : [],
    },
    created() {
        let me = this;
        Axios.post('notification/get').then(function(response){
          //console.log(response.data);
          me.notifications = response.data;
        }).catch(function(error) {
            console.log(error);
        });
        
        var user_id = $('meta[namec = "user_id"]').attr('content');

        Echo.private('App.User.' + user_id).notification((notification) =>{
            //console.log(notification);
            me.notifications.unshift(notification);
        });
    }

});
