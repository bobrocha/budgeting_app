<template>
	<panel class="profile-card">
		<template v-slot:body>
			<div class="profile-card-graphic">
				<i class="fas fa-user-circle"></i>
			</div>
			<div class="profile-card-info">
				<div class="profile-card-controls">
					<regular-button v-show="!toggled" @click="edit">Edit</regular-button>
					<regular-button :disabled="disabled" @click="save" v-show="toggled">Save</regular-button>
					<regular-button @click="cancelEdit">Cancel</regular-button>
				</div>
				<ul class="profile-card-details">
					<li>
						<label>Name: </label>
						<input type="text" v-model.trim="new_name" v-show="toggled" :placeholder="name" class="profile-card-details-input" required>
						<span v-show="!toggled" class="profile-card-details-data">{{ name }}</span>
					</li>
					<li>
						<label>Email: </label>
						<span class="profile-card-details-data">{{ email }}</span>
					</li>
					<li>
						<label>Password: </label>
						<input type="password" autocomplete="new-password" v-model.trim="new_password" v-show="toggled" placeholder="password" class="profile-card-details-input" required>
						<span v-show="!toggled" class="profile-card-details-data">{{ dummy_password }}</span>
					</li>
				</ul>
				<spinner v-show="initialized_request"></spinner>
				<error :error_exists="error_exists" :error_message="error_message" />
			</div>
		</template>
	</panel>
</template>

<script>
import Panel         from '@/components/Panel';
import RegularButton from '@/components/RegularButton';
import Spinner       from '@/components/Spinner';
import Error         from '@/components/Error';

export default {
	components : {
		Panel,
		RegularButton,
		Spinner,
		Error,
	},

	props : {
		name    : String,
		email   : String,
		user_id : Number,
	},

	data() {
		return {
			toggled             : false,
			dummy_password      : '*******',
			new_name            : '',
			new_password        : '',
			disabled            : false,
			initialized_request : false,
			error_message       : '',
			error_exists        : false,
		}
	},

	methods: {
		cancelEdit() {
			this.toggled             = false;
			this.new_name            = '';
			this.new_password        = '';
			this.error_exists        = false;
			this.initialized_request = false;
		},

		edit() {
			this.toggled  = true;
			this.new_name = this.name;
		},

		save() {
			this.error_message = '';
			this.error_exists  = false;

			const errors = [];

			if(!this.new_name) {
				errors.push('Name field must not be empty');
				this.error_exists  = true;
			}

			if(!this.new_password) {
				errors.push('Password field must not be empty');
				this.error_exists  = true;
			}

			this.error_message = errors.join(', ');

			if(this.new_name && this.new_password) {
				this.disabled            = true;
				this.initialized_request = true;

				this.$store.dispatch('updateUser', {
					id       : this.user_id,
					name     : this.new_name,
					password : this.new_password,
				})
				.then(() => {
					this.toggled      = false;
					this.new_password = '';
					this.new_name     = '';
				})
				.catch(errors => {
					this.error_exists  = true;
					this.error_message = errors.join(', ');
				})
				.finally(() => {
					this.disabled            = false;
					this.initialized_request = false;
				});
			}
		}
	},
}
</script>
<style>

.profile-card-graphic {
	border-bottom: 1px solid  #ddd;
	text-align: center;
	padding-bottom: 0.75rem;
}

.profile-card-graphic .fa-user-circle {
	font-size: 7rem;
}

.profile-card-controls {
	border-bottom: 1px solid  #ddd;
	padding: 0.75rem 0;
	text-align: center;
}


.profile-card-details {
	list-style-type: none;
	margin: 0;
	padding: 0;
}

.profile-card-details li {
	overflow: auto;
	padding: .75rem;
	border-bottom: 1px solid #ddd;
}

.profile-card-details-data,
.profile-card-details label {
	float: left;
	display: block;
}

.profile-card-details label {
	width: 45%;
	text-align: right;
	padding-right: 0.5rem;
}

.profile-card-details-data {
	width: 55%;
}

.profile-card-details-input {

	border: 1px solid #dadada;
	border-radius:3px;
	padding: 0.25rem;
	font-size: 75%;
}

.profile-card-details-input:focus {
	outline: none;
	border-color:#9ecaed;
	box-shadow:0 0 10px #9ecaed;
}

.profile-card .spinner-wrapper {
	margin-top: 1rem;
}
</style>