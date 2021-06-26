<template>
  <div class="row">
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-header">
          <i class="nav-icon fa fa-user"></i> Activity Profile
          <router-link
            :to="{name: 'ActivityProfileEdit', params: {id: userId}}"
            style="float:right;"
            class="btn btn-sm btn-info"
          >
            <i class="fa fa-pencil"></i>
          </router-link>
        </div>
        <div class="card-body text-center">
          <u>{{ data.title || ''}}</u>
          <br />
          <span style="font-weight: bold;">Title</span>
          <br />
          <br />
          <u>{{data.location || ''}}</u>
          <br />
          <span style="font-weight: bold;">Location</span>

          <br />
          <br />
          <u>{{data.date || ''}}</u>
          <br />
          <span style="font-weight: bold;">Date</span>
          <br />
          <br />
          <u>{{ convert12Hours(data.date +" "+data.time) || '' }}</u>
          <br />
          <span style="font-weight: bold;">Time</span>
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
          Attendees
          <div style="float:right" class="search-wrapper panel-heading col-sm-4">
            <input
              class="form-control"
              v-model="inputSearchAttendee"
              type="text"
              placeholder="Search"
            />
          </div>
          <button style="float:right;" @click="show()" class="btn btn-sm btn-info">
            <i class="fa fa-plus"></i>
          </button>
        </div>
        <div class="card-body" style="padding:0px">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th></th>
                  <th>Name</th>
                  <th>Number</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(member, index) in searchResult2" :key="member.id">
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
                  <td
                    @click="goToProfile(member.id)"
                    style="cursor:pointer"
                  >{{ member.last_name == null ? " ":member.last_name+","}} {{ member.first_name == null ? " ":member.first_name}} {{ member.name_extension == null ? " ":member.name_extension}} {{ member.middle_name == null ? " ":member.middle_name}}</td>
                  <td
                    @click="goToProfile(member.id)"
                    style="cursor:pointer"
                  >{{ member.contact_number == null ? " ":member.contact_number }}</td>
                  <td>
                    <a
                      href="#"
                      class="btn btn-sm btn-danger"
                      v-on:click="deleteEntry(member.id, index)"
                    >
                      <i class="fa fa-trash"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <modal name="add-attendies" height="auto" :scrollable="true">
      <div class="card" style="height:80vh">
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
        <div class="card-body" style="padding:0px; height:50vh;">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th></th>
                  <th>Name</th>
                  <th>Number</th>
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
                  <td
                    @click="goToProfile(member.id)"
                    style="cursor:pointer"
                  >{{ member.last_name == null ? " ":member.last_name+","}} {{ member.first_name == null ? " ":member.first_name}} {{ member.name_extension == null ? " ":member.name_extension}} {{ member.middle_name == null ? " ":member.middle_name}}</td>
                  <td
                    @click="goToProfile(member.id)"
                    style="cursor:pointer"
                  >{{ member.contact_number == null ? " ":member.contact_number }}</td>
                  <td>
                    <input
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
    var app = this;
    app.loadActivity();
    app.memberList();
  },
  data: function() {
    return {
      userId: 0,
      reports: [],
      inputSearchMessage: "",
      inputSearchAttendee: "",
      members: [],
      availability: [],
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
      }
    };
  },
  computed: {
    searchResult: function() {
      var self = this;
      return this.members.filter(item => {
        return (
          (
            item.first_name +
            " " +
            item.last_name +
            " " +
            item.name_extension +
            " " +
            item.middle_name +
            " " +
            item.contact_number +
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
            item.first_name +
            " " +
            item.last_name +
            " " +
            item.name_extension +
            " " +
            item.middle_name +
            " " +
            item.contact_number +
            " "
          )
            .toLowerCase()
            .indexOf(this.inputSearchAttendee.toLowerCase()) > -1
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
      this.$router.push({ name: "UserMemberProfile", params: { id: id } });
    },
    loadActivity() {
      let app = this;
      let id = app.$route.params.id;
      app.userId = id;
      axios
        .get("/auth/activity/profile/" + id)
        .then(function(resp) {
          // console.log(resp.data);
          app.data = resp.data.user;
          app.reports = resp.data.records;
        })
        .catch(function() {
          alert("Could not load your profile");
        });
    },
    show() {
      this.$modal.show("add-attendies");
    },
    hide() {
      this.$modal.hide("add-attendies");
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
      formData.append("activity_id", app.userId);
      formData.append("member_id", availability);
      axios
        .post("/auth/activity/storeattendees", formData)
        .then(function(resp) {
          console.log(resp);
          // app.$router.push("/main/activity/list");
          app.loadActivity();
          app.hide();
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not create activity");
          app.hide();
        });
    },
    memberList() {
      var app = this;
      app.isLoading = true;
      axios
        .get("/auth/member/list")
        .then(function(resp) {
          app.members = resp.data.records;
          app.isLoading = false;
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not load members");
          app.isLoading = false;
        });
    },
    deleteEntry(id, index) {
      if (confirm("Do you really want to delete it?")) {
        var app = this;
        axios
          .delete("/auth/activity/deleteattendee/" + id + "/" + app.userId)
          .then(function(resp) {
            app.reports.splice(index, 1);
          })
          .catch(function(resp) {
            alert("Could not delete!");
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
</style>