<template>
	<div class="panel">
		<div class="panel-header">
			<slot name="header"></slot>
		</div>
		<div class="panel-body">
			<slot name="body"></slot>
		</div>
		<div class="panel-footer">
			<slot name="footer"></slot>
		</div>
	</div>
</template>

<style>
.panel {
	border: 1px solid #ccc;
	border-radius: 4px;
	background-color: #fff;
	overflow: hidden;
	display: flex;
	flex-flow: column nowrap;
	justify-content: space-between;
}

.panel-header,
.panel-body,
.panel-footer {
	padding: 0.75rem;
}

.panel-header {
	background-color: #f5f5f5;;
	border-bottom: 1px solid #ddd;
}

.panel-footer {
	background-color: #f5f5f5;;
	border-top: 1px solid #ddd;
}
</style>