import Vue from "vue";
import VueRouter from "vue-router";
import Login from "../views/Login";
import Home from "../views/Home";
import Artcile from "../views/Artcile";

Vue.use(VueRouter);

const routes = [
  {
    path: "/login",
    component: Login
  },
  {
    path: "/",
    component: Home,
    children: [
      {
        path: "/artcile",
        component: Artcile,
        name:Artcile
      }
    ]
  }
];

const router = new VueRouter({
  routes
});

router.beforeEach((to, from, next) => {
  if (to.path === "/login") {
    next();
  }

  if (
    !window.sessionStorage.getItem("token") ||
    window.sessionStorage.getItem("token") == "" ||
    window.sessionStorage.getItem("token" == null) ||
    window.sessionStorage.getItem("token") == undefined
  ) {
    next("/login");
  }
  next();
});

export default router;
