<template>
  <div>
    <div class="card">
      <div class="card-header">
        <i class="nav-icon fa fa-plus"></i> Edit Tribe
      </div>
      <div class="card-body" style="padding:30px; margin:0;">
        <form autocomplete="off" v-on:submit="saveForm()">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="name">Tribe Name</label>
              <input
                type="text"
                class="form-control"
                v-model="data.tribe_name"
                placeholder="e.g., Pstr. Ronald Tribe"
                required
              />
            </div>
            <div class="form-group col-md-6">
              <label for="name">Tribe Leader</label>
              <input type="hidden" v-model="data.tribe_leader" />
              <input
                type="text"
                class="form-control"
                v-on:keyup="showMembers"
                v-model="data.tribe_leader_name"
                placeholder="e.g., Ronald Ocampo"
                required
              />
            </div>

            <div class="form-group col-md-6">
              <label for="name">
                Username
                <span class="help-block" v-if="has_error">* Username Already Taken</span>
              </label>
              <input
                type="text"
                class="form-control"
                v-model="data.username"
                placeholder="e.g., ptrronald"
                minlength="7"
                required
              />
            </div>
            <div class="form-group col-md-6">
              <label for="name">Password</label>
              <input
                type="text"
                class="form-control"
                v-model="data.password"
                minlength="3"
                placeholder="e.g., ptrronald123"
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

    <modal name="members-modal" height="auto" :scrollable="true">
      <div class="card" style="height:80vh">
        <div class="card-header">
          Member list
          <div style="float:right" class="search-wrapper panel-heading col-sm-4">
            <input
              class="form-control"
              v-model="inputSearchMessage"
              ref="search"
              type="text"
              placeholder="Search"
              v-focus
            />
          </div>
          <router-link
            :to="{name: 'MemberCreate'}"
            style="float:right;"
            class="btn btn-sm btn-info"
          >
            <i class="fa fa-plus"></i> Member
          </router-link>
        </div>
        <div class="card-body" style="padding:0px; height:50vh;">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th></th>
                  <th>Name</th>
                  <th>Number</th>
                  <th>Category</th>
                  <th>Tribe</th>
                  <th>Mentor</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(member) in searchResult" :key="member.id">
                  <td @click="selectTribeLeader(member)" style="cursor:pointer">
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
                  <td @click="selectTribeLeader(member)" style="cursor:pointer">{{member.full_name}}</td>
                  <td
                    @click="selectTribeLeader(member)"
                    style="cursor:pointer"
                  >{{ member.contact_number == null ? " ":member.contact_number }}</td>
                  <td
                    @click="selectTribeLeader(member)"
                    style="cursor:pointer"
                  >{{ member.category_name == null ? " ":member.category_name }}</td>
                  <td
                    @click="selectTribeLeader(member)"
                    style="cursor:pointer"
                  >{{ member.tribe_name == null ? " ":member.tribe_name }}</td>
                  <td
                    @click="selectTribeLeader(member)"
                    style="cursor:pointer"
                  >{{ member.mentor_name == null ? " ":member.mentor_name }}</td>
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
  mounted() {
    let app = this;
    let id = app.$route.params.id;
    app.userId = id;
    axios
      .get("/auth/tribe/edit/" + id)
      .then(function(resp) {
        app.data = resp.data.record;
      })
      .catch(function() {
        alert("Could not load your profile");
      });

    app.memberList();
  },
  // directives: {
  //   focus: {
  //     // directive definition
  //     inserted: function(el) {
  //       el.focus();
  //     }
  //   }
  // },
  data: function() {
    return {
      has_error: false,
      inputSearchMessage: "",
      members: [],
      data: {
        tribe_name: null,
        tribe_leader_name: "",
        tribe_leader: null,
        username: "",
        password: "",
        description: ""
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
            item.tribe_name +
            " " +
            item.mentor_name +
            " "
          )
            .toLowerCase()
            .indexOf(this.inputSearchMessage.toLowerCase()) > -1
        );
      });
    }
  },
  methods: {
    selectTribeLeader(member) {
      var app = this;

      app.data.tribe_leader = member.id;
      app.data.tribe_leader_name = member.full_name;
      app.hide();
    },
    showMembers() {
      var app = this;

      app.inputSearchMessage = app.data.tribe_leader_name;
      this.$modal.show("members-modal");
    },
    hide() {
      this.$modal.hide("members-modal");
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
    saveForm() {
      event.preventDefault();
      var app = this;
      var data = app.data;
      axios
        .post("/auth/tribe/update", data)
        .then(function(resp) {
          if (resp.data == "success") {
            app.$router.push("/main/tribe/profile/" + app.userId);
            app.has_error = false;
          } else {
            app.has_error = true;
          }
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not update tribe");
        });
    }
  }
};
</script>
<style>
.card-header > .btn {
  margin-top: 1px !important;
}
</style>