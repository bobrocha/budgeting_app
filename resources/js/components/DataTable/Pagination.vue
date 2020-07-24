<template>
		<ul class="pagination">
			<li class="pagination-item">
				<button
					:disabled="isFirstPage"
					@click="switchPage(first_page_number)"
					class="pagination-control"
				>First</button>
			</li>
			<li class="pagination-item">
				<button
				:disabled="isFirstPage"
				@click="previousPage"
				class="pagination-control">Previous</button>
			</li>
			<li class="pagination-item">
				<input
					v-model.trim="input_page_number"
					:min="first_page_number + 1"
					:max="last_page_number + 1"
					class="pagination-control page-number-input"
					type="number"
				>
			</li>
			<li class="pagination-item">
				<button
					@click="enterPageNumber"
					class="pagination-control"
				>Enter</button>
			</li>
			<li class="pagination-item">
				{{ pagoLocation }}
			</li>
			<li class="pagination-item">
				<button
					:disabled="isLastPage"
					@click="nextPage"
					class="pagination-control"
				>Next</button>
			</li>
			<li class="pagination-item">
				<button
					:disabled="isLastPage"
					@click="switchPage(last_page_number)"
					class="pagination-control"
					>Last</button>
			</li>
		</ul>
</template>

<script>
export default {
	props : {
		rows           : Array,
		items_per_page : Number,
		page_index     : Number,
	},
	data() {
		return {
			displayed_rows         : null,
			total_pages            : null,
			page_size              : null,
			pages                  : null,
			page                   : null,
			page_number            : 0,
			first_page_number      : null,
			last_page_number       : null,
			input_page_number      : null,
		}
	},
	methods : {
		setPageSize(size) {
			this.page_size = size;
		},
		paginate(rows = null) {
			this.total_pages = Math.ceil(rows.length / this.page_size);

			const pages = [];
			let page    = [];

			for(let i = 0; i < rows.length; i++) {
				const is_page    = (i + 1) % this.page_size === 0;
				const is_the_end = i === rows.length -1;

				page.push(rows[i]);

				/**
				 * If we have a full page, clear out the
				 * pages array and push page to pages
				 */
				if(is_page) {
					pages.push(page);
					page = [];
				}

				/**
				 * If we have reached the end of the array and the page is not empty
				 * push the ramaining push elements to pages.
				 */
				if(is_the_end && page.length) {
					pages.push(page);
					page = [];
				}
			}

			this.first_page_number = 0;
			this.last_page_number  = pages.length -1;
			this.pages             = pages;
			this.page              = pages[this.page_number];
		},
		getPage() {
			return this.page;
		},
		nextPage() {
			this.page_number++;

			if(this.page_number > this.last_page_number) {
				this.page_number = this.last_page_number
			}
			this.switchPage(this.page_number);
		},
		previousPage() {
			this.page_number--;

			if(this.page_number < this.first_page_number) {
				this.page_number = this.first_page_number;
			}

			this.switchPage(this.page_number);
		},
		switchPage(page_number) {
			this.displayed_rows = this.pages[page_number];
			this.page_number    = page_number;

			this.$emit('paginateRows', this.displayed_rows);
		},
		enterPageNumber() {
			const input_page_number = this.input_page_number - 1;
			const item_exists       = this.pages.hasOwnProperty(input_page_number);

			if(this.input_page_number && ( input_page_number && item_exists )) {
				this.switchPage(this.input_page_number - 1);
			}
		},
	},
	computed : {
		isFirstPage() {
			return this.page_number === this.first_page_number;
		},
		isLastPage() {
			return this.page_number === this.last_page_number;
		},
		pagoLocation() {
			return `Page ${this.page_number + 1} of ${this.last_page_number + 1}`;
		},
	},
	created() {
		this.setPageSize(this.items_per_page);
		this.paginate(this.rows);

		this.displayed_rows = this.getPage();

		this.$emit('paginateRows', this.displayed_rows);
	},
	watch : {
		page_index(index) {
			this.page_number = index;
		},
		rows(rows) {
			this.paginate(rows);

			this.displayed_rows = this.getPage();

			this.$emit('paginateRows', this.displayed_rows);
		}
	}
}
</script>

<style>
.pagination {
	text-align: center;
	background-color: #F3F3F3;
	list-style-type: none;
	padding: 0.5rem;
	margin: 0;
	border: 1px solid #ddd;
}

.pagination .pagination-item {
	display: inline-block;
	margin : 0 0.5rem;
}

.pagination .page-number-input {
	width: 75px;
}
</style>