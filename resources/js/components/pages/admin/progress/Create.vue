<template>
  <div>
    <div class="card">
      <div class="card-header">
        <i class="nav-icon fa fa-plus"></i> Add Progresss
      </div>
      <div class="card-body" style="padding:30px; margin:0;">
        <form autocomplete="off" v-on:submit="saveForm()">
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="name">Progress Name</label>
              <input
                type="text"
                class="form-control"
                v-model="data.progress_name"
                placeholder="e.g., Pstr. Students"
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
                placeholder="e.g. Phil 4:13 Tribe"
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
      data: {
        progress_name: "",
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
        .post("/auth/progress/store", data)
        .then(function(resp) {
          // console.log(resp);
          app.$router.push("/main/progress");
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not create progress");
        });
    }
  }
};
</script>
<style>
</style>