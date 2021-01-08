<template>
	<panel class="expenses-card">
		<template v-slot:body>
			<div class="expense-controls">
				<select  class="expense-month-select" v-model="selectedMonth">
					<option
						v-for="(month, i) in months"
						:key="i"
						:value="month"
					>{{ month }}</option>
				</select>
				<select  class="expense-year-select" v-model="selectedYear">
					<option
						v-for="(year, i) in expenseYears"
						:key="i"
						:value="year"
					>{{ year }}</option>
				</select>
			</div>
			<hr>
			<spinner v-show="initalizeRequest"></spinner>
			<data-table
				:fields="fields"
				:rows="displayedRows"
			></data-table>
		</template>
	</panel>
</template>

<script>
import { mapState, mapMutations } from 'vuex';
import Expenses                   from '../../services/Expenses';
import Panel                      from '../Panel';
import moment                     from 'moment';
import Spinner                    from '../Spinner';
import DataTable                  from './DataTable/DataTable';

const now   = moment();
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
	},
	data() {
		return {
			months           : months,
			expensesService  : {},
			currentMonth     : now.format('MMMM'),
			currentYear      : now.format('YYYY'),
			selectedYear     : null,
			selectedMonth    : null,
			initalizeRequest : false,
			fields           : fields,
			displayedRows    : [],

		}
	},
	created() {
		this.expensesService = new Expenses(this.token);
		this.selectedYear    = this.currentYear;
		this.selectedMonth   = this.currentMonth;

		this.getYears();
	},
	methods : {
		...mapMutations(['setExpenses', 'setExpenseYears']),
		getYears() {
			if(this.expenseYears.length) {
				return;
			};
			this.initalizeRequest = true;

			this.expensesService.years()
				.then(response => this.setExpenseYears(response))
				.catch(console.log)
				.finally(() => this.initalizeRequest = false);
		},
		async getExpenses() {
			await this.expensesService
				.get(this.selectedYear)
				.then(response => this.setExpenses(response));
		},
		getExpensesForSelectedMonth() {
			return this.expenses.filter(({ fields }) => {
				return moment(fields.date, 'MM/DD/YYYY').format('MMMM') === this.selectedMonth;
			});
		},
	},
	computed : {
		...mapState(['token', 'expenses', 'expenseYears']),
	},
	watch : {
		selectedYear() {
			this.initalizeRequest = true;

			this.getExpenses().then(() => {
				this.displayedRows = this.getExpensesForSelectedMonth();
			})
			.catch(console.log)
			.finally(() => {
				this.initalizeRequest = false;
			});
		},
		selectedMonth() {
			if(this.expenses.length) {
				this.displayedRows = this.getExpensesForSelectedMonth();
			}
		}
	},
}
</script>

<style>
.expense-controls {
	display: flex;
	justify-content: space-between;
}

.expenses-card select {
	padding: 0.25rem;
}
</style>