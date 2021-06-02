<template>
  <div>
    <el-breadcrumb separator="/">
      <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item><a href="/">文章管理</a></el-breadcrumb-item>
      <el-breadcrumb-item>文章列表</el-breadcrumb-item>
    </el-breadcrumb>

    <!-- 表格内容 -->
    <el-card class="box-card" style="margin-top: 32px;">
      <div>
        <el-button type="primary" style="margin-bottom: 20px;" @click="add"
          >添加</el-button
        >

        <el-input
          style="width:220px;margin-left:20px"
          placeholder="请输入内容"
          prefix-icon="el-icon-search"
          clearable
          v-model="param.key"
          @keyup.enter.native="search"
        >
        </el-input>
        <el-button type="info" @click="search()">搜索</el-button>
      </div>

      <el-table
        v-loading="loading"
        border
        :data="tableData"
        style="width: 100%"
      >
        <el-table-column prop="title" label="文章标题" width="180">
        </el-table-column>
        <el-table-column prop="content" label="文章内容" width="180">
        </el-table-column>
        <el-table-column prop="sort" label="排序" width="180">
        </el-table-column>
        <el-table-column prop="create_time" label="创建日期" width="180">
        </el-table-column>
        <el-table-column prop="update_time" label="更新日期" width="180">
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <!-- {{scope.row}} -->
            <el-button type="primary" size="mini" @click="edit(scope.row)"
              >编辑</el-button
            >
            <el-button type="danger" size="mini" @click="del(scope.row.id)"
              >删除</el-button
            >
          </template>
        </el-table-column>
      </el-table>

      <!-- 分页 -->
      <el-pagination
        style="margin-top: 20px;"
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        :current-page="currentPage"
        :page-sizes="[1, 2, 3, 4]"
        :page-size="param.limit"
        layout="total, sizes, prev, pager, next, jumper"
        :total="total"
      >
      </el-pagination>

      <!-- 添加的表单 -->
      <el-dialog title="添加文章" :visible.sync="addDialogBisible" width="30%">
        <el-form ref="form" :model="Addfrom" label-width="80px">
          <el-form-item label="文章标题">
            <el-input v-model="Addfrom.title"></el-input>
          </el-form-item>
          <el-form-item label="文章排序">
            <el-input v-model="Addfrom.sort"></el-input>
          </el-form-item>
          <el-form-item label="文章状态">
            <el-radio v-model="Addfrom.status" :label="1">启用</el-radio>
            <el-radio v-model="Addfrom.status" :label="0">禁用</el-radio>
          </el-form-item>
          <el-form-item label="文章内容">
            <el-input type="textarea" v-model="Addfrom.content"></el-input>
          </el-form-item>
        </el-form>

        <!-- <span>这是一段信息</span> -->
        <span slot="footer" class="dialog-footer">
          <el-button @click="addDialogBisible = false">取 消</el-button>
          <el-button type="primary" @click="addSave()">添 加</el-button>
        </span>
      </el-dialog>

      <!-- 编辑的表单 -->
      <el-dialog title="编辑文章" :visible.sync="editDialogBisible" width="30%">
        <el-form ref="form" :model="Editfrom" label-width="80px">
          <el-form-item label="文章标题">
            <el-input v-model="Editfrom.title"></el-input>
          </el-form-item>
          <el-form-item label="文章排序">
            <el-input v-model="Editfrom.sort"></el-input>
          </el-form-item>
          <el-form-item label="文章状态">
            <el-radio v-model="Editfrom.status" :label="1">启用</el-radio>
            <el-radio v-model="Editfrom.status" :label="0">禁用</el-radio>
          </el-form-item>
          <el-form-item label="文章内容">
            <el-input type="textarea" v-model="Editfrom.content"></el-input>
          </el-form-item>
        </el-form>

        <!-- <span>这是一段信息</span> -->
        <span slot="footer" class="dialog-footer">
          <el-button @click="editDialogBisible = false">取 消</el-button>
          <el-button type="primary" @click="editSave()">编 辑</el-button>
        </span>
      </el-dialog>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "artcile",
  data() {
    return {
      param: {
        key: "",
        page: 1,
        limit: 10
      },
      tableData: [],
      currentPage: 1,
      total: 1,
      loading: true,
      addDialogBisible: false,
      editDialogBisible: false,
      Addfrom: {
        title: "",
        content: "",
        sort: "",
        status: 1
      },
      Editfrom: {
        title: "",
        content: "",
        sort: "",
        status: 1,
        id:''
      }
    };
  },
  created() {
    this.getList();
  },
  methods: {
    //搜索一页多少条
    handleSizeChange(val) {
      (this.param.limit = val), this.getList();
    },
    //搜索第几页
    handleCurrentChange(val) {
      (this.param.page = val), this.getList();
    },

    //请求所有数据
    async getList() {
      this.loading = true;
      const {
        data: res
      } = await this.$ajax.get(
        "http://tpvue.ico:8033/index.php/api/artcile/index",
        { params: this.param }
      );
      this.tableData = res.msg;
      this.total = res.total;
      this.loading = false;
    },

    //删除
    async del(id) {
      const {
        data: res
      } = await this.$ajax.get(
        "http://tpvue.ico:8033/index.php/api/artcile/delete",
        { params: { id: id } }
      );

      if (res.code == 1) {
        //删除成功、刷新
        this.$msg.success(res.msg);
        this.getList();
      } else {
        //删除失败
        this.$msg.error(res.msg);
      }
    },

    //搜索
    search() {
      this.getList();
    },

    //添加
    add() {
      this.addDialogBisible = true;
    },
    //保存添加
    async addSave() {
      //提交数据
      const { data: res } = await this.$ajax.post(
        "http://tpvue.ico:8033/index.php/api/artcile/save",
        this.Addfrom
      );
      if (res.code == 1) {
        //关闭弹窗
        this.addDialogBisible = false;
        this.$msg.success(res.msg);
        //刷新页面
        this.getList();
        this.Addfrom = {
          title: "",
          content: "",
          sort: "",
          status: 1
        };
      } else {
        this.$msg.error(res.msg);
      }
    },

    //编辑
    edit(info) {
      this.editDialogBisible = true;
      this.Editfrom = {
        title: info.title,
        content: info.content,
        sort: info.sort,
        status: info.status,
        id:info.id
      };
    },
     //保存编辑
    async editSave() {
      //提交数据
      const { data: res } = await this.$ajax.post(
        "http://tpvue.ico:8033/index.php/api/artcile/save",
        this.Editfrom
      );
      if (res.code == 1) {
        //关闭弹窗
        this.editDialogBisible = false;
        this.$msg.success(res.msg);
        //刷新页面
        this.getList();
      } else {
        this.$msg.error(res.msg);
      }
    },
  }
};
</script>

<style></style>
