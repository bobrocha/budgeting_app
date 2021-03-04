<template>
	<div class="add-expense-modal">
		<modal
			:visible="isModalVisible"
			@closeModal="$emit('closeModal')"
			title="Add a Expense"
		>
		<template v-slot:modal-content>
			<div class="add-expense-modal-input">
					<label for="date">Date:</label>
					<input type="date" id="date" v-model.trim="fields.date">
			</div>
			<div class="add-expenses-modal-input">
				<label for="description">Description:</label>
				<input type="text" id="description" v-model.trim="fields.description">
			</div>
			<div class="add-expense-modal-select">
				<label for="category">Category:</label>
				<select v-model.trim="fields.category" id="category">
					<option
						v-for="(option, i) in expenseCategories"
						:key="i"
						:value="option.id"
					>{{ option.title }}</option>
				</select>
			</div>
			<div class="add-expenses-modal-input">
				<label for="amount">Amount:</label>
				<input type="Number" id="amount" v-model.trim="fields.amount">
			</div>
			<div class="edit-expenses-controls">
				<button @click="save">Save</button>
				<button @click="$emit('closeModal')">Cancel</button>
			</div>
			<error
				:error_exists="errorExists"
				:error_message="errorMessage"
			></error>
			<success
				:successMessage="successMessage"
				:isSuccessful="isSuccessful"
			></success>
			<spinner v-show="initializedRequest"></spinner>
		</template>
		</modal>
	</div>
</template>

<script>
import Spinner                    from '@/components/Spinner';
import Success                    from '@/components/Success';
import Error                      from '@/components/Error';
import Modal                      from '@/components/Modal';
import moment                     from 'moment';
import { mapState, mapMutations } from 'vuex';

export default {
  components : {
		Modal,
		Error,
		Success,
		Spinner,
	},

	props : {
		isModalVisible : {
			type    : Boolean,
			default : false,
		},
	},

	data() {
		return {
			fields             : {},
			errorExists        : false,
			errorMessage       : '',
			successMessage     : '',
			isSuccessful       : false,
			initializedRequest : false
		}
	},

	methods : {
		...mapMutations({
			addExpense : 'expensesStore/addExpense',
		}),

		createExpense(data) {
			this.initializedRequest = true;

			this.api.create(data).then((response) => {
				this.isSuccessful   = true;
				this.successMessage = 'Expense was added';

				this.addExpense(response)

				setTimeout(() => {
					this.$emit('closeModal');
				}, 500);
			})
			.catch(response => {
				this.errorExists  = true;
				this.errorMessage = response.join(',');
			})
			.finally(() => {
				this.initializedRequest = false;
			});
		},

		save() {
			this.validateModalValues();

			if(!this.errorExists) {
				this.createExpense(this.prepareData());
			}
		},

		prepareData() {
			const fieldsCopy = this.fields;

			fieldsCopy.category_id = fieldsCopy.category;
			fieldsCopy.year        = moment(fieldsCopy.date, 'YYYY-MM-DD').format('YYYY');

			delete fieldsCopy.category;

			return fieldsCopy;
		},

		validateModalValues() {
			const requiredFields = ['date', 'description', 'category', 'amount'];
			const missingFields  = [];
			this.errorMessage    = '';

			for(const field of requiredFields) {
				const hasField = this.fields.hasOwnProperty(field);

				if(!hasField) {
					missingFields.push(field);
				}

				if(hasField && this.fields[field].length === 0) {
					missingFields.push(field);
				}
			}

			if(missingFields.length) {
				this.errorMessage = missingFields.map(field => `The field <b>${field}</b> is required.`).join('<br />');
			}

				this.errorExists = !!missingFields.length;
		},
	},

	computed : {
		...mapState({
			api               : state => state.expensesStore.api,
			expenses          : state => state.expensesStore.expenses,
			expenseCategories : state => state.expensesStore.expenseCategories,
		}),
	}
}
</script>

<style>
.add-expense-modal label {
	display: inline-block;
	margin-top: 0.5rem;
	margin-bottom: 0.5rem;
}

.add-expense-modal-select label {
	display: block;;
}

.add-expense-modal input,
.add-expense-modal-select select {
	text-align: center;
	width: 100%;
	padding: 0.375rem 0.75rem;
	border: 1px solid #ced4da;
	border-radius: 0.25rem;
}
</style>