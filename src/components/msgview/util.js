export const buildQuery = data => {
	const u = new URLSearchParams(data);
	return u.toString();
};

export const timeBefore = time => {
	const dur = (+new Date() - time * 1e3) / 1000;
	const f = [
		{
			t: 31536000,
			v: '年'
		},
		{
			t: 2592000,
			v: '个月'
		},
		{
			t: 604800,
			v: '星期'
		},
		{
			t: 86400,
			v: '天'
		},
		{
			t: 3600,
			v: '小时'
		},
		{
			t: 60,
			v: '分钟'
		},
		{
			t: 1,
			v: '秒'
		}
	];
	for (let i = 0, j = f.length; i < j; i++) {
		const { t, v } = f[i];
		const c = Math.floor(dur / t);
		if (c !== 0 && c > 0) {
			return `${c}${v}前`;
		}
	}
	return '刚刚';
};

export const buildTree = data => {
	let items = {};
	data.forEach(item => {
		items[item.id] = item;
	});
	let tree = [];
	data.forEach(item => {
		if (items[item['pid']]) {
			if (!items[item['pid']].child) {
				items[item['pid']].child = [];
			}
			items[item['pid']].child.push(items[item['id']]);
		} else {
			tree.push(items[item['id']]);
		}
	});
	return tree;
};
