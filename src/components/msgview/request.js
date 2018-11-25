import { buildQuery } from "./util";

export const avatar = u => {
	return `https://gravatar.loli.net/avatar/${u}`;
};

const baseURL = "/messager";

// type, page , order
export const getList = (ns, kid, data) => {
	const u = baseURL + `/ajax/${ns}/${kid}`;
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
	const u = baseURL + `/create/${ns}/${kid}`;
	const body = JSON.stringify(data);
	return fetch(u, {
		body,
		method: "POST",
		headers: {
			"Content-Type": "application/json"
		}
	});
};
