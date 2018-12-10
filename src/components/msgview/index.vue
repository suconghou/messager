<style lang="less">
.messager-wrap {
    margin: 15px 0;
    .comm-title{
        padding: 10px;
        font-size: 16px;
        color: #2e6f2e;
    }
    .no-comment{
        color: #999;
        font-size: 14px;
        padding: 10px;
    }
}
</style>
<template>
    
    <div class="messager-wrap" v-if="!loading">
        <div class="comm-title">
            评论
        </div>
        <div class="no-comment" v-if="lists.length==0">
            目前暂无评论~
        </div>
        <div class="item-create" v-if="pid==0" >
            <create ref="create" :ns="ns" :kid="kid" :pid="pid" @setpid="setpid" @reload="reload" />
        </div>
        <div class="item-list">
            <item v-for="item in treeData" :ns="ns" :kid="kid" :pid="pid" :key="item.id" :data="item" @setpid="setpid" @reload="reload" />
        </div>
    </div>
</template>
<script>
import item from "./item";
import create from "./create";
import { buildTree } from './util'
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
            loading:true,
            pid:0,
			lists: []
		};
    },
    computed:{
        treeData(){
            return buildTree(this.lists);
        }
    },
	created() {
        this.initData();
    },
	mounted() {},
	methods: {
		async initData() {
            try{
                const res = await getList(this.ns, this.kid);
                const json = await res.json();
                console.info(json);
                if(json.code==0){
                    this.lists = json.data;
                }
                this.loading = false;
            }catch(e){
                console.error(e);
            }
            
        },
        setpid(pid){
            this.pid = pid;
        },
        reload(){
            this.initData();
        }
	}
};
</script>


