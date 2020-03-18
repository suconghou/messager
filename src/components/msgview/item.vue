<style lang="less">
@themeColor: #4990e2;
.message-item {
	margin: 20px 0;
	.item {
		position: relative;
	}
	.meta {
		position: absolute;
		left: 0;
		width: 70px;
		height: 100%;
		.avatar-img {
			display: block;
			margin: 0 auto;
			width: 48px;
			height: 48px;
			border-radius: 3px;
		}
	}
	.content {
		margin-left: 70px;
		padding: 0 10px 10px 0;
		position: relative;
		&.me {
			.text {
				color: #000;
				font-weight: 400;
			}
		}
		&.admin {
			.text {
				color: #1800d6;
				font-weight: 400;
			}
		}
		.date {
			margin: 0;
			font-size: 12px;
			color: #7f919e;
		}
		.text {
			margin: 10px 10px 0 0;
			line-height: 1.6;
			font-size: 14px;
			min-height: 20px;
			color: #2a2e2e;
			font-weight: 300;
			white-space: pre-line;
		}
		.uname {
			color: @themeColor;
			margin-right: 5px;
			font-weight: 600;
			font-size: 13px;
			&:hover {
				cursor: pointer;
			}
		}
	}
	.replay {
		margin: 5px 0 0;
		font-size: 13px;
		text-align: left;
		color: @themeColor;
		&:hover {
			cursor: pointer;
		}
	}
	.sub-create {
		width: 100%;
		margin: 10px 0 10px;
	}
	.sub-item {
		padding-left: 30px;
		.sub-item {
			.avatar-img {
				width: 40px;
				height: 40px;
			}
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
			</div>
			<div class="content" :class="{admin:data.admin,me:uinfo.i===data.uid}">
				<p class="date">
					<a @click="clickName" class="uname" v-text="uname"></a>
					<span v-text="ctime"></span>
				</p>
				<div class="text" v-text="content"></div>
				<p class="replay" @click="doreply">回复</p>
				<div class="sub-create" v-if="pid==data.id">
					<create
						ref="create"
						:pid="data.id"
						:ns="ns"
						:thread="thread"
						@setpid="setpid"
						@reload="reload"
						@info="i=>$emit('info',i)"
						:cancel="true"
					/>
				</div>
			</div>
		</div>

		<div class="sub-item" v-if="data.child">
			<item
				v-for="item in data.child"
				:uinfo="uinfo"
				:ns="ns"
				:thread="thread"
				:pid="pid"
				:key="item.id"
				:data="item"
				@setpid="setpid"
				@reload="reload"
				@info="i=>$emit('info',i)"
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
		},
		uinfo: {
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


