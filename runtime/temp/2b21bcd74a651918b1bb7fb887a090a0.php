<?php /*a:1:{s:69:"C:\newwww\wamp64\www\amzcount\application\index\view\index\index.html";i:1623349597;}*/ ?>
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
    <!-- <link rel="stylesheet" href="/static/element-ui.css"> -->
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">

    <script src="/static/element-ui.js"></script>
</head>
<style>
</style>
<body>
<div id="app">
    <template>
        <div style="position:fixed;right:23px;top:10px;z-index:9999999"><a href="/admin/index" style="color: #409EFF">进入后台</a>
        </div>
        <el-tabs type="border-card" v-model="activeName" @tab-click="handleClick" style="width: 98%;margin:0 auto;">
            <el-tab-pane label="美国" name="usa">
                <div style="width: 98%;height:200px;margin:0 auto;border: 1px solid #d1dbe5">
                    <div style="width:50%;float: left;margin-top: 30px;">
                        <el-form :inline="true" :model="usaform" class="demo-form-inline" style="margin-left: 20px;" @submit.native.prevent>
                            <el-form-item label="关键词">
                                <!--                                <el-input v-model.lazy="usaform.key_words" placeholder="请输入关键词" size="small"  @input="updateValue($event)"></el-input>-->
                                <input type="text" class="inputstyle" name="usa_key_words"/>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" size="small" @click="usaonSubmit('usa')" >搜索</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                    <div style="width:50%;float: left;margin-top: 10px;">
                        <div class="block" style="margin-bottom: 5px;">
                            <span class="demonstration">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日期：&nbsp;&nbsp;</span>
                            <el-date-picker
                                    size="small"
                                    unlink-panels
                                    format="yyyy-MM-dd"
                                    value-format="yyyy-MM-dd"
                                    v-model="usaform.sdate"
                                    type="daterange"
                                    range-separator="TO"
                                    start-placeholder="开始日期"
                                    end-placeholder="结束日期">
                            </el-date-picker>
                        </div>
                        <div>
                            <el-form ref="form" :model="usaform" label-width="100px">
                                <input type="radio" name="usaonly_who" checked="checked"
                                       style="float: left;margin-top: 13px;">
                                <el-form-item label="每周增长量：">
                                    <!--                                    <el-input v-model.number="usaform.val_change" size="small"-->
                                    <!--                                              style="width: 70%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle" name="usa_val_change"/>

                                </el-form-item>

                                <input type="radio" name="usaonly_who" style="float: left;margin-top: 13px;">
                                <el-form-item label="每周增长率：">
                                    <!--                                    <el-input v-model.number="usaform.percentage_change" size="small"-->
                                    <!--                                              style="width: 70%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle"
                                           name="usa_percentage_change"/>
                                    &nbsp;%
                                </el-form-item>
                                <el-form-item label="达标比例：" style="margin-left: 13px;">
                                    <!--                                    <el-input v-model.number="usaform.satisfy_p" size="small"-->
                                    <!--                                              style="width: 71%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle" name="usa_satisfy_p"/>
                                    &nbsp;%
                                </el-form-item>
                            </el-form>
                        </div>
                    </div>
                </div>
                <!-- <div style="widt:50px;height:30px;float: right;margin-right: 20px;margin-top: 10px;margin-bottom: 10px;background: #3a8ee6;border-color: #3a8ee6;color: #FFF;border: 1px solid block">导出</div> -->
                <el-menu default-active="1" class="el-menu-demo" mode="horizontal" style="width:98%;margin:0 auto">
                    <el-submenu index="2" style="float: right;">
                        <template slot="title">导出格式</template>
                        <el-menu-item index="2-1" @click="downloadExcel('usa','excel')">导出Excel</el-menu-item>
                        <el-menu-item index="2-2" @click="downloadExcel('usa','csv')">导出csv</el-menu-item>
                        <el-menu-item index="2-3" @click="downloadExcel('usa','csv',1)">导出全部(csv)</el-menu-item>
                    </el-submenu>
                </el-menu>
                <!-- <el-button class="dexc" style="float: right;margin-right: 20px;margin-top: 10px;margin-bottom: 10px;" size="small"
                           type="primary" icon="el-icon-download"
                           @click="downloadExcel('usa')">导出
                </el-button> -->

                <el-table
                        v-loading="loadingusa"
                        :data="usatableData"
                        tooltip-effect="dark"
                        :row-key="(row)=>{ return row.id}"
                        ref="usaTable"
                        stripe
                        style="width: 98%;margin: 0 auto;border: 1px solid #d1dbe5;"
                        height="670"
                        :header-cell-style="{textAlign: 'center'}"
                        :cell-style="{ textAlign: 'center' }"
                        @selection-change="handleSelectionChange">
                    <el-table-column
                            type="selection"
                            :reserve-selection="true"
                            width="55">
                    </el-table-column>
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
                            label="排名变化">
                    </el-table-column>
                    <el-table-column
                            prop="update_time"
                            label="更新时间">
                    </el-table-column>
                    <el-table-column
                            prop="pic"
                            label="图表"
                            width="1100">
                        <template slot-scope="scope">
                            <div>
                                {{ usatext(scope.$index, scope.row.id, 'usa') }}
                                <div :id="`usatiger-sale-trend-index` + scope.$index" class="tiger-trend-charts"></div>
                            </div>
                        </template>
                    </el-table-column>
                    </el-table-column>
                </el-table>
                <div class="block">
                    <el-pagination
                            @size-change="usahandleSizeChange"
                            @current-change="usahandleCurrentChange"
                            :current-page="usacurrentPage"
                            :page-sizes="[5, 10, 20, 50,100]"
                            :page-size="usasize"
                            layout="total, sizes, prev, pager, next, jumper"
                            :total="usatotle">
                    </el-pagination>
                </div>
            </el-tab-pane>
            <el-tab-pane label="英国" name="uk">
                <div style="width: 98%;height:200px;margin:0 auto;border: 1px solid #d1dbe5">
                    <div style="width:50%;float: left;margin-top: 30px;">
                        <el-form :inline="true" :model="ukform" class="demo-form-inline" style="margin-left: 20px;" @submit.native.prevent>
                            <el-form-item label="关键词">
                                <!--                                <el-input v-model="ukform.key_words" placeholder="请输入关键词" size="small" @input="updateValue($event)"></el-input>-->
                                <input type="text" class="inputstyle" name="uk_key_words"/>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" size="small" @click="ukonSubmit('uk')">搜索</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                    <div style="width:50%;float: left;margin-top: 10px;">
                        <div class="block" style="margin-bottom: 5px;">
                            <span class="demonstration">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日期：&nbsp;&nbsp;</span>
                            <el-date-picker
                                    size="small"
                                    unlink-panels
                                    format="yyyy-MM-dd"
                                    value-format="yyyy-MM-dd"
                                    v-model="ukform.sdate"
                                    type="daterange"
                                    range-separator="TO"
                                    start-placeholder="开始日期"
                                    end-placeholder="结束日期">
                            </el-date-picker>
                        </div>
                        <div>
                            <el-form ref="form" :model="ukform" label-width="100px">
                                <input type="radio" name="ukonly_who" checked="checked"
                                       style="float: left;margin-top: 13px;">
                                <el-form-item label="每周增长量：">
                                    <!--                                    <el-input v-model.number="ukform.val_change" size="small"-->
                                    <!--                                              style="width: 70%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle" name="uk_val_change"/>

                                </el-form-item>

                                <input type="radio" name="ukonly_who" style="float: left;margin-top: 13px;">
                                <el-form-item label="每周增长率：">
                                    <!--                                    <el-input v-model.number="ukform.percentage_change" size="small"-->
                                    <!--                                              style="width: 70%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle"
                                           name="uk_percentage_change"/>
                                    &nbsp;%
                                </el-form-item>
                                <el-form-item label="达标比例：" style="margin-left: 13px;">
                                    <!--                                    <el-input v-model.number="ukform.satisfy_p" size="small"-->
                                    <!--                                              style="width: 71%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle" name="uk_satisfy_p"/>
                                    &nbsp;%
                                </el-form-item>
                            </el-form>
                        </div>
                    </div>
                </div>
                <el-menu default-active="1" class="el-menu-demo" mode="horizontal" style="width:98%;margin:0 auto">
                    <el-submenu index="2" style="float: right;">
                        <template slot="title">导出格式</template>
                        <el-menu-item index="2-1" @click="downloadExcel('uk','excel')">导出Excel</el-menu-item>
                        <el-menu-item index="2-2" @click="downloadExcel('uk','csv')">导出csv</el-menu-item>
                        <el-menu-item index="2-3" @click="downloadExcel('uk','csv',1)">导出全部(csv)</el-menu-item>

                    </el-submenu>
                </el-menu>
                <!--  <el-button style="float: right;margin-right: 20px;margin-top: 10px;margin-bottom: 10px;" size="small"
                            type="primary" icon="el-icon-download"
                            @click="downloadExcel('uk')">导出
                 </el-button> -->

                <el-table
                        v-loading="loadinguk"
                        :data="uktableData"
                        tooltip-effect="dark"
                        :row-key="(row)=>{ return row.id}"
                        ref="ukTable"
                        stripe
                        style="width: 98%;margin: 0 auto;border: 1px solid #d1dbe5;"
                        height="670"
                        :header-cell-style="{textAlign: 'center'}"
                        :cell-style="{ textAlign: 'center' }"
                        @selection-change="handleSelectionChange">
                    <el-table-column
                            type="selection"
                            :reserve-selection="true"
                            width="55">
                    </el-table-column>
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
                            label="排名变化">
                    </el-table-column>
                    <el-table-column
                            prop="update_time"
                            label="更新时间">
                    </el-table-column>
                    <el-table-column
                            prop="pic"
                            label="图表"
                            width="1100">
                        <template slot-scope="scope">
                            <div>
                                {{ uktext(scope.$index, scope.row.id, 'uk') }}
                                <div :id="`uktiger-sale-trend-index` + scope.$index" class="tiger-trend-charts"></div>
                            </div>
                        </template>
                    </el-table-column>
                    </el-table-column>
                </el-table>
                <div class="block">
                    <el-pagination
                            @size-change="ukhandleSizeChange"
                            @current-change="ukhandleCurrentChange"
                            :current-page="ukcurrentPage"
                            :page-sizes="[5, 10, 20, 50,100]"
                            :page-size="uksize"
                            layout="total, sizes, prev, pager, next, jumper"
                            :total="uktotle">
                    </el-pagination>
                </div>
            </el-tab-pane>
            <el-tab-pane label="德国" name="de">
                <div style="width: 98%;height:200px;margin:0 auto;border: 1px solid #d1dbe5">
                    <div style="width:50%;float: left;margin-top: 30px;">
                        <el-form :inline="true" :model="deform" class="demo-form-inline" style="margin-left: 20px;" @submit.native.prevent>
                            <el-form-item label="关键词">
                                <!--                                <el-input v-model="deform.key_words" placeholder="请输入关键词" size="small" @input="updateValue($event)"></el-input>-->
                                <input type="text" class="inputstyle" name="de_key_words"/>

                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" size="small" @click="deonSubmit('de')">搜索</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                    <div style="width:50%;float: left;margin-top: 10px;">
                        <div class="block" style="margin-bottom: 5px;">
                            <span class="demonstration">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日期：&nbsp;&nbsp;</span>
                            <el-date-picker
                                    size="small"
                                    unlink-panels
                                    format="yyyy-MM-dd"
                                    value-format="yyyy-MM-dd"
                                    v-model="deform.sdate"
                                    type="daterange"
                                    range-separator="TO"
                                    start-placeholder="开始日期"
                                    end-placeholder="结束日期">
                            </el-date-picker>
                        </div>
                        <div>
                            <el-form ref="form" :model="deform" label-width="100px">
                                <input type="radio" name="deonly_who" checked="checked"
                                       style="float: left;margin-top: 13px;">
                                <el-form-item label="每周增长量：">
                                    <!--                                    <el-input v-model.number="deform.val_change" size="small"-->
                                    <!--                                              style="width: 70%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle" name="de_val_change"/>
                                </el-form-item>

                                <input type="radio" name="deonly_who" style="float: left;margin-top: 13px;">
                                <el-form-item label="每周增长率：">
                                    <!--                                    <el-input v-model.number="deform.percentage_change" size="small"-->
                                    <!--                                              style="width: 70%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle"
                                           name="de_percentage_change"/>
                                    &nbsp;%
                                </el-form-item>
                                <el-form-item label="达标比例：" style="margin-left: 13px;">
                                    <!--                                    <el-input v-model.number="deform.satisfy_p" size="small"-->
                                    <!--                                              style="width: 71%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle" name="de_satisfy_p"/>
                                    &nbsp;%
                                </el-form-item>
                            </el-form>
                        </div>
                    </div>
                </div>
                <el-menu default-active="1" class="el-menu-demo" mode="horizontal" style="width:98%;margin:0 auto">
                    <el-submenu index="2" style="float: right;">
                        <template slot="title">导出格式</template>
                        <el-menu-item index="2-1" @click="downloadExcel('de','excel')">导出Excel</el-menu-item>
                        <el-menu-item index="2-2" @click="downloadExcel('de','csv')">导出csv</el-menu-item>
                        <el-menu-item index="2-3" @click="downloadExcel('de','csv',1)">导出全部(csv)</el-menu-item>
                    </el-submenu>
                </el-menu>
                <!-- <el-button style="float: right;margin-right: 20px;margin-top: 10px;margin-bottom: 10px;" size="small"
                           type="primary" icon="el-icon-download"
                           @click="downloadExcel('de')">导出
                </el-button> -->

                <el-table
                        v-loading="loadingde"
                        :data="detableData"
                        tooltip-effect="dark"
                        :row-key="(row)=>{ return row.id}"
                        ref="deTable"
                        stripe
                        style="width: 98%;margin: 0 auto;border: 1px solid #d1dbe5;"
                        height="670"
                        :header-cell-style="{textAlign: 'center'}"
                        :cell-style="{ textAlign: 'center' }"
                        @selection-change="handleSelectionChange">
                    <el-table-column
                            type="selection"
                            :reserve-selection="true"
                            width="55">
                    </el-table-column>
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
                            label="排名变化">
                    </el-table-column>
                    <el-table-column
                            prop="update_time"
                            label="更新时间">
                    </el-table-column>
                    <el-table-column
                            prop="pic"
                            label="图表"
                            width="1100">
                        <template slot-scope="scope">
                            <div>
                                {{ detext(scope.$index, scope.row.id, 'de') }}
                                <div :id="`detiger-sale-trend-index` + scope.$index" class="tiger-trend-charts"></div>
                            </div>
                        </template>
                    </el-table-column>
                    </el-table-column>
                </el-table>
                <div class="block">
                    <el-pagination
                            @size-change="dehandleSizeChange"
                            @current-change="dehandleCurrentChange"
                            :current-page="decurrentPage"
                            :page-sizes="[5, 10, 20, 50,100]"
                            :page-size="desize"
                            layout="total, sizes, prev, pager, next, jumper"
                            :total="detotle">
                    </el-pagination>
                </div>
            </el-tab-pane>
            <el-tab-pane label="日本" name="jp">
                <div style="width: 98%;height:200px;margin:0 auto;border: 1px solid #d1dbe5">
                    <div style="width:50%;float: left;margin-top: 30px;">
                        <el-form :inline="true" :model="jpform" class="demo-form-inline" style="margin-left: 20px;" @submit.native.prevent>
                            <el-form-item label="关键词">
                                <!--                                <el-input v-model="jpform.key_words" placeholder="请输入关键词" size="small" @input="updateValue($event)"></el-input>-->
                                <input type="text" class="inputstyle" name="jp_key_words"/>

                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" size="small" @click="jponSubmit('jp')">搜索</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                    <div style="width:50%;float: left;margin-top: 10px;">
                        <div class="block" style="margin-bottom: 5px;">
                            <span class="demonstration">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日期：&nbsp;&nbsp;</span>
                            <el-date-picker
                                    size="small"
                                    unlink-panels
                                    format="yyyy-MM-dd"
                                    value-format="yyyy-MM-dd"
                                    v-model="jpform.sdate"
                                    type="daterange"
                                    range-separator="TO"
                                    start-placeholder="开始日期"
                                    end-placeholder="结束日期">
                            </el-date-picker>
                        </div>
                        <div>
                            <el-form ref="form" :model="jpform" label-width="100px">
                                <input type="radio" name="jponly_who" checked="checked"
                                       style="float: left;margin-top: 13px;">
                                <el-form-item label="每周增长量：">
                                    <!--                                    <el-input v-model.number="jpform.val_change" size="small"-->
                                    <!--                                              style="width: 70%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle" name="jp_val_change"/>

                                </el-form-item>

                                <input type="radio" name="jponly_who" style="float: left;margin-top: 13px;">
                                <el-form-item label="每周增长率：">
                                    <!--                                    <el-input v-model.number="jpform.percentage_change" size="small"-->
                                    <!--                                              style="width: 70%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle"
                                           name="jp_percentage_change"/>
                                    &nbsp;%
                                </el-form-item>
                                <el-form-item label="达标比例：" style="margin-left: 13px;">
                                    <!--                                    <el-input v-model.number="jpform.satisfy_p" size="small"-->
                                    <!--                                              style="width: 71%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle" name="jp_satisfy_p"/>
                                    &nbsp;%
                                </el-form-item>
                            </el-form>
                        </div>
                    </div>
                </div>
                <el-menu default-active="1" class="el-menu-demo" mode="horizontal" style="width:98%;margin:0 auto">
                    <el-submenu index="2" style="float: right;">
                        <template slot="title">导出格式</template>
                        <el-menu-item index="2-1" @click="downloadExcel('jp','excel')">导出Excel</el-menu-item>
                        <el-menu-item index="2-2" @click="downloadExcel('jp','csv')">导出csv</el-menu-item>
                        <el-menu-item index="2-3" @click="downloadExcel('jp','csv',1)">导出全部(csv)</el-menu-item>

                    </el-submenu>
                </el-menu>
                <!-- <el-button style="float: right;margin-right: 20px;margin-top: 10px;margin-bottom: 10px;" size="small"
                           type="primary" icon="el-icon-download"
                           @click="downloadExcel('jp')">导出
                </el-button> -->

                <el-table
                        v-loading="loadingjp"
                        :data="jptableData"
                        tooltip-effect="dark"
                        :row-key="(row)=>{ return row.id}"
                        ref="jpTable"
                        stripe
                        style="width: 98%;margin: 0 auto;border: 1px solid #d1dbe5;"
                        height="670"
                        :header-cell-style="{textAlign: 'center'}"
                        :cell-style="{ textAlign: 'center' }"
                        @selection-change="handleSelectionChange">
                    <el-table-column
                            type="selection"
                            :reserve-selection="true"
                            width="55">
                    </el-table-column>
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
                            label="排名变化">
                    </el-table-column>
                    <el-table-column
                            prop="update_time"
                            label="更新时间">
                    </el-table-column>
                    <el-table-column
                            prop="pic"
                            label="图表"
                            width="1100">
                        <template slot-scope="scope">
                            <div>
                                {{ jptext(scope.$index, scope.row.id, 'jp') }}
                                <div :id="`jptiger-sale-trend-index` + scope.$index" class="tiger-trend-charts"></div>
                            </div>
                        </template>
                    </el-table-column>
                    </el-table-column>
                </el-table>
                <div class="block">
                    <el-pagination
                            @size-change="jphandleSizeChange"
                            @current-change="jphandleCurrentChange"
                            :current-page="jpcurrentPage"
                            :page-sizes="[5, 10, 20, 50,100]"
                            :page-size="jpsize"
                            layout="total, sizes, prev, pager, next, jumper"
                            :total="jptotle">
                    </el-pagination>
                </div>
            </el-tab-pane>
            <el-tab-pane label="西班牙" name="esp">
                <div style="width: 98%;height:200px;margin:0 auto;border: 1px solid #d1dbe5">
                    <div style="width:50%;float: left;margin-top: 30px;">
                        <el-form :inline="true" :model="espform" class="demo-form-inline" style="margin-left: 20px;" @submit.native.prevent>
                            <el-form-item label="关键词">
                                <!--                                <el-input v-model="espform.key_words" placeholder="请输入关键词" size="small" @input="updateValue($event)"></el-input>-->
                                <input type="text" class="inputstyle" name="esp_key_words"/>

                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" size="small" @click="esponSubmit('esp')">搜索</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                    <div style="width:50%;float: left;margin-top: 10px;">
                        <div class="block" style="margin-bottom: 5px;">
                            <span class="demonstration">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日期：&nbsp;&nbsp;</span>
                            <el-date-picker
                                    size="small"
                                    unlink-panels
                                    format="yyyy-MM-dd"
                                    value-format="yyyy-MM-dd"
                                    v-model="espform.sdate"
                                    type="daterange"
                                    range-separator="TO"
                                    start-placeholder="开始日期"
                                    end-placeholder="结束日期">
                            </el-date-picker>
                        </div>
                        <div>
                            <el-form ref="form" :model="espform" label-width="100px">
                                <input type="radio" name="esponly_who" checked="checked"
                                       style="float: left;margin-top: 13px;">
                                <el-form-item label="每周增长量：">
                                    <!--                                    <el-input v-model.number="espform.val_change" size="small"-->
                                    <!--                                              style="width: 70%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle" name="esp_val_change"/>

                                </el-form-item>

                                <input type="radio" name="esponly_who" style="float: left;margin-top: 13px;">
                                <el-form-item label="每周增长率：">
                                    <!--                                    <el-input v-model.number="espform.percentage_change" size="small"-->
                                    <!--                                              style="width: 70%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle"
                                           name="esp_percentage_change"/>
                                    &nbsp;%
                                </el-form-item>
                                <el-form-item label="达标比例：" style="margin-left: 13px;">
                                    <!--                                    <el-input v-model.number="espform.satisfy_p" size="small"-->
                                    <!--                                              style="width: 71%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle" name="esp_satisfy_p"/>
                                    &nbsp;%
                                </el-form-item>
                            </el-form>
                        </div>
                    </div>
                </div>
                <el-menu default-active="1" class="el-menu-demo" mode="horizontal" style="width:98%;margin:0 auto">
                    <el-submenu index="2" style="float: right;">
                        <template slot="title">导出格式</template>
                        <el-menu-item index="2-1" @click="downloadExcel('esp','excel')">导出Excel</el-menu-item>
                        <el-menu-item index="2-2" @click="downloadExcel('esp','csv')">导出csv</el-menu-item>
                        <el-menu-item index="2-3" @click="downloadExcel('esp','csv',1)">导出全部(csv)</el-menu-item>

                    </el-submenu>
                </el-menu>
                <!-- <el-button style="float: right;margin-right: 20px;margin-top: 10px;margin-bottom: 10px;" size="small"
                           type="primary" icon="el-icon-download"
                           @click="downloadExcel('esp')">导出 -->
                </el-button>

                <el-table
                        v-loading="loadingesp"
                        :data="esptableData"
                        tooltip-effect="dark"
                        :row-key="(row)=>{ return row.id}"
                        ref="espTable"
                        stripe
                        style="width: 98%;margin: 0 auto;border: 1px solid #d1dbe5;"
                        height="670"
                        :header-cell-style="{textAlign: 'center'}"
                        :cell-style="{ textAlign: 'center' }"
                        @selection-change="handleSelectionChange">
                    <el-table-column
                            type="selection"
                            :reserve-selection="true"
                            width="55">
                    </el-table-column>
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
                            label="排名变化">
                    </el-table-column>
                    <el-table-column
                            prop="update_time"
                            label="更新时间">
                    </el-table-column>
                    <el-table-column
                            prop="pic"
                            label="图表"
                            width="1100">
                        <template slot-scope="scope">
                            <div>
                                {{ esptext(scope.$index, scope.row.id, 'esp') }}
                                <div :id="`esptiger-sale-trend-index` + scope.$index" class="tiger-trend-charts"></div>
                            </div>
                        </template>
                    </el-table-column>
                    </el-table-column>
                </el-table>
                <div class="block">
                    <el-pagination
                            @size-change="esphandleSizeChange"
                            @current-change="esphandleCurrentChange"
                            :current-page="espcurrentPage"
                            :page-sizes="[5, 10, 20, 50,100]"
                            :page-size="espsize"
                            layout="total, sizes, prev, pager, next, jumper"
                            :total="esptotle">
                    </el-pagination>
                </div>
            </el-tab-pane>
            <el-tab-pane label="意大利" name="it">
                <div style="width: 98%;height:200px;margin:0 auto;border: 1px solid #d1dbe5">
                    <div style="width:50%;float: left;margin-top: 30px;">
                        <el-form :inline="true" :model="itform" class="demo-form-inline" style="margin-left: 20px;" @submit.native.prevent>
                            <el-form-item label="关键词">
                                <!--                                <el-input v-model="itform.key_words" placeholder="请输入关键词" size="small" @input="updateValue($event)"></el-input>-->
                                <input type="text" class="inputstyle" name="it_key_words"/>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" size="small" @click="itonSubmit('it')">搜索</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                    <div style="width:50%;float: left;margin-top: 10px;">
                        <div class="block" style="margin-bottom: 5px;">
                            <span class="demonstration">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日期：&nbsp;&nbsp;</span>
                            <el-date-picker
                                    size="small"
                                    unlink-panels
                                    format="yyyy-MM-dd"
                                    value-format="yyyy-MM-dd"
                                    v-model="itform.sdate"
                                    type="daterange"
                                    range-separator="TO"
                                    start-placeholder="开始日期"
                                    end-placeholder="结束日期">
                            </el-date-picker>
                        </div>
                        <div>
                            <el-form ref="form" :model="itform" label-width="100px">
                                <input type="radio" name="itonly_who" checked="checked"
                                       style="float: left;margin-top: 13px;">
                                <el-form-item label="每周增长量：">
                                    <!--                                    <el-input v-model.number="itform.val_change" size="small"-->
                                    <!--                                              style="width: 70%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle" name="it_val_change"/>
                                </el-form-item>

                                <input type="radio" name="itonly_who" style="float: left;margin-top: 13px;">
                                <el-form-item label="每周增长率：">
                                    <!--                                    <el-input v-model.number="itform.percentage_change" size="small"-->
                                    <!--                                              style="width: 70%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle"
                                           name="it_percentage_change"/>
                                    &nbsp;%
                                </el-form-item>
                                <el-form-item label="达标比例：" style="margin-left: 13px;">
                                    <!--                                    <el-input v-model.number="itform.satisfy_p" size="small"-->
                                    <!--                                              style="width: 71%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle" name="it_satisfy_p"/>
                                    &nbsp;%
                                </el-form-item>
                            </el-form>
                        </div>
                    </div>
                </div>
                <el-menu default-active="1" class="el-menu-demo" mode="horizontal" style="width:98%;margin:0 auto">
                    <el-submenu index="2" style="float: right;">
                        <template slot="title">导出格式</template>
                        <el-menu-item index="2-1" @click="downloadExcel('it','excel')">导出Excel</el-menu-item>
                        <el-menu-item index="2-2" @click="downloadExcel('it','csv')">导出csv</el-menu-item>
                        <el-menu-item index="2-3" @click="downloadExcel('it','csv',1)">导出全部(csv)</el-menu-item>

                    </el-submenu>
                </el-menu>
                <!-- <el-button style="float: right;margin-right: 20px;margin-top: 10px;margin-bottom: 10px;" size="small"
                           type="primary" icon="el-icon-download"
                           @click="downloadExcel('it')">导出 -->
                </el-button>

                <el-table
                        v-loading="loadingit"
                        :data="ittableData"
                        tooltip-effect="dark"
                        :row-key="(row)=>{ return row.id}"
                        ref="itTable"
                        stripe
                        style="width: 98%;margin: 0 auto;border: 1px solid #d1dbe5;"
                        height="670"
                        :header-cell-style="{textAlign: 'center'}"
                        :cell-style="{ textAlign: 'center' }"
                        @selection-change="handleSelectionChange">
                    <el-table-column
                            type="selection"
                            :reserve-selection="true"
                            width="55">
                    </el-table-column>
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
                            label="排名变化">
                    </el-table-column>
                    <el-table-column
                            prop="update_time"
                            label="更新时间">
                    </el-table-column>
                    <el-table-column
                            prop="pic"
                            label="图表"
                            width="1100">
                        <template slot-scope="scope">
                            <div>
                                {{ ittext(scope.$index, scope.row.id, 'it') }}
                                <div :id="`ittiger-sale-trend-index` + scope.$index" class="tiger-trend-charts"></div>
                            </div>
                        </template>
                    </el-table-column>
                    </el-table-column>
                </el-table>
                <div class="block">
                    <el-pagination
                            @size-change="ithandleSizeChange"
                            @current-change="ithandleCurrentChange"
                            :current-page="itcurrentPage"
                            :page-sizes="[5, 10, 20, 50,100]"
                            :page-size="itsize"
                            layout="total, sizes, prev, pager, next, jumper"
                            :total="ittotle">
                    </el-pagination>
                </div>
            </el-tab-pane>
            <el-tab-pane label="法国" name="fr">
                <div style="width: 98%;height:200px;margin:0 auto;border: 1px solid #d1dbe5">
                    <div style="width:50%;float: left;margin-top: 30px;">
                        <el-form :inline="true" :model="frform" class="demo-form-inline" style="margin-left: 20px;" @submit.native.prevent>
                            <el-form-item label="关键词">
                                <!--                                <el-input v-model="frform.key_words" placeholder="请输入关键词" size="small" @input="updateValue($event)"></el-input>-->
                                <input type="text" class="inputstyle" name="fr_key_words"/>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" size="small" @click="fronSubmit('fr')">搜索</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                    <div style="width:50%;float: left;margin-top: 10px;">
                        <div class="block" style="margin-bottom: 5px;">
                            <span class="demonstration">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日期：&nbsp;&nbsp;</span>
                            <el-date-picker
                                    size="small"
                                    unlink-panels
                                    format="yyyy-MM-dd"
                                    value-format="yyyy-MM-dd"
                                    v-model="frform.sdate"
                                    type="daterange"
                                    range-separator="TO"
                                    start-placeholder="开始日期"
                                    end-placeholder="结束日期">
                            </el-date-picker>
                        </div>
                        <div>
                            <el-form ref="form" :model="frform" label-width="100px">
                                <input type="radio" name="fronly_who" checked="checked"
                                       style="float: left;margin-top: 13px;">
                                <el-form-item label="每周增长量：">
                                    <!--                                    <el-input v-model.number="frform.val_change" size="small"-->
                                    <!--                                              style="width: 70%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle" name="fr_val_change"/>
                                </el-form-item>

                                <input type="radio" name="fronly_who" style="float: left;margin-top: 13px;">
                                <el-form-item label="每周增长率：">
                                    <!--                                    <el-input v-model.number="frform.percentage_change" size="small"-->
                                    <!--                                              style="width: 70%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle"
                                           name="fr_percentage_change"/>
                                    &nbsp;%
                                </el-form-item>
                                <el-form-item label="达标比例：" style="margin-left: 13px;">
                                    <!--                                    <el-input v-model.number="frform.satisfy_p" size="small"-->
                                    <!--                                              style="width: 71%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle" name="fr_satisfy_p"/>
                                    &nbsp;%
                                </el-form-item>
                            </el-form>
                        </div>
                    </div>
                </div>
                <el-menu default-active="1" class="el-menu-demo" mode="horizontal" style="width:98%;margin:0 auto">
                    <el-submenu index="2" style="float: right;">
                        <template slot="title">导出格式</template>
                        <el-menu-item index="2-1" @click="downloadExcel('fr','excel')">导出Excel</el-menu-item>
                        <el-menu-item index="2-2" @click="downloadExcel('fr','csv')">导出csv</el-menu-item>
                        <el-menu-item index="2-3" @click="downloadExcel('fr','csv',1)">导出全部(csv)</el-menu-item>

                    </el-submenu>
                </el-menu>
                <!-- <el-button style="float: right;margin-right: 20px;margin-top: 10px;margin-bottom: 10px;" size="small"
                           type="primary" icon="el-icon-download"
                           @click="downloadExcel('fr')">导出
                </el-button> -->

                <el-table
                        v-loading="loadingfr"
                        :data="frtableData"
                        tooltip-effect="dark"
                        :row-key="(row)=>{ return row.id}"
                        ref="frTable"
                        stripe
                        style="width: 98%;margin: 0 auto;border: 1px solid #d1dbe5;"
                        height="670"
                        :header-cell-style="{textAlign: 'center'}"
                        :cell-style="{ textAlign: 'center' }"
                        @selection-change="handleSelectionChange">
                    <el-table-column
                            type="selection"
                            :reserve-selection="true"
                            width="55">
                    </el-table-column>
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
                            label="排名变化">
                    </el-table-column>
                    <el-table-column
                            prop="update_time"
                            label="更新时间">
                    </el-table-column>
                    <el-table-column
                            prop="pic"
                            label="图表"
                            width="1100">
                        <template slot-scope="scope">
                            <div>
                                {{ frtext(scope.$index, scope.row.id, 'fr') }}
                                <div :id="`frtiger-sale-trend-index` + scope.$index" class="tiger-trend-charts"></div>
                            </div>
                        </template>
                    </el-table-column>
                    </el-table-column>
                </el-table>
                <div class="block">
                    <el-pagination
                            @size-change="frhandleSizeChange"
                            @current-change="frhandleCurrentChange"
                            :current-page="frcurrentPage"
                            :page-sizes="[5, 10, 20, 50,100]"
                            :page-size="frsize"
                            layout="total, sizes, prev, pager, next, jumper"
                            :total="frtotle">
                    </el-pagination>
                </div>
            </el-tab-pane>
            <el-tab-pane label="墨西哥" name="mx">
                <div style="width: 98%;height:200px;margin:0 auto;border: 1px solid #d1dbe5">
                    <div style="width:50%;float: left;margin-top: 30px;">
                        <el-form :inline="true" :model="mxform" class="demo-form-inline" style="margin-left: 20px;" @submit.native.prevent>
                            <el-form-item label="关键词">
                                <!--                                <el-input v-model="mxform.key_words" placeholder="请输入关键词" size="small" @input="updateValue($event)"></el-input>-->
                                <input type="text" class="inputstyle" name="mx_key_words"/>

                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" size="small" @click="mxonSubmit('mx')">搜索</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                    <div style="width:50%;float: left;margin-top: 10px;">
                        <div class="block" style="margin-bottom: 5px;">
                            <span class="demonstration">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日期：&nbsp;&nbsp;</span>
                            <el-date-picker
                                    size="small"
                                    unlink-panels
                                    format="yyyy-MM-dd"
                                    value-format="yyyy-MM-dd"
                                    v-model="mxform.sdate"
                                    type="daterange"
                                    range-separator="TO"
                                    start-placeholder="开始日期"
                                    end-placeholder="结束日期">
                            </el-date-picker>
                        </div>
                        <div>
                            <el-form ref="form" :model="mxform" label-width="100px">
                                <input type="radio" name="mxonly_who" checked="checked"
                                       style="float: left;margin-top: 13px;">
                                <el-form-item label="每周增长量：">
                                    <!--                                    <el-input v-model.number="mxform.val_change" size="small"-->
                                    <!--                                              style="width: 70%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle" name="mx_val_change"/>

                                </el-form-item>

                                <input type="radio" name="mxonly_who" style="float: left;margin-top: 13px;">
                                <el-form-item label="每周增长率：">
                                    <!--                                    <el-input v-model.number="mxform.percentage_change" size="small"-->
                                    <!--                                              style="width: 70%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle"
                                           name="mx_percentage_change"/>
                                    &nbsp;%
                                </el-form-item>
                                <el-form-item label="达标比例：" style="margin-left: 13px;">
                                    <!--                                    <el-input v-model.number="mxform.satisfy_p" size="small"-->
                                    <!--                                              style="width: 71%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle" name="mx_satisfy_p"/>
                                    &nbsp;%
                                </el-form-item>
                            </el-form>
                        </div>
                    </div>
                </div>
                <el-menu default-active="1" class="el-menu-demo" mode="horizontal" style="width:98%;margin:0 auto">
                    <el-submenu index="2" style="float: right;">
                        <template slot="title">导出格式</template>
                        <el-menu-item index="2-1" @click="downloadExcel('mx','excel')">导出Excel</el-menu-item>
                        <el-menu-item index="2-2" @click="downloadExcel('mx','csv')">导出csv</el-menu-item>
                        <el-menu-item index="2-3" @click="downloadExcel('mx','csv',1)">导出全部(csv)</el-menu-item>
                    </el-submenu>
                </el-menu>
                <!-- <el-button style="float: right;margin-right: 20px;margin-top: 10px;margin-bottom: 10px;" size="small"
                           type="primary" icon="el-icon-download"
                           @click="downloadExcel('mx')">导出
                </el-button> -->

                <el-table
                        v-loading="loadingmx"
                        :data="mxtableData"
                        tooltip-effect="dark"
                        :row-key="(row)=>{ return row.id}"
                        ref="mxTable"
                        stripe
                        style="width: 98%;margin: 0 auto;border: 1px solid #d1dbe5;"
                        height="670"
                        :header-cell-style="{textAlign: 'center'}"
                        :cell-style="{ textAlign: 'center' }"
                        @selection-change="handleSelectionChange">
                    <el-table-column
                            type="selection"
                            :reserve-selection="true"
                            width="55">
                    </el-table-column>
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
                            label="排名变化">
                    </el-table-column>
                    <el-table-column
                            prop="update_time"
                            label="更新时间">
                    </el-table-column>
                    <el-table-column
                            prop="pic"
                            label="图表"
                            width="1100">
                        <template slot-scope="scope">
                            <div>
                                {{ mxtext(scope.$index, scope.row.id, 'mx') }}
                                <div :id="`mxtiger-sale-trend-index` + scope.$index" class="tiger-trend-charts"></div>
                            </div>
                        </template>
                    </el-table-column>
                    </el-table-column>
                </el-table>
                <div class="block">
                    <el-pagination
                            @size-change="mxhandleSizeChange"
                            @current-change="mxhandleCurrentChange"
                            :current-page="mxcurrentPage"
                            :page-sizes="[5, 10, 20, 50,100]"
                            :page-size="mxsize"
                            layout="total, sizes, prev, pager, next, jumper"
                            :total="mxtotle">
                    </el-pagination>
                </div>
            </el-tab-pane>
            <el-tab-pane label="加拿大" name="ca">
                <div style="width: 98%;height:200px;margin:0 auto;border: 1px solid #d1dbe5">
                    <div style="width:50%;float: left;margin-top: 30px;">
                        <el-form :inline="true" :model="caform" class="demo-form-inline" style="margin-left: 20px;" @submit.native.prevent>
                            <el-form-item label="关键词">
                                <!--                                <el-input v-model="caform.key_words" placeholder="请输入关键词" size="small" @input="updateValue($event)"></el-input>-->
                                <input type="text" class="inputstyle" name="ca_key_words"/>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" size="small" @click="caonSubmit('ca')">搜索</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                    <div style="width:50%;float: left;margin-top: 10px;">
                        <div class="block" style="margin-bottom: 5px;">
                            <span class="demonstration">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日期：&nbsp;&nbsp;</span>
                            <el-date-picker
                                    size="small"
                                    unlink-panels
                                    format="yyyy-MM-dd"
                                    value-format="yyyy-MM-dd"
                                    v-model="caform.sdate"
                                    type="daterange"
                                    range-separator="TO"
                                    start-placeholder="开始日期"
                                    end-placeholder="结束日期">
                            </el-date-picker>
                        </div>
                        <div>
                            <el-form ref="form" :model="caform" label-width="100px">
                                <input type="radio" name="caonly_who" checked="checked"
                                       style="float: left;margin-top: 13px;">
                                <el-form-item label="每周增长量：">
                                    <!--                                    <el-input v-model.number="caform.val_change" size="small"-->
                                    <!--                                              style="width: 70%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle" name="ca_val_change"/>

                                </el-form-item>

                                <input type="radio" name="caonly_who" style="float: left;margin-top: 13px;">
                                <el-form-item label="每周增长率：">
                                    <!--                                    <el-input v-model.number="caform.percentage_change" size="small"-->
                                    <!--                                              style="width: 70%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle"
                                           name="ca_percentage_change"/>
                                    &nbsp;%
                                </el-form-item>
                                <el-form-item label="达标比例：" style="margin-left: 13px;">
                                    <!--                                    <el-input v-model.number="caform.satisfy_p" size="small"-->
                                    <!--                                              style="width: 71%;" @input="updateValue($event)"></el-input>-->
                                    <input style="width: 70%;" type="text" class="inputstyle" name="ca_satisfy_p"/>
                                    &nbsp;%
                                </el-form-item>
                            </el-form>
                        </div>
                    </div>
                </div>
                <el-menu default-active="1" class="el-menu-demo" mode="horizontal" style="width:98%;margin:0 auto">
                    <el-submenu index="2" style="float: right;">
                        <template slot="title">导出格式</template>
                        <el-menu-item index="2-1" @click="downloadExcel('ca','excel')">导出Excel</el-menu-item>
                        <el-menu-item index="2-2" @click="downloadExcel('ca','csv')">导出csv</el-menu-item>
                        <el-menu-item index="2-3" @click="downloadExcel('ca','csv',1)">导出全部(csv)</el-menu-item>

                    </el-submenu>
                </el-menu>
                <!-- <el-button style="float: right;margin-right: 20px;margin-top: 10px;margin-bottom: 10px;" size="small"
                           type="primary" icon="el-icon-download"
                           @click="downloadExcel('ca')">导出
                </el-button> -->

                <el-table
                        v-loading="loadingca"
                        :data="catableData"
                        tooltip-effect="dark"
                        :row-key="(row)=>{ return row.id}"
                        ref="caTable"
                        stripe
                        style="width: 98%;margin: 0 auto;border: 1px solid #d1dbe5;"
                        height="670"
                        :header-cell-style="{textAlign: 'center'}"
                        :cell-style="{ textAlign: 'center' }"
                        @selection-change="handleSelectionChange">
                    <el-table-column
                            type="selection"
                            :reserve-selection="true"
                            width="55">
                    </el-table-column>
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
                            label="排名变化">
                    </el-table-column>
                    <el-table-column
                            prop="update_time"
                            label="更新时间">
                    </el-table-column>
                    <el-table-column
                            prop="pic"
                            label="图表"
                            width="1100">
                        <template slot-scope="scope">
                            <div>
                                {{ catext(scope.$index, scope.row.id, 'ca') }}
                                <div :id="`catiger-sale-trend-index` + scope.$index" class="tiger-trend-charts"></div>
                            </div>
                        </template>
                    </el-table-column>
                    </el-table-column>
                </el-table>
                <div class="block">
                    <el-pagination
                            @size-change="cahandleSizeChange"
                            @current-change="cahandleCurrentChange"
                            :current-page="cacurrentPage"
                            :page-sizes="[5, 10, 20, 50,100]"
                            :page-size="casize"
                            layout="total, sizes, prev, pager, next, jumper"
                            :total="catotle">
                    </el-pagination>
                </div>
            </el-tab-pane>
        </el-tabs>

    </template>

