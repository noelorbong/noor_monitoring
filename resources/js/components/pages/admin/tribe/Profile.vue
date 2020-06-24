<template>
  <div class="row vld-parent">
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-header">
          <i class="nav-icon fa fa-fort-awesome"></i> Tribe Info
          <router-link
            v-if="userId != 1"
            :to="{name: 'TribeEdit' ,params: {id: userId}}"
            style="float:right;"
            class="btn btn-sm btn-info"
          >
            <i class="fa fa-pencil"></i>
          </router-link>
        </div>
        <div class="card-body text-center">
          <u>{{ data.tribe_name || ''}}</u>
          <br />
          <span style="font-weight: bold;">Tribe Name</span>
          <br />
          <br />
          <u>{{data.tribe_leader_name || ''}}</u>
          <br />
          <span style="font-weight: bold;">Tribe Leader</span>

          <br />
          <br />
          <u>{{data.tribe_member || ''}}</u>
          <br />
          <span style="font-weight: bold;">Member</span>
          <br />
          <br />
          <u>{{ data.description || ''}}</u>
          <br />
          <span style="font-weight: bold;">Description</span>

          <br />
          <br />
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-9">
      <div class="card">
        <div class="card-header">
          <i class="nav-icon fa fa-list-alt"></i>
          Members
          <div
            style="float: right;margin: 0px;padding: 0px;"
            class="search-wrapper panel-heading col-sm-4"
          >
            <input
              class="form-control"
              v-model="inputSearchMembers"
              type="text"
              placeholder="Search"
            />
          </div>
          <button
            v-if="userId != 1"
            style="float:right; margin-left:10px; margin-right:10px;"
            @click="showCustom()"
            class="btn btn-sm btn-info"
          >
            <i class="fa fa-plus"></i> Custom Members
          </button>
          <button
            v-if="userId != 1"
            style="float:right;"
            @click="show()"
            class="btn btn-sm btn-info"
          >
            <i class="fa fa-plus"></i> Members
          </button>
          <button style="float:right; margin-right:5px;" class="btn btn-sm btn-success">
            <download-excel
              :data="searchResult2"
              :fields="json_fields"
              worksheet="My Worksheet"
              type="csv"
              :name="filename"
            >
              <i class="fa fa-file-excel-o"></i>
            </download-excel>
          </button>
        </div>
        <div class="card-body" style="padding:0px">
          <loading
            :active.sync="isLoading"
            :can-cancel="true"
            :is-full-page="fullPage"
            :loader="loader"
            :color="loaderColor"
          ></loading>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th></th>
                  <th>Name</th>
                  <th>Number</th>
                  <th>Category</th>
                  <th>Mentor</th>
                  <th>Label</th>
                  <th v-if="userId != 1"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(member) in searchResult2" :key="member.id">
                  <td @click="goToProfile(member.id)" style="cursor:pointer">
                    <!-- <router-link :to="{name: 'MemberProfile', params: {id: member.id}}"> -->
                    <img
                      v-if="member.photo"
                      :src="'uploads/'+member.photo"
                      style="border-radius: 50%;"
                      height="40"
                      width="40"
                    />
                    <img
                      v-else
                      :src="'uploads/image_icon.png'"
                      style="border-radius: 50%;"
                      height="40"
                      width="40"
                    />
                    <!-- </router-link> -->
                  </td>
                  <td @click="goToProfile(member.id)" style="cursor:pointer">{{member.full_name}}</td>
                  <td
                    @click="goToProfile(member.id)"
                    style="cursor:pointer"
                  >{{ member.contact_number == null ? " ":member.contact_number }}</td>
                  <td
                    @click="goToProfile(member.id)"
                    style="cursor:pointer"
                  >{{ member.category_name == null ? " ":member.category_name }}</td>
                  <td
                    @click="goToProfile(member.id)"
                    style="cursor:pointer"
                  >{{ member.mentor_name == null ? " ":member.mentor_name }}</td>
                  <td
                    @click="goToProfile(member.id)"
                    style="cursor:pointer"
                  >{{ member.label == null ? " ":member.label }}</td>
                  <td v-if="userId != 1">
                    <button class="btn btn-sm btn-danger" v-on:click="removeEntry(member.id)">
                      <i class="fa fa-close"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <modal name="add-members" height="auto" :scrollable="true">
      <div class="card" style="margin:0; padding: 0;">
        <div class="card-header">
          Member list
          <div style="float:right" class="search-wrapper panel-heading col-sm-4">
            <input
              class="form-control"
              v-model="inputSearchMessage"
              type="text"
              placeholder="Search"
            />
          </div>
        </div>
        <div class="card-body" style="margin:0px; padding:0">
          <div class="table-responsive-modal">
            <table class="table table-striped" style="padding:0px; margin:0px;">
              <thead>
                <tr>
                  <th></th>
                  <th>Name</th>
                  <th>Number</th>
                  <th>Category</th>
                  <th>Tribe</th>
                  <th>Mentor</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(member) in searchResult" :key="member.id">
                  <td @click="goToProfile(member.id)" style="cursor:pointer">
                    <!-- <router-link :to="{name: 'MemberProfile', params: {id: member.id}}"> -->
                    <img
                      v-if="member.photo"
                      :src="'uploads/'+member.photo"
                      style="border-radius: 50%;"
                      height="40"
                      width="40"
                    />
                    <img
                      v-else
                      :src="'uploads/image_icon.png'"
                      style="border-radius: 50%;"
                      height="40"
                      width="40"
                    />
                    <!-- </router-link> -->
                  </td>
                  <td @click="goToProfile(member.id)" style="cursor:pointer">{{member.full_name}}</td>
                  <td
                    @click="goToProfile(member.id)"
                    style="cursor:pointer"
                  >{{ member.contact_number == null ? " ":member.contact_number }}</td>
                  <td
                    @click="goToProfile(member.id)"
                    style="cursor:pointer"
                  >{{ member.category_name == null ? " ":member.category_name }}</td>
                  <td
                    @click="goToProfile(member.id)"
                    style="cursor:pointer"
                  >{{ member.tribe_name == null ? " ":member.tribe_name }}</td>
                  <td
                    @click="goToProfile(member.id)"
                    style="cursor:pointer"
                  >{{ member.mentor_name == null ? " ":member.mentor_name }}</td>
                  <td>
                    <p v-if="unavailability.includes(member.id)">InList</p>
                    <input
                      v-else
                      type="checkbox"
                      :id="member.id"
                      :value="member.id"
                      v-model="availability"
                    />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer text-muted">
          <button style="float:right;" @click="saveData()" class="btn btn-sm btn-info">
            <i class="fa fa-save"></i>
          </button>
        </div>
      </div>
    </modal>

    <modal name="add-custom-members" height="auto" :scrollable="true">
      <div class="card" style="margin:0; padding: 0;">
        <div class="card-header">Add Custom Member</div>
        <div class="card-body">
          <form autocomplete="off" v-on:submit="saveForm()">
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="name">Last Name</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="member.last_name"
                  placeholder="e.g., Dela Cruz"
                  required
                />
              </div>
              <div class="form-group col-md-3">
                <label for="name">First Name</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="member.first_name"
                  placeholder="e.g., Juan"
                  required
                />
              </div>
              <div class="form-group col-md-2">
                <label for="name">Name Extension</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="member.name_extension"
                  placeholder="e.g., Jr.,II"
                />
              </div>
              <div class="form-group col-md-3">
                <label for="name">Middle Name</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="member.middle_name"
                  placeholder="e.g., Mercado"
                />
              </div>
              <div class="form-group col-md-12">
                <label for="Gender">Gender</label>
                <select class="form-control" v-model="member.gender">
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>
            </div>

            <input type="hidden" class="form-control" v-model="member.tribe" />

            <button type="submit" class="btn btn-primary">Save</button>
          </form>
        </div>
      </div>
    </modal>
  </div>
