
export const avatar = u => {
	return `https://gravatar.loli.net/avatar/${u}`;
};

const baseURL = "http://127.0.0.1/message";

export const getList = (ns, thread) => {
	const u = baseURL + `/ajax/list`;
	const data = {
		ns,
		thread
	};
	const body = JSON.stringify(data);
	return fetch(u, {
		body,
		method: "POST",
	});
};

export const create = (ns, thread, data) => {
	data.ns = ns;
	data.thread = thread;
	const u = baseURL + `/ajax/create`;
	const body = JSON.stringify(data);
	return fetch(u, {
		body,
		method: "POST",
	});
};

export const auth = (name, email, url) => {
	const data = { name, email }
	if (url) {
		data.url = url
	}
	const body = JSON.stringify(data)
	const u = baseURL + '/ajax/login'
	return fetch(u, {
		body,
		method: 'POST'
	})
}


export const genJwt = async (name, email, url) => {
	const res = await auth(name, email, url)
	const json = await res.json();
	if (json.code == 0) {
		localStorage.setItem('jwt', json.data)
		return getJwt()
	}
	return false
}

// 获取并校验,上层加try
export const getJwt = () => {
	const jwt = localStorage.getItem('jwt')
	if (jwt && jwt.split('.').length == 2) {
		const t = jwt.split('.')[0];
		const info = JSON.parse(atob(t))
		if (info.expire < (+new Date() / 1000)) {
			throw new Error("身份已过期")
		}
		return { info, jwt }
	}
	return false
}