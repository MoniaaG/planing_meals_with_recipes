/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import $ from 'jquery';
import bootbox from 'bootbox';

window.bootbox = bootbox;

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */


$(document).on("hidden.bs.modal", ".bootbox.modal", function(e) {    
    if( $(".modal").hasClass("show"))
        $("body").addClass("modal-open");
});


