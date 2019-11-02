<template>
  <div>
     <div class="index-header">
        <el-row type="flex" class="" justify="space-between">
        <el-col v-on:click.native="selectPosition('PG')" :span="4"><el-player-item  pos="组织后卫" v-bind:data="play.PG" class="el-player-item"></el-player-item></el-col>
        <el-col v-on:click.native="selectPosition('SG')" :span="4"><el-player-item pos="得分后卫" v-bind:data="play.SG" class="el-player-item"></el-player-item></el-col>
        <el-col v-on:click.native="selectPosition('SF')" :span="4"><el-player-item pos="小前锋" v-bind:data="play.SF" class="el-player-item"></el-player-item></el-col>
        <el-col v-on:click.native="selectPosition('PF')" :span="4"><el-player-item pos="大前锋" v-bind:data="play.PF" class="el-player-item"></el-player-item></el-col>
        <el-col v-on:click.native="selectPosition('C')" :span="4"><el-player-item pos="中锋" v-bind:data="play.C" class="el-player-item"></el-player-item></el-col>
      </el-row> 
      <el-row type="flex" class="index-player-msg-row" justify="space-between" align="middle">
        <el-col :span="4">
           <el-avatar :size="60" shape="square"  fit="contain" :src="selectedPlayer.avatar"></el-avatar>
        </el-col>
        <el-col :span="20">
           <div>
             位置:{{selectPos}}  姓名:{{selectedPlayer.player_name}}
           </div>
           <div>
             PTS:{{selectedPlayer.PTS}} REB:{{selectedPlayer.REB}} AST:{{selectedPlayer.AST}} STL:{{selectedPlayer.STL}} BLK:{{selectedPlayer.BLK}} TO:{{selectedPlayer.TO}} SCORE:{{selectedPlayer.score}}
           </div>
        </el-col>
      </el-row> 
    </div>
    <el-table
      :data="playerList"
      @cell-click="selectPlayer"
      :show-header = true
      :height = "listHeight"
      style="width: 100%">
        <el-table-column width="100">
          <template slot="header" slot-scope="scope">
              <el-button v-if="status === 0" @click="save">保存阵容</el-button>
              <el-button v-else-if="status === 1" @click="">比赛进行中</el-button>
              <el-button v-else @click="">结束</el-button>  
          </template>
          <template slot-scope="scope">
            <el-avatar :size="50" fit="contain" :src="scope.row.avatar"></el-avatar>
      　　</template>
        </el-table-column>
        <el-table-column>
          <template slot="header" slot-scope="scope">
            <div class="index-condition-row">
              <div>工资帽:{{limitScore}}</div>
              <div v-if="status === 0">已选:{{selectScore}}</div>
              <div v-else>评分:{{selectScore}}</div>
            </div>
          </template>
          <template slot-scope="scope">
      　　　　<span>{{ scope.row.player_name }}</span></br>
             <span>{{ scope.row.score }}</span>
      　　</template>
        </el-table-column>: 
    </el-table>
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
          selectScore:0,
          limitScore:0,
          game : {},
          room : {},
          status : 0,
          selectPos : 'C',
          players : {'C': [], 'PF': [], 'SF': [], 'SG': [], 'PG': []},
          play : {'C': {}, 'PF': {}, 'SF': {}, 'SG': {}, 'PG': {}},
          loading: false
        }
      },
      computed: {
        playerList: function () {
          return this.players[this.selectPos]
        },
        listHeight : function () {
          return window.innerHeight - 350
        },
        selectedPlayer : function(){
          return this.play[this.selectPos];
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
              player.score = parseFloat(player.score.toFixed(2))
            } else {
              player.score = this.room.PTS * player.DPTS + this.room.REB * player.DREB + this.room.AST * player.DAST + this.room.STL * player.DSTL + this.room.BLK * player.DBLK + this.room.TO * player.DTO
              player.score = parseFloat(player.score.toFixed(2))
            }
            //console.log(this.game.play);
            if (typeof(this.game.play)!='undefined') 
            { 
                if (parseInt(player.player_id) === parseInt(this.game.play.data.C)) this.play.C = player
                if (parseInt(player.player_id) === parseInt(this.game.play.data.PF)) this.play.PF = player
                if (parseInt(player.player_id) === parseInt(this.game.play.data.SF)) this.play.SF = player
                if (parseInt(player.player_id) === parseInt(this.game.play.data.SG)) this.play.SG = player
                if (parseInt(player.player_id) === parseInt(this.game.play.data.PG)) this.play.PG = player
            }
          }
          arr['PG'].sort(function(x, y){return y.score - x.score})
          arr['SG'].sort(function(x, y){return y.score - x.score})
          arr['PF'].sort(function(x, y){return y.score - x.score})
          arr['SF'].sort(function(x, y){return y.score - x.score})
          arr['C'].sort(function(x, y){return y.score - x.score})
          
          this.players = arr
        },
        selectPlayer(data){
          if (data.player_id !== this.play['C'].player_id &&
              data.player_id !== this.play['PF'].player_id &&
              data.player_id !== this.play['SF'].player_id &&
              data.player_id !== this.play['PG'].player_id &&
              data.player_id !== this.play['SG'].player_id) {
                this.play[this.selectPos] = data
                this.updateSelectScore()
          }else{
                this.$message({
                    message: '球员已经被选',
                    type: 'warning'
                });
          }
        },
        updateSelectScore(){
          var score = 0
          if (this.play.C.score !== undefined) score+=parseFloat(this.play.C.score)
          if (this.play.PF.score !== undefined) score+=parseFloat(this.play.PF.score)
          if (this.play.SF.score !== undefined) score+=parseFloat(this.play.SF.score)
          if (this.play.SG.score !== undefined) score+=parseFloat(this.play.SG.score)
          if (this.play.PG.score !== undefined) score+=parseFloat(this.play.PG.score)
          this.selectScore = score.toFixed(2);
        },
        selectPosition(pos) {
          this.selectPos = pos
        },
        save(){
          this.saveGamePlay();
        },
        async saveGamePlay() {
          let playData = {'C': this.play['C'].player_id, 'PF': this.play['PF'].player_id, 'SF': this.play['SF'].player_id, 'SG': this.play['SG'].player_id, 'PG': this.play['PG'].player_id, 'game_id': this.game.id}
          console.log(playData)
          if (playData['C'] !== undefined && playData['PF'] !== undefined && playData['SF'] !== undefined && playData['SG'] !== undefined && playData['PG'] !== undefined) {
              try {
                let postData = {
                  url: 'games/' + this.game.id + '/play',
                  data: playData,
                  method: 'POST'
                }
                this.loading = true;
                let topicsResponse = await Ajax.authRequest(postData)
                this.$message({
                  message: '保存成功',
                  type: 'success'
                });
                console.log(topicsResponse)
              } catch (err) {
                this.loading = false;
                this.$message.error('保存失败');
              }
            }else{
                this.$message.error('保存失败');
            }
        },
        async getGame() {
          try {
            // 请求话题列表接口
            this.loading = true;
            let topicsResponse = await Ajax.authRequest('games')
            this.loading = false;
            this.game = topicsResponse.data.data
            this.room = this.game.room.data
            this.limitScore = this.room.total.toFixed(2);
            this.status = this.game.status
            this.updatePlayers()
            this.updateSelectScore()
          } catch (err) {
            this.loading = false;
          }
        }
      },
      created () {
        this.getGame()
      },
      beforeMount() {
        
      }
    }
</script>

<style type="text/css">
  .index-header{
    width:100%;
    margin-left:0px;
    margin-right:0px;
    margin-top:50px;
    padding-left:0px;
    padding-right:0px;
    background-color : #FFFFFF;
    height :160px;
  }
  .index-meun-item{
    height : 120px;
    width: 18%;
  }
  .index-player-msg-row{
    margin-top : 15px;
  },
  .index-condition-row{
  }
  .index-condition-row > div{
    vertical-align: middle;
    height : 100%
  }
  .el-player-item{
    height : 70px;
    padding-left:0px;
    padding-right:0px;
    display : inline-block;
  }
  .el-menu{
    height : 120px;
  }
  .index-header-player{
    margin-left:0px;
    margin-right:0px;
    padding-left:0px;
    padding-right:0px;
    width:100%;
  }
</style>
