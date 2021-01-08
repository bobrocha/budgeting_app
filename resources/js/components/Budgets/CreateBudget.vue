<template>
<div class="create-budget">
	<modal
		:visible="isModalVisible"
		:title="modalTitle"
		@closeModal="closeModal"
	>
		<template v-slot:modal-content>
			<div class="create-budget-modal">
				<h3>Select Category</h3>
				<select v-model="modalValues.category" class="create-budget-categories">
					<option
						v-for="(category, i) in categories"
						:key="i"
						:value="category.id"
					>{{ category.title }}</option>
				</select>
				<div class="create-budget-amount">
					<label for="create-budget-amount">Amount: </label>
					<input
						v-model.trim="modalValues.amount"
						type="number"
						id="create-budget-amount"
						step="0.01"
						placeholder="Budget Amount"
					>
				</div>
				<div class="create-budget-controls">
					<button @click="save" class="create-budget-save-button">Save</button>
					<button @click="closeModal" class="create-budget-cancel-button">Cancel</button>
				</div>
				<error
					:error_exists="errorExists"
					:error_message="errorMessage"
				></error>
				<success
					:successMessage="successMessage"
					:isSucessful="isSucessful"
				></success>
				<spinner v-show="initializdRequest"></spinner>
			</div>
		</template>
	</modal>
	<button class="create-budget-button" @click="showModal">
		<i class="fas fa-plus"></i>Create Budget
	</button>
</div>
</template>

<script>
import Modal                      from '../Modal';
import Spinner                    from '../Spinner';
import Error                      from '../Error';
import Success                    from '../Success';
import Budgets                    from '../../services/Budgets';
import moment                     from 'moment';
import { mapState, mapMutations } from 'vuex';

export default {
	props : {
		categories    : Array,
		selectedMonth : String,
	},
	components : {
		Modal,
		Spinner,
		Error,
		Success,
	},
	data() {
		return {
			modalTitle        : 'Create Budget',
			isModalVisible    : false,
			modalValues       : {},
			errorExists       : false,
			errorMessage      : '',
			successMessage    : '',
			isSucessful       : false,
			budgetsService    : {},
			initializdRequest : false,
		}
	},
	created() {
		this.budgetsService = new Budgets(this.token);
	},
	methods: {
		...mapMutations(['addBudget']),
		closeModal() {
			this.isModalVisible = false;
			this.modalValues    = {};
			this.errorMessage   = '';
			this.errorExists    = false;
			this.isSucessful    = false;
			this.successMessage = '';
		},
		showModal() {
			this.isModalVisible = true;
		},
		save() {
			this.validateModalValues();

			if(!this.errorExists) {
				this.createBudget(this.modalValues);
			}
		},
		validateModalValues() {
			const requiredFields = ['category', 'amount'];
			const missingFields  = [];
			this.errorMessage    = '';

			for(const field of requiredFields) {
				const hasField = this.modalValues.hasOwnProperty(field);

				if(!hasField) {
					missingFields.push(field);
				}

				// Verify field is not empty
				if(hasField) {
					if(this.modalValues[field].length === 0) {
						missingFields.push(field);
					}
				}
			}

			// If there is missing fields, build error message
			if(missingFields.length) {
				this.errorMessage = missingFields.map(field => `The field <b>${field}</b> is required.`).join(`<br>`);
			}

			this.errorExists = !!missingFields.length;
		},
		createBudget(data) {
			const startDate        = moment(this.selectedMonth, 'MMMM').startOf('month').format('YYYY-MM-DD');
			const endDate          = moment(this.selectedMonth, 'MMMM').endOf('month').format('YYYY-MM-DD');
			const year             = moment().format('YYYY');
			const request          = {...data, startDate, endDate, year};
			this.initializdRequest = true;

			this.budgetsService.create(request).then(response => {
				this.isSucessful    = true;
				this.successMessage = 'Budget was added succesfully';
				response.title      = this.categories.find(({ id }) => id === response.category).title;;

				this.addBudget(response);
				setTimeout(() =>  {
					this.isSucessful    = false;
					this.successMessage = '';
				}, 500);
			})
			.catch(error => {
				this.errorExists  = true;
				this.errorMessage = error;
			})
			.finally(() => {
				this.initializdRequest = false;
			});
		}
	},
	computed : {
		...mapState(['token', 'budgets']),
	},
}
</script>

<style>
.create-budget-button {
	padding-left: 1.8rem;
	position: relative;
}

.create-budget-button .fas {
	position: absolute;
	left: 6px;
}

.create-budget-modal {
	display: flex;
	flex-flow: column nowrap;
	color: #495057;
	max-width: 300px;
}

.create-budget-modal input,
.create-budget-modal select {
	border: 1px solid #ced4da;
	border-radius: 0.25rem;
	width: 100%;
	padding: 0.375rem 0.75rem;
}

.create-budget-categories,
.create-budget-amount {
	margin-bottom: 1rem;
}

.create-budget-amount label {
	margin-bottom: 0.5rem;
	display: inline-block;
}

.create-budget-modal .error,
.create-budget-modal .success {
	margin-top: 1rem;
}
</style>