<template>
  <div>
    <mu-container>
      <mu-bottom-nav>
        <mu-bottom-nav-item title="游戏" icon=""></mu-bottom-nav-item>
        <mu-bottom-nav-item title="个人" icon=""></mu-bottom-nav-item>
      </mu-bottom-nav>
    </mu-container>
    <router-view></router-view>
  </div>
</template>

<script>
    export default {
       name: "App"
    }
</script>