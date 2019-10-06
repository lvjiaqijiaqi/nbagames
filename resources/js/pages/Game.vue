<template>
    <el-table
      :data="tableData"
      style="width: 100%">
      <el-table-column label="头像" width="100">
    　　<template slot-scope="scope">
    　　　　<img :src="scope.row.img" width="40" height="40" class="head_pic"/>
    　　</template>
      </el-table-column>
      <el-table-column
        prop="date"
        label="日期">
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
        },
        async getList () {
          try {
          let res = await Ajax.request('test')
          console.log(res)
        } catch (err) {
          console.log(err)
          alert('请求出错')
        } 
        }
      },
    }
  </script>