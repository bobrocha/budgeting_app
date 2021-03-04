<template>
	<panel class="budgets-card">
		<template v-slot:body>
			<div class="budget-header">
				<h2>{{ budgetDate }}</h2>
				<div class="budget-years">
					<a href="#">Current Year</a>
					<a href="#">Last Year</a>
					<a href="#">All Time</a>
				</div>
			</div>
			<ul class="budget-timeline" @click="setSelectedMonth">
				<li
					v-for="(month, i) in months"
					:key="i"
					class="budget-timeline-item"
				>
					<a
						:class="{ 'budget-timeline-active-item' : month === selectedMonth }"
						:data-month="month"
						href="#"
					>{{ month }}</a>
				</li>
			</ul>
			<hr>
			<div class="budget-controls">
				<create-budget
					:categories="categories"
					:selectedMonth="selectedMonth"
				></create-budget>
				<div class="budget-sort">
					Sort:
					<select v-model="selectedSort">
						<option></option>
						<option value="title">Category</option>
						<option value="amount-desc">Hight - Low Amount</option>
						<option value="amount-asc">Low - High Amount</option>
					</select>
				</div>
			</div>
			<edit-budget
				:budget="editBudgetObject"
				:isEditing="isEditing"
				@editBudget="editBudget"
				:amount="amount"
				:budgetsService="budgetsService"
			></edit-budget>
			<hr>
			<spinner v-show="initializedRequest"></spinner>
			<budgets-list
				:budgets="getBudgetsForCurrentMonth"
				@deleteBudget="deleteBudget"
				@editBudget="editBudget"
			></budgets-list>
		</template>
	</panel>
</template>

<script>
import Panel                      from '@/components/Panel';
import Spinner                    from '@/components/Spinner';
import CreateBudget               from '@/components/Budgets/CreateBudget';
import EditBudget                 from '@/components/Budgets/EditBudget';
import moment                     from 'moment';
import { mapState, mapMutations } from 'vuex';
import BudgetCategories           from '@/services/BudgetCategories';
import BudgetsList                from '@/components/Budgets/BudgetsList';
import Budgets                    from '@/services/Budgets';

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

export default {
	components : {
		Panel,
		Spinner,
		CreateBudget,
		EditBudget,
		BudgetsList,
	},
	data() {
		return {
			budgetDate         : '',
			currentMonth       : moment().format('MMMM'),
			categories         : null,
			initializedRequest : false,
			editBudgetObject   : {},
			isEditing          : false,
			budgetsService     : {},
			months             : months,
			selectedMonth      : '',
			selectedSort       : null,
		};
	},
	created() {
		this.getBudgets();
		this.setSelectedMonth(null, this.currentMonth);
	},
	methods : {
		...mapMutations(['removeBudget', 'setBudgets']),
		setSelectedMonth(event, month) {
			// This will run if it's triggered with a click
			if(event && event.target.tagName === 'A') {
				const clickedMonth = event.target.dataset.month;
				this.budgetDate    = moment(clickedMonth, 'MMMM').format('MMMM YYYY');
				this.selectedMonth = clickedMonth;
			}

			// This get's run if it's not triggered with a click
			if(month) {
				this.budgetDate    = moment().format('MMMM YYYY');
				this.selectedMonth = month;
			}

			this.selectedSort = null;
		},
		getBudgets() {
			if(this.budgets.length) {
				return;
			}

			this.initializedRequest      = true;
			this.budgetsService          = new Budgets(this.token);
			this.budgetCategoriesService = new BudgetCategories(this.token);

			this.budgetCategoriesService.get().then(response => this.categories = response);
			this.budgetsService.get().then(response => {
				this.setBudgets(response);
			})
			.finally(() => {
				this.initializedRequest = false;
			});
		},
		deleteBudget(id) {
			this.budgetsService.destroy(id).then(() => {
				this.removeBudget(id);
			});
		},
		/**
		 * This method toggles the isEditing prop in order to bring up
		 * the modal in EditBudget and populate an object editBudgetObject that is used
		 * by EditBudget to display information on the modal. Tis method is initially triggered
		 * by BudgetList and affects EditBudget.
		 */
		editBudget(budget, isEditing) {
			this.editBudgetObject = budget;
			this.isEditing        = isEditing;
		},
	},
	computed : {
		...mapState(['token', 'budgets']),
		amount() {
			return this.editBudgetObject.amount ? this.editBudgetObject.amount : '0';
		},
		getBudgetsForCurrentMonth() {
			const sortType = this.selectedSort;
			const result   = this.budgets.filter(({ month }) => month === this.selectedMonth );

			if(sortType === 'amount-asc') {
				result.sort((a, b) => +a.amount - +b.amount);
			} else if(sortType === 'amount-desc') {
				result.sort((a, b) => +a.amount - +b.amount).reverse();
			} else if(sortType === 'title') {
				result.sort((a, b) => {
					const titleA = a.title.toUpperCase();
					const titleB = b.title.toUpperCase();

					return titleA > titleB ? 1 : -1;
				});
			}

			return result;
		}
	},
}
</script>

<style>
.budgets-card a {
	text-decoration: none;
}

.budget-timeline {
	list-style-type: none;
	padding: 0;
	display: flex;
	flex-flow: row wrap;
	justify-content: space-between;
}

.budget-years a,
.budget-timeline-item a{
	color: rgb(134, 133, 133);
	font-weight: 100;
}

.budget-timeline-item a:hover {
		background-color: rgb(109, 109, 109);
	color: #fff;
}

.budget-timeline-item a {
	display: block;
	padding: 0.5rem;
	font-size: 0.8rem;
	background-color: rgb(238, 236, 236);
	margin-right: 0.2rem;
	margin-top: 0.2rem;
	border-radius: 5px;
	border: 1px solid rgb(206, 204, 204);
}
.budget-header h2 {
	font-weight: 100;
	color: rgb(134, 133, 133);
}

.budget-timeline-active-item {
	background-color: rgb(109, 109, 109) !important;
	color: #fff !important;
}

.budget-controls {
	display: flex;
	justify-content: space-between;
	align-items: center;
}

.budget-sort select {
	padding: 0.25rem;
	height: 2rem;
}

@media (min-width: 992px) {

}
</style>