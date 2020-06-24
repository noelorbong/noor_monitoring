<template>
  <div class="vld-parent">
    <loading
      :active.sync="isLoading"
      :can-cancel="true"
      :is-full-page="fullPage"
      :loader="loader"
      :color="loaderColor"
    ></loading>
    <div class="row">
      <div class="col-sm-6">
        <h3>Tribes</h3>
      </div>
      <div class="col-sm-3"></div>
      <div class="search-wrapper col-sm-3">
        <input
          class="form-control"
          id="searchBox"
          type="text"
          v-model="inputSearchMessage"
          placeholder="Search"
        />
      </div>
    </div>

    <div class="spacer" style="margin-bottom: 10px;"></div>

    <div class="row">
      <div class="col-sm-4" v-for="(data) in searchResult" :key="data.id">
        <div class="card">
          <div class="card-header">
            <i class="nav-icon fa fa-fort-awesome"></i>
            {{ data.tribe_name || ''}}
          </div>
          <div class="card-body">
            <div>
              <span style="font-weight: bold;">Tribe Leader:</span>
              {{ data.tribe_leader_name || 'None'}}
              <br />
            </div>
            <div>
              <span style="font-weight: bold;">Username:</span>
              {{ data.username || 'None'}}
              <br />
            </div>
            <div>
              <span style="font-weight: bold;">Password:</span>
              {{ data.password || 'None'}}
              <br />
            </div>
            <div>
              <span style="font-weight: bold;">Member:</span>
              {{ data.tribe_member || ''}}
              <br />
            </div>
            <p
              class="truncate"
              :id="'truncate_'+data.id"
              style="font-weight: initial; cursor:pointer; text-align: justify;"
              @click="showMoreLess(data.id)"
            >
              <span style="color: black;">
                <span style="font-weight: bold;">Description:</span>
                {{ data.description || ''}}
              </span>
              <br />
            </p>
          </div>
          <div class="card-footer">
            <!-- <a  href="/nodedevicesview/input/{{$record->id}}" > -->

            <router-link
              :to="{name: 'TribeProfile', params: {id: data.id}}"
              class="btn btn-info btn-sm"
            >
              <i class="nav-icon fa fa-eye"></i> View
            </router-link>
            <router-link
              v-if="data.id != 1"
              :to="{name: 'TribeEdit' , params: {id: data.id}}"
              class="btn btn-success btn-sm"
            >
              <i class="nav-icon fa fa-pencil"></i> Edit
            </router-link>
            <button
              v-if="data.id != 1"
              class="btn btn-sm btn-danger"
              v-on:click="deleteEntry(data.id)"
            >
              <i class="fa fa-trash"></i>
            </button>
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
      inputSearchMessage: "",
      datas: [],
      fullPage: false,
      isLoading: true,
      loader: "dots",
      loaderColor: "rgb(11, 49, 200)"
    };
  },
  created: function() {
    var app = this;
    app.isLoading = true;
    axios
      .get("/auth/tribe/list")
      .then(function(resp) {
        app.datas = resp.data.records;
        app.isLoading = false;
      })
      .catch(function(resp) {
        console.log(resp);
        alert("Could not load tribe");
        app.isLoading = false;
      });
  },
  computed: {
    searchResult: function() {
      var self = this;
      return this.datas.filter(item => {
        return (
          (
            item.tribe_name +
            " " +
            item.username +
            " " +
            item.password +
            " " +
            item.tribe_leader_name +
            " " +
            item.description +
            " "
          )
            .toLowerCase()
            .indexOf(this.inputSearchMessage.toLowerCase()) > -1
        );
      });
    }
  },
  methods: {
    goToProfile(id) {
      this.$router.push({ name: "ActivityProfile", params: { id: id } });
    },
    showMoreLess(index) {
      var element = document.getElementById("truncate_" + index);
      element.classList.toggle("truncate");
    },
    deleteEntry(id) {
      if (confirm("Do you really want to delete it?")) {
        var app = this;
        axios
          .delete("/auth/tribe/delete/" + id)
          .then(function(resp) {
            var index = app.datas
              .map(x => {
                return x.id;
              })
              .indexOf(id);
            app.datas.splice(index, 1);
          })
          .catch(function(resp) {
            alert("Could not delete tribe");
          });
      }
    },
    onCancel() {
      console.log("cancelled the loader.");
    }
  }
};
</script>
<style lang='scss' scoped>
.truncate {
  /* need automatic multi-line height */
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  word-wrap: break-word;
  /* color:#170694; */
}

.table-responsive {
  height: 68vh;
  margin: 0px;
  padding: 0px;
  text-align: center;
}
.table {
  margin: 0px;
  padding: 0px;
  text-align: center;
}
td {
  vertical-align: middle;
  padding: 5px;
  text-align: center;
}
th {
  text-align: center;
}
</style>