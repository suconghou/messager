<style lang="less">
.message-create {
	.form-info {
		input {
			height: 22px;
			outline: none;
			border: none;
			width: ~'calc(100% - 50px)';
		}
		.form-name,
		.form-email,
		.form-url {
			display: inline-block;
			margin: 10px 0 0 10px;
			width: 30%;
			min-width: 100px;
			border: 1px solid #eee;
			font-size: 12px;
			box-sizing: border-box;
		}
		.t-name,
		.t-email,
		.t-url {
			display: inline-block;
			width: 40px;
			color: #aaa;
			text-align: center;
		}
	}
	.form-content {
		margin: 10px 10px 0 10px;
		overflow: hidden;
		border: 1px solid #eee;
		display: inline-block;
		box-sizing: border-box;
		width: 96%;
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
		margin: 0 0 0 10px;
		font-size: 12px;
		height: 20px;
		p {
			margin: 0;
			color: #f00;
		}
	}
	.form-others {
		margin: 0 0 0 10px;
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
			<div class="form-name">
				<span class="t-name">昵称:</span>
				<input type="text" name="name" v-model="jwtInfo.info.name" :readonly="r" />
			</div>
			<div class="form-email">
				<span class="t-email">邮箱:</span>
				<input type="text" name="email" v-model="jwtInfo.info.email" :readonly="r" />
			</div>
			<div class="form-url">
				<span class="t-url">网址:</span>
				<input type="text" name="url" v-model="jwtInfo.info.url" :readonly="r" />
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
	computed: {
		r() {
			return Boolean(this.jwtInfo.jwt);
		}
	},
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


