<template>
	<div class="hello">
		<messager ns="hi" thread="21" />
	</div>
</template>

<script>
import messager from './msgview/index.vue';

export default {
	components: {
		messager,
	},
	props: {
		msg: String,
	},
};
</script>

<style lang="less"></style>
