/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';

window.Vue.use(VueRouter);

import PostsIndex from './components/posts/PostsIndex.vue';
import PostsCreate from './components/posts/PostsCreate.vue';
import PostsEdit from './components/posts/PostsEdit.vue';

import SettingsIndex from './components/settings/SettingsIndex';
import SettingsCreate from './components/settings/SettingsCreate.vue';
import SettingsEdit from './components/settings/SettingsEdit.vue';

import HomeComponent from "./components/HomeComponent";

import PostsComponent from "./components/PostsComponent";
import PostComponent from "./components/posts/PostComponent";

const routes = [
    {path: '/admin/posts', component: PostsIndex, name: 'indexPost'},
    {path: '/admin/posts/create', component: PostsCreate, name: 'createPost'},
    {path: '/admin/posts/edit/:id', component: PostsEdit, name: 'editPost'},

    {path: '/admin/settings', component: SettingsIndex, name: 'adminSettings'},
    {path: '/admin/settings/create', component: SettingsCreate, name: 'createSettings'},
    {path: '/admin/settings/edit/:id', component: SettingsEdit, name: 'editSettings'},

    {path: '/home', component: HomeComponent, name: 'adminHome'},

    {path: '/posts', component: PostsComponent, name: 'posts'},
    {path: '/posts/:id', component: PostComponent, name: 'showPost'},

    { path: '*', redirect: { name: 'indexPost' } }
];


 const router = new VueRouter({
     mode: 'history',
     base: '/',
     fallback: true, //router should fallback to hash (#) mode when the browser does not support history.pushState
     routes
 });

 const app = new Vue({ router }).$mount('#app');

 if (document.getElementById('app_token')) {
     axios.defaults.headers.common['Authorization'] = 'Bearer ' + document.getElementById('app_token').value;
 }

