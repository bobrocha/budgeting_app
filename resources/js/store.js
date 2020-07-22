import Vue     from 'vue';
import Vuex    from 'vuex';
import Account from './services/Account';

Vue.use(Vuex);

const initial_state = {
	toke : sessionStorage.getItem('access_token') || null,
	user : {},
}

const store = new Vuex.Store({
	state : {
		token : sessionStorage.getItem('access_token') || null,
		user  : {},
	},
	mutations : {
		setToken(state, token) {
			state.token = token;

			sessionStorage.setItem('access_token', token);
		},
		unsetToken(state) {
			state.token = null;

			sessionStorage.removeItem('access_token');

			this.replaceState(initial_state);
		},
		setUser(state, user) {
			state.user = user;
		},
		setUserName(state, name) {
			state.user.name = name;
		}
	},
	getters : {
		isAuthenitcated(state) {
			return state.token !== null;
		},
	},
	actions : {
		async getUser({ commit, state }) {
			const result = await Account.getUser(state.token);

			commit('setUser', result);

			return result;
		},

		async updateUser({ commit, state }, { id, name, password }) {
			const result = await Account.updateUser(state.token, id, name, password);

			if(result.errors) {
				throw result.errors;
			}

			commit('setUserName', result.name);
		}
	}
});

export default store;