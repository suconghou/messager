<style lang="less">
.messager-item {
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
			content: "";
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
			line-height: 1.2;
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
}
</style>
<template>
    <div class="messager-item" :rel="data.id">
        <div class="item">
            <div class="meta">
                <img class="avatar-img" :src="avatar" alt="">
                <p class="replay" @click="doreply">回复</p>
            </div>
            <div class="content">
                <p class="date">
                    <a @click="clickName" class="uname" v-text="uname"></a>
                    <span v-text="ctime"></span>
                </p>
                <div class="text" v-text="content">
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { avatar } from "./request";
import { timeBefore } from "./util";
export default {
	props: {
		data: {
			type: Object,
			required: true
		}
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
		}
	},
	methods: {
		clickName() {
			if (this.data.url) {
				window.open(this.data.url);
			}
		},
		doreply() {}
	}
};
</script>


