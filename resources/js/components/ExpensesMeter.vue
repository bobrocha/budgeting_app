<template>
	<div class="expenses-meter">
		<div class="meter-label">$700.00 of $1500.00</div>
		<meter min="0" max="1500" value="700" ></meter>
	</div>
</template>

<style>
.expenses-meter {
	padding: 1rem;
	border-radius: 3px;
	border: 1px solid black;
	/* background-color: brown; */
}
meter {
	width: 100%;
	/* background-color: brown; */
}

.meter-label {
	margin-bottom: 1rem;
}
</style>