class Request {
	constructor(base_url = 'http://localhost:3000/budgeting_app/public/api') {
		this.BASE_URL = base_url;
	}

	async post(url = '', headers = {}, payload = {}) {
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

	async get(url, headers = {}) {
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

	async delete(url, headers = {}) {
		try {
			const request = await fetch(url, {
				method: 'DELETE',
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

	async patch(url, headers = {}, payload = {}) {
		try {
			const request = await fetch(url, {
				method: 'PATCH',
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

	buildUrl(appendage) {
		return `${this.BASE_URL}${appendage}`;
	}
}

export default Request