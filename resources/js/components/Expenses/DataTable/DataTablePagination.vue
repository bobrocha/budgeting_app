<template>
	<div v-show="pages.length > 1" class="data-table-pagination">
		<div class="data-table-pagination-item">
			<button
				@click="goToFirstPage"
				v-show="!isFirstPage"
			>First</button>
		</div>
		<div class="data-table-pagination-item">
			<button @click="goToPreviousPage">Previous</button>
		</div>
		<div class="data-table-pagination-item">
			<input
				class="page-number-input"
				type="number"
				:min="firstPageNumber + 1"
				:max="lastPageNumber + 1"
				v-model.trim="inputPageNumber"
				@keydown.enter="goToInputPageNumber"
			/>
		</div>
		<div class="data-table-pagination-item">
			<button @click="goToInputPageNumber">Enter</button>
		</div>
		<div class="data-table-pagination-item">
			{{ location }}
		</div>
		<div class="data-table-pagination-item">
			<button @click="goToNextPage">Next</button>
		</div>
		<div class="data-table-pagination-item">
			<button
				@click="goToLastPage"
				v-show="!isLastPage"
			>Last</button>
		</div>
	</div>
</template>

<script>
export default {
	props : {
		pageSize   : Number,
		rows       : Array,
	},

	created() {
		this.paginate(this.rows);
		this.$emit('setDisplayedRows', this.page);
	},

	data() {
		return {
			pages           : [],
			page            : [],
			pageNumber      : 0,
			firstPageNumber : 0,
			lastPageNumber  : null,
			inputPageNumber : null,
		}
	},

	methods : {
		calculateTotalPages() {
			return Math.ceil(this.rows.length / this.pageSize);
		},

		isPage(number) {
			return number % this.pageSize === 0;
		},

		paginate(rows) {
			if(rows.length) {
				/**
				 * Reset page number to the pegining
				 * when pagination is triggered.
				 * Pagination can be triggered from
				 * filtering so not reseting the page number
				 * results in rows that should display not
				 * displaying.
				 */
				this.pageNumber = 0;
				const pages     = [];
				const page      = [];

				for(let i = 0; i < rows.length; i++) {
					page.push(rows[i]);

					/**
					 * Check the count to determine if we have
					 * reached the page size specified by
					 * pageSize. If we have add the first "page"
					 * to the "pages" array and clear out the "page"
					 */
					if(this.isPage(i + 1)) {
						pages.push(page.splice(0, page.length));
					}

					/**
					 * Handle remainder, left over elements, logic
					 * for when there are elements remaining but
					 * don't constitute a full page. Also handle scenario
					 * where all the items constitute one page.
					 */
					if(i === rows.length -1 && page.length) {
						pages.push(page.splice(0, page.length));
					}
				}

				this.pages           = pages;
				this.page            = pages[this.pageNumber];
				this.firstPageNumber = 0;
				this.lastPageNumber  = this.pages.length -1;
			} else {
				this.pages          = [];
				this.page           = [];
				this.lastPageNumber = null;
			}
		},

		switchPage(pageNumber) {
			this.page = this.pages[pageNumber];

			this.$emit('setDisplayedRows', this.page);
		},

		goToNextPage() {
			this.pageNumber++;

			if(this.pageNumber >= this.lastPageNumber) {
				this.pageNumber = this.lastPageNumber;
			}

			this.switchPage(this.pageNumber);
		},

		goToPreviousPage() {
			this.pageNumber--;

			if(this.pageNumber <= this.firstPageNumber) {
				this.pageNumber = this.firstPageNumber;
			}

			this.switchPage(this.pageNumber);
		},

		goToLastPage() {
			this.pageNumber = this.lastPageNumber;

			this.switchPage(this.pageNumber);
		},

			goToFirstPage() {
			this.pageNumber = this.firstPageNumber;

			this.switchPage(this.pageNumber);
		},

		goToInputPageNumber() {
			if(this.inputPageNumber) {
				const lowRange       = this.inputPageNumber >= (this.firstPageNumber + 1);
				const highrange      = this.inputPageNumber <= (this.lastPageNumber + 1)
				const isWithingRange = lowRange && highrange;

				if(isWithingRange) {
					// Do this so computed props reevaulate
					this.pageNumber = this.inputPageNumber -1;

					this.switchPage(this.pageNumber)
				}
			}
		},
	},

	computed : {
		location() {
			return `${this.pageNumber + 1} of ${this.calculateTotalPages()}`
		},

		isLastPage() {
			return this.pageNumber === this.lastPageNumber;
		},

		isFirstPage() {
			return this.pageNumber === this.firstPageNumber;
		},

	},

	watch : {
		/**
		 * Mainly used for when new values are added,
		 * via ajax to reevaluate pagination.
		 */
		rows(val) {
			this.paginate(val);
			this.$emit('setDisplayedRows', this.page);
		},
	}
}
</script>
<style>
.data-table-pagination {
	margin-top: 1rem;
	text-align: center;
}

.data-table-pagination-item {
	display: inline-block;
	margin-left: 0.25rem;
}

.data-table-pagination .page-number-input {
	width: 75px;
}
</style>