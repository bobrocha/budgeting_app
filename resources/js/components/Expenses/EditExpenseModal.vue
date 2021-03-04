<template>
	<div class="edit-expenses-modal">
		<modal
				:visible="isModalVisible"
				title="Edit Expense"
				@closeModal="$emit('closeModal')"
			>
			<template v-slot:modal-content>
				<div class="edit-expenses-modal-input">
					<label for="date">Date:</label>
					<input type="date" id="date" v-model.trim="fields.date">
				</div>
				<div class="edit-expenses-modal-input">
					<label for="description">Description:</label>
					<input type="text" id="description" v-model.trim="fields.description">
				</div>
				<div class="edit-expenses-modal-select">
					<label for="category">Category:</label>
					<select v-model.trim="categoryID" id="category">
						<option
							v-for="(option, i) in expenseCategories"
							:key="i"
							:value="option.id"
							id="description"
							:selected="option.id === categoryID"
						>{{ option.title }}</option>
					</select>
				</div>
				<div class="edit-expenses-modal-input">
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
				<spinner v-show="initializdRequest"></spinner>
			</template>
		</modal>
	</div>
</template>

<script>
import Spinner                  from '@/components/Spinner';
import Success                  from '@/components/Success';
import Error                    from '@/components/Error';
import Modal                    from '@/components/Modal';
import moment                   from 'moment';
import { mapState, mapActions } from 'vuex';

export default {
	components : {
		Modal,
		Error,
		Spinner,
		Success,
	},

	props : {
		rowData        : Object,
		isModalVisible : Boolean,
	},

	created() {
		for (const key in this.rowData.fields) {
			if(key === 'date') {
				this.fields[key] = moment(this.rowData.fields[key], 'MM/DD/YYYY').format('YYYY-MM-DD');
			} else {
				this.fields[key] = this.rowData.fields[key];
			}
		}

		this.categoryID    = this.rowData.attributes.categoryID;
		this.transactionID = this.rowData.attributes.transactionID;
	},

	data() {
		return {
			fields            : {},
			categoryID        : null,
			transactionID     : null,
			errorExists       : false,
			errorMessage      : '',
			initializdRequest : false,
			isSuccessful      : false,
			successMessage    : '',
		}
	},

	methods : {
		...mapActions({
			updateExpense : 'expensesStore/updateExpense',
		}),

		save() {
			this.validateModalValues();

			if(!this.errorExists) {
				const data             = this.prepareData();
				this.initializdRequest = true;

				this.updateExpense(data).then((result) => {
					this.$emit('rowUpdated', result);

					this.isSuccessful   = true;
					this.successMessage = 'Row updated';

					setTimeout(() =>  {
						this.isSuccessful   = false;
						this.successMessage = '';

						this.$emit('closeModal');
					}, 750);
				})
				.finally(() =>{
					this.initializdRequest = false;
				});
			}
		},

		prepareData() {
			const fieldsCopy = this.fields;

			fieldsCopy.category_id = this.categoryID;
			delete fieldsCopy.category;

			const dataObject = {
				id      : this.transactionID,
				payload : fieldsCopy,
			};

			return dataObject;
		},

		validateModalValues() {
			const requiredFields = Object.keys(this.fields);
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
				expenses          : state => state.expensesStore.expenses,
				expenseCategories : state => state.expensesStore.expenseCategories,
		}),

		selectedDate() {
			return moment(this.fields.date, 'MM/DD/YYYY').format('YYYY-MM-DD');
		}
	},
}
</script>

<style>
.edit-expenses-modal label {
	display: inline-block;
	margin-top: 0.5rem;
	margin-bottom: 0.5rem;
}

.edit-expenses-modal-select label {
	display: block;;
}

.edit-expenses-modal input,
.edit-expenses-modal-select select {
	text-align: center;
	width: 100%;
	padding: 0.375rem 0.75rem;
	border: 1px solid #ced4da;
	border-radius: 0.25rem;
}

.edit-expenses-controls {
	margin-top: 1rem;
}
</style>