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

	async years() {
		const url = `${this.BASE_URL}/years`;

		return await this.request.get(url, this.headers);
	}
}

export default Expenses;