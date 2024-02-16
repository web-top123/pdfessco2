require('./bootstrap');

const store = new Vuex.Store({

    state: {
        categories: [],
        list: [],
        modals: new Set(),
    },

    getters: {
        categoriesList() {

        },
        uploadList() {

        },
    },

    mutations: {
        categories(state, value) {
            state.categories = value;
        },
        list(state, value) {
            state.list = value;
        },
    }


});


store.commit('categories', categories);
store.commit('list', list);


const app = new Vue({
    el: '#app',
    mixins: [navbar],
    store,
    data: {
        upload: {
            description: '',
            file: undefined,
            title: '',
        },
        search: {
            query: '',
            cat: 0,
        },
        scrollShadow: false,
    },
    mounted() {
        //console.log(this.$store.state.categories);
    }
});

//category item
//file item