</div>

<script>
</script>
</body>
<script>
    new Vue({
        el: '#app',
        data: function () {
            return {
                usacurrentPage: 1,
                usasize: 5,
                usatotle: 0,
                usaform: {},
                usatableData: [],
                loadingusa: false,


                ukcurrentPage: 1,
                uksize: 5,
                uktotle: 0,
                ukform: {},
                uktableData: [],
                loadinguk: false,


                decurrentPage: 1,
                desize: 5,
                detotle: 0,
                deform: {},
                detableData: [],
                loadingde: false,


                jpcurrentPage: 1,
                jpsize: 5,
                jptotle: 0,
                jpform: {},
                jptableData: [],
                loadingjp: false,


                espcurrentPage: 1,
                espsize: 5,
                esptotle: 0,
                espform: {},
                esptableData: [],
                loadingesp: false,


                itcurrentPage: 1,
                itsize: 5,
                ittotle: 0,
                itform: {},
                ittableData: [],
                loadingit: false,


                frcurrentPage: 1,
                frsize: 5,
                frtotle: 0,
                frform: {},
                frtableData: [],
                loadingfr: false,


                mxcurrentPage: 1,
                mxsize: 5,
                mxtotle: 0,
                mxform: {},
                mxtableData: [],
                loadingmx: false,


                cacurrentPage: 1,
                casize: 5,
                catotle: 0,
                caform: {},
                catableData: [],
                loadingca: false,


                multipleSelection: [], //选中的数据
                excelData: [],
                activeName: 'usa',
                isclicktabs: ['usa']
            }
        },
        components: {},
        methods: {
            updateValue: function (value) {
                // var formattedValue = value //对值进行处理
                // //
                // // //手动触发input事件,将格式化后的值传过去,这是最终显示输入框的输出
                // this.$emit('input', Number(formattedValue))
            },
            usagetListdata(cu) {
                this.loadingusa = true
                var that = this
                if ($("input[name='usa_key_words']").val() != '' || $("input[name='usa_percentage_change']").val() != '' || $("input[name='usa_satisfy_p']").val() != '' || this.usaform.sdate != undefined || $("input[name='usa_val_change']").val() != '') {
                    // console.log(this.form.length)
                    var obj = document.getElementsByName("usaonly_who");
                    if (obj[0].checked == true) {
                        $("input[name='usa_percentage_change']").val('')
                        // delete this.usaform.percentage_change
                    }
                    if (obj[1].checked == true) {
                        $("input[name='usa_val_change']").val('')
                        // delete this.usaform.val_change
                    }
                    console.log($("input[name='usa_satisfy_p']").val());
                    if ($("input[name='usa_satisfy_p']").val() != '') {
                        if ($("input[name='usa_val_change']").val() == '' && $("input[name='usa_percentage_change']").val() == '') {
                            this.$message.error('使用“达标比例”时，“每周增长量”或“每周增长率”必须填写其中一个');
                            this.loadingusa = false
                            return false
                        }
                    }
                }
                var search = {
                    key_words: $("input[name='usa_key_words']").val(),
                    percentage_change: $("input[name='usa_percentage_change']").val(),
                    satisfy_p: $("input[name='usa_satisfy_p']").val(),
                    val_change: $("input[name='usa_val_change']").val(),
                    sdate: this.usaform.sdate,

                }
                $.ajax({
                    type: "POST",
                    url: "/index/index/getList",
                    data: {
                        limit: that.usasize,
                        page: that.usacurrentPage,
                        search: search,
                        cu: cu
                    },
                    dataType: "json",
                    success: function (data) {
                        var jdata = JSON.parse(data);
                        if (jdata.code == 1) {
                            that.usatableData = jdata.data
                            that.usatotle = jdata.totle
                            that.usapicdata = jdata.picdata
                            that.loadingusa = false
                        } else {
                            that.usatableData = []
                            that.loadingusa = false
                            that.$message.error(jdata.message);
                        }


                    },
                    error: function (jqXHR) {
                        that.loadingusa = false
                    }
                });
            },
            ukgetListdata(cu) {
                this.loadinguk = true
                var that = this
                if ($("input[name='uk_key_words']").val() != '' || $("input[name='uk_percentage_change']").val() != '' || $("input[name='uk_satisfy_p']").val() != '' || this.ukform.sdate != undefined || $("input[name='uk_val_change']").val() != '') {
                    // console.log(this.form.length)
                    var obj = document.getElementsByName("ukonly_who");
                    if (obj[0].checked == true) {
                        $("input[name='uk_percentage_change']").val('')
                        // delete this.usaform.percentage_change
                    }
                    if (obj[1].checked == true) {
                        $("input[name='uk_val_change']").val('')
                        // delete this.usaform.val_change
                    }
                    console.log($("input[name='uk_satisfy_p']").val());
                    if ($("input[name='uk_satisfy_p']").val() != '') {
                        if ($("input[name='uk_val_change']").val() == '' && $("input[name='uk_percentage_change']").val() == '') {
                            this.$message.error('使用“达标比例”时，“每周增长量”或“每周增长率”必须填写其中一个');
                            this.loadinguk = false
                            return false
                        }
                    }
                }
                var search = {
                    key_words: $("input[name='uk_key_words']").val(),
                    percentage_change: $("input[name='uk_percentage_change']").val(),
                    satisfy_p: $("input[name='uk_satisfy_p']").val(),
                    val_change: $("input[name='uk_val_change']").val(),
                    sdate: this.ukform.sdate,

                }
                $.ajax({
                    type: "POST",
                    url: "/index/index/getList",
                    data: {
                        limit: that.uksize,
                        page: that.ukcurrentPage,
                        search: search,
                        cu: cu
                    },
                    dataType: "json",
                    success: function (data) {
                        var jdata = JSON.parse(data);
                        if (jdata.code == 1) {
                            that.uktableData = jdata.data
                            that.uktotle = jdata.totle
                            that.ukpicdata = jdata.picdata
                            that.loadinguk = false
                        } else {
                            that.uktableData = []
                            that.loadinguk = false
                            that.$message.error(jdata.message);
                        }


                    },
                    error: function (jqXHR) {
                        that.loadinguk = false
                    }
                });
            },
            degetListdata(cu) {
                this.loadingde = true
                var that = this
                if ($("input[name='de_key_words']").val() != '' || $("input[name='de_percentage_change']").val() != '' || $("input[name='de_satisfy_p']").val() != '' || this.deform.sdate != undefined || $("input[name='de_val_change']").val() != '') {
                    // console.log(this.form.length)
                    var obj = document.getElementsByName("deonly_who");
                    if (obj[0].checked == true) {
                        $("input[name='de_percentage_change']").val('')
                        // delete this.usaform.percentage_change
                    }
                    if (obj[1].checked == true) {
                        $("input[name='de_val_change']").val('')
                        // delete this.usaform.val_change
                    }
                    console.log($("input[name='de_satisfy_p']").val());
                    if ($("input[name='de_satisfy_p']").val() != '') {
                        if ($("input[name='de_val_change']").val() == '' && $("input[name='de_percentage_change']").val() == '') {
                            this.$message.error('使用“达标比例”时，“每周增长量”或“每周增长率”必须填写其中一个');
                            this.loadingde = false
                            return false
                        }
                    }
                }
                var search = {
                    key_words: $("input[name='de_key_words']").val(),
                    percentage_change: $("input[name='de_percentage_change']").val(),
                    satisfy_p: $("input[name='de_satisfy_p']").val(),
                    val_change: $("input[name='de_val_change']").val(),
                    sdate: this.deform.sdate,

                }
                $.ajax({
                    type: "POST",
                    url: "/index/index/getList",
                    data: {
                        limit: that.desize,
                        page: that.decurrentPage,
                        search: search,
                        cu: cu
                    },
                    dataType: "json",
                    success: function (data) {
                        var jdata = JSON.parse(data);
                        if (jdata.code == 1) {
                            that.detableData = jdata.data
                            that.detotle = jdata.totle
                            that.depicdata = jdata.picdata
                            that.loadingde = false
                        } else {
                            that.detableData = []
                            that.loadingde = false
                            that.$message.error(jdata.message);
                        }


                    },
                    error: function (jqXHR) {
                        that.loadingde = false
                    }
                });
            },
            jpgetListdata(cu) {
                this.loadingjp = true
                var that = this
                if ($("input[name='jp_key_words']").val() != '' || $("input[name='jp_percentage_change']").val() != '' || $("input[name='jp_satisfy_p']").val() != '' || this.jpform.sdate != undefined || $("input[name='jp_val_change']").val() != '') {
                    // console.log(this.form.length)
                    var obj = document.getElementsByName("jponly_who");
                    if (obj[0].checked == true) {
                        $("input[name='jp_percentage_change']").val('')
                        // delete this.usaform.percentage_change
                    }
                    if (obj[1].checked == true) {
                        $("input[name='jp_val_change']").val('')
                        // delete this.usaform.val_change
                    }
                    console.log($("input[name='jp_satisfy_p']").val());
                    if ($("input[name='jp_satisfy_p']").val() != '') {
                        if ($("input[name='jp_val_change']").val() == '' && $("input[name='jp_percentage_change']").val() == '') {
                            this.$message.error('使用“达标比例”时，“每周增长量”或“每周增长率”必须填写其中一个');
                            this.loadingjp = false
                            return false
                        }
                    }
                }
                var search = {
                    key_words: $("input[name='jp_key_words']").val(),
                    percentage_change: $("input[name='jp_percentage_change']").val(),
                    satisfy_p: $("input[name='jp_satisfy_p']").val(),
                    val_change: $("input[name='jp_val_change']").val(),
                    sdate: this.jpform.sdate,

                }
                $.ajax({
                    type: "POST",
                    url: "/index/index/getList",
                    data: {
                        limit: that.jpsize,
                        page: that.jpcurrentPage,
                        search: search,
                        cu: cu
                    },
                    dataType: "json",
                    success: function (data) {
                        var jdata = JSON.parse(data);
                        if (jdata.code == 1) {
                            that.jptableData = jdata.data
                            that.jptotle = jdata.totle
                            that.jppicdata = jdata.picdata
                            that.loadingjp = false
                        } else {
                            that.jptableData = []
                            that.loadingjp = false
                            that.$message.error(jdata.message);
                        }


                    },
                    error: function (jqXHR) {
                        that.loadingjp = false
                    }
                });
            },
            espgetListdata(cu) {
                this.loadingesp = true
                var that = this
                if ($("input[name='esp_key_words']").val() != '' || $("input[name='esp_percentage_change']").val() != '' || $("input[name='esp_satisfy_p']").val() != '' || this.espform.sdate != undefined || $("input[name='esp_val_change']").val() != '') {
                    // console.log(this.form.length)
                    var obj = document.getElementsByName("esponly_who");
                    if (obj[0].checked == true) {
                        $("input[name='esp_percentage_change']").val('')
                        // delete this.usaform.percentage_change
                    }
                    if (obj[1].checked == true) {
                        $("input[name='esp_val_change']").val('')
                        // delete this.usaform.val_change
                    }
                    console.log($("input[name='esp_satisfy_p']").val());
                    if ($("input[name='esp_satisfy_p']").val() != '') {
                        if ($("input[name='esp_val_change']").val() == '' && $("input[name='esp_percentage_change']").val() == '') {
                            this.$message.error('使用“达标比例”时，“每周增长量”或“每周增长率”必须填写其中一个');
                            this.loadingesp = false
                            return false
                        }
                    }
                }
                var search = {
                    key_words: $("input[name='esp_key_words']").val(),
                    percentage_change: $("input[name='esp_percentage_change']").val(),
                    satisfy_p: $("input[name='esp_satisfy_p']").val(),
                    val_change: $("input[name='esp_val_change']").val(),
                    sdate: this.espform.sdate,

                }
                $.ajax({
                    type: "POST",
                    url: "/index/index/getList",
                    data: {
                        limit: that.espsize,
                        page: that.espcurrentPage,
                        search: search,
                        cu: cu
                    },
                    dataType: "json",
                    success: function (data) {
                        var jdata = JSON.parse(data);
                        if (jdata.code == 1) {
                            that.esptableData = jdata.data
                            that.esptotle = jdata.totle
                            that.esppicdata = jdata.picdata
                            that.loadingesp = false
                        } else {
                            that.esptableData = []
                            that.loadingesp = false
                            that.$message.error(jdata.message);
                        }


                    },
                    error: function (jqXHR) {
                        that.loadingesp = false
                    }
                });
            },
            itgetListdata(cu) {
                this.loadingit = true
                var that = this
                if ($("input[name='it_key_words']").val() != '' || $("input[name='it_percentage_change']").val() != '' || $("input[name='it_satisfy_p']").val() != '' || this.itform.sdate != undefined || $("input[name='it_val_change']").val() != '') {
                    // console.log(this.form.length)
                    var obj = document.getElementsByName("itonly_who");
                    if (obj[0].checked == true) {
                        $("input[name='it_percentage_change']").val('')
                        // delete this.usaform.percentage_change
                    }
                    if (obj[1].checked == true) {
                        $("input[name='it_val_change']").val('')
                        // delete this.usaform.val_change
                    }
                    console.log($("input[name='it_satisfy_p']").val());
                    if ($("input[name='it_satisfy_p']").val() != '') {
                        if ($("input[name='it_val_change']").val() == '' && $("input[name='it_percentage_change']").val() == '') {
                            this.$message.error('使用“达标比例”时，“每周增长量”或“每周增长率”必须填写其中一个');
                            this.loadingit = false
                            return false
                        }
                    }
                }
                var search = {
                    key_words: $("input[name='it_key_words']").val(),
                    percentage_change: $("input[name='it_percentage_change']").val(),
                    satisfy_p: $("input[name='it_satisfy_p']").val(),
                    val_change: $("input[name='it_val_change']").val(),
                    sdate: this.itform.sdate,

                }
                $.ajax({
                    type: "POST",
                    url: "/index/index/getList",
                    data: {
                        limit: that.itsize,
                        page: that.itcurrentPage,
                        search: search,
                        cu: cu
                    },
                    dataType: "json",
                    success: function (data) {
                        var jdata = JSON.parse(data);
                        if (jdata.code == 1) {
                            that.ittableData = jdata.data
                            that.ittotle = jdata.totle
                            that.itpicdata = jdata.picdata
                            that.loadingit = false
                        } else {
                            that.ittableData = []
                            that.loadingit = false
                            that.$message.error(jdata.message);
                        }


                    },
                    error: function (jqXHR) {
                        that.loadingit = false
                    }
                });
            },
            frgetListdata(cu) {
                this.loadingfr = true
                var that = this
                if ($("input[name='fr_key_words']").val() != '' || $("input[name='fr_percentage_change']").val() != '' || $("input[name='fr_satisfy_p']").val() != '' || this.frform.sdate != undefined || $("input[name='fr_val_change']").val() != '') {
                    // console.log(this.form.length)
                    var obj = document.getElementsByName("fronly_who");
                    if (obj[0].checked == true) {
                        $("input[name='fr_percentage_change']").val('')
                        // delete this.usaform.percentage_change
                    }
                    if (obj[1].checked == true) {
                        $("input[name='fr_val_change']").val('')
                        // delete this.usaform.val_change
                    }
                    console.log($("input[name='fr_satisfy_p']").val());
                    if ($("input[name='fr_satisfy_p']").val() != '') {
                        if ($("input[name='fr_val_change']").val() == '' && $("input[name='fr_percentage_change']").val() == '') {
                            this.$message.error('使用“达标比例”时，“每周增长量”或“每周增长率”必须填写其中一个');
                            this.loadingfr = false
                            return false
                        }
                    }
                }
                var search = {
                    key_words: $("input[name='fr_key_words']").val(),
                    percentage_change: $("input[name='fr_percentage_change']").val(),
                    satisfy_p: $("input[name='fr_satisfy_p']").val(),
                    val_change: $("input[name='fr_val_change']").val(),
                    sdate: this.frform.sdate,

                }
                $.ajax({
                    type: "POST",
                    url: "/index/index/getList",
                    data: {
                        limit: that.frsize,
                        page: that.frcurrentPage,
                        search: search,
                        cu: cu
                    },
                    dataType: "json",
                    success: function (data) {
                        var jdata = JSON.parse(data);
                        if (jdata.code == 1) {
                            that.frtableData = jdata.data
                            that.frtotle = jdata.totle
                            that.frpicdata = jdata.picdata
                            that.loadingfr = false
                        } else {
                            that.frtableData = []
                            that.loadingfr = false
                            that.$message.error(jdata.message);
                        }


                    },
                    error: function (jqXHR) {
                        that.loadingfr = false
                    }
                });
            },
            mxgetListdata(cu) {
                this.loadingmx = true
                var that = this
                if ($("input[name='mx_key_words']").val() != '' || $("input[name='mx_percentage_change']").val() != '' || $("input[name='mx_satisfy_p']").val() != '' || this.mxform.sdate != undefined || $("input[name='mx_val_change']").val() != '') {
                    // console.log(this.form.length)
                    var obj = document.getElementsByName("mxonly_who");
                    if (obj[0].checked == true) {
                        $("input[name='mx_percentage_change']").val('')
                        // delete this.usaform.percentage_change
                    }
                    if (obj[1].checked == true) {
                        $("input[name='mx_val_change']").val('')
                        // delete this.usaform.val_change
                    }
                    console.log($("input[name='mx_satisfy_p']").val());
                    if ($("input[name='mx_satisfy_p']").val() != '') {
                        if ($("input[name='mx_val_change']").val() == '' && $("input[name='mx_percentage_change']").val() == '') {
                            this.$message.error('使用“达标比例”时，“每周增长量”或“每周增长率”必须填写其中一个');
                            this.loadingmx = false
                            return false
                        }
                    }
                }
                var search = {
                    key_words: $("input[name='mx_key_words']").val(),
                    percentage_change: $("input[name='mx_percentage_change']").val(),
                    satisfy_p: $("input[name='mx_satisfy_p']").val(),
                    val_change: $("input[name='mx_val_change']").val(),
                    sdate: this.mxform.sdate,

                }
                $.ajax({
                    type: "POST",
                    url: "/index/index/getList",
                    data: {
                        limit: that.mxsize,
                        page: that.mxcurrentPage,
                        search: search,
                        cu: cu
                    },
                    dataType: "json",
                    success: function (data) {
                        var jdata = JSON.parse(data);
                        if (jdata.code == 1) {
                            that.mxtableData = jdata.data
                            that.mxtotle = jdata.totle
                            that.mxpicdata = jdata.picdata
                            that.loadingmx = false
                        } else {
                            that.mxtableData = []
                            that.loadingmx = false
                            that.$message.error(jdata.message);
                        }


                    },
                    error: function (jqXHR) {
                        that.loadingmx = false
                    }
                });
            },
            cagetListdata(cu) {
                this.loadingca = true
                var that = this
                if ($("input[name='ca_key_words']").val() != '' || $("input[name='ca_percentage_change']").val() != '' || $("input[name='ca_satisfy_p']").val() != '' || this.caform.sdate != undefined || $("input[name='ca_val_change']").val() != '') {
                    // console.log(this.form.length)
                    var obj = document.getElementsByName("caonly_who");
                    if (obj[0].checked == true) {
                        $("input[name='ca_percentage_change']").val('')
                        // delete this.usaform.percentage_change
                    }
                    if (obj[1].checked == true) {
                        $("input[name='ca_val_change']").val('')
                        // delete this.usaform.val_change
                    }
                    console.log($("input[name='ca_satisfy_p']").val());
                    if ($("input[name='ca_satisfy_p']").val() != '') {
                        if ($("input[name='ca_val_change']").val() == '' && $("input[name='ca_percentage_change']").val() == '') {
                            this.$message.error('使用“达标比例”时，“每周增长量”或“每周增长率”必须填写其中一个');
                            this.loadingca = false
                            return false
                        }
                    }
                }
                var search = {
                    key_words: $("input[name='ca_key_words']").val(),
                    percentage_change: $("input[name='ca_percentage_change']").val(),
                    satisfy_p: $("input[name='ca_satisfy_p']").val(),
                    val_change: $("input[name='ca_val_change']").val(),
                    sdate: this.caform.sdate,

                }
                $.ajax({
                    type: "POST",
                    url: "/index/index/getList",
                    data: {
                        limit: that.casize,
                        page: that.cacurrentPage,
                        search: search,
                        cu: cu
                    },
                    dataType: "json",
                    success: function (data) {
                        var jdata = JSON.parse(data);
                        if (jdata.code == 1) {
                            that.catableData = jdata.data
                            that.catotle = jdata.totle
                            that.capicdata = jdata.picdata
                            that.loadingca = false
                        } else {
                            that.catableData = []
                            that.loadingca = false
                            that.$message.error(jdata.message);
                        }


                    },
                    error: function (jqXHR) {
                        that.loadingca = false
                    }
                });
            },

            usatext: function (idname, id, cu) {
                // console.log(this.tableData[idname].key_words)
                var that = this
                // console.log(idname)
                let option = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                            type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        },
                        position: function (point, params, dom, rect, size) {
                            var x = 0; // x坐标位置
                            var y = 0; // y坐标位置

                            // 当前鼠标位置
                            var pointX = point[0];
                            var pointY = point[1];
                            // 提示框大小
                            var boxWidth = size.contentSize[0];
                            var boxHeight = size.contentSize[1];

                            // boxWidth > pointX 说明鼠标左边放不下提示框
                            if (boxWidth > pointX) {
                                x = 5;
                            } else { // 左边放的下
                                x = pointX - boxWidth;
                            }

                            // boxHeight > pointY 说明鼠标上边放不下提示框
                            if (boxHeight > pointY) {
                                y = 5;
                            } else { // 上边放得下
                                y = pointY - boxHeight;
                            }
                            return [x, y];
                        },
                        formatter: function (datas) {
                            var res = '关键词：' + that.usatableData[idname].key_words + '<br/>排名：' + datas[0].value + '<br/>日期：' + datas[0].axisValue;

                            return res;
                        },
                        textStyle: {
                            color: '#5c6c7c',
                            fontSize: 10
                        },
                    },
                    axisLabel: {
                        interval: 0,
                        rotate: 40
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
                        data: this.usatableData[idname][id].update_time
                    },
                    yAxis: {
                        show: true,
                        type: 'value'
                    },
                    series: [{
                        data: this.usatableData[idname][id].c_rank,
                        type: 'bar',
                        barWidth: 30,
                        showBackground: true,
                        backgroundStyle: {
                            color: 'rgba(180, 180, 180, 0.2)'
                        },
                        label: {
                            normal: {
                                // show:true,
                                textStyle: {
                                    fontSize: 10
                                }
                            }
                        }
                    }]
                }
                var id = cu + 'tiger-sale-trend-index' + idname
                this.$nextTick(() => {
                    let myChart = echarts.init(document.getElementById(id), 'macarons');
                    myChart.setOption(option);
                    myChart.resize();
                })
            },
            uktext: function (idname, id, cu) {
                // console.log(this.tableData[idname].key_words)
                var that = this
                // console.log(idname)
                let option = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                            type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        },
                        position: function (point, params, dom, rect, size) {
                            var x = 0; // x坐标位置
                            var y = 0; // y坐标位置

                            // 当前鼠标位置
                            var pointX = point[0];
                            var pointY = point[1];
                            // 提示框大小
                            var boxWidth = size.contentSize[0];
                            var boxHeight = size.contentSize[1];

                            // boxWidth > pointX 说明鼠标左边放不下提示框
                            if (boxWidth > pointX) {
                                x = 5;
                            } else { // 左边放的下
                                x = pointX - boxWidth;
                            }

                            // boxHeight > pointY 说明鼠标上边放不下提示框
                            if (boxHeight > pointY) {
                                y = 5;
                            } else { // 上边放得下
                                y = pointY - boxHeight;
                            }
                            return [x, y];
                        },
                        formatter: function (datas) {
                            var res = '关键词：' + that.uktableData[idname].key_words + '<br/>排名：' + datas[0].value + '<br/>日期：' + datas[0].axisValue;

                            return res;
                        },
                        textStyle: {
                            color: '#5c6c7c',
                            fontSize: 10
                        },
                    },
                    axisLabel: {
                        interval: 0,
                        rotate: 40
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
                        data: this.uktableData[idname][id].update_time
                    },
                    yAxis: {
                        show: true,
                        type: 'value'
                    },
                    series: [{
                        data: this.uktableData[idname][id].c_rank,
                        type: 'bar',
                        barWidth: 30,
                        showBackground: true,
                        backgroundStyle: {
                            color: 'rgba(180, 180, 180, 0.2)'
                        },
                        label: {
                            normal: {
                                // show:true,
                                textStyle: {
                                    fontSize: 10
                                }
                            }
                        }
                    }]
                }
                var id = cu + 'tiger-sale-trend-index' + idname
                this.$nextTick(() => {
                    let myChart = echarts.init(document.getElementById(id), 'macarons');
                    myChart.setOption(option);
                    myChart.resize();
                })
            },
            detext: function (idname, id, cu) {
                // console.log(this.tableData[idname].key_words)
                var that = this
                // console.log(idname)
                let option = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                            type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        },
                        position: function (point, params, dom, rect, size) {
                            var x = 0; // x坐标位置
                            var y = 0; // y坐标位置

                            // 当前鼠标位置
                            var pointX = point[0];
                            var pointY = point[1];
                            // 提示框大小
                            var boxWidth = size.contentSize[0];
                            var boxHeight = size.contentSize[1];

                            // boxWidth > pointX 说明鼠标左边放不下提示框
                            if (boxWidth > pointX) {
                                x = 5;
                            } else { // 左边放的下
                                x = pointX - boxWidth;
                            }

                            // boxHeight > pointY 说明鼠标上边放不下提示框
                            if (boxHeight > pointY) {
                                y = 5;
                            } else { // 上边放得下
                                y = pointY - boxHeight;
                            }
                            return [x, y];
                        },
                        formatter: function (datas) {
                            var res = '关键词：' + that.detableData[idname].key_words + '<br/>排名：' + datas[0].value + '<br/>日期：' + datas[0].axisValue;

                            return res;
                        },
                        textStyle: {
                            color: '#5c6c7c',
                            fontSize: 10
                        },
                    },
                    axisLabel: {
                        interval: 0,
                        rotate: 40
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
                        data: this.detableData[idname][id].update_time
                    },
                    yAxis: {
                        show: true,
                        type: 'value'
                    },
                    series: [{
                        data: this.detableData[idname][id].c_rank,
                        type: 'bar',
                        barWidth: 30,
                        showBackground: true,
                        backgroundStyle: {
                            color: 'rgba(180, 180, 180, 0.2)'
                        },
                        label: {
                            normal: {
                                // show:true,
                                textStyle: {
                                    fontSize: 10
                                }
                            }
                        }
                    }]
                }
                var id = cu + 'tiger-sale-trend-index' + idname
                this.$nextTick(() => {
                    let myChart = echarts.init(document.getElementById(id), 'macarons');
                    myChart.setOption(option);
                    myChart.resize();
                })
            },
            jptext: function (idname, id, cu) {
                // console.log(this.tableData[idname].key_words)
                var that = this
                // console.log(idname)
                let option = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                            type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        },
                        formatter: function (datas) {
                            var res = '关键词：' + that.jptableData[idname].key_words + '<br/>本周排名：' + datas[0].value + '<br/>上周排名：' + that.jptableData[idname].l_rank + '<br/>排名变化：' + that.jptableData[idname].chang + '<br/>日期：' + datas[0].axisValue;

                            return res;
                        },
                        textStyle: {
                            color: '#5c6c7c',
                            fontSize: 10
                        },
                    },
                    axisLabel: {
                        interval: 0,
                        rotate: 40
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
                        data: this.jptableData[idname][id].update_time
                    },
                    yAxis: {
                        show: true,
                        type: 'value'
                    },
                    series: [{
                        data: this.jptableData[idname][id].c_rank,
                        type: 'bar',
                        barWidth: 30,
                        showBackground: true,
                        backgroundStyle: {
                            color: 'rgba(180, 180, 180, 0.2)'
                        },
                        label: {
                            normal: {
                                // show:true,
                                textStyle: {
                                    fontSize: 10
                                }
                            }
                        }
                    }]
                }
                var id = cu + 'tiger-sale-trend-index' + idname
                this.$nextTick(() => {
                    let myChart = echarts.init(document.getElementById(id), 'macarons');
                    myChart.setOption(option);
                    myChart.resize();
                })
            },
            esptext: function (idname, id, cu) {
                // console.log(this.tableData[idname].key_words)
                var that = this
                // console.log(idname)
                let option = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                            type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        },
                        position: function (point, params, dom, rect, size) {
                            var x = 0; // x坐标位置
                            var y = 0; // y坐标位置

                            // 当前鼠标位置
                            var pointX = point[0];
                            var pointY = point[1];
                            // 提示框大小
                            var boxWidth = size.contentSize[0];
                            var boxHeight = size.contentSize[1];

                            // boxWidth > pointX 说明鼠标左边放不下提示框
                            if (boxWidth > pointX) {
                                x = 5;
                            } else { // 左边放的下
                                x = pointX - boxWidth;
                            }

                            // boxHeight > pointY 说明鼠标上边放不下提示框
                            if (boxHeight > pointY) {
                                y = 5;
                            } else { // 上边放得下
                                y = pointY - boxHeight;
                            }
                            return [x, y];
                        },
                        formatter: function (datas) {
                            var res = '关键词：' + that.esptableData[idname].key_words + '<br/>排名：' + datas[0].value + '<br/>日期：' + datas[0].axisValue;

                            return res;
                        },
                        textStyle: {
                            color: '#5c6c7c',
                            fontSize: 10
                        },
                    },
                    axisLabel: {
                        interval: 0,
                        rotate: 40
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
                        data: this.esptableData[idname][id].update_time
                    },
                    yAxis: {
                        show: true,
                        type: 'value'
                    },
                    series: [{
                        data: this.esptableData[idname][id].c_rank,
                        type: 'bar',
                        barWidth: 30,
                        showBackground: true,
                        backgroundStyle: {
                            color: 'rgba(180, 180, 180, 0.2)'
                        },
                        label: {
                            normal: {
                                // show:true,
                                textStyle: {
                                    fontSize: 10
                                }
                            }
                        }
                    }]
                }
                var id = cu + 'tiger-sale-trend-index' + idname
                this.$nextTick(() => {
                    let myChart = echarts.init(document.getElementById(id), 'macarons');
                    myChart.setOption(option);
                    myChart.resize();
                })
            },
            ittext: function (idname, id, cu) {
                // console.log(this.tableData[idname].key_words)
                var that = this
                // console.log(idname)
                let option = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                            type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        },
                        position: function (point, params, dom, rect, size) {
                            var x = 0; // x坐标位置
                            var y = 0; // y坐标位置

                            // 当前鼠标位置
                            var pointX = point[0];
                            var pointY = point[1];
                            // 提示框大小
                            var boxWidth = size.contentSize[0];
                            var boxHeight = size.contentSize[1];

                            // boxWidth > pointX 说明鼠标左边放不下提示框
                            if (boxWidth > pointX) {
                                x = 5;
                            } else { // 左边放的下
                                x = pointX - boxWidth;
                            }

                            // boxHeight > pointY 说明鼠标上边放不下提示框
                            if (boxHeight > pointY) {
                                y = 5;
                            } else { // 上边放得下
                                y = pointY - boxHeight;
                            }
                            return [x, y];
                        },
                        formatter: function (datas) {
                            var res = '关键词：' + that.ittableData[idname].key_words + '<br/>排名：' + datas[0].value + '<br/>日期：' + datas[0].axisValue;

                            return res;
                        },
                        textStyle: {
                            color: '#5c6c7c',
                            fontSize: 10
                        },
                    },
                    axisLabel: {
                        interval: 0,
                        rotate: 40
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
                        data: this.ittableData[idname][id].update_time
                    },
                    yAxis: {
                        show: true,
                        type: 'value'
                    },
                    series: [{
                        data: this.ittableData[idname][id].c_rank,
                        type: 'bar',
                        barWidth: 30,
                        showBackground: true,
                        backgroundStyle: {
                            color: 'rgba(180, 180, 180, 0.2)'
                        },
                        label: {
                            normal: {
                                // show:true,
                                textStyle: {
                                    fontSize: 10
                                }
                            }
                        }
                    }]
                }
                var id = cu + 'tiger-sale-trend-index' + idname
                this.$nextTick(() => {
                    let myChart = echarts.init(document.getElementById(id), 'macarons');
                    myChart.setOption(option);
                    myChart.resize();
                })
            },
            mxtext: function (idname, id, cu) {
                // console.log(this.tableData[idname].key_words)
                var that = this
                // console.log(idname)
                let option = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                            type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        },
                        position: function (point, params, dom, rect, size) {
                            var x = 0; // x坐标位置
                            var y = 0; // y坐标位置

                            // 当前鼠标位置
                            var pointX = point[0];
                            var pointY = point[1];
                            // 提示框大小
                            var boxWidth = size.contentSize[0];
                            var boxHeight = size.contentSize[1];

                            // boxWidth > pointX 说明鼠标左边放不下提示框
                            if (boxWidth > pointX) {
                                x = 5;
                            } else { // 左边放的下
                                x = pointX - boxWidth;
                            }

                            // boxHeight > pointY 说明鼠标上边放不下提示框
                            if (boxHeight > pointY) {
                                y = 5;
                            } else { // 上边放得下
                                y = pointY - boxHeight;
                            }
                            return [x, y];
                        },
                        formatter: function (datas) {
                            var res = '关键词：' + that.mxtableData[idname].key_words + '<br/>排名：' + datas[0].value + '<br/>日期：' + datas[0].axisValue;

                            return res;
                        },
                        textStyle: {
                            color: '#5c6c7c',
                            fontSize: 10
                        },
                    },
                    axisLabel: {
                        interval: 0,
                        rotate: 40
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
                        data: this.mxtableData[idname][id].update_time
                    },
                    yAxis: {
                        show: true,
                        type: 'value'
                    },
                    series: [{
                        data: this.mxtableData[idname][id].c_rank,
                        type: 'bar',
                        barWidth: 30,
                        showBackground: true,
                        backgroundStyle: {
                            color: 'rgba(180, 180, 180, 0.2)'
                        },
                        label: {
                            normal: {
                                // show:true,
                                textStyle: {
                                    fontSize: 10
                                }
                            }
                        }
                    }]
                }
                var id = cu + 'tiger-sale-trend-index' + idname
                this.$nextTick(() => {
                    let myChart = echarts.init(document.getElementById(id), 'macarons');
                    myChart.setOption(option);
                    myChart.resize();
                })
            },
            frtext: function (idname, id, cu) {
                // console.log(this.tableData[idname].key_words)
                var that = this
                // console.log(idname)
                let option = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                            type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        },
                        position: function (point, params, dom, rect, size) {
                            var x = 0; // x坐标位置
                            var y = 0; // y坐标位置

                            // 当前鼠标位置
                            var pointX = point[0];
                            var pointY = point[1];
                            // 提示框大小
                            var boxWidth = size.contentSize[0];
                            var boxHeight = size.contentSize[1];

                            // boxWidth > pointX 说明鼠标左边放不下提示框
                            if (boxWidth > pointX) {
                                x = 5;
                            } else { // 左边放的下
                                x = pointX - boxWidth;
                            }

                            // boxHeight > pointY 说明鼠标上边放不下提示框
                            if (boxHeight > pointY) {
                                y = 5;
                            } else { // 上边放得下
                                y = pointY - boxHeight;
                            }
                            return [x, y];
                        },
                        formatter: function (datas) {
                            var res = '关键词：' + that.frtableData[idname].key_words + '<br/>排名：' + datas[0].value + '<br/>日期：' + datas[0].axisValue;

                            return res;
                        },
                        textStyle: {
                            color: '#5c6c7c',
                            fontSize: 10
                        },
                    },
                    axisLabel: {
                        interval: 0,
                        rotate: 40
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
                        data: this.frtableData[idname][id].update_time
                    },
                    yAxis: {
                        show: true,
                        type: 'value'
                    },
                    series: [{
                        data: this.frtableData[idname][id].c_rank,
                        type: 'bar',
                        barWidth: 30,
                        showBackground: true,
                        backgroundStyle: {
                            color: 'rgba(180, 180, 180, 0.2)'
                        },
                        label: {
                            normal: {
                                // show:true,
                                textStyle: {
                                    fontSize: 10
                                }
                            }
                        }
                    }]
                }
                var id = cu + 'tiger-sale-trend-index' + idname
                this.$nextTick(() => {
                    let myChart = echarts.init(document.getElementById(id), 'macarons');
                    myChart.setOption(option);
                    myChart.resize();
                })
            },
            catext: function (idname, id, cu) {
                // console.log(this.tableData[idname].key_words)
                var that = this
                // console.log(idname)
                let option = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                            type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        },
                        position: function (point, params, dom, rect, size) {
                            var x = 0; // x坐标位置
                            var y = 0; // y坐标位置

                            // 当前鼠标位置
                            var pointX = point[0];
                            var pointY = point[1];
                            // 提示框大小
                            var boxWidth = size.contentSize[0];
                            var boxHeight = size.contentSize[1];

                            // boxWidth > pointX 说明鼠标左边放不下提示框
                            if (boxWidth > pointX) {
                                x = 5;
                            } else { // 左边放的下
                                x = pointX - boxWidth;
                            }

                            // boxHeight > pointY 说明鼠标上边放不下提示框
                            if (boxHeight > pointY) {
                                y = 5;
                            } else { // 上边放得下
                                y = pointY - boxHeight;
                            }
                            return [x, y];
                        },
                        formatter: function (datas) {
                            var res = '关键词：' + that.catableData[idname].key_words + '<br/>排名：' + datas[0].value + '<br/>日期：' + datas[0].axisValue;

                            return res;
                        },
                        textStyle: {
                            color: '#5c6c7c',
                            fontSize: 10
                        },
                    },
                    axisLabel: {
                        interval: 0,
                        rotate: 40
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
                        data: this.catableData[idname][id].update_time
                    },
                    yAxis: {
                        show: true,
                        type: 'value'
                    },
                    series: [{
                        data: this.catableData[idname][id].c_rank,
                        type: 'bar',
                        barWidth: 30,
                        showBackground: true,
                        backgroundStyle: {
                            color: 'rgba(180, 180, 180, 0.2)'
                        },
                        label: {
                            normal: {
                                // show:true,
                                textStyle: {
                                    fontSize: 10
                                }
                            }
                        }
                    }]
                }
                var id = cu + 'tiger-sale-trend-index' + idname
                this.$nextTick(() => {
                    let myChart = echarts.init(document.getElementById(id), 'macarons');
                    myChart.setOption(option);
                    myChart.resize();
                })
            },
            usahandleSizeChange(val) {
                this.usasize = val
                this.usagetListdata('usa')
            },
            usahandleCurrentChange(val) {
                this.usacurrentPage = val
                this.usagetListdata('usa')
            },


            ukhandleSizeChange(val) {
                this.uksize = val
                this.ukgetListdata('uk')
            },
            ukhandleCurrentChange(val) {
                this.ukcurrentPage = val
                this.ukgetListdata('uk')
            },

            dehandleSizeChange(val) {
                this.desize = val
                this.degetListdata('de')
            },
            dehandleCurrentChange(val) {
                this.decurrentPage = val
                this.degetListdata('de')
            },

            jphandleSizeChange(val) {
                this.jpsize = val
                this.jpgetListdata('jp')
            },
            jphandleCurrentChange(val) {
                this.jpcurrentPage = val
                this.jpgetListdata('jp')
            },

            esphandleSizeChange(val) {
                this.espsize = val
                this.espgetListdata('esp')
            },
            esphandleCurrentChange(val) {
                this.espcurrentPage = val
                this.espgetListdata('esp')
            },

            ithandleSizeChange(val) {
                this.itsize = val
                this.itgetListdata('it')
            },
            ithandleCurrentChange(val) {
                this.itcurrentPage = val
                this.itgetListdata('it')
            },

            frhandleSizeChange(val) {
                this.frsize = val
                this.frgetListdata('fr')
            },
            frhandleCurrentChange(val) {
                this.frcurrentPage = val
                this.frgetListdata('fr')
            },

            mxhandleSizeChange(val) {
                this.mxsize = val
                this.mxgetListdata('mx')
            },
            mxhandleCurrentChange(val) {
                this.mxcurrentPage = val
                this.mxgetListdata('mx')
            },

            cahandleSizeChange(val) {
                this.casize = val
                this.cagetListdata('ca')
            },
            cahandleCurrentChange(val) {
                this.cacurrentPage = val
                this.cagetListdata('ca')
            },


            usaonSubmit(cu) {
                this.usacurrentPage = 1
                this.usagetListdata(cu)
            },
            ukonSubmit(cu) {
                this.ukcurrentPage = 1
                this.ukgetListdata(cu)
            },
            deonSubmit(cu) {
                this.decurrentPage = 1
                this.degetListdata(cu)
            },
            jponSubmit(cu) {
                this.jpcurrentPage = 1
                this.jpgetListdata(cu)
            },
            esponSubmit(cu) {
                this.espcurrentPage = 1
                this.espgetListdata(cu)
            },
            itonSubmit(cu) {
                this.itgetListdata(cu)
            },
            fronSubmit(cu) {
                this.frcurrentPage = 1
                this.frgetListdata(cu)
            },
            mxonSubmit(cu) {
                this.mxcurrentPage = 1
                this.mxgetListdata(cu)
            },
            caonSubmit(cu) {
                this.cacurrentPage = 1
                this.cagetListdata(cu)
            },


            handleSelectionChange(val) {
                this.multipleSelection = val;
                // console.log(this.multipleSelection[0]);
            },
            downloadExcel(cu, type, all = 0) {
                var that = this
                    var satisfy_p = $("input[name='"+cu+"_satisfy_p']").val()
                    var val_change = $("input[name='"+cu+"_val_change']").val()
                    var key_words = $("input[name='"+cu+"_key_words']").val()
                    var percentage_change = $("input[name='"+cu+"_percentage_change']").val()
                if (cu == 'usa') {
                    if (that.usaform.sdate != undefined) {
                        var sdate = that.usaform.sdate[0]
                        var edate = that.usaform.sdate[1]
                    } else {
                        var sdate = 'undefined'
                        var edate = 'undefined'
                    }
                }
                if (cu == 'uk') {
                    if (that.ukform.sdate != undefined) {
                        var sdate = that.ukform.sdate[0]
                        var edate = that.ukform.sdate[1]
                    } else {
                        var sdate = 'undefined'
                        var edate = 'undefined'
                    }
                }
                if (cu == 'de') {
                    if (that.deform.sdate != undefined) {
                        var sdate = that.deform.sdate[0]
                        var edate = that.deform.sdate[1]
                    } else {
                        var sdate = 'undefined'
                        var edate = 'undefined'
                    }
                }
                if (cu == 'jp') {
                    if (that.jpform.sdate != undefined) {
                        var sdate = that.jpform.sdate[0]
                        var edate = that.jpform.sdate[1]
                    } else {
                        var sdate = 'undefined'
                        var edate = 'undefined'
                    }
                }
                if (cu == 'esp') {
                    if (that.espform.sdate != undefined) {
                        var sdate = that.espform.sdate[0]
                        var edate = that.espform.sdate[1]
                    } else {
                        var sdate = 'undefined'
                        var edate = 'undefined'
                    }
                }
                if (cu == 'it') {
                    if (that.itform.sdate != undefined) {
                        var sdate = that.itform.sdate[0]
                        var edate = that.itform.sdate[1]
                    } else {
                        var sdate = 'undefined'
                        var edate = 'undefined'
                    }
                }
                if (cu == 'fr') {
                    if (that.frform.sdate != undefined) {
                        var sdate = that.frform.sdate[0]
                        var edate = that.frform.sdate[1]
                    } else {
                        var sdate = 'undefined'
                        var edate = 'undefined'
                    }
                }
                if (cu == 'mx') {
                    if (that.mxform.sdate != undefined) {
                        var sdate = that.mxform.sdate[0]
                        var edate = that.mxform.sdate[1]
                    } else {
                        var sdate = 'undefined'
                        var edate = 'undefined'
                    }
                }
                if (cu == 'ca') {
                    if (that.caform.sdate != undefined) {
                        var sdate = that.caform.sdate[0]
                        var edate = that.caform.sdate[1]
                    } else {
                        var sdate = 'undefined'
                        var edate = 'undefined'
                    }
                }



                if (this.multipleSelection.length == 0 && all == 0) {
                    this.$message.error('请选择需要导出的数据');
                    return
                }
                var ids = '';
                for (var i = 0; i < this.multipleSelection.length; i++) {
                    ids += this.multipleSelection[i].id + ',';
                }
                that.loadingusa = true
                 that.loadinguk = true
                  that.loadingde = true
                   that.loadingjp = true
                    that.loadingesp = true
                     that.loadingit = true
                      that.loadingfr = true
                       that.loadingmx = true
                        that.loadingca = true
                    $.ajax({
                    type: "POST",
                    url: "/index/index/expExcel?ids=" + ids + '&cu=' + cu + "&type=" + type + "&all=" + all + "&satisfy_p=" + satisfy_p + "&val_change=" + val_change + "&key_words=" + key_words + "&percentage_change=" + percentage_change + "&sdate=" + sdate + "&edate=" + edate,
                    dataType: "json",
                    success: function (data) {
                            that.loadingusa = false
                             that.loadinguk = false
                              that.loadingde = false
                               that.loadingjp = false
                                that.loadingesp = false
                                 that.loadingit = false
                                  that.loadingfr = false
                                   that.loadingmx = false
                                    that.loadingca = false
                                   window.location.href=data;
                                    // window.open(data);
                    },
                    error: function (jqXHR) {
                        that.loadingusa = false
                    }
                });
                
            },
            handleClick(tab, event) {
                this.multipleSelection = [];
                this.$refs.usaTable.clearSelection();
                this.$refs.ukTable.clearSelection();
                this.$refs.deTable.clearSelection();
                this.$refs.jpTable.clearSelection();
                this.$refs.espTable.clearSelection();
                this.$refs.itTable.clearSelection();
                this.$refs.frTable.clearSelection();
                this.$refs.mxTable.clearSelection();
                this.$refs.caTable.clearSelection();
                if (this.isclicktabs.indexOf(tab.name) == -1) {
                    if (tab.name == 'usa') {
                        this.usagetListdata('usa')
                    }
                    if (tab.name == 'uk') {
                        this.ukgetListdata('uk')
                    }
                    if (tab.name == 'de') {
                        this.degetListdata('de')
                    }
                    if (tab.name == 'jp') {
                        this.jpgetListdata('jp')
                    }
                    if (tab.name == 'esp') {
                        this.espgetListdata('esp')
                    }
                    if (tab.name == 'it') {
                        this.itgetListdata('it')
                    }
                    if (tab.name == 'fr') {
                        this.frgetListdata('fr')
                    }
                    if (tab.name == 'mx') {
                        this.mxgetListdata('mx')
                    }
                    if (tab.name == 'ca') {
                        this.cagetListdata('ca')
                    }
                    this.isclicktabs.push(tab.name)
                }

            }
        },
        watch: {
            size: function (newval, oldval) {

            },
        },
        mounted: function () {
            var that=this
                $("input[name='usa_key_words']").keydown(function(event) {  
                     if (event.keyCode == 13) { 
                        that.usaonSubmit('usa')
                     }  
                 })  
                $("input[name='uk_key_words']").keydown(function(event) {  
                     if (event.keyCode == 13) { 
                        that.ukonSubmit('uk')
                     }  
                 })  
                $("input[name='de_key_words']").keydown(function(event) {  
                     if (event.keyCode == 13) { 
                        that.deonSubmit('de')
                     }  
                 })  
                $("input[name='jp_key_words']").keydown(function(event) {  
                     if (event.keyCode == 13) { 
                        that.jponSubmit('jp')
                     }  
                 })  
                $("input[name='esp_key_words']").keydown(function(event) {  
                     if (event.keyCode == 13) { 
                        that.esponSubmit('esp')
                     }  
                 })  
                $("input[name='it_key_words']").keydown(function(event) {  
                     if (event.keyCode == 13) { 
                        that.itonSubmit('it')
                     }  
                 })  
                $("input[name='fr_key_words']").keydown(function(event) {  
                     if (event.keyCode == 13) { 
                        that.fronSubmit('fr')
                     }  
                 })  
                $("input[name='mx_key_words']").keydown(function(event) {  
                     if (event.keyCode == 13) { 
                        that.mxonSubmit('mx')
                     }  
                 })  
                $("input[name='ca_key_words']").keydown(function(event) {  
                     if (event.keyCode == 13) { 
                        that.caonSubmit('ca')
                     }  
                 })  
            this.usagetListdata('usa')
            // this.ukgetListdata('uk')
            // this.degetListdata('de')
            // this.jpgetListdata('jp')
            // this.espgetListdata('esp')
            // this.itgetListdata('it')
            // this.frgetListdata('fr')
            // this.mxgetListdata('mx')
            // this.cagetListdata('ca')
        }
    })
</script>

<style>
    &
    -frame {
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

    .cell {
        font-size: 12px;
    }

    .el-form-item {
        margin-bottom: 0px;
    }

    .dexc:hover {
        background-color: red;
    }

    .inputstyle {
        height: 32px;
        line-height: 32px;
        -webkit-appearance: none;
        background-color: #FFF;
        background-image: none;
        border-radius: 4px;
        border: 1px solid #DCDFE6;
        box-sizing: border-box;
        color: #606266;
        display: inline-block;
        font-size: inherit;
        outline: 0;
        padding: 0 15px;
        transition: border-color .2s cubic-bezier(.645, .045, .355, 1);
        width: 100%;
    }
</style>
</html>
