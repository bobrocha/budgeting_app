<template>
	<thead class="data-table-head">
		<tr>
			<th
				class="field-title"
				v-for="(field, i) in fields"
				:key="`${field.id}-${i}`"
				@click="$emit('sortResults', field.id)"
			>{{ field.title }}
				<i class="fa fa-sort"></i>
			</th>
			<th></th>
		</tr>
		<tr v-show="filterRowToggle" class="data-table-filter-row">
			<td
				v-for="(field, i) in fields"
				:key="`${i}-${field.id}`"
			><input type="text" v-model.trim="filterRow[field.id]" /></td>
			<td class="data-table-filter-row-controls">
					<i class="fa fa-filter" title="Add"></i>
			</td>
		</tr>
	</thead>
</template>

<script>
export default {
	props : {
		fields : Array,
		filterRowToggle : {
			default : false,
			type    : Boolean
		},
	},

	data() {
		return {
			filterRow : {},
		}
	},

	watch : {
		filterRowToggle(val) {
			if(!val) {
				this.filterRow = {};
			}
		},

		filterRow :  {
			handler(val) {
			this.$emit('filterRow', val);
			},
			deep : true,
		}
	}
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

.row-controls {
	min-width: 50px;
}

.data-table-filter-row-controls {
	text-align: center;
	cursor: pointer;
}

.data-table-filter-row {
	background-color: rgb(239, 248, 116);
	animation: fadeIn 2s;
}

.data-table-filter-row input {
	width: 100%;
}

@keyframes fadeIn {
	from { opacity: 0; }
	to { opacity: 1; }
}
</style>