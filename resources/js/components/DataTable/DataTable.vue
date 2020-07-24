<template>
	<div class="data-table-container">
		<div class="controls">
			<button class="filter-row-button" @click="toggleFilterRow">{{ filter_row_button_text }}</button>
		</div>
		<table class="data-table">
			<data-table-head
				:fields="fields"
				:filter_row_toggle="filter_row_toggle"
				@filterRows="filterRows"
				@sortTableByFieldId="sortTableByFieldId"
			>
			</data-table-head>
			<data-table-body
				:rows="displayed_rows"
			></data-table-body>
		</table>
		<pagination
			:items_per_page="items_per_page"
			:rows="paginated_rows"
			@paginateRows="paginateRows"
			:page_index="page_number"
		></pagination>
	</div>
</template>

<script>
import DataTableHead from './DataTableHead';
import DataTableBody from './DataTableBody';
import Pagination    from './Pagination';

export default {
	props : {
		fields         : Array,
		rows           : Array,
		items_per_page : Number,
	},
	components : {
		DataTableHead,
		DataTableBody,
		Pagination,
	},
	data () {
		return {
			filter_row_toggle      : false,
			displayed_rows         : null,
			paginated_rows         : null,
			filter_row_button_text : 'Show Filter',
			sort_key               : false,
			page_number            : null,
		}
	},
	methods : {
		filterRows(filter_row_obj) {
			const object_keys = Object.keys(filter_row_obj);

			const filtered = this.rows.filter(row => {
				return object_keys.every(key => {
					const found_value = String(row[key]).toLowerCase();
					const input_value = String(filter_row_obj[key]).toLowerCase();

					return found_value.includes(input_value);
				});
			});

			/**
			 * While doing the filtering,
			 * reset the page index to the
			 * beginning. Updated the rows prop to paginate
			 * the newly filtered results.
			 */
			this.page_number    = 0;
			this.paginated_rows = filtered;
		},
		sortTableByFieldId(id) {
			const sorted_rows = this.paginated_rows;

			sorted_rows.sort((a, b) => {

				if(a[id] === b[id]) {
					return 0;
				}

				// Ascending
				if(this.sort_key === false) {
					return a[id] < b[id] ? -1 : 1;
				}

				// Descending
				return a[id] > b[id] ? -1 : 1;
			});

			this.sort_key       = !this.sort_key;
			this.paginated_rows = sorted_rows;console.log('foo');
		},
		toggleFilterRow() {
			this.filter_row_toggle = !this.filter_row_toggle;

			if(this.filter_row_toggle) {
				this.filter_row_button_text = 'Hide Filter Row';
			} else {
				this.filter_row_button_text = 'Show Filter Row';
			}
		},

		/**
		 * This method is triggered by an event in the pagination
		 * component, receiving the rows to display for the page.
		 */
		paginateRows(rows) {
			this.displayed_rows = rows;
		},
	},
	created() {
		/**
		 * This property is used by
		 * the pagination component
		 * which takes care of the pagination.
		 */
		this.paginated_rows = this.rows;

		/**
		 * This property is what is sent down
		 * to the table body to display. It is
		 * updated by filtering, sorting and pagination.
		 */
		this.displayed_rows = this.rows;
	}
}
</script>

<style>
table.data-table {
	width: 100%;
	border-collapse: collapse;
	border: 1px solid #ddd;
}

.data-table tr {
	border-top: 1px solid #ddd;
}

.data-table tr:nth-child(odd) td {
	background-color: #F3F3F3;
}

.data-table td, .data-table th {
	padding: 8px 10px;
	border: 1px solid #ddd;
}

.data-table input {
	width: 100%;
}

.data-table-container .controls {
	text-align: center;
	padding: 1rem;
	border: 1px solid #ddd;
}


</style>