<template>
   <el-table
      :data="playerList"
      style="width: 100%">
      <el-table-column  width="100">
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
</template>

<script>
    import Ajax from '../api.js'
    export default {
       name: "Index",
       data() {
        return {
          game : {},
          room : {},
          status : 0,
          selectPos : 'C',
          players : {'C': [], 'PF': [], 'SF': [], 'SG': [], 'PG': []},
          play : {}
        }
      },
      computed: {
        playerList: function () {
          return this.players[this.selectPos]
        }
      },
      methods: {
        updatePlayers() {
          //console.log(this.game)
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
