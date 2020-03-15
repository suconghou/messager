<style lang="less">
.message-create {
	.form-info {
	}
	.form-content {
		margin: 10px 0 10px 0;
		overflow: hidden;
		border: 1px solid #eee;
		display: inline-block;
		box-sizing: border-box;
		width: 100%;
		.text {
			border: none;
			outline: none;
			resize: none;
			width: ~'calc( 100% - 60px )';
			height: 60px;
			border: none;
			float: left;
			padding: 5px;
		}
		.btn-submit {
			float: left;
			height: 70px;
			width: 50px;
			background: #eee;
			border: none;
			display: block;
			cursor: pointer;
			outline: none;
			color: #999;
			&:hover {
				background: #e5e5e5;
			}
		}
	}
	.form-text {
		margin: 10px 0 5px;
		font-size: 12px;
		height: 20px;
		p {
			margin: 0;
			color: #f00;
		}
	}
	.form-others {
		margin: 0;
		font-size: 12px;
		.cancel-btn {
			cursor: pointer;
			color: #555;
			&:hover {
				color: #fd6262;
			}
		}
	}
}
</style>
<template>
	<form class="message-create">
		<div class="form-info">
			<div class="form-user">
				<auth @info="onInfo" @error="onError" />
			</div>
		</div>
		<div class="form-content">
			<textarea v-model="content" class="text" placeholder="写点什么吧..."></textarea>
			<button type="button" class="btn-submit" @click="send">发送</button>
		</div>
		<div class="form-others" v-if="cancel">
			<span class="cancel-btn" @click="doCancel">取消</span>
		</div>
		<div class="form-text">
			<p v-if="text" v-text="text"></p>
		</div>
	</form>
</template>
<script>
import { create } from './request';
import auth from './auth';
export default {
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
		cancel: {
			type: Boolean,
			default: false
		}
	},
	components: {
		auth
	},
	data() {
		return {
			jwtInfo: {
				info: {},
				jwt: ''
			},
			content: '',
			text: '',
			loading: false
		};
	},
	methods: {
		onInfo(info) {
			this.jwtInfo = info;
		},
		onError(msg) {
			this.text = msg;
		},
		async send() {
			if (this.loading) {
				return;
			}
			try {
				this.loading = true;
				this.text = '提交中...';
				if (!this.jwtInfo.jwt) {
					this.text = '请先登录';
					return;
				}
				const data = {
					pid: this.pid,
					jwt: this.jwtInfo.jwt,
					content: this.content
				};
				const res = await create(this.ns, this.thread, data);
				const json = await res.json();
				this.text = json.msg;
				if (json.code == 0) {
					this.empty();
					this.$emit('reload');
					this.text = '';
					this.doCancel();
				} else {
					this.text = json.msg;
				}
				console.info(res);
				this.loading = false;
			} catch (e) {
				console.error(e);
			}
		},
		empty() {
			this.content = '';
		},
		doCancel() {
			this.$emit('setpid', 0);
		}
	}
};
</script>


