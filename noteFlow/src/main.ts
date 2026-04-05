import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import App from "./App.vue";
import "./styles/main.css";

// ─── Routes ──────────────────────────────────────────
const routes = [
  {
    path: "/",
    name: "Home",
    component: () => import("./pages/Home.vue"),
  },
  {
    path: "/signup",
    name: "Signup",
    component: () => import("./pages/Signupview.vue"),
  },
  {
    path: "/login",
    name: "Login",
    component: () => import("./pages/Loginview.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(_to, _from, savedPosition) {
    if (savedPosition) return savedPosition;
    return { top: 0 };
  },
});

// ─── App ──────────────────────────────────────────────
const app = createApp(App);
app.use(router);
app.mount("#app");
