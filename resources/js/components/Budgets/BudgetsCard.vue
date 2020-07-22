<template>
	<panel class="budgets-card">
		<template v-slot:body>
			<data-table
				:fields="fields"
			></data-table>
		</template>
	</panel>
</template>

<script>
import Panel           from '../Panel';
import RegularButton   from '../RegularButton';
import Spinner         from '../Spinner';
import Error           from '../Error';
import DataTable       from '../DataTable/DataTable';

const fields = ['Amount','Month','Year'];

export default {
	components : {
		Panel,
		RegularButton,
		Spinner,
		Error,
		DataTable,
	},
	data() {
		return {
			fields : fields,
		};
	},
}
</script>

<style>
.no-budget-card h3 {
	margin: 0;
	text-align: center;
}

.no-budget-card .no-budgets-msg p {
	margin: 0;
	text-align: center;
	line-height: 3rem;
}
</style>