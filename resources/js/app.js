
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//global.$ = global.jQuery = require('jquery');
//require('./bootstrap');

window.Vue = require('vue');

require('admin-lte');

window.datatables = require('datatables.net-bs');

$('#logout_button').on('click', function(){
    swal({
        title: 'Log Out',
        text: 'Are you sure you want to logout?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if(result.value){
            location.href = '/logout';
        }
    });
});