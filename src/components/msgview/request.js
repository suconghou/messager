
export const avatar = u => {
	return `https://gravatar.loli.net/avatar/${u}`;
};

const baseURL = "http://127.0.0.1:9092/index.php";

export const getList = (ns, thread) => {
	const u = baseURL + `/message/ajax/list`;
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
	const u = baseURL + `/message/ajax/create`;
	const body = JSON.stringify(data);
	return fetch(u, {
		body,
		method: "POST",
	});
};

export const auth = (email, pass) => {
	const data = { pass, email }
	const body = JSON.stringify(data)
	const u = baseURL + '/sso/email_login'
	return fetch(u, {
		body,
		method: 'POST'
	})
}

export const refresh = async (jwt) => {
	const data = { jwt }
	const body = JSON.stringify(data)
	const u = baseURL + '/sso/jwt_login'
	const res = await fetch(u, {
		body,
		method: 'POST'
	})
	const json = await res.json()
	if (json.code == 0) {
		localStorage.setItem('jwt', json.data)
		return json.data
	}
	throw new Error(json.msg)
}


export const genJwt = async (email, pass) => {
	const res = await auth(email, pass)
	const json = await res.json();
	if (json.code == 0) {
		localStorage.setItem('jwt', json.data)
		return getJwt()
	}
	throw new Error(json.msg)
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