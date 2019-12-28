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
				<p class="replay" @click="doreply">回复</p>
			</div>
			<div class="content">
				<p class="date">
					<a @click="clickName" class="uname" v-text="uname"></a>
					<span v-text="ctime"></span>
				</p>
				<div class="text" v-text="content"></div>
			</div>
		</div>
		<div class="sub-create" v-if="pid==data.id">
			<create
				ref="create"
				:pid="data.id"
				:ns="ns"
				:thread="thread"
				@setpid="setpid"
				@reload="reload"
				:cancel="true"
			/>
		</div>
		<div class="sub-item" v-if="data.child">
			<item
				v-for="item in data.child"
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
import create from './create';
import { avatar } from './request';
import { timeBefore } from './util';
export default {
	name: 'item',
	props: {
		ns: {
			type: String,
			required: true
		},
		thread: {
			type: String,
			required: true
		},
		pid: {
			type: [String, Number],
			default: 0
		},
		data: {
			type: Object,
			required: true
		}
	},
	data() {
		return {};
	},
	components: {
		create
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
		}
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
		}
	}
};
</script>


