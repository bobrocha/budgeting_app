<template>
	<div class="main-content">
		<slot></slot>
	</div>
</template>

<script>
export default {

}
</script>

<style>
.main-content {
	padding: 1rem;
}

@media (min-width: 992px) {
	.main-content {
		flex: 4;
		border-left: 1px solid #ccc;
	}
}
</style>