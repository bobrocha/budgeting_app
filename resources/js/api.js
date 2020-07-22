class API {
	constructor(base_url) {
		this.BASE_URL = base_url;
	}

	async login(user) {
		const headers =  {
			'Content-Type' : 'application/json;charset=utf-8',
		};

		const url    = this.buildUrl('/login');
		const result = await this.postRequest(url, headers, user);

		if(result.error) {
			throw new Error(result.message);
		}

		return result;
	}

	buildUrl(appendage) {
		return `${this.BASE_URL}${appendage}`;
	}

	async postRequest(url = '', headers = {}, payload = {}) {
		try {
			const request = await fetch(url, {
				method: 'POST',
				headers,
				body: JSON.stringify(payload),
			});

			// Bad request
			if(!request.ok) {
				throw new Error(`HTTP error ${request.status}`);
			}

			return await request.json();
		} catch(e) {
			console.log(e)
		}
	}

	async getRequest(url, headers = {}) {
		try {
			const request = await fetch(url, {
				method: 'GET',
				headers,
			});

			// Bad request
			if(!request.ok) {
				throw new Error(`HTTP error ${request.status}`);
			}

			return await request.json();
		} catch(e) {
			console.log(e)
		}
	}
}

export default new API('http://localhost:3000/budgeting_app/public/api');