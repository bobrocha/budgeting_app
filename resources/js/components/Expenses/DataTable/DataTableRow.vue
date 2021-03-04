<template>
	<tr>
		<td
			v-for="(field, i) in row.fields"
			:key="`${field}-${i}`"
		>{{ field }}</td>
		<td class="row-controls">
			<i
				class="fas fa-edit"
				title="Edit"
				@click="$emit('editRow', row)"
			></i>
			<i
				class="fas fa-trash-alt"
				title="Delete"
				@click="$emit('deleteRow', row)"
			></i>
		</td>
	</tr>
</template>

<script>
export default {
	props : {
		row : Object,
	},
}
</script>

<style>
.data-table tbody .row-controls {
	text-align: center;
}

.data-table tbody .row-controls i {
	cursor: pointer;
}
</style>