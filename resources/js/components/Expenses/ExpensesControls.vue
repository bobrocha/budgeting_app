<template>
	<div class="expenses-controls">
		<select
			v-if="months.length"
			:value="selectedMonth"
			lass="expense-month-select"
			@change="handleMonthSelect"
		>
			<option
				v-for="(month, i) in months"
				:key="i"
				:value="month"
			>{{ month }}</option>
		</select>
		<button
			class="add-expense-button"
			@click="$emit('addExpense')"
		><i class="fa fa-plus"></i>Add a Expense</button>
		<button
			@click="handleFilterRowToggle"
		>{{ filterToggleText }}</button>
		<select
			v-if="years.length > 1"
			:value="selectedYear"
			class="expense-year-select"
			@change="handleYearSelect"
		>
			<option
				v-for="(year, i) in years"
				:key="i"
				:value="year"
			>{{ year }}</option>
		</select>
	</div>
</template>

<script>
export default {
	props : {
		months        : Array,
		years         : Array,
		selectedMonth : String,
		selectedYear  : Number,
	},

	data() {
		return {
			filterToggle : false,
			insertToggle : false,
		}
	},

	methods : {
		handleFilterRowToggle() {
			this.filterToggle = !this.filterToggle;

			if(this.insertToggle) {
				this.insertToggle = !this.insertToggle;
			}

			this.$emit('handleFilterRowToggle');
		},

		handleInsertRowToggle() {
			this.insertToggle = !this.insertToggle;

			if(this.filterToggle) {
				this.filterToggle = !this.filterToggle;
			}

			this.$emit(`handleInsertRowToggle`);
		},

		handleMonthSelect(e) {
			this.$emit('monthSelected', e.target.value)
		},

		handleYearSelect(e) {
			this.$emit('yearSelected', e.target.value)
		},
	},

	computed : {
		filterToggleText() {
			return this.filterToggle ? 'Hide Filter Row' : 'Show Filter Row';
		},

		insertToggleText() {
			return this.insertToggle ? 'Hide Inser Row' : 'Show Insert Row';
		},
	},

	watch: {
		months(val) {console.log(val)}
	},

}
</script>

<style>
.expenses-controls {
	display: flex;
	justify-content: space-between;
}

.expenses-controls select {
	padding: 0.25rem;
}

.add-expense-button {
	position: relative;
	padding-left: 1.8rem;
}

.add-expense-button .fa-plus {
	position: absolute;
	left: 6px;
}
</style>