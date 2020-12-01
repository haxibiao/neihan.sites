window._ = require('lodash');

// 兼容dplayer
require('es6-promise').polyfill();

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.$ = window.jQuery = require('jquery');

// bootstraps component
require('bootstrap').Modal;
