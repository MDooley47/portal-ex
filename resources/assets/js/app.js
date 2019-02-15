window.jQuery      = window.$ = require('jquery');
                                require('bootstrap'); // bootstrap is dependent on jquery
                                require('select2');
window.bootbox     =            require('bootbox'); // bootbox is dependent on bootstrap
window.pluralize   =            require('pluralize');
window.PortalAPI   =            require('./PortalAPI.js');
window.FormBuilder =            require('./FormBuilder.js');

import Setup from "./Setup.js";

window.DEBUG = true;

$(document).ready(function() {
    new Setup();
});