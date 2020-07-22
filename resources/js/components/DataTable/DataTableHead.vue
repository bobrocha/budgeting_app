<template>
	<thead class="data-table-head">
		<tr class="title-row">
			<th
				v-for="(field, index) in fields"
				:key="index"
			>{{ field }}</th>
			<th colspan="2"></th>
		</tr>
		<tr class="insert-row">
			<slot></slot>
			<td><button>Add</button></td>
			<td><button>Clear</button></td>
		</tr>
	</thead>
</template>

<script>
export default {
	props : {
		fields : Array,
		insert_row : Array,
	},
	mounted() {
		console.log(this.insert_row);
	}
}
</script>

<style>
.data-table-head .title-row {
	text-align: left;
}

.data-table-head .insert-row input {
	width: 100%;
}
</style>