<template>
<div class="data-table-wrapper">
	<modal
		:visible="isModalVisible"
		title="Edit Expense"
		@closeModal="isModalVisible = false"
	>
		<template></template>
	</modal>
		<table class="data-table">
			<data-table-head
				:fields="fields"
			></data-table-head>
			<data-table-body
				:rows="rows"
				@editRow="editRow"
				@deleteRow="deleteRow"
			>
			</data-table-body>
		</table>
	</div>
</template>

<script>
import DataTableBody from '../DataTable/DataTableBody';
import DataTableHead from '../DataTable/DataTableHead';
import Modal         from '../../Modal';
import Success       from '../../Success';
import Error         from '../../Error';

export default {
	components : {
		DataTableHead,
		DataTableBody,
		Modal,
		Error,
		Success,
	},
	props : {
		fields : Array,
		rows   : Array,
	},
	data() {
		return {
			isModalVisible : true,
		}
	},
	created() {},
	methods : {
		editRow(data) {
			this.isModalVisible = true;
			console.log(data);
		},
		deleteRow(data) {
			console.log(data);
		},
	},
	computed : {},
	watch : {},
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