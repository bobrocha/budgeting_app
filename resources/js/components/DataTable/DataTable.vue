<template>
	<table class="data-table">
		<data-table-head
			:fields="fields"
		></data-table-head>
	</table>
</template>

<script>
import DataTableHead   from './DataTableHead';
import DataTableBody   from './DataTableBody';

export default {
	props : {
		fields     : Array,
		rows       : Array,
	},
	components : {
		DataTableHead,
		DataTableBody,
		DataTableFooter
	},
}
</script>

<style>

</style>