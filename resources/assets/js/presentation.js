require('./bootstrap');

const navbar = require('./navbar')()

const app = new Vue({
    mixins: [navbar],
    el: '#app',
});
