import Request from './Request';

class BudgetCategories {
	constructor(accessToken) {
		this.request  = new Request;
		this.BASE_URL = this.request.buildUrl('/budget_categories');
		this.headers  = {
			Authorization  : `Bearer ${accessToken}`,
			'Content-Type' : 'application/json',
			'Accept'       : 'application/json',
		};
	}

	async get() {
		return await this.request.get(this.BASE_URL, this.headers);
	}
}

export default BudgetCategories;