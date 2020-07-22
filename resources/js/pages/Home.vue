<template>
	<div class="home">
		<div class="row">
			<panel class="home-panel">
				<template v-slot:header>
					<h3>Recent Expenses</h3>
				</template>
				<template v-slot:body>
					<RecentExpensesTable />
				</template>
			</panel>
			<panel class="home-panel">
				<template v-slot:header>
					<h3>Expenses For Month</h3>
				</template>
				<template v-slot:body>
					<ExpensesMeter />
				</template>
			</panel>
		</div>
		<div class="row">
			<panel class="home-panel">
				<template v-slot:header>
					<h3>Budget</h3>
				</template>
				<template v-slot:body>
					<p>$1500.00</p>
				</template>
			</panel>
			<panel class="home-panel">
				<template v-slot:header>
					<h3>Available Funds</h3>
				</template>
				<template v-slot:body>
					<p>$600.00</p>
				</template>
			</panel>
		</div>
	</div>
</template>

<script>
import Panel               from '../components/Panel';
import RecentExpensesTable from '../components/RecentExpensesTable';
import ExpensesMeter       from '../components/ExpensesMeter';
import API                from '../api';

export default {
	components : {
		Panel,
		RecentExpensesTable,
		ExpensesMeter,
	},
}
</script>

<style>
.home-panel {
	margin-bottom: 1rem;
}
.home-panel h3 {
	margin: 0;
}

.home-panel .panel-body p {
	font-size: 2rem;
	margin: 0;
	text-align: center;
	line-height: 6rem;
}
@media (min-width: 768px) {
	.row {
		display: flex;
		margin-bottom: 1rem;
	}

	.home-panel.panel {
		flex: 1;
	}

	.home-panel.panel:nth-child(2) {
		margin-left: 1rem;
	}
}
</style>