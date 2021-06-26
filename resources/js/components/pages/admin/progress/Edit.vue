<template>
  <div>
    <div class="card">
      <div class="card-header">
        <i class="nav-icon fa fa-plus"></i> Edit Progress
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
                placeholder="e.g., Lion"
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
                placeholder="e.g. "
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
    app.data.id = id;
    axios
      .get("/auth/progress/edit/" + id)
      .then(function(resp) {
        app.data = resp.data.record;
      })
      .catch(function() {
        alert("Could not load Progress");
      });
  },
  data: function() {
    return {
      data: {
        id: 0,
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
        .post("/auth/progress/update", data)
        .then(function(resp) {
          console.log(resp);
          app.$router.push("/main/progress/profile/" + app.data.id);
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not update progress");
        });
    }
  }
};
</script>
<style>