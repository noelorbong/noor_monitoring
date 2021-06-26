<template>
  <div class="row">
    <div class="col-sm-6 col-xl-4">
      <div class="card">
        <div class="card-header">
          <i class="nav-icon fa fa-user"></i> Profile
          <router-link
            :to="{name: 'UserMemberProfileEdit', params: {id: userId}}"
            style="float:right;"
            class="btn btn-sm btn-info"
          >
            <i class="fa fa-pencil"></i>
          </router-link>
        </div>
        <div class="card-body text-center">
          <img class="profile-img" :src="'uploads/'+user.photo" />
          <br />
          <br />
          <!-- <u>{{ user.last_name == null ? " ":user.last_name}}, {{ user.first_name == null ? " ":user.first_name}} {{ user.name_extension == null ? " ":user.name_extension}} {{ user.middle_name == null ? " ":user.middle_name }}</u> -->
          <u>{{ user.full_name}}</u>
          <br />
          <span style="font-weight: bold;">Full Name</span>
          <br />
          <br />
          <u>{{user.email_address == null ? " ":user.email_address}}</u>
          <br />
          <span style="font-weight: bold;">Email</span>

          <br />
          <br />
          <u>{{user.contact_number == null ? " ":user.contact_number}}</u>
          <br />
          <span style="font-weight: bold;">Number</span>
          <br />
          <br />
          <u>{{ user.dob == null ? " ":user.gender}}</u>
          <br />
          <span style="font-weight: bold;">Gender</span>
          <br />
          <br />
          <u>{{ user.dob == null ? " ":user.dob}}</u>
          <br />
          <span style="font-weight: bold;">Date Of Birth</span>
          <br />
          <br />
          <u>{{ user.complete_address}}</u>
          <!-- <u>{{ user.address1 || '' }} {{ user.address2 == null ? " ":user.address2+","}} {{ user.barangay == null ? " ":user.barangay+","}} {{ user.municipality == null ? " ":user.municipality+","}} {{ user.province == null ? " ":user.province+ ","}} {{ user.zipcode == null ? " ":user.zipcode}}</u> -->
          <br />
          <span style="font-weight: bold;">Address</span>
          <br />
          <br />
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-8">
      <div class="card">
        <div class="card-header">
          <i class="nav-icon fa fa-list-alt"></i> More Info
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-4" style="text-align:center;">
              <u
                style="cursor:pointer;"
                @click="goToTribe(user.tribe_id)"
              >{{ user.tribe_name || '' }}</u>
              <br />
              <span style="font-weight: bold;">Tribe Name</span>
            </div>
            <div class="col-sm-4" style="text-align:center;">
              <u
                style="cursor:pointer;"
                @click="reloadProfile(user.tribe_leader)"
              >{{ user.tribe_leader_name || '' }}</u>
              <br />
              <span style="font-weight: bold;">Tribe Leader</span>
            </div>
            <div class="col-sm-4" style="text-align:center;">
              <u
                style="cursor:pointer;"
                @click="reloadProfile(user.mentor)"
              >{{ user.mentor_name || '' }}</u>
              <br />
              <span style="font-weight: bold;">Mentor</span>
            </div>
            <div class="col-sm-4" style="text-align:center;">
              <u>{{ user.label || '' }}</u>
              <br />
              <span style="font-weight: bold;">Label</span>
            </div>
            <div class="col-sm-4" style="text-align:center;">
              <u>{{ user.spiritual_dob || '' }}</u>
              <br />
              <span style="font-weight: bold;">Spiritual Birthday</span>
            </div>
            <div class="col-sm-4" style="text-align:center;">
              <u>{{ user.category_name || '' }}</u>
              <br />
              <span style="font-weight: bold;">Categories</span>
            </div>
            <div class="col-sm-4" style="text-align:center;">
              <u>{{ user.specializedministry_name || '' }}</u>
              <br />
              <span style="font-weight: bold;">Specialized Ministry</span>
            </div>
            <div class="col-sm-4" style="text-align:center;">
              <u>{{ user.progress_name || '' }}</u>
              <br />
              <span style="font-weight: bold;">Progress</span>
            </div>
            <div class="col-sm-4" style="text-align:center;">
              <u>{{ user.lifeclass_name || '' }}</u>
              <br />
              <span style="font-weight: bold;">Life Class</span>
            </div>
            <br />
            <br />
            <div class="col-sm-12">
              <hr />
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a
                    class="nav-link active"
                    id="flocks-tab"
                    data-toggle="tab"
                    href="#flocks"
                    role="tab"
                    aria-controls="flocks"
                    aria-selected="true"
                  >Flocks</a>
                </li>
                <li class="nav-item">
                  <a
                    class="nav-link"
                    id="Attended-tab"
                    data-toggle="tab"
                    href="#Attended"
                    role="tab"
                    aria-controls="Attended"
                    aria-selected="false"
                  >Attended Activities</a>
                </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                <div
                  class="tab-pane fade show active"
                  id="flocks"
                  role="tabpanel"
                  aria-labelledby="flocks-tab"
                >
                  <div class="card" style="border:none; padding:0px; margin-bottom:5px;">
                    <div class="card-body" style="padding:0px; margin:0px;">
                      <div
                        style="float:right; margin-left: 5px;"
                        class="search-wrapper panel-heading"
                      >
                        <input
                          class="form-control"
                          v-model="inputSearch"
                          ref="search"
                          type="text"
                          placeholder="Search"
                          v-focus
                        />
                      </div>
                      <button
                        @click="showMembers()"
                        class="btn btn-sm btn-info"
                        style="float:right"
                      >
                        <i class="fa fa-plus"></i> Flock
                      </button>
                    </div>
                  </div>
                  <div class="card">
                    <!-- <div class="card-header">
                      
                    </div>-->
                    <div class="card-body" style="padding:0px;">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th></th>
                              <th>Name</th>
                              <th>Number</th>
                              <th>Category</th>
                              <th>Tribe</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(member) in flockSearchResult" :key="member.id">
                              <td @click="reloadProfile(member.id)" style="cursor:pointer">
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
                              <td
                                @click="reloadProfile(member.id)"
                                style="cursor:pointer"
                              >{{member.full_name}}</td>
                              <td
                                @click="reloadProfile(member.id)"
                                style="cursor:pointer"
                              >{{ member.contact_number == null ? " ":member.contact_number }}</td>
                              <td
                                @click="reloadProfile(member.id)"
                                style="cursor:pointer"
                              >{{ member.category_name == null ? " ":member.category_name }}</td>
                              <td
                                @click="reloadProfile(member.id)"
                                style="cursor:pointer"
                              >{{ member.tribe_name == null ? " ":member.tribe_name }}</td>
                              <td>
                                <button
                                  class="btn btn-sm btn-danger"
                                  v-on:click="removeEntry(member.id)"
                                >
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
                <div
                  class="tab-pane fade"
                  id="Attended"
                  role="tabpanel"
                  aria-labelledby="Attended-tab"
                >
                  <div class="card">
                    <!-- <div class="card-header">
                      <i class="nav-icon fa fa-list-alt"></i> Reports
                    </div>-->
                    <div class="card-body" style="padding:0px">
                      <div class="table-responsive">
                        <table class="table table-striped text-center">
                          <thead>
                            <tr>
                              <th>Event</th>
                              <th>Location</th>
                              <th>Date</th>
                              <th>Time</th>
                              <th>Description</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(report) in reports" :key="report.id">
                              <td>{{ report.title }}</td>
                              <td>{{ report.location }}</td>
                              <td>{{ report.date }}</td>
                              <td>{{ report.time }}</td>
                              <td>{{ report.description }}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
  </div>
