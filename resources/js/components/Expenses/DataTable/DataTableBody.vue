<template>
	<tbody>
		<data-table-row
			v-for="(row, i) in rows"
			:key="i"
			:row="row"
			@editRow="$emit('editRow', $event)"
			@deleteRow="$emit('deleteRow', $event)"
		></data-table-row>
	</tbody>
</template>

<script>
import DataTableRow from '@/components/Expenses/DataTable/DataTableRow';

export default {
	components : {
		DataTableRow,
	},
	props : {
		rows : Array,
	},
}
</script>