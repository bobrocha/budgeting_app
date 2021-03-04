<template>
	<div v-show="isSuccessful" class="success" v-html="successMessage"></div>
</template>
<script>
export default {
	props : {
		successMessage : {
			type    : String,
			default : '',
		},
			isSuccessful : {
				type    : Boolean,
				default : false,
		},
	},
}
</script>

<style>
.success {
	background-color: rgb(5, 160, 5);
	color: #fff;
	border-radius: 3px;
	padding: 0.5rem;
	text-align: center;
}
</style>