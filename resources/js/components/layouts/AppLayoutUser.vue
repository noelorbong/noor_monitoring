<template>
  <div class="app">
    <AppHeader />
    <div class="app-body">
      <Sidebar :navItems="nav" />
      <main class="main">
        <!-- <breadcrumb :list="list" /> -->
        <div class="spacer" style="margin: 15px;"></div>
        <div class="container-fluid">
          <router-view></router-view>
        </div>
      </main>
      <!-- <AppAside /> -->
    </div>
    <AppFooter />
  </div>
</template>

<script>
import nav from "../_nav_user.js";
import {
  Header as AppHeader,
  Sidebar,
  Aside as AppAside,
  Footer as AppFooter,
  Breadcrumb
} from "../components/";

export default {
  name: "full",
  components: {
    AppHeader,
    Sidebar,
    AppAside,
    AppFooter,
    Breadcrumb
  },
  data() {
    return {
      nav: nav.items
    };
  },
  computed: {
    name() {
      return this.$route.name;
    },
    list() {
      return this.$route.matched;
    }
  }
};
</script>
<style lang="scss">
// Import Font Awesome Icons Set
$fa-font-path: "~font-awesome/fonts/";
@import "~font-awesome/css/font-awesome.min.css";
// Import Simple Line Icons Set
$simple-line-font-path: "~simple-line-icons/fonts/";
@import "~simple-line-icons/css/simple-line-icons.css";
</style>
<style lang="scss">
// Import Main styles for this application
// @import "./sass/app";
</style>