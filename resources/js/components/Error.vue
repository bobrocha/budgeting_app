<template>
	<div v-show="error_exists" class="error">
		{{ error_message }}
	</div>
</template>

<script>
export default {
	props : {
		error_exists : {
			type    : Boolean,
			default : false,
		},
		error_message : {
			type : String,
			default : '',
		},
	},
}
</script>

<style>
.error {
	background-color: #d9534f;
	color: #fff;
	border-radius: 3px;
	padding: 0.5rem;
	text-align: center;
}
</style>