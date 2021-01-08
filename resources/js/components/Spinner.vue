<template>
	<div class="spinner-wrapper">
		<span class="spinner-circle"></span>
	</div>
</template>
<style>

.spinner-wrapper {
	margin-top: 1rem;
	text-align: center;
}

.spinner-wrapper .spinner-circle{
	border: 5px solid #f3f3f3;
	border-radius: 50%;
	border-top: 5px solid #3498db;
	width: 25px;
	height: 25px;
	animation: spin 1s linear infinite;
	display: inline-block;
}

@keyframes spin {
	0% { transform: rotate(0deg); }
	100% { transform: rotate(360deg); }
}
</style>