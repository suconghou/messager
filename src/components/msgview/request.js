
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
