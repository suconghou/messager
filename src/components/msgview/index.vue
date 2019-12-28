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
		<div class="comment-title">评论</div>
		<div class="no-comment" v-if="lists.length==0">目前暂无评论~</div>
		<div class="item-create" v-if="pid==0">
			<create ref="create" :ns="ns" :thread="thread" :pid="pid" @setpid="setpid" @reload="reload" />
		</div>
		<div class="item-list">
			<item
				v-for="item in lists.data"
				:ns="ns"
				:thread="thread"
				:pid="pid"
				:key="item.id"
				:data="item"
				@setpid="setpid"
				@reload="reload"
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
	props: {
		ns: {
			type: String,
			required: true
		},
		thread: {
			type: String,
			required: true
		}
	},
	components: {
		item,
		create
	},
	data() {
		return {
			loading: true,
			pid: 0,
			lists: { num: 0, data: [] }
		};
	},
	computed: {
		cls() {
			return {
				loading: this.loading && this.lists.num == 0
			};
		}
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
				} else {
					this.lists = { num: 0, data: [] };
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
		}
	}
};
</script>


