<template>
  <div class="vld-parent">
    <loading
      :active.sync="isLoading"
      :can-cancel="true"
      :on-cancel="onCancel"
      :is-full-page="fullPage"
      :loader="loader"
      :color="loaderColor"
    ></loading>
    <div class="card">
      <div class="card-header">
        {{data.title}} Activity list
        <div style="float:right" class="search-wrapper panel-heading col-sm-4">
          <input class="form-control" type="text" v-model="inputSearchMessage" placeholder="Search" />
        </div>
        <button class="btn btn-sm btn-info" style="float:right" @click="show()">
          <i class="nav-icon fa fa-bar-chart"></i>
        </button>
        <router-link
          :to="{name: 'ActivityCreate',  params: {id: mainId}}"
          class="btn btn-primary btn-sm"
          style="float:right; margin-right:5px;"
        >
          <i class="nav-icon fa fa-plus"></i> Add
        </router-link>
      </div>
      <div class="card-body" style="padding:0px">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Location</th>
                <th>Attendee</th>
                <th>Date</th>
                <th>Time</th>
                <th>Description</th>
                <th>Created</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(data, index) in searchResult" :key="data.id">
                <td @click="goToProfile(data.id)" style="cursor:pointer">{{ data.location || '' }}</td>
                <td
                  @click="goToProfile(data.id)"
                  style="cursor:pointer; width: 20px;"
                >{{ data.attendee || ''}}</td>
                <td
                  @click="goToProfile(data.id)"
                  style="cursor:pointer; width: 100px;"
                >{{ data.date || ''}}</td>

                <td
                  @click="goToProfile(data.id)"
                  style="cursor:pointer; width: 30px;"
                >{{ data.time || ''}}</td>
                <td
                  class="truncate"
                  @click="goToProfile(data.id)"
                  style="cursor:pointer; max-width: 100px;"
                >{{ data.description || ''}}</td>
                <td
                  @click="goToProfile(data.id)"
                  style="cursor:pointer;  width: 160px; "
                >{{ data.created_at }}</td>
                <td style="width: 120px;">
                  <router-link
                    :to="{name: 'ActivityProfileEdit', params: {id: data.id}}"
                    style="float:right;"
                    class="btn btn-sm btn-info"
                  >
                    <i class="nav-icon fa fa-pencil"></i>
                  </router-link>
                  <button
                    href="#"
                    class="btn btn-sm btn-danger"
                    v-on:click="deleteEntry(data.id, index)"
                  >
                    <i class="fa fa-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <modal name="view-graph" height="auto" width="50%" :scrollable="true">
      <div class="card" style="margin:auto;">
        <div class="card-header">Graphical View</div>
        <div class="card-body">
          <line-example :height="300" chartId="chart-line-01" />
        </div>
      </div>
    </modal>
  </div>
</template>
<script>
import LineExample from "./LineExample.vue";

export default {
  components: {
    LineExample
  },
  data() {
    return {
      inputSearchMessage: "",
      mainId: 0,
      datas: [],
      data: {
        id: null,
        title: null,
        description: ""
      },
      fullPage: false,
      isLoading: true,
      loader: "dots",
      loaderColor: "rgb(11, 49, 200)"
    };
  },
  created: function() {
    var app = this;
    app.isLoading = true;
    let id = app.$route.params.id;
    app.mainId = id;
    axios
      .get("/auth/activity/list/" + id)
      .then(function(resp) {
        app.datas = resp.data.records;
        app.data = resp.data.record;
        app.isLoading = false;
      })
      .catch(function(resp) {
        console.log(resp);
        alert("Could not load Activity");
        app.isLoading = false;
      });
  },
  computed: {
    searchResult: function() {
      var self = this;
      return this.datas.filter(item => {
        return (
          (
            item.title +
            " " +
            item.location +
            " " +
            item.date +
            " " +
            item.time +
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

    show() {
      this.$modal.show("view-graph");
    },
    hide() {
      this.$modal.hide("view-graph");
    },

    deleteEntry(id, index) {
      if (confirm("Do you really want to delete it?")) {
        var app = this;
        axios
          .delete("/auth/activity/delete/" + id)
          .then(function(resp) {
            app.datas.splice(index, 1);
          })
          .catch(function(resp) {
            alert("Could not delete Activity");
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
.v--modal-box {
  width: 50% !important;
  background: transparent !important;
}

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