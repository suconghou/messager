<style lang="less">
.messager-wrap {
}
</style>
<template>
    
    <div class="messager-wrap">
        <div class="item-create">
            <create ref="create" :ns="ns" :kid="kid" />
        </div>
        <div class="item-list">
            <item v-for="item in lists" :key="item.id" :data="item" />
        </div>
    </div>
</template>
<script>
import item from "./item";
import create from "./create";

import { getList } from "./request";

export default {
	props: {
		ns: {
			type: String,
			required: true
		},
		kid: {
			type: String,
			required: true
		}
	},
	components: {
        item,
        create
	},
	data() {
		return {
			lists: []
		};
	},
	created() {
        this.initData();
    },
	mounted() {},
	methods: {
		async initData() {
            const res = await getList(this.ns, this.kid);
            const json = await res.json();
            console.info(json);
            if(json.code==0){
                this.lists = json.data;
            }
		}
	}
};
</script>


