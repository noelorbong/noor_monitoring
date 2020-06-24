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
    this.getCurUser();
  },
  http: {
    // headers: {
    //   "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    // }
  },
  methods: {
    getCurUser: function() {
      var app = this;
      axios
        .get("/currentuser")
        .then(function(resp) {
          app.UserInfo = resp.data;
          app.UserName = resp.data.name;
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not load user");
        });
    },
    setUser: function() {}
  }
};
</script>
