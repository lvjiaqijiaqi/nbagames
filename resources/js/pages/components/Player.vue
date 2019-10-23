<template>
  <div class="play-container">
    <div class="play-item">
    <el-avatar shape="square" :size="50" fit='contain' :src='data.avatar'></el-avatar>
    </div>
    <div class="play-item-div">{{data.score ? data.score : 'score'}}</div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "Player",
    props: {
      pos : {
        type: String,
        required: true,
        default: ''
      },
      data : {
        type: Object,
        required: false,
        default: {}
      }
    },
    methods: {
      greet: function (data) {
    // `this` 在方法里指向当前 Vue 实例
        alert(data)
      }
    }
  }
</script>

<style type="text/css" scoped>
  .play-container{
  }
  .play-item{
    margin-left : 2px;
    display : inline-block;
  }
  .play-item-div{
    font-size : 8px;
  }
  .play-item-line{
    height : 100%;
    width : 1px;
    background-color : #409EFF;
  }
</style>