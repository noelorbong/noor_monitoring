<template>
  <div>
    <div class="card">
      <div class="card-header">
        <i class="nav-icon fa fa-pencil"></i> Edit Activity
        <router-link
          :to="{name: 'TribeactivityProfile', params: {id: userId}}"
          style="float:right;"
          class="btn btn-sm btn-warning"
        >
          <i class="fa fa-arrow-left"></i>
        </router-link>
      </div>
      <div class="card-body" style="padding:30px; margin:0;">
        <form autocomplete="off" v-on:submit="updateForm()">
          <input type="hidden" v-model="data.id" />
          <div class="form-row">
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
      .get("/auth/tribeactivity/profile/" + id)
      .then(function(resp) {
        // console.log(resp.data);
        app.data = resp.data.user;
        app.reports = resp.data.records;
      })
      .catch(function() {
        alert("Could not load your profile");
      });
  },
  data: function() {
    return {
      userId: 0,
      data: {
        id: null,
        title: null,
        location: "",
        date: null,
        time: null,
        description: ""
      }
    };
  },
  methods: {
    updateForm() {
      if (confirm("Do you really want to Update the Activity?")) {
        event.preventDefault();
        var app = this;
        var data = app.data;
        axios
          .post("/auth/tribeactivity/update", data)
          .then(function(resp) {
            console.log(resp);
            app.$router.push("/user/tribeactivity/profile/" + app.userId);
          })
          .catch(function(resp) {
            console.log(resp);
            alert("Could not Update Tribe Activity");
          });
      }
    }
  }
};
</script>
<style>
.profile_image {
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 5px;
  width: 145px;
  height: 145px;
  margin-bottom: 5px;
}

.profile_image:hover {
  box-shadow: 0 0 2px 1px rgba(0, 140, 186, 0.5);
}
.profile {
  width: 145px;
  height: 145px;
  float: left;
  text-align: center;
}
</style>