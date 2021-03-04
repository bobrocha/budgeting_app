<template>
	<panel class="expenses-card">
		<template v-slot:body>
			<edit-expense-modal
				v-if="isEditingExpense"
				:isModalVisible="isEditingExpense"
				@closeModal="isEditingExpense = false"
				:rowData="editingRowData"
				@rowUpdated="rowUpdated"
			></edit-expense-modal>
			<add-expense-modal
				v-if="isAddingExpense"
				:isModalVisible="isAddingExpense"
				@closeModal="isAddingExpense = false"
			></add-expense-modal>
			<expenses-controls
				:months="months"
				:years="expenseYears"
				:selectedMonth="selectedMonth"
				:selectedYear="selectedYear"
				@handleFilterRowToggle="filterRowToggle = !filterRowToggle"
				@monthSelected="handleMonthSelect"
				@yearSelected="handleYearSelect"
				@addExpense="isAddingExpense = true"
			></expenses-controls>
			<hr>
			<spinner v-show="initializedRequest"></spinner>
			<data-table
				:fields="fields"
				:rows="rows"
				:pageSize="3"
				:filterRowToggle="filterRowToggle"
				@editRow="handleEditRow"
				@deleteRow="handleDeleteRow"
			></data-table>
		</template>
	</panel>
</template>

<script>
import { mapState, mapActions } from 'vuex';
import Panel                    from '@/components/Panel';
import moment                   from 'moment';
import Spinner                  from '@/components/Spinner';
import DataTable                from '@/components/Expenses/DataTable/Datatable';
import ExpensesControls         from '@/components/Expenses/ExpensesControls';
import EditExpenseModal         from '@/components/Expenses/EditExpenseModal';
import AddExpenseModal          from '@/components/Expenses/AddExpenseModal';


const now    = moment();
const months = [
	'January',
	'February',
	'March',
	'April',
	'May',
	'June',
	'July',
	'August',
	'September',
	'October',
	'November',
	'December',
];

const fields = [
	{
		id    : 'date',
		title : 'Date',
	},
	{
		id    : 'description',
		title : 'Description',
	},
	{
		id    : 'category',
		title : 'Category',
	},
	{
		id    : 'amount',
		title : 'Amount',
	},
];

export default {
	components : {
		Panel,
		Spinner,
		DataTable,
		ExpensesControls,
		EditExpenseModal,
		AddExpenseModal,
	},

	data() {
		return {
			months           : months,
			selectedYear     : +now.format('YYYY'),
			selectedMonth    : now.format('MMMM'),
			fields           : fields,
			filterRowToggle  : false,
			rows             : [],
			isEditingExpense : false,
			editingRowData   : {},
			isAddingExpense  : false,
		}
	},

	created() {
		this.init();
	},

	methods : {
		...mapActions({
			initStore     : 'expensesStore/initStore',
			getExpenses   : 'expensesStore/getExpenses',
			deleteExpense : 'expensesStore/deleteExpense',
		}),

		init() {
			this.initStore(this.selectedYear).then(() => {
				this.handleMonthSelect(this.selectedMonth)
			});
		},

		rowUpdated(data) {
			const id    = data.attributes.transactionID;
			const index = this.rows.findIndex(({ attributes }) => attributes.transactionID === id);

			this.rows.splice(index, 1, data);
		},

		handleDeleteRow(data) {
			const id = data.attributes.transactionID;

			this.deleteExpense(id).then(() => {
				const index = this.rows.findIndex(({ attributes }) => attributes.transactionID === id);

				this.rows.splice(index, 1);
			});
		},

		handleEditRow(data) {
			this.editingRowData   = data;
			this.isEditingExpense = true;
		},

		handleMonthSelect(month) {
			this.selectedMonth = month;

			const filtered = this.expenses.filter(({ fields }) => {
				return month === moment(fields.date, 'MM/DD/YYYY').format('MMMM');
			});

			this.rows = filtered;
		},


		handleYearSelect(year) {
			this.selectedYear = +year;

			this.getExpenses(year).then(() => {
				this.handleMonthSelect(this.selectedMonth);
			});
		},
	},

	computed : {
		...mapState({
			token              : state => state.token,
			expenses           : state => state.expensesStore.expenses,
			expenseYears       : state => state.expensesStore.expenseYears,
			initializedRequest : state => state.expensesStore.initializedRequest,
		}),
	},
}
</script>