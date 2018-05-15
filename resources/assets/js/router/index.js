import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter) 

const routes = [
  { path: '/', redirect: '/notebooks' },
  { path: '/notebooks', component: require('../components/write/Notebooks.vue')},
  { 
    path: '/notebooks/:collectionId', 
    component: require('../components/write/Notebooks.vue'), 
    props: true,
    children: [
      {
        path: 'notes/:articleId',
        component: require('../components/write/Notes.vue'),
      },
    ]
  },
  { path: '/recycle', component: require('../components/write/Recycle.vue') },
  { path: '/recycle/:recycleId', component: require('../components/write/Recycle.vue'),props: true, },
]

export default new VueRouter({
  routes
})