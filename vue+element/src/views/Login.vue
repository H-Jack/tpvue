<template>
  <el-form ref="form" :model="LoginForm" label-width="80px">
    <el-form-item label="账号">
      <el-input v-model="LoginForm.username"></el-input>
    </el-form-item>
    <el-form-item label="密码">
      <el-input type="password" v-model="LoginForm.password"></el-input>
    </el-form-item>
    <el-form-item>
      <el-button type="primary" @click="login()">登录</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
export default {
  data() {
    return {
      LoginForm: {
        username: "admin",
        password: "123456"
      }
    };
  },
  created() {
    // this.login();
  },
  methods: {
    async login() {
      const { data: res } = await this.$ajax.post(
        "http://tpvue.ico:8033/index.php/api/login/login",
        this.LoginForm
      );

      //登录成功 提示信息 message组件（element）
      if (res.code == 1) {
        this.$msg.success(res.msg); //这里的$msg已经挂载了element的message组件

        //存入session
        window.sessionStorage.setItem('token',res.token)

        //跳转到后台首页
        this.$router.push("/")
         
      } else {
        this.$msg.error(res.msg); //这里的$msg已经挂载了element的message组件
      }
    }
  }
};
</script>

<style scoped></style>
