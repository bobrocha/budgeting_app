<template>
<div class="data-table-wrapper">
		<table class="data-table">
			<data-table-head
				:fields="fields"
				:filterRowToggle="filterRowToggle"
				@filterRow="handleFilterRow"
				@sortResults="sortResults"
			></data-table-head>
			<data-table-body
				:rows="displayedRows"
				@editRow="$emit('editRow', $event)"
				@deleteRow="$emit('deleteRow', $event)"
			>
			</data-table-body>
		</table>
		<data-table-pagination
			:rows="paginatedRows"
			:pageSize="pageSize"
			@setDisplayedRows="setDisplayedRows"
		></data-table-pagination>
	</div>
</template>

<script>
import DataTableBody       from '@/components/Expenses/DataTable/DataTableBody';
import DataTableHead       from '@/components/Expenses/DataTable/DataTableHead';
import DataTablePagination from '@/components/Expenses/DataTable/DataTablePagination';

export default {
	components : {
		DataTableHead,
		DataTableBody,
		DataTablePagination,
	},

	props : {
		fields          : Array,
		rows            : Array,
		pageSize        : Number,
		filterRowToggle : Boolean,
	},

	data() {
		return {
			/**
			 * This is a collection of rows
			 * that would represent a "page"
			 * amongst the pages genereated
			 * by pagination that is then displayed
			 * in the table body
			 */
			displayedRows : [],
			/**
			 * This property is initllay a copy of all
			 * the rows. It's then passed down to the pagination
			 * compnent for pagination. This property is updated by
			 * the table head when there is filtering, or sorting involved.
			 * This property is updated as well by the month and year controls
			 * to trigger pagination and what is displayed on the page. Essentially
			 * this property affects what is displayed on the table body.
			 */
			paginatedRows : this.rows,
			sortKey       : false,
		}
	},

	methods : {
		setDisplayedRows(rows) {
			this.displayedRows = rows;
		},

		handleFilterRow(filterRowObj) {
			const objectKeys = Object.keys(filterRowObj);

			const filtered = this.rows.filter(row => {
				return objectKeys.every(key => {
					const foundValue = String(row.fields[key]).toLowerCase();
					const inputValue = String(filterRowObj[key]).toLowerCase();

					return foundValue.includes(inputValue);
				});
			});

			this.paginatedRows = filtered;
		},

		sortResults(id) {
			const sortedRows = this.paginatedRows;

			sortedRows.sort((a, b) => {
				let fieldA = a.fields[id].toLowerCase();
				let fieldB = b.fields[id].toLowerCase();

				if(Number.isFinite(+fieldA) && Number.isFinite(+fieldB)) {
					fieldA = +fieldA;
					fieldB = +fieldB;
				}

				if(fieldA === fieldB) {
					return 0;
				}

				// Ascending
				if(this.sortKey === false) {
					return fieldA < fieldB ? -1 : 1;
				}

				// Descending
				return fieldA > fieldB ? -1 : 1;
			});

			this.sortKey       = !this.sortKey;
			this.paginatedRows = sortedRows;
		},
	},

	watch : {
		rows(val) {
			/**
			 * If ajax or something updates the rows updated
			 * paginatedRows so that pagination is triggered.
			 */
			this.paginatedRows = val;
		}
	},
}
</script>

<style>
.data-table {
	width: 100%;
	border-collapse: collapse;
	border: 1px solid #ddd;
}

.data-table tr {
	border-top: 1px solid #ddd;
}

.data-table tbody tr:nth-child(odd) td {
	background-color: #F3F3F3;
}
</style>