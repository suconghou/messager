<style lang="less">
.message-wrap {
	font-family: italic, Helvetica, Arial, 'Heiti SC', 'Microsoft YaHei';
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
		border-bottom: 2px solid #e7e9ee;
		height: 36px;
		line-height: 36px;
		font-size: 15px;
		font-weight: 700;
		margin: 20px 0 40px;
		box-sizing: border-box;
		.c-num {
			display: inline-block;
			color: #656c7a;
			border-bottom: 2px solid #656c7a;
			position: relative;
			top: -2px;
			padding: 0 7px;
		}
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
		<div class="comment-title">
			<div class="c-num">{{count}}条评论</div>
		</div>
		<div class="no-comment" v-if="!count">目前暂无评论~</div>
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
			count: 0,
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
		}
	}
};
</script>


