<template>
	<button @click="$emit('click')">
		<slot></slot>
	</button>
</template>

<style>
button {
	margin: .25rem 0;
	padding: .25rem .50rem;
	background-color: #f5f5f5;
	cursor: pointer;
	border-width: 1px;
	border-radius: .25rem;
}

button:hover {
	background-color: #6c757d;
	color: #fff
}

button:focus {
	outline: none;
}
</style>