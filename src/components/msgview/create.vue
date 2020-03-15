<style lang="less">
.message-create {
	.form-info {
	}
	.form-content {
		.ctext {
			border: 1px solid #cfd8dc;
			color: #333;
			outline: none;
			resize: none;
			height: 140px;
			padding: 7px;
			width: 100%;
			box-sizing: border-box;
			&:focus {
				border-color: #93b7c7;
			}
		}
	}
	.btn-submit {
		outline: none;
		background: #6198c3;
		border: none;
		height: 30px;
		width: 60px;
		cursor: pointer;
		float: right;
		color: #fff;
		font-size: 15px;
	}
	.form-text {
		font-size: 12px;
		height: 20px;
		p {
			margin: 0;
			color: #f00;
		}
	}
	.action-btns {
		margin: 10px 0 10px;
		.cancel-btn {
			outline: none;
			border: none;
			height: 30px;
			width: 60px;
			cursor: pointer;
			float: right;
			color: #2196f3;
			font-size: 13px;
		}
	}
}
</style>
<template>
	<form class="message-create">
		<div class="form-content">
			<textarea v-model="content" class="ctext" placeholder="写点什么吧..."></textarea>
		</div>
		<div class="form-info">
			<div class="form-user">{{jwtInfo.info.name}} & 头像</div>
		</div>
		<div class="action-btns">
			<button type="button" class="btn-submit" @click="send">发送</button>
			<button type="button" class="cancel-btn" v-if="cancel" @click="doCancel">取消</button>
		</div>
		<div class="form-text">
			<p v-if="text" v-text="text"></p>
		</div>
	</form>
</template>
<script>
import { create, getJwt, genJwt } from './request';
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
	computed: {},
	beforeMount() {
		this.parseInfo();
	},
	methods: {
		parseInfo() {
			const info = getJwt();
			if (info) {
				this.jwtInfo = info;
			}
		},
		async reInitJwt() {
			const { name, email, url } = this.jwtInfo.info;
			const info = await genJwt(name, email, url);
			if (info) {
				this.jwtInfo = info;
			}
		},
		async send() {
			if (this.loading) {
				return;
			}
			try {
				this.loading = true;
				this.text = '提交中...';
				if (!this.jwtInfo.jwt) {
					await this.reInitJwt();
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
				}
				console.info(res);
				this.loading = false;
			} catch (e) {
				console.error(e);
			}
		},
		empty() {
			this.name = '';
			this.email = '';
			this.url = '';
			this.content = '';
		},
		doCancel() {
			this.$emit('setpid', 0);
		}
	}
};
</script>