</template>

<script>
export default {
  mounted() {
    var app = this;
    app.loadActivity();
    app.memberList();
  },
  data: function() {
    return {
      userId: 0,
      reports: [],
      inputSearchMessage: "",
      inputSearchMembers: "",
      members: [],
      member: {
        last_name: "",
        first_name: "",
        name_extension: "",
        middle_name: "",
        tribe: 0,
        category: 1,
        label: 0,
        gender: "Male"
      },
      availability: [],
      unavailability: [],
      data: {
        // id: null,
        // name: "",
        // address1: "",
        // dob: "",
        // photo: "default.png",
        // email: "",
        // password: "",
        // created_at: "",
        // updated_at: ""
      },
      filename: "",
      json_fields: {
        Name: "full_name",
        Number: "contact_number",
        Category: "category_name",
        Tribe: "tribe_name",
        Mentor: "mentor_name",
        Label: "label",
        Address: "complete_address"
      },
      fullPage: false,
      isLoading: true,
      loader: "dots",
      loaderColor: "rgb(11, 49, 200)"
    };
  },
  computed: {
    searchResult: function() {
      var self = this;
      return this.members.filter(item => {
        return (
          (
            item.full_name +
            " " +
            item.contact_number +
            " " +
            item.category_name +
            " " +
            item.mentor_name +
            " " +
            item.label +
            " "
          )
            .toLowerCase()
            .indexOf(this.inputSearchMessage.toLowerCase()) > -1
        );
      });
    },
    searchResult2: function() {
      var self = this;
      return this.reports.filter(item => {
        return (
          (
            item.full_name +
            " " +
            item.contact_number +
            " " +
            item.category_name +
            " " +
            item.mentor_name +
            " " +
            item.label +
            " "
          )
            .toLowerCase()
            .indexOf(this.inputSearchMembers.toLowerCase()) > -1
        );
      });
    }
  },
  methods: {
    convert12Hours(datetime) {
      var dt = new Date(datetime);
      var hours = dt.getHours(); // gives the value in 24 hours format
      var AmOrPm = hours >= 12 ? "pm" : "am";
      hours = hours % 12 || 12;
      var minutes = dt.getMinutes();
      if (hours < 10) {
        hours = "0" + hours;
      }

      if (minutes < 10) {
        minutes = "0" + minutes;
      }
      var finalTime = hours + ":" + minutes + " " + AmOrPm;
      return finalTime; // final time Time - 22:10
    },
    goToProfile(id) {
      this.$router.push({
        name: "MemberProfile",
        params: {
          id: id
        }
      });
    },
    loadActivity() {
      let app = this;
      let id = app.$route.params.id;
      app.userId = id;
      app.isLoading = true;
      axios
        .get("/auth/tribe/profile/" + id)
        .then(function(resp) {
          // console.log(resp.data);
          app.data = resp.data.user;
          app.reports = resp.data.records;
          app.filename = resp.data.user.tribe_name + ".xls";
          app.availability = [];
          app.unavailability = [];
          $.each(resp.data.records, function(key, value) {
            app.availability.push(value.id);
          });
          app.unavailability = app.availability;

          $.each(app.reports, function(key, value) {
            if (value.category) {
              var category_name = [];
              var category_split = value.category.split(",");

              $.each(category_split, function(key1, value1) {
                $.each(resp.data.categories, function(key2, value2) {
                  if (value1 == value2.value) {
                    category_name.push(value2.text);
                  }
                });
              });
              app.reports[key].category_name = category_name.join(",");
            }
          });

          app.isLoading = false;
        })
        .catch(function() {
          alert("Could not load your profile");
          app.isLoading = false;
        });
    },
    show() {
      this.$modal.show("add-members");
    },
    hide() {
      this.$modal.hide("add-members");
    },
    showCustom() {
      this.$modal.show("add-custom-members");
    },
    hideCustom() {
      this.$modal.hide("add-custom-members");
    },
    saveData: function() {
      var app = this;
      var availability = app.availability.join(","); // assuming your are saving the availability as a string in the user data
      console.log(availability);
      if (!availability) {
        alert("Please Select Attendee.");
        return;
      }
      var formData = new FormData();
      formData.append("tribe_id", app.userId);
      formData.append("member_id", availability);
      axios
        .post("/auth/tribe/member/update", formData)
        .then(function(resp) {
          console.log(resp);
          // app.$router.push("/main/activity/list");
          app.loadActivity();
          app.memberList();
          app.hide();
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not update..");
          app.hide();
        });
    },
    saveForm() {
      var app = this;
      app.member.tribe = app.userId;
      var member = app.member;
      axios
        .post("/auth/member/store", member)
        .then(function(resp) {
          app.loadActivity();
          app.hideCustom();
          app.member.last_name = "";
          app.member.first_name = "";
          app.member.name_extension = "";
          app.member.middle_name = "";
          app.member.tribe = 0;
          app.member.category = 1;
          app.member.label = 0;
          app.member.gender = "Male";
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not create member");
        });
    },
    memberList() {
      var app = this;
      // app.isLoading = true;
      axios
        .get("/auth/member/list")
        .then(function(resp) {
          app.members = resp.data.records;

          $.each(app.members, function(key, value) {
            if (value.category) {
              var category_name = [];
              var category_split = value.category.split(",");

              $.each(category_split, function(key1, value1) {
                $.each(resp.data.categories, function(key2, value2) {
                  if (value1 == value2.value) {
                    category_name.push(value2.text);
                  }
                });
              });
              app.members[key].category_name = category_name.join(",");
            }
          });
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not load members");
          // app.isLoading = false;
        });
    },
    removeEntry(id) {
      var app = this;
      //console.log("Id: " + id + " index:" + mainIndex);

      if (confirm("Do you really want to remove it?")) {
        axios
          .delete("/auth/tribe/remove/" + id)
          .then(function(resp) {
            var index = app.reports
              .map(x => {
                return x.id;
              })
              .indexOf(id);

            app.reports.splice(index, 1);

            app.data.tribe_member = app.reports.length;
            app.availability = [];
            app.unavailability = [];
            $.each(app.reports, function(key, value) {
              app.availability.push(value.id);
            });
            app.unavailability = app.availability;
          })
          .catch(function(resp) {
            alert("Could not remove!");
          });
      }
    }
  }
};
</script>

<style>
.profile-img {
  max-width: 150px;
  width: 100px;
  height: 100px;
  border: 5px solid #fff;
  border-radius: 100%;
  box-shadow: 0 2px 2px rgba(0, 0, 0, 0.3);
}

.v--modal-box {
  height: auto !important;
  width: 60% !important;
  /* margin: 0 !important; */
  padding: 0 !important;
  margin-left: auto;
  margin-right: auto;
  margin-top: 8%;
  /* margin: auto; */
  top: auto !important;
  left: auto !important;
}

.card-header > .btn {
  margin-top: 1px !important;
}
</style>
