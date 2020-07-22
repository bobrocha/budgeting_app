import Request from './Request';

class Account {
	constructor() {
		this.request = new Request();
	}

	async getUser(access_token) {
		const headers = {
			Authorization : `Bearer ${access_token}`,
		};

		const url    = this.request.buildUrl('/user')
		const result = await this.request.get(url, headers);

		return result;
	}

	async updateUser(access_token, id, name, password) {
		const headers = {
			Authorization  : `Bearer ${access_token}`,
			'Content-Type' : 'application/json',
			'Accept'       : 'application/json',
		};

		const url    = this.request.buildUrl(`/users/update/${id}`)
		const result = await this.request.post(url , headers, {name, password});

		return result;
	}
}

export default new Account;