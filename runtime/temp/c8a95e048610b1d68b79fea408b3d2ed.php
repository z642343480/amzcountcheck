<?php /*a:1:{s:62:"D:\wamp64\www\amzcount\application\index\view\index\index.html";i:1622047002;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>amzcount</title>
    <script src="http://127.0.0.1/amzcount/public/static/jq.js"></script>
    <link rel="stylesheet" href="http://127.0.0.1/amzcount/public/static/layui/css/layui.css">
    <script src="http://127.0.0.1/amzcount/public/static/layui/layui.js"></script>
    <script src="http://127.0.0.1/amzcount/public/static/echarts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
</head>
<style>
</style>
<body>
<div id="app">
    <template>
        <el-table
                :data="tableData"
                style="width: 98%;margin: 0 auto"
                height="550">
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
                    label="涨跌">
            </el-table-column>
            <el-table-column
                    prop="update_time"
                    label="更新时间">
            </el-table-column>
            <el-table-column
                    prop="pic"
                    label="图表"
                    width="800">
                <template slot-scope="scope">
                    <div>
                        {{ text(scope.$index) }}
                        <div :id="`tiger-sale-trend-index` + scope.$index" class="tiger-trend-charts"></div>
                    </div>
                </template>
            </el-table-column>
            </el-table-column>
        </el-table>
    </template>
    <template>
        <div class="block">
            <span class="demonstration">完整功能</span>
            <el-pagination
                    @size-change="handleSizeChange"
                    @current-change="handleCurrentChange"
                    :current-page="currentPage4"
                    :page-sizes="[100, 200, 300, 400]"
                    :page-size="100"
                    layout="total, sizes, prev, pager, next, jumper"
                    :total="400">
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
                currentPage1: 5,
                currentPage2: 5,
                currentPage3: 5,
                currentPage4: 4,
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
            text: function (idname) {
                let option = {
                    tooltip : {
                        trigger: 'axis',
                        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        },
                        formatter: function (datas) {
                            var res = '关键词：key_word<br/>涨跌：12<br/>日期：2021-05-18';

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
                        data: ['2020-05-18', '2020-05-25', '2020-06-1', '2020-06-8', '2020-06-15', '2020-06-22']
                    },
                    yAxis: {
                        show: true,
                        type: 'value'
                    },
                    series:  [{
                        data: [12, {
                            value: 20,
                            itemStyle: {
                                color: '#a90000'
                            }
                        }, 3, 5, 16, 12],
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
                console.log(`每页 ${val} 条`);
            },
            handleCurrentChange(val) {
                console.log(`当前页: ${val}`);
            }
        },
        mounted:function () {
            var that=this
            $.ajax({
                type:"GET",
                url:"/index/index/getList",
                dataType:"json",
                success:function(data){
                    var jdata=JSON.parse( data );
                    that.tableData=jdata
                },
                error:function(jqXHR){

                }
            });
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
        height: 100px;
        min-width: 100px;
    }

</style>
</html>
