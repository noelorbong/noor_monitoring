<template>
  <header class="app-header navbar">
    <button
      class="navbar-toggler mobile-sidebar-toggler d-lg-none"
      type="button"
      @click="mobileSidebarToggle"
    >&#9776;</button>
    <b-link class="navbar-brand" to="/"></b-link>

    <button
      class="navbar-toggler sidebar-toggler d-md-down-none"
      type="button"
      @click="sidebarMinimize"
    >&#9776;</button>
    <!-- <b-nav is-nav-bar class="d-md-down-none">
      <b-nav-item class="px-3">Dashboard</b-nav-item>
      <b-nav-item class="px-3">Users</b-nav-item>
      <b-nav-item class="px-3">Settings</b-nav-item>
    </b-nav>-->
    <b-nav is-nav-bar class="ml-auto">
      <!-- <b-nav-item class="d-md-down-none">
        <i class="icon-bell"></i>
        <span class="badge badge-pill badge-danger">5</span>
      </b-nav-item>
      <b-nav-item class="d-md-down-none">
        <i class="icon-list"></i>
      </b-nav-item>
      <b-nav-item class="d-md-down-none">
        <i class="icon-location-pin"></i>
      </b-nav-item>-->
      <b-nav-item-dropdown right>
        <template slot="button-content">
          <!-- <img src="/img/avatars/6.jpg" class="img-avatar" alt="admin@bootstrapmaster.com" /> -->
          <!-- <span class="d-md-down-none">admin</span> -->
          <User />
        </template>
        <b-dropdown-header tag="div" class="text-center">
          <strong>Account</strong>
        </b-dropdown-header>
        <!-- <b-dropdown-item>
          <i class="fa fa-shield"></i> Lock Account
        </b-dropdown-item>-->
        <b-dropdown-item v-if="$auth.check()">
          <a @click.prevent="$auth.logout()">
            <i class="fa fa-lock"></i> Logout
          </a>
        </b-dropdown-item>
      </b-nav-item-dropdown>
      <b-nav-item class="d-md-down-none">
        <!-- <i class="icon-location-pin"></i> -->
      </b-nav-item>
    </b-nav>
    <!-- <button
      class="navbar-toggler aside-menu-toggler d-md-down-none"
      type="button"
      @click="asideToggle"
    >&#9776;</button>-->
  </header>
</template>
<script>
import User from "../badge/User.vue";
export default {
  name: "appheader",
  components: {
    User
  },
  methods: {
    sidebarToggle(e) {
      e.preventDefault();
      document.body.classList.toggle("sidebar-hidden");
    },
    sidebarMinimize(e) {
      e.preventDefault();
      document.body.classList.toggle("sidebar-minimized");
    },
    mobileSidebarToggle(e) {
      e.preventDefault();
      document.body.classList.toggle("sidebar-mobile-show");
    },
    asideToggle(e) {
      e.preventDefault();
      document.body.classList.toggle("aside-menu-hidden");
    },

    logOut() {
      var token = document.head.querySelector('meta[name="csrf-token"]');
      window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
      // console.log(token.content);
      var formdata = new FormData();
      formdata.append("_token", token.content);
      var app = this;
      // this.$axios.setToken(false);
      // this.$axios.setHeader("Authorization", null);
      axios
        .post("/logout", formdata)
        .then(function(resp) {
          console.log(resp);
          app.$router.push({ path: "/" });
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not logout");
        });
    }
  }
};
</script>
