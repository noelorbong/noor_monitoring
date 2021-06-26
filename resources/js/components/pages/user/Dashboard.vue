<template>
  <div class="row">
    <div style="margin-bottom: 1rem;" class="col-sm-4">
      <div class="card">
        <div class="card-header">
          <i class="nav-icon fa fa-info-circle"></i>
          New Member
          <select
            style="float:right;"
            class="col-sm-3 form-control"
            v-model="day"
            @change="selectDays()"
          >
            <option
              v-for="day_option in day_options"
              :key="day_option.value"
              v-bind:value="day_option.value"
            >{{ day_option.text }}</option>
          </select>
        </div>
        <div class="card-body text-center">
          <div class="row">
            <div class="col-sm-12">
              <h3 style>{{member_count}}</h3>

              <span style="font-weight: bold;">New member for the last {{day}} days</span>
              <button class="btn-sm btn btn-info" @click="show()">View</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div style="margin-bottom: 1rem;" class="col-sm-4" v-for="(data) in datas" :key="data.id">
      <div class="card">
        <div class="card-header">
          <i class="nav-icon fa fa-envira"></i>
           <a v-if="data.main == 2" @click="goToActivity(data.id)" style="cursor:pointer">
          {{ data.title || ''}} </a>
           <a v-else @click="goToMainActivity(data.id)" style="cursor:pointer">
          {{ data.title || ''}} </a>
        </div>
        <div class="card-body">
          <div>
            <span style="font-weight: bold;">Name:</span>
            {{ data.title || ''}}
            <br />
          </div>
          <div>
            <span style="font-weight: bold;">Date:</span>
            {{ data.date || ''}}
            <br />
          </div>
          <div>
            <span style="font-weight: bold;">Time:</span>
            {{ convert12Hours(data.date + " "+data.time) || ''}}
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
      </div>
    </div>

    <modal name="new-members" height="auto" :scrollable="true">
      <div class="card" style="margin:0; padding: 0;">
        <div class="card-header">
          New Members
          <div style="float:right" class="search-wrapper panel-heading col-sm-4">
            <input
              class="form-control"
              v-model="inputSearchMessage"
              type="text"
              placeholder="Search"
            />
          </div>
          <button style="float:right; margin-right:5px;" class="btn btn-sm btn-success">
            <download-excel
              :data="searchResult"
              :fields="json_fields"
              worksheet="My Worksheet"
              type="csv"
              :name="filename"
            >
              <i class="fa fa-file-excel-o"></i> Export
            </download-excel>
          </button>
        </div>
        <div class="card-body" style="margin:0px; padding:0">
          <div class="table-responsive-modal">
            <table class="table table-striped" style="padding:0px; margin:0px;">
              <thead>
                <tr>
                  <th></th>
                  <th>Name</th>
                  <th>Category</th>
                  <!-- <th>Tribe</th> -->
                  <th>Mentor</th>
                  <th>Spi. Birthday</th>
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
                  >{{ member.category_name == null ? " ":member.category_name }}</td>
                  <!-- <td
                    @click="goToProfile(member.id)"
                    style="cursor:pointer"
                  >{{ member.tribe_name == null ? " ":member.tribe_name }}</td> -->
                  <td
                    @click="goToProfile(member.id)"
                    style="cursor:pointer"
                  >{{ member.mentor_name == null ? " ":member.mentor_name }}</td>
                  <td
                    @click="goToProfile(member.id)"
                    style="cursor:pointer"
                  >{{ member.spiritual_dob == null ? " ":member.spiritual_dob }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </modal>
  </div>
</template>
<script>
export default {
  data() {
    return {
      inputSearchMessage: "",
      tribe_id:0,
      day: 15,
      filename: "",
      day_options: [],
      member_count: 0,
      members: [],
      datas: [],
      fullPage: false,
      isLoading: true,
      loader: "dots",
      loaderColor: "rgb(11, 49, 200)",
      json_fields: {
        Name: "full_name",
        Number: "contact_number",
        Category: "category_name",
        Tribe: "tribe_name",
        Mentor: "mentor_name",
        Label: "label",
        "Spiritual Birthday": "spiritual_dob",
        Address: "complete_address"
      }
    };
  },
  created: function() {
    var app = this;
   
    var app = this;
      this.$http({
        url: "auth/user",
        method: "GET"
      }).then(
        res => {
          // app.UserInfo = res.data.data;
          // app.UserName = res.data.data.email;
          var l_tribe_id = res.data.data.tribe;
          app.tribe_id = l_tribe_id;
          app.dayOptions();
          app.selectDays();
          app.upcomingActivities();
        },
        () => {
          this.has_error = true;
        }
      );

    
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
            item.tribe_name +
            " " +
            item.mentor_name
          )
            .toLowerCase()
            .indexOf(this.inputSearchMessage.toLowerCase()) > -1
        );
      });
    }
  },
  methods: {
    dayOptions() {
      let app = this;
      var days_in_year = 365;
      var started_day = 1;
      var days = {
        text: 1,
        value: 1
      };

      for (started_day = 1; days_in_year >= started_day; started_day++) {
        days = {
          text: started_day,
          value: started_day
        };
        app.day_options.push(days);
      }
    },
    goToProfile(id) {
      this.$router.push({
        name: "UserMemberProfile",
        params: {
          id: id
        }
      });
    },
    goToActivity(id) {
      this.$router.push({
        name: "TribeactivityProfile",
        params: {
          id: id
        }
      });
    },
    goToMainActivity(id) {
      this.$router.push({
        name: "UserActivityProfile",
        params: {
          id: id
        }
      });
    },

    
    selectDays() {
      var app = this;
      var days = app.day;
      app.isLoading = true;
      axios
        .get("/auth/user/member/new/count/" + days+"/"+app.tribe_id)
        .then(function(resp) {
          app.member_count = resp.data.records.count;
        })
        .catch(function(resp) {
          console.log(resp);
        });
    },
    upcomingActivities(){
       var app = this;
        app.isLoading = true;
        axios
          .get("/auth/tribeactivity/upcoming/"+app.tribe_id)
          .then(function(resp) {
            app.datas = resp.data.records;
            app.isLoading = false;
          })
          .catch(function(resp) {
            console.log(resp);
            alert("Could not load Activity");
            app.isLoading = false;
          });
    },
    viewNew() {
      var app = this;
      var days = app.day;
      app.isLoading = true;
      axios
        .get("/auth/user/member/new/" + days+"/"+app.tribe_id)
        .then(function(resp) {
          app.members = resp.data.records;
          app.isLoading = false;

          $.each(app.members, function(key, value) {
            if (value.category) {
              var category_name = [];
              var category_split = value.category.split(",");
              app.filename = "last_" + days + "_new_member.xls";
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
        });
    },
    show() {
      this.$modal.show("new-members");
      this.viewNew();
    },
    hide() {
      this.$modal.hide("new-members");
    },
    showMoreLess(index) {
      var element = document.getElementById("truncate_" + index);
      element.classList.toggle("truncate");
    },
    deleteEntry(id, index) {
      if (confirm("Do you really want to delete it?")) {
        var app = this;
        axios
          .delete("/auth/activity/main/delete/" + id)
          .then(function(resp) {
            app.datas.splice(index, 1);
          })
          .catch(function(resp) {
            alert("Could not delete Activity");
          });
      }
    },
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