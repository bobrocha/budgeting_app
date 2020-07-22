<template>
	<panel class="login-card">
		<template v-slot:header>
			<h3>Login</h3>
		</template>
		<template v-slot:body>
			<form @submit.prevent>
				<div class="form-group">
					<label for="email">Email</label>
					<input v-model="email" type="email" id="email" required>
				</div>
				<div class="form-group">
					<label for="passeord">Password</label>
					<input v-model="password" type="password" id="password" required>
				</div>
				<error
					:error_exists="error_exists"
					:error_message="error_message"
				></error>
				<spinner v-show="initialized_request"></spinner>
				<div class="form-group">
					<regular-button @click="login" :disabled="login_disabled">Login</regular-button>
				</div>
			</form>
		</template>
	</panel>
</template>

<script>
import Panel         from '../components/Panel';
import RegularButton from '../components/RegularButton';
import Spinner       from '../components/Spinner';
import Error         from '../components/Error';
import API           from '../api';

export default {
	components : {
		Panel,
		RegularButton,
		Error,
		Spinner,
	},
	data() {
		return {
			email               : null,
			password            : null,
			login_disabled      : false,
			error_exists        : false,
			error_message       : '',
			initialized_request : false,
		}
	},
	methods : {
		login() {
			this.error_exists = false;

			if(this.email && this.password) {
				this.login_disabled      = true;
				this.initialized_request = true;

				API.login({
					username : this.email,
					password : this.password,
				}).then(({ access_token }) => {
					this.email    = null;
					this.password = null;

					this.$store.commit('setToken', access_token);
					window.location.reload();
				})
				.catch(({ message }) => {
					this.error_message = message;
					this.error_exists  = true;
				})
				.finally(() => {
					this.login_disabled      = false;
					this.initialized_request = false;
				});
			}
		}
	},
	computed : {
		isAuthenitcated() {
			return this.$store.getters.isAuthenitcated;;
		}
	},
	beforeRouteEnter(to, from, next) {
		next(vm => {
			if(vm.isAuthenitcated) {
				next('home');
			}
		});
	}
}
</script>

<style>
.login-card {
	max-width: 450px;
	max-height: 450px;
	margin: 1rem auto;
}

.login-card h3 {
	margin: 0;
	text-align: center;
}

.login-card .form-group label,
.login-card .form-group input {
	display: block;
	width: 100%;
	margin-bottom: 1rem;
}

.login-card .form-group input {
	padding: 0.5rem;
	border: 1px solid #dadada;
	border-radius: 3px;
}

.login-card .form-group input:focus {
	outline:none;
	border-color:#9ecaed;
	box-shadow:0 0 10px #9ecaed;
}

.login-card .error {
	margin-bottom: 1rem;
}
</style>