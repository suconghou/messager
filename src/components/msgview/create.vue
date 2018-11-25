<style lang="less">
.messager-create {
	width: 700px;
	.form-info {
		input {
			height: 22px;
			outline: none;
			border: none;
			width: ~"calc(100% - 50px)";
		}
		.form-name,
		.form-email,
		.form-url {
			display: inline-block;
			margin: 10px 0 0 10px;
			width: 25%;
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
		margin: 10px;
		overflow: hidden;
		border: 1px solid #eee;
		display: inline-block;
		box-sizing: border-box;
		width: 80%;
		.text {
			border: none;
			outline: none;
			resize: none;
			width: ~"calc( 100% - 60px )";
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
		margin: 0 0 10px 10px;
		font-size: 12px;
		height: 20px;
		p {
			margin: 0;
			color: #f00;
		}
	}
}
</style>
<template>
    <form class="messager-create">
        <div class="form-info">
            <div class="form-name">
                <span class="t-name">昵称:</span>
                <input type="text" v-model="name">
            </div>
            <div class="form-email">
                <span class="t-email">邮箱:</span>
                <input type="text" v-model="email">
            </div>
            <div class="form-url">
                <span class="t-url">网址:</span>
                <input type="text" v-model="url">
            </div>
        </div>
        <div class="form-content">
            <textarea v-model="content" class="text" placeholder="写点什么吧..."></textarea>
            <button type="button" class="btn-submit" @click="send">发送</button>
        </div>
        <div class="form-text">
            <p v-if="text" v-text="text"></p>
        </div>
    </form>
</template>
<script>
import { create } from "./request";
export default {
	props: {
		ns: {
			type: String,
			required: true
		},
		kid: {
			type: String,
			required: true
		},
		pid: {
			type: [String, Number],
			default: 0
		}
	},
	data() {
		return {
			name: "",
			email: "",
			url: "",
			content: "",
			text: "",
			loading: false
		};
	},
	methods: {
		async send() {
			if (this.loading) {
				return;
			}
			this.loading = true;
			this.text = "提交中...";
			const data = {
				pid: this.pid,
				name: this.name,
				email: this.email,
				url: this.url,
				content: this.content
			};
			const res = await create(this.ns, this.kid, data);
			const json = await res.json();
			this.text = json.msg;
			if (json.code == 0) {
                this.empty();
                this.$emit("reload");
				setTimeout(() => {
					this.text = "";
				}, 1500);
			} else {
			}
			console.info(res);
			this.loading = false;
		},
		empty() {
			this.name = "";
			this.email = "";
			this.url = "";
			this.content = "";
		}
	}
};
</script>


