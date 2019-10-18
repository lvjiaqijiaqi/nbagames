<template>
  <div>
    <div class="index-header">
      <el-row>
        <el-col :span="8"><el-player-item pos="小前锋" v-bind:data="play.SF" class="el-player-item"></el-player-item></el-col>
        <el-col :span="8"><el-player-item pos="大前锋" v-bind:data="play.PF" class="el-player-item"></el-player-item></el-col>
        <el-col :span="8"><el-player-item pos="中锋" v-bind:data="play.C" class="el-player-item"></el-player-item></el-col>
      </el-row>
      <el-row>
        <el-col :span="8"><el-player-item pos="组织后卫" v-bind:data="play.PG" class="el-player-item"></el-player-item></el-col>
        <el-col :span="8"><el-player-item pos="得分后卫" v-bind:data="play.SG" class="el-player-item"></el-player-item></el-col>
      </el-row>
      <el-menu class="el-menu-demo" mode="horizontal" @select="handleSelect">
      <el-menu-item class = 'index-meun-item' index="PG">核心后卫</el-menu-item>
      <el-menu-item class = 'index-meun-item' index="SG">得分后卫</el-menu-item>
      <el-menu-item class = 'index-meun-item' index="SF">小前锋</el-menu-item>
      <el-menu-item class = 'index-meun-item' index="PF">大前锋</el-menu-item>
      <el-menu-item class = 'index-meun-item' index="C">中锋</el-menu-item>
      </el-menu>
    </div>
    <div>
      <el-table
        class="index-list"
        :data="playerList"
        @cell-click="selectPlayer"
        :show-header = false
        style="width: 100%">
        <el-table-column  width="100">
          <template slot="header" slot-scope="scope">
            <el-input
              v-model="search"
              size="mini"
              placeholder="输入关键字搜索"/>
          </template>
      　　<template slot-scope="scope">
            <el-avatar :size="50" :src="scope.row.avatar"></el-avatar>
      　　</template>
        </el-table-column>
        <el-table-column>
          <template slot-scope="scope">
      　　　　<h5>{{ scope.row.player_name }}</h5>
      　　</template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
    import Ajax from '../api.js'
    import PlayerItem from './components/Player.vue'
    export default {
       name: "Index",
       components: {
         'el-player-item': PlayerItem
       },
       data() {
        return {
          game : {},
          room : {},
          status : 0,
          selectPos : 'C',
          players : {'C': [], 'PF': [], 'SF': [], 'SG': [], 'PG': []},
          play : {'C': {}, 'PF': {}, 'SF': {}, 'SG': {}, 'PG': {}},
        }
      },
      computed: {
        playerList: function () {
          return this.players[this.selectPos]
        }
      },
      methods: {
        updatePlayers() {
          var arr = {'C': [], 'PF': [], 'SF': [], 'SG': [], 'PG': []}
          for (var i = 0; i < this.game.gamePlayers.data.length; i++) {
            let player = this.game.gamePlayers.data[i]
            var pos = player.position_type
            if (pos & 1 << 0) {
              arr['C'].push(player)
            }
            if (pos & 1 << 1) {
              arr['PF'].push(player)
            }
            if (pos & 1 << 2) {
              arr['SF'].push(player)
            }
            if (pos & 1 << 3) {
              arr['SG'].push(player)
            }
            if (pos & 1 << 4) {
              arr['PG'].push(player)
            }
            if (this.status === 0) {
              player.score = this.room.PTS * player.PTS + this.room.REB * player.REB + this.room.AST * player.AST + this.room.STL * player.STL + this.room.BLK * player.BLK + this.room.TO * player.TO
              player.score = player.score.toFixed(2)
            } else {
              player.score = this.room.PTS * player.DPTS + this.room.REB * player.DREB + this.room.AST * player.DAST + this.room.STL * player.DSTL + this.room.BLK * player.DBLK + this.room.TO * player.DTO
              player.score = player.score.toFixed(2)
            }
            /*if (parseInt(player.player_id) === parseInt(this.game.play.data.C)) this.play.C = player
            if (parseInt(player.player_id) === this.game.play.data.PF) this.play.PF = player
            if (parseInt(player.player_id) === this.game.play.data.SF) this.play.SF = player
            if (parseInt(player.player_id) === this.game.play.data.SG) this.play.SG = player
            if (parseInt(player.player_id) === this.game.play.data.PG) this.play.PG = player*/
          }
          console.log(arr)
          this.players = arr
        },
        selectPlayer(data){
          this.play[this.selectPos] = data
          console.log(data)
        },
        handleSelect(key, keyPath) {
          this.selectPos = keyPath
          console.log(key, keyPath)
        },
        async getGame() {
          try {
            // 请求话题列表接口
            let topicsResponse = await Ajax.authRequest('games')
            this.game = topicsResponse.data.data
            this.room = this.game.room.data
            this.status = this.game.status
            this.updatePlayers()
          } catch (err) {

          }
        }
      },
      created () {
        this.getGame()
      }
    }
</script>

<style type="text/css" scoped>
  .index-header{
    background-color : #FFFFFF;
    position:fixed;
    z-index : 9999;
    height :200px;
    top : 0px;
  }
  .index-list{
    margin-top :180px
  }
  .index-meun-item{
    width: 20%;
  }
  .el-player-item{
    height : 70px;
  }
</style>
