<template>
	<thead class="data-table-head">
		<tr>
			<th
				@click="$emit('sortTableByFieldId', field.id)"
				class="field-title"
				v-for="(field, id) in fields"
				:key="id"
			>{{ field.title }}</th>
		</tr>
		<tr v-show="filter_row_toggle" class="filter-row">
			<td
				v-for="(field, id) in fields"
				:key="id"
			><input v-model="filter_row[`${field['id']}`]"></td>
		</tr>
	</thead>
</template>

<script>
export default {
	props : {
		fields            : Array,
		filter_row_toggle : Boolean,
	},
	data() {
		return {
			filter_row : {},
		};
	},
	watch : {
		/**
		 * Watch for changes to the filter row and emit an event.
		 * This event is then sent to the table triggering a filter
		 * on the rows displayed by the table body.
		 */
		filter_row : {
			handler(filter_row_obj) {
				this.$emit('filterRows', filter_row_obj)
			},
			deep: true
		},
	},
	mounted() {
		//console.log(this.filter_row);
	}
}
</script>

<style>
.data-table .field-title {
	cursor: pointer;
}

.data-table .filter-row {
	background-image: linear-gradient(rgb(189, 188, 188), rgb(131, 130, 130));
}
</style>