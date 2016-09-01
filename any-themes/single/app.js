import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'

Vue.use(VueRouter)
Vue.use(VueResource)

// DEBUG 模式
Vue.config.debug = true
Vue.http.options.headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=utf-8'
Vue.http.options.emulateJSON = true
 
const router = new VueRouter({
	saveScrollPosition: true,
	transitionOnLoad:true,
	history: true
})
const loadComponent = path => {
	return {
		// component:require('./components/'+path+'.vue')
		component(resolve) {
			require(['./components/' + path + '.vue'], resolve)
		}
	}
}
router.map({
	'/': loadComponent('home'),
	'/view': loadComponent('view')
})

router.redirect({
	'*': '/'
})
router.start(require('./app.vue'), '#app')