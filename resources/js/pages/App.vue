<template>
  <div>
    <router-view></router-view>
    <el-menu  class="app_bar" active-text-color="#409EFF" mode="horizontal" @select="handleSelect">
      <el-menu-item class="app_bar_item" index='index'>游戏</el-menu-item>
      <el-menu-item class="app_bar_item" index='home'>个人</el-menu-item>
    </el-menu>
  </div>
</template>

<script>
    export default {
       name: "App",
       data() {
        return {
          pages : {
            'index' : '/',
            'home' : '/home',
           }
        }
      },
       methods: {
         handleSelect(key, keyPath) {
          this.$router.push(this.pages[keyPath]);
        }
       }
    }
</script>

<style type="text/css" scoped>
  .app_bar{
    width : 100%;
    z-index : 99999;
    position:fixed;
    bottom: 0;
  }
  .app_bar_item{
    text-align: center;
    width : 50%;
  }
</style>