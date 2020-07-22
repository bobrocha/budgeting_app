<template>
	<div class="container">
			<Navigation :name="user.name" />
			<MainContent>
				<RouterView />
			</MainContent>
	</div>
</template>
<script>
import Navigation   from '../components/Navigation';
import MainContent  from '../components/MainContent';
import { mapState } from 'vuex';
import API          from '../api';

export default {
	components : {
		Navigation,
		MainContent,
	},
	mounted() {
		this.$store.dispatch('getUser');
	},
	computed : {
		...mapState(['user']),
	},
}
</script>
<style>
html {
	font-family: 'Font Awesome 5 Free', sans-serif;
	font-size: 100%;
}

body {
	background-color: #e9ebee;
}
.container {
	width: 100%;
	margin: 1rem auto;
	border-radius: 4px;
	background-color: #F3F3F3;
	box-shadow: 0px 5px 10px 5px #a6a6a6;
	min-height: 800px;
	overflow: hidden;
}

@media (min-width: 576px) {
	.container {
		/* max-width: 540px; */
		/* background-color: pink; */
	}
}

@media (min-width: 768px) {
	.container {
		/* max-width: 720px; */
		/* display: flex; */
		/* background-color: red; */
	}
}

@media (min-width: 992px) {
	.container {
		/* max-width: 960px; */
		/* background-color: blue; */
		display: flex;
	}
}

@media (min-width: 1200px) {
	.container {
		max-width: 1140px;
		/* background-color: orange; */
	}
}

.hidden {
	display: none;
}

*,
*::before,
*::after {
	box-sizing: border-box;
}
</style>