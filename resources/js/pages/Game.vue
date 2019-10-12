<template>
    <el-table
      :data="tableData"
      style="width: 100%">
      <el-table-column  width="100">
    　　<template slot-scope="scope">
    　　　　<img :src="scope.row.img" width="40" height="40" class="head_pic"/>
    　　</template>
      </el-table-column>
      <el-table-column>
        <template slot-scope="scope">
    　　　　<h5>{{ scope.row.name }}</h5>
    　　</template>
      </el-table-column>
    </el-table>
  </template>

  <script>
    import Ajax from '../api.js'
 
    export default {
      data() {
        return {
          game : {},
          room : {},
          status : {},
          tableData: [{
            date: '2016-05-02',
            name: '王小虎',
            address: '上海市普陀区金沙江路 1518 弄'
          }, {
            date: '2016-05-04',
            name: '王小虎',
            address: '上海市普陀区金沙江路 1517 弄'
          }, {
            date: '2016-05-01',
            name: '王小虎',
            address: '上海市普陀区金沙江路 1519 弄'
          }, {
            date: '2016-05-03',
            name: '王小虎',
            address: '上海市普陀区金沙江路 1516 弄'
          }]
        }
      },
      created () {
        localStorage.setItem('access_token', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9uYmFnYW1lcy50ZXN0IiwiaWF0IjoxNTcwMzc3NTg5LCJleHAiOjE2MDE5MTM1ODksIm5iZiI6MTU3MDM3NzU4OSwianRpIjoiNmVrYjd6NUNIbDV6MGQ1RCIsInN1YiI6MSwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.xgRM1d5frxTctSMXugcpcY3xarTBlx_5WnqECNC9qfA')
        this.getGame()
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
              if (parseInt(player.player_id) === parseInt(this.game.play.data.C)) this.play.C = player
              if (parseInt(player.player_id) === this.game.play.data.PF) this.play.PF = player
              if (parseInt(player.player_id) === this.game.play.data.SF) this.play.SF = player
              if (parseInt(player.player_id) === this.game.play.data.SG) this.play.SG = player
              if (parseInt(player.player_id) === this.game.play.data.PG) this.play.PG = player
          }
          console.log(this.play)
          this.allplayers = arr
          this.players = this.allplayers[this.selectedPos]
        },
        async getGame() {
          try {
            // 请求话题列表接口
            let topicsResponse = await Ajax.authRequest('games')
            console.log(topicsResponse);
            this.game = topicsResponse.data.data
            this.room = this.game.room.data
            this.status = this.game.status
          } catch (err) {

          }
        }
      }
    }
  </script>