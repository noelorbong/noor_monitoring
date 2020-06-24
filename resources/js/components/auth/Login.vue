<template>
  <div class="app flex-row align-items-center vld-parent">
    <loading
      :active.sync="isLoading"
      :can-cancel="true"
      :is-full-page="fullPage"
      :loader="loader"
      :color="loaderColor"
    ></loading>

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card-group mb-0">
            <div class="card p-4">
              <div class="card-body">
                <div class="alert alert-danger" v-if="has_error">
                  <p>Login Failed Double check your credentials.</p>
                </div>
                <form autocomplete="off" @submit.prevent="handleSubmit" method="post">
                  <h1>Login</h1>
                  <p class="text-muted">Sign In to your account</p>
                  <div class="input-group mb-3">
                    <span class="input-group-addon">
                      <i class="icon-user"></i>
                    </span>
                    <input
                      type="text"
                      id="email"
                      class="form-control"
                      placeholder="user@example.com"
                      v-model="email"
                      required
                    />
                  </div>
                  <div class="input-group mb-4">
                    <span class="input-group-addon">
                      <i class="icon-lock"></i>
                    </span>
                    <input
                      type="password"
                      id="password"
                      class="form-control"
                      v-model="password"
                      required
                    />
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <button type="submit" class="btn btn-primary px-4">Login</button>
                    </div>
                    <div class="col-6 text-right">
                      <button type="button" class="btn btn-link px-0">Forgot password?</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
              <div class="card-body text-center">
                <div>
                  <h2>Sign up</h2>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                  <button type="button" class="btn btn-primary active mt-3">Register Now!</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: null,
      password: null,
      has_error: false,
      fullPage: false,
      isLoading: false,
      loader: "dots",
      loaderColor: "rgb(255,0,0)"
    };
  },
  mounted() {
    //
  },

  methods: {
    login() {
      // get the redirect object

      var app = this;
      var redirect = this.$auth.redirect();
      this.$auth.login({
        params: {
          email: app.email,
          password: app.password
        },
        success: function() {
          // handle redirection
          app.isLoading = false;

          this.$router.push("/main");
          const redirectTo = redirect
            ? redirect.from.name
            : this.$auth.user().role === 2
            ? "Dashboard"
            : "UserDashboard";
          this.$router.push({ name: redirectTo });
        },
        error: function() {
          app.has_error = true;
          // console.log("error");
          app.isLoading = false;
        },
        rememberMe: true,
        fetchUser: true
      });
    },
    handleSubmit(e) {
      var app = this;
      e.preventDefault();
      if (this.password.length > 0) {
        app.isLoading = true;
        this.$http
          .post("/auth/login", {
            email: this.email,
            password: this.password
          })
          .then(response => {
            this.login();
          })
          .catch(function(error) {
            // console.error(error.response);
            app.has_error = true;
            app.isLoading = false;
            // console.log("error");
          });
      }
    }
  }
};
</script>