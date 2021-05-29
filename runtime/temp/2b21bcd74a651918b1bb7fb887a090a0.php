<?php /*a:1:{s:69:"C:\newwww\wamp64\www\amzcount\application\index\view\index\index.html";i:1622273695;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>amzcount</title>
    <script src="/static/jq.js"></script>
    <link rel="stylesheet" href="/static/layui/css/layui.css">
    <script src="/static/layui/layui.js"></script>
    <script src="/static/echarts.js"></script>
    <script src="/static/vue.js"></script>
    <link rel="stylesheet" href="/static/element-ui.css">
    <script src="/static/element-ui.js"></script>
</head>
<style>
</style>
<body>
<div id="app">
    <template>
        <div style="width:50%;float: left;margin-top: 30px;">
          <el-form :inline="true" :model="form" class="demo-form-inline" style="margin-left: 20px;">
              <el-form-item label="关键词">
                <el-input v-model="form.key_words" placeholder="请输入关键词"></el-input>
              </el-form-item>
              <el-form-item>
                <el-button type="primary" @click="onSubmit">搜索</el-button>
              </el-form-item>
           </el-form>
         </div>
        <div style="width:50%;float: left;margin-top: 10px;">
             <div class="block" style="margin-bottom: 20px;">
                <span class="demonstration">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日期：&nbsp;&nbsp;</span>
                <el-date-picker
                  unlink-panels
                  format="yyyy-MM-dd"
                  value-format="yyyy-MM-dd"
                  v-model="form.sdate"
                  type="daterange"
                  range-separator="TO"
                  start-placeholder="开始日期"
                  end-placeholder="结束日期">
                </el-date-picker>
            </div>
            <div>
                <el-form ref="form" :model="form" label-width="100px">
                      <input type="radio" name="aa" style="float: left;margin-top: 13px;"><el-form-item label="每周增长量1：">
                        <el-input v-model="form.val_change" style="width: 70%;"></el-input>
                      </el-form-item>
                      <input type="radio" name="aa" style="float: left;margin-top: 13px;"><el-form-item label="每周增长率：">
                        <el-input v-model="form.percentage_change" style="width: 70%;"></el-input>&nbsp;%
                      </el-form-item>
                      <el-form-item label="达标比例：">
                        <el-input v-model="form.satisfy_p" style="width: 70%;margin-left: 13px;"></el-input>&nbsp;%
                      </el-form-item>
                  </el-form>
            </div>
        </div>

        <el-table
                v-loading="loading"
                :data="tableData"
                style="width: 98%;margin: 0 auto"
                height="670"
                :header-cell-style="{textAlign: 'center'}"
                :cell-style="{ textAlign: 'center' }">
            <el-table-column
                    fixed
                    prop="key_words"
                    label="关键词">
            </el-table-column>
            <el-table-column
                    prop="c_rank"
                    label="本周排名">
            </el-table-column>
            <el-table-column
                    prop="l_rank"
                    label="上周排名">
            </el-table-column>
            <el-table-column
                    prop="chang"
                    label="较上周排名变化">
            </el-table-column>
            <el-table-column
                    prop="update_time"
                    label="更新时间">
            </el-table-column>
            <el-table-column
                    prop="pic"
                    label="图表"
                    width="1300">
                <template slot-scope="scope">
                    <div>
                        {{ text(scope.$index,scope.row.id) }}
                        <div :id="`tiger-sale-trend-index` + scope.$index" class="tiger-trend-charts"></div>
                    </div>
                </template>
            </el-table-column>
            </el-table-column>
        </el-table>
    </template>
    <template>
        <div class="block">
            <el-pagination
                    @size-change="handleSizeChange"
                    @current-change="handleCurrentChange"
                    :current-page="currentPage"
                    :page-sizes="[5, 10, 20, 50]"
                    :page-size="size"
                    layout="total, sizes, prev, pager, next, jumper"
                    :total="totle">
            </el-pagination>
        </div>
    </template>
</div>

<script>
</script>
</body>
<script>
    new Vue({
        el: '#app',
        data: function() {
            return {
                currentPage:1,
                size:5,
                totle:0,
                form:{},
                loading: false,
                picdata:[],
                tableData: [
                //     {
                //     key_words: 'text_key_work',
                //     c_rank: '2',
                //     l_rank: '4',
                //     chang: '2',
                //     update_time: '2021-05-25',
                //     pic: ''
                // },{
                //     key_words: 'text_key_work',
                //     c_rank: '2',
                //     l_rank: '4',
                //     chang: '2',
                //     update_time: '2021-05-25',
                //     pic: ''
                // },{
                //     key_words: 'text_key_work',
                //     c_rank: '2',
                //     l_rank: '4',
                //     chang: '2',
                //     update_time: '2021-05-25',
                //     pic: ''
                // },{
                //     key_words: 'text_key_work',
                //     c_rank: '2',
                //     l_rank: '4',
                //     chang: '2',
                //     update_time: '2021-05-25',
                //     pic: ''
                // },{
                //     key_words: 'text_key_work',
                //     c_rank: '2',
                //     l_rank: '4',
                //     chang: '2',
                //     update_time: '2021-05-25',
                //     pic: ''
                // }
                ]
            }
        },
        methods: {
            getListdata() {
                this.loading=true
               var that=this
                $.ajax({
                    type:"POST",
                    url:"/index/index/getList",
                    data:{
                        limit:that.size,
                        page:that.currentPage,
                        search:that.form
                    },
                    dataType:"json",
                    success:function(data){
                        var jdata=JSON.parse( data );
                        that.tableData=jdata.data
                        that.totle=jdata.totle
                        that.picdata=jdata.picdata
                        that.loading=false
                    },
                    error:function(jqXHR){

                    }
                });
            },

            text: function (idname,id) {
                    // console.log(this.tableData[idname].key_words)
                    var that=this
                    // console.log(idname)
                let option = {
                    tooltip : {
                        trigger: 'axis',
                        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        },
                        formatter: function (datas) {
                            var res = '关键词：'+that.tableData[idname].key_words+'<br/>涨跌：'+datas[0].value+'<br/>日期：'+datas[0].axisValue;

                            return res;
                        },
                        textStyle: {
                            color: '#5c6c7c',
                            fontSize:10
                        },
                    },
                    axisLabel: {
                        interval:0,
                        rotate:40
                    },
                    grid: {
                        top: 7,
                        bottom: 20
                        // containLabel: true
                    },
                    xAxis: {
                        type: 'category',
                        // axisLabel:{
                        //     clickable:true,
                        //     interval:0,
                        //     rotate:30
                        // },
                        data: this.tableData[idname][id].update_time
                    },
                    yAxis: {
                        show: true,
                        type: 'value'
                    },
                    series:  [{
                        data: this.tableData[idname][id].chang,
                        type: 'bar',
                        showBackground: true,
                        backgroundStyle: {
                            color: 'rgba(180, 180, 180, 0.2)'
                        },
                        label:{
                            normal:{
                                // show:true,
                                textStyle: {
                                    fontSize:10
                                }
                            }
                        }
                    }]
                }
                var id='tiger-sale-trend-index'+idname
                this.$nextTick(() => {
                    let myChart = echarts.init(document.getElementById(id), 'macarons');
                    myChart.setOption(option);
                    myChart.resize();
                })
            },
            handleSizeChange(val) {
                this.size=val
                console.log(`每页 ${val} 条`);
            },
            handleCurrentChange(val) {
                this.currentPage=val
                console.log(`当前页: ${val}`);
            },
            onSubmit() {
                this.getListdata()
            }
        },
          watch: {
            size: function (newval, oldval) {
                this.getListdata()
            },
            currentPage: function (newval, oldval) {
                this.getListdata()
            }
          },
        mounted:function () {
                this.getListdata()
        }
    })
</script>
<style>
    &-frame {
        display: flex;
        flex-flow: column nowrap;
        justify-content: space-between;
    }
    .price-bar {
        color: red !important;
    }
    .tiger-trend-charts {
        height: 120px;
        min-width: 100px;
    }
    .cell{
        font-size: 12px;
    }

</style>
</html>
