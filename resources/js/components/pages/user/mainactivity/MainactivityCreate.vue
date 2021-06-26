<template>
  <div>
    <div class="card">
      <div class="card-header">
        <i class="nav-icon fa fa-plus"></i> Add Activity
      </div>
      <div class="card-body" style="padding:30px; margin:0;">
        <form autocomplete="off" v-on:submit="saveForm()">
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="name">Title</label>
              <input
                type="text"
                class="form-control"
                v-model="data.title"
                placeholder="e.g., Prayer Meeting"
                required
              />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="grade_level">Description</label>
              <textarea
                class="form-control"
                v-model="data.description"
                placeholder="e.g. Believers gathering."
              ></textarea>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Save</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function() {
    return {
      url: "/uploads/image_icon.png",
      data: {
        title: null,
        description: ""
      }
    };
  },
  methods: {
    saveForm() {
      event.preventDefault();
      var app = this;
      var data = app.data;
      axios
        .post("/auth/activity/main/store", data)
        .then(function(resp) {
          console.log(resp);
          app.$router.push("/main/activity/main");
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not create activity");
        });
    }
  }
};
</script>
<style>
</style>