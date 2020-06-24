<template>
  <div>
    <div class="card">
      <div class="card-header">
        <i class="nav-icon fa fa-plus"></i> Edit Category
      </div>
      <div class="card-body" style="padding:30px; margin:0;">
        <form autocomplete="off" v-on:submit="saveForm()">
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="name">Category Name</label>
              <input
                type="text"
                class="form-control"
                v-model="data.category_name"
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
      .get("/auth/category/edit/" + id)
      .then(function(resp) {
        app.data = resp.data.record;
      })
      .catch(function() {
        alert("Could not load category");
      });
  },
  data: function() {
    return {
      data: {
        id: 0,
        category_name: "",
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
        .post("/auth/category/update", data)
        .then(function(resp) {
          console.log(resp);
          app.$router.push("/main/category/profile/" + app.data.id);
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not create category");
        });
    }
  }
};
</script>
<style>
</style>