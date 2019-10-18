<template>
  <div class="play-container">
    <div class="play-item">
    <el-avatar shape="square" :size="50" :src='data.avatar'></el-avatar>
    </div>
    <div class="play-item">
    <div>{{pos ? pos : '位置'}}</div>
    <div>{{data.player_name ? data.player_name : '姓名'}}</div>
    <div>{{data.score ? data.score : 'score'}}</div>
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
    }
  }
</script>

<style type="text/css" scoped>
  .play-container{
    display: flex;
    flex-direction : row;
    flex-wrap: nowrap;
    align-items : center;
  }
  .play-item{
    margin-left : 2px;
  }
  .play-item > div{
    font : 8px;

  }
</style>