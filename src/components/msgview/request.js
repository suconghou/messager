import { buildQuery } from "./util";

export const avatar = u => {
	return `https://gravatar.loli.net/avatar/${u}`;
};

const baseURL = "http://127.0.0.1:9900/messager";

// type, page , order
export const getList = (ns, kid) => {
	const u = baseURL + `/ajax/getList`;
	const data = {
		ns,
		kid
	};
	const body = JSON.stringify(data);
	return fetch(u, {
		body,
		method: "POST",
		headers: {
			"Content-Type": "application/json"
		}
	});
};

export const create = (ns, kid, data) => {
	data.ns = ns;
	data.kid = kid;
	const u = baseURL + `/ajax/create`;
	const body = JSON.stringify(data);
	return fetch(u, {
		body,
		method: "POST",
		headers: {
			"Content-Type": "application/json"
		}
	});
};