</template>
<script>
export default {
  mounted() {
    let app = this;
    let id = app.$route.params.id;
    app.reloadProfile(id);
  },
  data: function() {
    return {
      url: "/uploads/image_icon.png",
      userId: 0,
      photo_name: "",
      reports: [],
      flocks: [],
      members: [],
      availability: [],
      unavailability: [],
      inputSearch: "",
      inputSearchMessage: "",
      user: {
        // id: null,
        // name: "",
        // address1: "",
        // dob: "",
        // photo: "default.png",
        // email: "",
        // password: "",
        // created_at: "",
        // updated_at: ""
      }
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
    flockSearchResult: function() {
      var self = this;
      return this.flocks.filter(item => {
        return (
          (
            item.full_name +
            " " +
            item.contact_number +
            " " +
            item.category_name +
            " " +
            item.tribe_name +
            " " +
            item.mentor_name +
            " "
          )
            .toLowerCase()
            .indexOf(this.inputSearch.toLowerCase()) > -1
        );
      });
    }
  },
  methods: {
    reloadProfile(id) {
      let app = this;
      app.userId = id;
      // this.$router.push({ name: "UserMemberProfile", params: { id: id } });
      app.loadProfile(id);
      app.loadFlocks(id);
      app.loadReports(id);
    },
    loadProfile(id) {
      let app = this;
      axios
        .get("/auth/member/profile/" + id)
        .then(function(resp) {
          // console.log(resp.data);
          app.user = resp.data.user;

          if (app.user.category) {
            var category_name = [];
            var category_split = app.user.category.split(",");

            $.each(category_split, function(key1, value1) {
              $.each(resp.data.categories, function(key2, value2) {
                if (value1 == value2.value) {
                  category_name.push(value2.text);
                }
              });
            });
            app.user.category_name = category_name.join(",");
          }
          // the convertion of ...ties to ...try.name hahahahahaha
          if (app.user.specializedministry) {
            var specializedministry_name = [];
            var specializedministry_split = app.user.specializedministry.split(",");

            $.each(specializedministry_split, function(key1, value1) {
              $.each(resp.data.specializedministries, function(key2, value2) {
                if (value1 == value2.value) {
                  specializedministry_name.push(value2.text);
                }
              });
            });
            app.user.specializedministry_name = specializedministry_name.join(",");
          }


          
        })
        .catch(function() {
          alert("Could not load your profile");
        });
    },
    loadFlocks(id) {
      let app = this;
      axios
        .get("/auth/member/flock/" + id)
        .then(function(resp) {
          app.flocks = resp.data.flocks;

          $.each(app.flocks, function(key, value) {
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
              app.flocks[key].category_name = category_name.join(",");
            }
          });

          app.availability = [];
          app.unavailability = [];
          $.each(resp.data.flocks, function(key, value) {
            app.availability.push(value.id);
          });
          app.unavailability = app.availability;
        })
        .catch(function() {
          alert("Could not load your profile");
        });
    },
    loadReports(id) {
      let app = this;
      axios
        .get("/auth/member/report/" + id)
        .then(function(resp) {
          app.reports = resp.data.records;
        })
        .catch(function() {
          alert("Could not load your profile");
        });
    },
    goToTribe(id) {
      this.$router.push({ name: "UserMemberList", params: { id: id } });
    },
    showMembers() {
      var app = this;
      this.$modal.show("add-members");
      app.memberList();
      //this.$refs.search.focus();
    },
    hide() {
      this.$modal.hide("add-members");
    },
    memberList() {
      var app = this;
      // app.isLoading = true;
      axios
        .get("/auth/member/list")
        .then(function(resp) {
          app.members = resp.data.records;
          // app.isLoading = false;

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
    saveData: function() {
      var app = this;
      var availability = app.availability.join(","); // assuming your are saving the availability as a string in the user data
      console.log(availability);
      if (!availability) {
        alert("Please Select.");
        return;
      }
      var formData = new FormData();
      formData.append("mentor_id", app.userId);
      formData.append("member_id", availability);
      axios
        .post("/auth/member/mentor/update", formData)
        .then(function(resp) {
          // console.log(resp);
          // app.$router.push("/main/activity/list");
          app.loadFlocks(app.userId);
          app.memberList();
          app.hide();
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not update..");
          app.hide();
        });
    },
    removeEntry(id) {
      var app = this;
      //console.log("Id: " + id + " index:" + mainIndex);

      if (confirm("Do you really want to remove it?")) {
        axios
          .delete("/auth/member/flock/remove/" + id)
          .then(function(resp) {
            var index = app.flocks
              .map(x => {
                return x.id;
              })
              .indexOf(id);

            app.flocks.splice(index, 1);

            //app.data.tribe_member = app.reports.length;
            app.availability = [];
            app.unavailability = [];
            $.each(app.flocks, function(key, value) {
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
<style type="text/css">
.profile-img {
  max-width: 150px;
  width: 100px;
  height: 100px;
  border: 5px solid #fff;
  border-radius: 100%;
  box-shadow: 0 2px 2px rgba(0, 0, 0, 0.3);
}
.table-responsive {
  height: 70vh;
  overflow-x: auto;
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