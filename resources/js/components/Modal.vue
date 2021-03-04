<template>
	<div v-show="visible" class="modal-container" tabindex="0" @keyup.esc="close">
		<div class="modal-overlay" @click="close"></div>
		<div class="modal">
			<div class="modal-header">
				<h3>{{ title }}</h3>
				<span @click="close" class="modal-close-button">&times;</span>
			</div>
			<div class="modal-content">
				<slot name="modal-content"></slot>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props : {
		title   : String,
		visible : Boolean,
	},

	methods : {
		close() {
			this.$emit('closeModal');
		},
	},
}
</script>

<style>
.modal-container {
	position: absolute;
	top: 0;
	left: 0;
	color: #495057;
}
.modal-overlay {
	position: fixed;
	z-index: 1000;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	background-color: rgba(0,0,0,0.4);
}

.modal {
	background-color: #fefefe;
	z-index: 10000;
	position: fixed;
	top: 15%;
	left: 50%;
	transform: translateX(-50%);
	min-width: 300px;
	border: 1px solid #888;
	width: auto;
	height: auto;
	margin: auto;
	border-radius: 6px;
}

.modal-header {
	display: flex;
	border-bottom: 1px solid #ced4da;
	padding: 0.5rem 1rem;
}

.modal-header h3 {
	margin: 5px 0 0 0;
}

.modal-content {
	text-align: center;
	padding: 1rem;
}

.modal-close-button {
	color: #aaaaaa;
	font-size: 1.5rem;
	font-weight: bold;
	cursor: pointer;
	margin-left: auto;
}
</style>