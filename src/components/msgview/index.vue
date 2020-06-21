<style lang="less">
.message-wrap {
	margin: 15px 0;
	position: relative;
	&.loading::after {
		content: ' ';
		position: absolute;
		top: 0;
		right: 0;
		left: 0;
		bottom: 0;
		background: url('http://cdn.ourwill.cn/fed-static/img/loading.gif') no-repeat center;
		background-size: 30px;
	}
	.comment-title {
		padding: 10px;
		font-size: 16px;
		color: #2e6f2e;
	}
	.no-comment {
		color: #999;
		font-size: 14px;
		padding: 10px;
	}
}
</style>
<template>
	<div class="message-wrap" :class="cls">
		<div class="comment-title">{{ count }}条评论</div>
		<div v-if="!count" class="no-comment">
			目前暂无评论~
		</div>
		<div v-if="pid == 0" class="item-create">
			<create
				ref="create"
				:ns="ns"
				:thread="thread"
				:pid="pid"
				@setpid="setpid"
				@reload="reload"
				@info="(i) => (uinfo = i)"
			/>
		</div>
		<div class="item-list">
			<item
				v-for="item in lists.data"
				:key="item.id"
				:uinfo="uinfo"
				:ns="ns"
				:thread="thread"
				:pid="pid"
				:data="item"
				@setpid="setpid"
				@reload="reload"
				@info="(i) => (uinfo = i)"
			/>
		</div>
	</div>
</template>
<script>
import item from './item';
import create from './create';
import { buildTree } from './util';
import { getList } from './request';

export default {
	components: {
		item,
		create,
	},
	props: {
		ns: {
			type: String,
			required: true,
		},
		thread: {
			type: String,
			required: true,
		},
	},
	data() {
		return {
			loading: true,
			pid: 0,
			count: 0,
			lists: { num: 0, data: [] },
			uinfo: {},
		};
	},
	computed: {
		cls() {
			return {
				loading: this.loading && this.lists.num == 0,
			};
		},
	},
	created() {},
	mounted() {
		this.init();
	},
	methods: {
		async init() {
			try {
				this.loading = true;
				const res = await getList(this.ns, this.thread);
				const json = await res.json();
				if (json.code == 0) {
					this.lists = json.data;
					this.count = json.count;
				} else {
					this.lists = { num: 0, data: [] };
					this.count = 0;
				}
				this.loading = false;
			} catch (e) {
				console.error(e);
			}
		},
		setpid(pid) {
			this.pid = pid;
		},
		reload() {
			this.init();
		},
	},
};
</script>
