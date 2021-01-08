<template>
	<thead class="data-table-head">
		<tr>
			<th
				class="field-title"
				v-for="(field, i) in fields"
				:key="i"
			>{{ field.title }}</th>
			<th></th>
		</tr>
		<tr class="data-table-insert-row"></tr>
	</thead>
</template>

<script>
export default {
	props : {
		fields : Array,
	},
	data() {
		return {}
	},
	created() {},
	methods : {},
	computed : {},
	watch : {},
}
</script>

<style>
.data-table .field-title {
	cursor: pointer;
}

.data-table thead {
	text-align: left;
}

.data-table td,
.data-table th {
	padding: 0.25rem 0.375rem;
	border: 1px solid #ddd;
}
</style>