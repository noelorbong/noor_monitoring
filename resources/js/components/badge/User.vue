<template>
  <span class="d-md-down-none">{{ UserName }}</span>
</template>

<script>
export default {
  data() {
    return {
      UserInfo: [],
      UserName: ""
    };
  },
  created: function() {
    // this.getUsers();
  },
  mounted() {
    this.getUsers();
  },
  methods: {
    getCurUsers: function() {
      var app = this;
      axios
        .get("/auth/user")
        .then(function(resp) {
          console.log("response:" + JSON.stringify(resp.data.data));
          app.UserInfo = resp.data.data;
          app.UserName = resp.data.data.email;
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not load user");
        });
    },
    getUsers() {
      var app = this;
      this.$http({
        url: "auth/user",
        method: "GET"
      }).then(
        res => {
          app.UserInfo = res.data.data;
          app.UserName = res.data.data.email;
        },
        () => {
          this.has_error = true;
        }
      );
    }
  }
};
</script>
