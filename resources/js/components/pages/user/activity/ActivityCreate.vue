<template>
  <div>
    <div class="card">
      <div class="card-header">
        <i class="nav-icon fa fa-plus"></i> Add Activity
      </div>
      <div class="card-body" style="padding:30px; margin:0;">
        <form autocomplete="off" v-on:submit="saveForm()">
          <div class="form-row">
            <input type="hidden" v-model="data.main_acitivity_id" />
            <div class="form-group col-md-6">
              <label for="name">Title</label>
              <input
                type="text"
                class="form-control"
                v-model="data.title"
                placeholder="e.g., Prayer Meeting"
                readonly
              />
            </div>
            <div class="form-group col-md-6">
              <label for="name">Location</label>
              <input
                type="text"
                class="form-control"
                v-model="data.location"
                placeholder="e.g. Christ the Living Hope Community Church"
                required
              />
            </div>
            <div class="form-group col-md-6">
              <label for="name">Date</label>
              <input type="date" class="form-control" v-model="data.date" required />
            </div>
            <div class="form-group col-md-6">
              <label for="name">Time</label>
              <input type="time" class="form-control" v-model="data.time" required />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="grade_level">Description</label>
              <textarea class="form-control" v-model="data.description" placeholder="e.g. Team A"></textarea>
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
  mounted() {
    let app = this;
    let id = app.$route.params.id;
    app.userId = id;
    axios
      .get("/auth/activity/main/edit/" + id)
      .then(function(resp) {
        app.data.main_acitivity_id = resp.data.record.id;
        app.data.title = resp.data.record.title;
      })
      .catch(function() {
        alert("Could not load identfy data");
      });
  },
  data: function() {
    return {
      userId: null,
      data: {
        main_acitivity_id: null,
        title: null,
        location: "",
        date: null,
        time: null,
        description: ""
      },
      data_main: {
        id: null,
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
        .post("/auth/activity/store", data)
        .then(function(resp) {
          console.log(resp);
          app.$router.push("/main/activity/list/" + app.userId);
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