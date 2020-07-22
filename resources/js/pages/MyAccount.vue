<template>
	<ProfileCard
		:name="user.name"
		:email="user.email"
		:user_id="user.id"
	/>
</template>

<script>
import ProfileCard  from '../components/ProfileCard';
import { mapState } from 'vuex';

export default {
	components : {
		ProfileCard,
	},
	computed : {
		...mapState(['user']),
	},
}
</script>
<style>

</style>