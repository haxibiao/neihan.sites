
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue'
// import ApolloClient from 'apollo-client'
import ApolloClient, { createNetworkInterface } from 'apollo-client'
// import { HttpLink } from 'apollo-link-http'
// import { InMemoryCache } from 'apollo-cache-inmemory'
import VueApollo from 'vue-apollo'

const networkInterface = createNetworkInterface({ 
		// uri: 'https://api.graph.cool/simple/v1/cjepctjc10r1m0191f3ckcge3'
		uri: 'https://l.dongmeiwei.com/graphql'
})

const apolloClient = new ApolloClient({
  networkInterface,
})

// const link = new HttpLink({
//   // You should use an absolute URL here
//   uri: 'https://dongmeiwei.com/graphql',
// })

// Create the apollo client
// const apolloClient = new ApolloClient({
//   link,
//   cache: new InMemoryCache(),
//   connectToDevTools: true,
// });

// Install the vue plugin
Vue.use(VueApollo)

//Create a provider
const apolloProvider = new VueApollo({
  defaultClient: apolloClient,
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('apollo', require('./components/Apollo.vue'));

const app = new Vue({
	apolloProvider
})
.$mount('#app');
