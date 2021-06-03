<template>
  <div class="button">
    <button @click="onClick">{{text}}</button>
  </div>
</template>
<script>
export default {
  props: ['text'],          // 获取父组件的传值
  data () {
    return {

    }
  },
  methods: {
    onClick () {
      console.log('点击了子组件')
      // 通过触发自定义事件修改父组件传递的text
      this.$emit('event1', '我修改了text')
    }
  }
}
</script>
<style lang="scss" scoped>
.button {
  button {
    width: 100px;
  }
}
</style>