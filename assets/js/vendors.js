const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});
require('@fortawesome/fontawesome-free')
