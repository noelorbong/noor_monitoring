<template>
  <div>
    <div class="card">
      <div class="card-header">
        <i class="nav-icon fa fa-plus"></i> Edit Activity
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
          <button type="submit" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    let app = this;
    let id = app.$route.params.id;
    app.userId = id;
    axios
      .get("/auth/activity/main/edit/" + id)
      .then(function(resp) {
        app.data = resp.data.record;
      })
      .catch(function() {
        alert("Could not load your profile");
      });
  },
  data: function() {
    return {
      data: {
        title: null,
        description: "",
        data: {
          id: null,
          title: null,
          description: ""
        }
      }
    };
  },
  methods: {
    saveForm() {
      event.preventDefault();
      var app = this;
      var data = app.data;
      axios
        .post("/auth/activity/main/update", data)
        .then(function(resp) {
          console.log(resp);
          app.$router.push("/main/activity/main");
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not update activity");
        });
    }
  }
};
</script>
<style>
</style>