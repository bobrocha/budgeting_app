import Request from './Request';

class Expenses {
	constructor(accessToken) {
		this.request     = new Request;
		this.BASE_URL    = this.request.buildUrl('/transactions');
		this.accessToken = accessToken
		this.headers     = {
			Authorization  : `Bearer ${this.accessToken}`,
			'Content-Type' : 'application/json',
			'Accept'       : 'application/json',
		};
	};

	async get(year) {
		const url    = `${this.BASE_URL}?year=${year}`;
		const result = await this.request.get(url, this.headers);

		if(result.error) {
			throw result.error;
		}

		return result;
	}

	async create(payload) {
		const url    = this.BASE_URL;
		const result = await this.request.post(url, this.headers, payload);

		if(result.errors) {
			throw result.errors;
		}

		return result;
	}

	async years() {
		const url = `${this.BASE_URL}/years`;

		return await this.request.get(url, this.headers);
	}

	async patch({ id, payload }) {
		const url = `${this.BASE_URL}/${id}`;

		return await this.request.patch(url, this.headers, payload);
	}

	async delete(id) {
		const url = `${this.BASE_URL}/${id}`;

		return await this.request.delete(url, this.headers);
	}
}

export default Expenses;