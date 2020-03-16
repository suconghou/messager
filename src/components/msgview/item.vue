<style lang="less">
@themeColor: #2196f3;
.message-item {
	margin: 20px 0;
	.item {
		position: relative;
	}
	.meta {
		position: absolute;
		left: 0;
		width: 60px;
		height: 100%;
		.avatar-img {
			display: block;
			margin: 0;
			width: 44px;
			height: 44px;
			border-radius: 3px;
			cursor: pointer;
		}
	}
	.content {
		margin-left: 60px;
		padding: 10px;
		position: relative;
		border: 1px solid #cfd8dc;
		box-sizing: border-box;
		&:before {
			position: absolute;
			top: 11px;
			left: -16px;
			content: '';
			width: 0;
			height: 0;
			border: 8px solid #cfd8dc;
			border-left-color: transparent;
			border-top-color: transparent;
			border-bottom-color: transparent;
		}
		&:after {
			position: absolute;
			top: 11px;
			left: -15px;
			content: '';
			width: 0;
			height: 0;
			border: 8px solid #fff;
			border-left-color: transparent;
			border-top-color: transparent;
			border-bottom-color: transparent;
		}
		&.admin {
			background-color: #f9f9f9;
			&:after {
				border-color: #f9f9f9;
				border-left-color: transparent;
				border-top-color: transparent;
				border-bottom-color: transparent;
			}
		}
		&.me {
			.text {
				color: #2196f3;
			}
		}
		.date {
			margin: 0;
			font-size: 14px;
			color: #666;
		}
		.text {
			margin: 15px 0 5px 0;
			line-height: 1.6;
			font-size: 16px;
			color: #333;
		}
		.uname {
			color: #666;
			margin-right: 2px;
			font-weight: 600;
			font-size: 14px;
			&:hover {
				cursor: pointer;
				text-decoration: underline;
			}
		}
	}

	.sub-create {
		width: 100%;
		margin: 10px 0 10px;
	}
}
</style>
<template>
	<div class="message-item" :rel="data.id">
		<div class="item">
			<div class="meta">
				<img class="avatar-img" :src="avatar" @click="doreply" alt="回复他" />
			</div>
			<div class="content" :class="{admin:data.admin,me:uinfo.i===data.uid}">
				<p class="date">
					<a @click="clickName" class="uname" v-text="uname"></a>
					<span>发表于</span>
					<span v-text="ctime"></span>
				</p>
				<div class="text" v-text="content"></div>
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


