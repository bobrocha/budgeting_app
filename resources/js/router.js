import Vue       from 'vue';
import VueRouter from 'vue-router';
import Container from './components/container';
import Home      from './pages/Home';
import Expenses  from './pages/Expenses';
import Budget    from './pages/Budget';
import MyAccount from './pages/MyAccount';
import Login     from './pages/Login';
import store     from './store';

Vue.use(VueRouter);

const routes = [
	{
		path      : '/budgeting_app/public/',
		component : Container,
		name      : 'root',
		children  : [
			{
				path      : 'home',
				component : Home,
				name      : 'home',
			},
			{
				path      : 'expenses',
				component : Expenses,
				name      : 'expenses',
			},
			{
				path      : 'budget',
				component : Budget,
				name      : 'budget',
			},
			{
				path      : 'myaccount',
				component : MyAccount,
				name      : 'myaccount',
			},
		],
	},
	{
		path      : '/budgeting_app/public/login',
		component : Login,
		name      : 'login',
	}
];

const router = new VueRouter({
	mode   : 'history',
	routes : routes,
});

let isAuthenticated = store.getters.isAuthenitcated;

router.beforeEach((to, from, next) => {
	if(to.name !== 'login' && !isAuthenticated) {
		next({ name: 'login' });
	} else {
		next();
	}
});

export default router;