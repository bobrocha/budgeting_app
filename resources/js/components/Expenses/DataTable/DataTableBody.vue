<template>
	<tbody>
		<tr
			v-for="(row, i) in rows"
			:key="i"
		>
			<td
				v-for="(field, i) in row.fields"
				:key="i"
			>{{ field }}</td>
			<td class="row-controls">
				<i @click="editRow(row)" class="fas fa-edit" title="Edit"></i>
				<i  @click="deleteRow(row)" class="fas fa-trash-alt" title="Delete"></i>
			</td>
		</tr>
	</tbody>
</template>

<script>
export default {
	props : {
		rows : Array,
	},
	data() {
		return {}
	},
	methods : {
		editRow(row) {
			this.$emit('editRow', {...row});
		},
		deleteRow(row) {
			this.$emit('deleteRow', row.attributes.transactionID);
		},
	},
	computed : {},
	watch : {},
}
</script>

<style>
.data-table tbody .row-controls i {
	cursor: pointer;
}
</style>