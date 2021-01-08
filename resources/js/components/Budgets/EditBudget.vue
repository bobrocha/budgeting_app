<template>
	<div class="edit-budget">
		<modal
			:visible="isModalVisible"
			:title="budget.title"
			@closeModal="closeModal"
		>
			<template v-slot:modal-content>
				<div class="edit-budget-content">
					<div class="edit-budget-amount">
						<label for="edit-amount">Amount: $</label>
						<input
							v-model.trim="amountModel"
							type="number"
							id="edit-amount"
							step="0.01"
							placeholder="Budget Amount"
						>
					</div>
					<div class="edit-budget-controls">
						<button @click="save" class="edit-budget-save-button">Save</button>
						<button @click="closeModal" class="edit-budget-cancel-button">Cancel</button>
					</div>
					<success
						:successMessage="successMessage"
						:isSucessful="isSucessful"
					></success>
					<spinner v-show="initializdRequest"></spinner>
				</div>
			</template>
		</modal>
	</div>
</template>

<script>
import Modal            from '../Modal';
import Spinner          from '../Spinner';
import Error            from '../Error';
import Success          from '../Success';
import Budgets          from '../../services/Budgets';
import { mapMutations } from 'vuex';

export default {
	props : {
		budget         : Object,
		isEditing      : Boolean,
		amount         : String,
		budgetsService : Object,
	},
	components : {
		Modal,
		Spinner,
		Error,
		Success,
	},
	data() {
		return {
			isModalVisible    : false,
			amountModel       : this.amount,
			initializdRequest : false,
			successMessage    : '',
			isSucessful       : false,
		}
	},
	methods : {
		...mapMutations(['updateBudget']),
		closeModal() {
			this.$emit('editBudget', {}, false);
		},
		save() {
			if(this.amountModel) {
				this.initializdRequest = true;
				const budgetObject     = {...this.budget};

				budgetObject.amount = this.amountModel;

				this.budgetsService.update(budgetObject).then(response => {
					budgetObject.amount = response.amount;

					this.updateBudget(budgetObject);

					this.isSucessful    = true;
					this.successMessage = 'Budget was succesfully updated';

					setTimeout(() =>  {
						this.isSucessful    = false;
						this.successMessage = '';
						this.closeModal();
					}, 750);
				})
				.catch(error => console.log(error))
				.finally(() => {
					this.initializdRequest = false;
				});
			}
		},
	},
	watch : {
		isEditing(val) {
			this.isModalVisible = val;
		},
		amount(val) {
			this.amountModel = val;
		}
	},
}
</script>

<style>
.edit-budget-content {
	display: flex;
	flex-flow: column nowrap;
	color: #495057;
	max-width: 400px;
}

.edit-budget-amount {
	display: flex;
	margin-bottom: 1rem;
	align-items: center;
}

.edit-budget-amount input {
	margin-left: 0.375rem;
	border: 1px solid #ced4da;
	border-radius: 0.25rem;
	padding: 0.375rem 0.75rem;
}

.edit-budget-content .error,
.edit-budget-content .success {
	margin-top: 1rem;
}
</style>