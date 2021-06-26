<template>
  <div class="welcome-background">
    <div class="flex-center position-ref full-height">
      <div class="top-right links">
        <!-- <router-link :to="{name: 'Dashboard'}">Dashboard</router-link> -->
        <b-link v-if="UserName != '' && Role == 2" to="/main/dashboard">Dashboard</b-link>
        <b-link v-else-if="UserName != '' && Role == 1" to="/user/dashboard">Dashboard</b-link>
        <b-link v-else to="/login">Login</b-link>
      </div>

      <div class="content">
        <div class="title m-b-md">
          <img style="width:20vw" src="/images/chose1s_banner.png" title="Chosen1s" alt="Chosen1s" />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      UserInfo: [],
      UserName: "",
      Role: 1
    };
  },
  created: function() {
    // this.getUsers();
  },
  mounted() {
    this.getUsers();
  },
  methods: {
    getUsers() {
      var app = this;
      this.$http({
        url: "auth/user",
        method: "GET"
      }).then(
        res => {
          if (res.data.data) {
            app.UserInfo = res.data.data;
            // console.log(app.UserInfo);
            app.UserName = res.data.data.name;
            app.Role = res.data.data.role;
          }
        },
        () => {
          this.has_error = true;
        }
      );
    }
  }
};
</script>

<style>
.content {
  width: 100%;
}
.title {
  width: 100%;
  /* font-weight: 500; */
  /* color: #636b6f; */
  -webkit-text-stroke: 2px #ffffff;
  background-color: rgba(245, 245, 245, 0.5);
}
.welcome-background {
  background: url(/images/background.jpg) no-repeat center center fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}

html,
body {
  /* background-color: #fff; */
  background-color: transparent;
  color: #636b6f;
  font-family: "Nunito", sans-serif;
  font-weight: 200;
  height: 100vh;
  margin: 0;
}

.full-height {
  height: 100vh;
}

.flex-center {
  align-items: center;
  display: flex;
  justify-content: center;
}

.position-ref {
  position: relative;
}

.top-right {
  position: absolute;
  right: 10px;
  top: 18px;
}

.content {
  text-align: center;
}

.title {
  font-size: 84px;
}

.links > a {
  color: #656060;
  padding: 0 25px;
  font-size: 13px;
  font-weight: 600;
  letter-spacing: 0.1rem;
  text-decoration: none;
  text-transform: uppercase;
}

.m-b-md {
  margin-bottom: 30px;
}
</style>
