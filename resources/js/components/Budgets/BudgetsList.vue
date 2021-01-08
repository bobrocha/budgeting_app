<template>
	<div class="budgets-list">
		<div v-show="budgets.length" v-for="(budget, i) in budgets" :key="i" class="budget-list-item">
			<div class="budget-list-item-content">
				<div class="budget-list-item-header">
					<div class="budget-list-item-categoryt">Category : {{ budget.title }}</div>
					<div class="budget-list-item-expense">${{ budget.spent }} of ${{ budget.amount }}</div>
				</div>
				<div class="budget-list-item-meter">
					<meter min="0" :max=budget.amount :value=budget.spent></meter>
				</div>
			</div>
			<div class="budget-list-item-controls">
				<i @click="edit(budget)" class="fas fa-edit" title="Edit"></i>
				<i @click="destroy(budget.id)" class="fas fa-trash-alt" title="Delete"></i>
			</div>
		</div>
		<h3 class="no-budgets-msg" v-show="!budgets.length">No budgets exist for this month</h3>
	</div>
</template>

<script>
import Budgets      from '../../services/Budgets';
import moment       from 'moment';
import { mapState } from 'vuex';

export default {
	props : {
		budgets : {
			type    : Array,
			default : () => [{
				category : 'N/A',
				spent    : 0,
				amount   : 0,
			}],
		},
	},
	data() {
		return {
			//
		}
	},
	methods : {
		destroy(id) {
			this.$emit('deleteBudget', id);
		},
		edit(budget) {
			this.$emit('editBudget', budget, true);
		},
	}
}
</script>

<style>
.no-budgets-msg {
	text-align: center;
}

.budgets-list {
	margin-top: 1rem;
}

.budget-list-item {
	border: 1px solid #ced4da;
	padding: 1rem;
	border-radius: 0.25rem;
	margin-bottom: 1rem;
	display: flex;
}

.budget-list-item-content {
	flex: 1;
}

.budget-list-item-header {
	display: flex;
	justify-content: space-between;
}

.budget-list-item-meter meter {
	height: 25px;
}

.budget-list-item-controls {
	display: flex;
	flex-flow: column wrap;
	margin-left: 1rem;
}

.budget-list-item-controls .fas {
	cursor: pointer;
	margin-bottom: 1rem;
}

.budget-list-item-controls .fas:last-child {
	margin-bottom: 0;
}
</style>