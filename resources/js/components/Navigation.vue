<template>
	<nav>
		<div class="hamburger-menu">
			<a @click="hamburger_menu = !hamburger_menu" href="#"><i class="fas fa-bars"></i></a>
		</div>
		<ul :class="{ hidden : hamburger_menu }">
			<li><a href="#"><i class="fas fa-id-badge"></i>{{ name }}</a></li>
			<li><router-link to="/budgeting_app/public/home"><i class="fas fa-home"></i>Home</router-link></li>
			<li><router-link to="/budgeting_app/public/expenses"><i class="fas fa-money-bill-alt"></i>Expenses</router-link></li>
			<li><router-link to="/budgeting_app/public/budget"><i class="fas fa-piggy-bank"></i>Budgets</router-link></li>
			<li><router-link to="/budgeting_app/public/myaccount"><i class="fas fa-user-circle"></i>My Account</router-link></li>
			<li><a @click="logout" href="#"><i class="fas fa-sign-out-alt"></i>Log out</a></li>
		</ul>
	</nav>
</template>
<script>

export default {
	props : {
		name : {
			type : String,
			default : '',
		},
	},

	data() {
		return {
			hamburger_menu : true,
		}
	},

	methods : {
		logout() {
			this.$store.commit('unsetToken');
			window.location.reload();
		},
	},
}
</script>
<style>
nav {
	/* flex: 1; */
}

nav ul {
	background-color: #fff;
	list-style-type: none;
	margin: 0;
	padding: 0;
	height: 100%;
	/* text-align: center; */
	font-weight: 100;
}

nav ul li {
	border-bottom: 1px solid #ccc;
}

nav ul a {
	text-decoration: none;
	color: #333;
	display: block;
	padding: 1rem;
}

nav ul a .fas {
	margin-right: 0.5rem;
}

nav ul a:hover {
	background-color: rgb(165, 165, 165);
	color: #fff;
}

.router-link-active {
	border-right: 6px solid rgb(63, 63, 63);
	background-color: rgb(165, 165, 165);
}

.router-link-active {
	font-weight: 900;
	color: rgb(63, 63, 63);
}

.hamburger-menu {
	background-color: rgb(165, 165, 165);
	padding: 1rem;
}

.hamburger-menu a {
	color: #333;
}

@media (min-width: 992px) {
	nav {
		flex: 1;
	}

	nav ul {
		display: block !important;
	}

	.hamburger-menu {
		display: none;
	}
}
</style>