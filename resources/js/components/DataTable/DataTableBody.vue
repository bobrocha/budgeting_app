<template>
	<tbody>
		<tr
			v-for="(row, i) in displayed_rows"
			:key="i"
		>
		<td
			v-for="(value, i) in Object.values(row)"
			:key="i"
		>{{ value }}</td>
		</tr>
	</tbody>
</template>

<script>
export default {
	props : {
		rows : Array,
	},
	data() {
		return {
			displayed_rows : null,
		}
	},
	methods : {
		//
	},
	watch : {
		/**
		 * Watch for rows to change and updated what is displayed on page,
		 * this can be affected by filtering
		 */
		rows(rows) {
			this.displayed_rows = rows;
		}
	},
	created() {
		this.displayed_rows = this.rows;
	}
}
</script>

<style>

</style>