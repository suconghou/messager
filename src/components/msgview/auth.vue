<template>
	<div class="auth-form">
		<div v-if="info">
			<span class="name">{{info.info.n}}</span>
			<img class="ava" :src="ava" alt />
			<span class="reset" @click="logout">切换账号</span>
		</div>
		<div v-else>
			<input type="text" placeholder="用户名" class="l-i" v-model="email" />
			<input type="password" placeholder="密码" class="l-i" v-model="pass" />
			<button class="l-btn" type="button" @click="doLogin">登陆</button>
		</div>
	</div>
</template>

<script>
import { getJwt, genJwt, avatar, refresh } from './request';
export default {
	data() {
		return {
			info: null,
			email: '',
			pass: ''
		};
	},
	created() {
		this.init();
	},
	computed: {
		ava() {
			if (this.info) {
				return avatar(this.info.info.a);
			}
		}
	},
	methods: {
		async init() {
			try {
				const info = await getJwt();
				if (!info) {
					// 没有jwt或无法解析
					return;
				}
				this.$emit('info', info);
				this.info = info;
				if (info.info.expire - +new Date() / 1000 < 86400) {
					// 提前一天更新延期
					await refresh(info.jwt);
				}
			} catch (e) {
				console.error(e);
			}
		},
		async doLogin() {
			try {
				if (!this.email) {
					throw new Error('用户名必填');
				}
				if (!this.pass) {
					throw new Error('密码必填');
				}
				const info = await genJwt(this.email, this.pass);
				if (!info) {
					return;
				}
				this.$emit('info', info);
				this.info = info;
			} catch (e) {
				this.$emit('error', e.message || e);
			}
		},
		async logout() {
			this.info = null;
			this.$emit('info', { jwt: '', info: {} });
		}
	}
};
</script>

<style lang="less">
.auth-form {
	.l-i {
		display: inline-block;
		border: 1px solid #dbdfe4;
		outline: none;
		border-radius: 4px;
		height: 25px;
		box-sizing: border-box;
		text-indent: 5px;
		margin-right: 10px;
		width: 36%;
		min-width: 30px;
		max-width: 200px;
		&:focus {
			border-color: rgba(29, 47, 58, 0.75);
		}
	}
	.l-btn {
		outline: none;
		background: rgba(29, 47, 58, 0.75);
		border: none;
		border-radius: 4px;
		height: 25px;
		min-width: 50px;
		max-width: 30%;
		cursor: pointer;
		color: #fff;
		font-size: 12px;
	}
	.name {
		height: 30px;
		line-height: 30px;
		float: right;
		font-size: 14px;
		color: #666;
	}
	.reset {
		height: 30px;
		line-height: 30px;
		font-size: 12px;
		cursor: pointer;
		color: #999;
	}
	.ava {
		width: 30px;
		height: 30px;
		float: right;
		border-radius: 50%;
	}
}
</style>