<style lang="less">
.message-item {
	margin: 20px 0;
	.item {
		position: relative;
	}
	.meta {
		position: absolute;
		left: 0;
		width: 80px;
		height: 100%;
		.avatar-img {
			display: block;
			margin: 0 auto;
			width: 40px;
			height: 40px;
		}
		.replay {
			margin: 5px 0 0;
			font-size: 12px;
			text-align: center;
			color: #555;
			cursor: pointer;
			&:hover {
				color: #fd6262;
			}
		}
	}
	.content {
		margin-left: 80px;
		border-left: solid #bbbbbb 2px;
		padding: 10px;
		position: relative;
		border-radius: 3px;
		&.me {
			color: #00a1ff;
		}
		&.admin {
			background-color: #f5f5f5;
			&:after {
				border-right-color: #f5f5f5;
			}
		}
		&:before,
		&:after {
			content: '';
			border: solid 10px transparent;
			border-left: none;
			border-right-color: #bbbbbb;
			position: absolute;
			left: -10px;
			top: 10px;
		}
		&:after {
			border-right-color: #fff;
			left: -8px;
		}
		.date {
			margin: 0;
			font-size: 12px;
			color: #333;
		}
		.text {
			margin: 10px 10px 0 0;
			line-height: 1.6;
			font-size: 12px;
			min-height: 20px;
			white-space: pre-line;
		}
		.uname {
			color: #555;
			cursor: pointer;
			margin-right: 5px;
			&:hover {
				color: #fd6262;
			}
		}
	}
	.sub-create {
		padding-left: 10px;
	}
	.sub-item {
		padding-left: 30px;
		.sub-item {
			.sub-item {
				.sub-item {
					.sub-item {
						.sub-item {
							.sub-item {
								.sub-item {
									padding-left: 10px;
								}
							}
						}
					}
				}
			}
		}
	}
}
</style>
<template>
	<div class="message-item" :rel="data.id">
		<div class="item">
			<div class="meta">
				<img class="avatar-img" :src="avatar" alt />
				<p class="replay" @click="doreply">
					回复
				</p>
			</div>
			<div class="content" :class="{ admin: data.admin, me: me }">
				<p class="date">
					<a class="uname" @click="clickName" v-text="uname" />
					<span v-text="ctime" />
				</p>
				<div class="text" v-text="content" />
			</div>
		</div>
		<div v-if="pid == data.id" class="sub-create">
			<create
				ref="create"
				:pid="data.id"
				:ns="ns"
				:thread="thread"
				:cancel="true"
				@setpid="setpid"
				@reload="reload"
				@info="(i) => $emit('info', i)"
			/>
		</div>
		<div v-if="data.child" class="sub-item">
			<item
				v-for="item in data.child"
				:key="item.id"
				:uinfo="uinfo"
				:ns="ns"
				:thread="thread"
				:pid="pid"
				:data="item"
				@setpid="setpid"
				@reload="reload"
				@info="(i) => $emit('info', i)"
			/>
		</div>
	</div>
</template>
<script>
import create from './create';
import { avatar } from './request';
import { timeBefore } from './util';
export default {
	name: 'Item',
	components: {
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
		pid: {
			type: [String, Number],
			default: 0,
		},
		data: {
			type: Object,
			required: true,
		},
		uinfo: {
			type: Object,
			required: true,
		},
	},
	data() {
		return {};
	},
	computed: {
		avatar() {
			return avatar(this.data.avatar);
		},
		ctime() {
			return timeBefore(this.data.time);
		},
		uname() {
			return this.data.name;
		},
		content() {
			return this.data.content;
		},
		me() {
			return this.data.uid === this.uinfo.i;
		},
	},
	mounted() {},
	methods: {
		clickName() {
			if (this.data.url) {
				window.open(this.data.url);
			}
		},
		doreply() {
			this.$emit('setpid', this.data.id);
		},
		setpid(pid) {
			this.$emit('setpid', pid);
		},
		reload() {
			this.$emit('reload');
		},
	},
};
</script>
