<template>
  <div class="hello">
      <messager ns="hi" kid="21"/>
  </div>
</template>


<script>
import messager from "./msgview/index.vue";

export default {
	props: {
		msg: String
	},
	components: {
		messager
	}
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="less">
</style>
