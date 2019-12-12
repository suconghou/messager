<template>
	<div class="hello">
		<messager ns="hi" thread="21" />
	</div>
</template>


<script>
import messager from './msgview/index.vue';

export default {
	props: {
		msg: String
	},
	components: {
		messager
	}
};
</script>

<style  lang="less">
</style>
