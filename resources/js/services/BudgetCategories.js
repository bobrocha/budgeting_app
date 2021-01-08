import Request from './Request';

class BudgetCategories {
	constructor() {
		this.request  = new Request;
		this.BASE_URL = this.request.buildUrl('/budget_categories');
	}

	async get(accessToken) {
		const headers = {
			Authorization  : `Bearer ${accessToken}`,
			'Content-Type' : 'application/json',
			'Accept'       : 'application/json',
		};

		return await this.request.get(this.BASE_URL, headers);
	}
}

export default new BudgetCategories;