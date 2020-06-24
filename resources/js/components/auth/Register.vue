<template>
  <div class="app flex-row align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card mx-4">
            <div class="card-body p-4">
              <form autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
                <div class="alert alert-danger" v-if="has_error && !success">
                  <span class="help-block" v-if="has_error && errors.email">
                    {{ errors.email }}
                    <br />
                  </span>

                  <span class="help-block" v-if="has_error && errors.password">{{ errors.password }}</span>

                  <!-- <p
                    v-if="error == 'registration_validation_error'"
                  >Error, can not register at the moment. If the problem persists, please contact an administrator.</p>
                  <p
                    v-else
                  >Error, can not register at the moment. If the problem persists, please contact an administrator.</p>-->
                </div>
                <h1>Register</h1>
                <p class="text-muted">Create your account</p>
                <div class="input-group mb-3">
                  <span class="input-group-addon">
                    <i class="icon-user"></i>
                  </span>
                  <input
                    type="text"
                    v-model="name"
                    class="form-control"
                    placeholder="Name"
                    required
                  />
                </div>

                <div
                  class="input-group mb-3"
                  v-bind:class="{ 'has-error': has_error && errors.email }"
                >
                  <span class="input-group-addon">@</span>
                  <input
                    type="email"
                    id="email"
                    class="form-control"
                    placeholder="user@example.com"
                    v-model="email"
                    required
                  />
                </div>

                <div
                  class="input-group mb-3"
                  v-bind:class="{ 'has-error': has_error && errors.password }"
                >
                  <span class="input-group-addon">
                    <i class="icon-lock"></i>
                  </span>
                  <input
                    placeholder="Password"
                    type="password"
                    id="password"
                    class="form-control"
                    v-model="password"
                    required
                  />
                </div>

                <div
                  class="input-group mb-4"
                  v-bind:class="{ 'has-error': has_error && errors.password }"
                >
                  <span class="input-group-addon">
                    <i class="icon-lock"></i>
                  </span>
                  <input
                    type="password"
                    id="password_confirmation"
                    placeholder="Repeat password"
                    class="form-control"
                    v-model="password_confirmation"
                    required
                  />
                </div>

                <button type="submit" class="btn btn-block btn-success">Create Account</button>
              </form>
            </div>
            <!-- <div class="card-footer p-4">
              <div class="row">
                <div class="col-6">
                  <button class="btn btn-block btn-facebook" type="button">
                    <span>facebook</span>
                  </button>
                </div>
                <div class="col-6">
                  <button class="btn btn-block btn-twitter" type="button">
                    <span>twitter</span>
                  </button>
                </div>
              </div>
            </div>-->
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
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      has_error: false,
      error: "",
      errors: {},
      success: false
    };
  },
  methods: {
    register() {
      var app = this;
      this.$auth.register({
        data: {
          name: app.name,
          email: app.email,
          password: app.password,
          password_confirmation: app.password_confirmation
        },
        success: function() {
          app.success = true;
          this.$router.push({
            name: "login",
            params: { successRegistrationRedirect: true }
          });
        },
        error: function(res) {
          // console.log(res.response.data.errors);
          app.has_error = true;
          app.error = res.response.data.error;
          app.errors = res.response.data.errors || {};
        }
      });
    }
  }
};
</script>