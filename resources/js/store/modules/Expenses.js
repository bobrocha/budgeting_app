import ExpensesService          from '@/services/Expenses';
import BudgetCategoriesService  from '@/services/BudgetCategories';
import moment                   from 'moment';

const state = () => ({
	expenses           : [],
	displayedRows      : [],
	expenseYears       : [],
	expenseCategories  : [],
	api                : null,
	initializedRequest : false,
});

const getters = {};

const mutations = {
	setExpenseCategories(state, categories) {
		state.expenseCategories = categories;
	},

	setExpenses(state, expenses) {
		state.expenses = expenses;
	},

	setExpenseYears(state, years) {
		state.expenseYears = years;
	},

	setApi(state, api) {
		state.api = api;
	},

	setInitializedRequest(state, initialized) {
		state.initializedRequest = initialized;
	},

	updateExpense(state, {id, category_id, date, amount, description }) {
		const categoryID    = category_id;
		const transactionID = id;
		const category      = state.expenseCategories.find(({ id }) => id === category_id).title;
		const index         = state.expenses.findIndex(({ attributes }) => attributes.transactionID === id);

		const expense = {
			attributes : {
				categoryID,
				transactionID
			},
			fields : {
				date : moment(date, 'YYYY-MM-DD').format('MM/DD/YYYY'),
				description : description,
				category    : category,
				amount      : amount,
			}
		};

		state.expenses.splice(index, 0, expense);
	},

	deleteExpense(state, id) {
		const index = state.expenses.findIndex(({ attributes }) => attributes.transactionID === id);

		state.expenses.splice(index, 1);
	},

	addExpense(state, expense) {console.log(state.expenses.length);
		state.expenses.unshift(expense);console.log(state.expenses.length)
	}
};

const actions = {
	async initStore({ state, rootState, commit, dispatch }, currentYear) {
		commit('setInitializedRequest', true);
		commit('setApi', new ExpensesService(rootState.token));

		await dispatch('getYears');
		await dispatch('getExpenses', currentYear);
		await dispatch('getExpenseCategories');

		commit('setInitializedRequest', false);
	},

	async getYears({ state, commit }) {
		const result = await state.api.years();

		commit('setExpenseYears', result);
	},

	async getExpenses({ state, commit }, year) {
		const result = await state.api.get(year);

		commit('setExpenses', result);
	},

	async getExpenseCategories({ rootState, commit }) {
		const service = new BudgetCategoriesService(rootState.token);
		const result  = await service.get();

		commit('setExpenseCategories', result);
	},

	async updateExpense({ state, commit }, data) {
		const result = await state.api.patch(data);

		commit('updateExpense', result);

		return state.expenses.find(({ attributes }) => attributes.transactionID === result.id);
	},

	async deleteExpense({ state, commit }, id) {
		commit('setInitializedRequest', true);

		const result = await state.api.delete(id);

		commit('deleteExpense', id);
		commit('setInitializedRequest', false);

		return result;
	},
};

export default {
	namespaced : true,
	state,
	getters,
	actions,
	mutations,
}