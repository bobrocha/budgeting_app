import Request from './Request';


class Budgets {
	constructor(accessToken) {
		this.request     = new Request;
		this.BASE_URL    = this.request.buildUrl('/budgets');
		this.accessToken = accessToken
		this.headers     = {
			Authorization  : `Bearer ${this.accessToken}`,
			'Content-Type' : 'application/json',
			'Accept'       : 'application/json',
		};
	}

	async create(budgetData) {
		const url    = `${this.BASE_URL}/store`;
		const result = await this.request.post(url, this.headers, budgetData);

		if(result.errors) {
			throw result.errors;
		}

		return result;
	}

	async get() {
		return await this.request.get(this.BASE_URL, this.headers);
	}

	async destroy(id) {
		const url = `${this.BASE_URL}/destroy/${id}`;

		return await this.request.post(url, this.headers);
	}

	async update(budgetData) {
		const url    = `${this.BASE_URL}/update/${budgetData.id}`;

		return await this.request.post(url, this.headers, budgetData);
	}
}

export default Budgets;