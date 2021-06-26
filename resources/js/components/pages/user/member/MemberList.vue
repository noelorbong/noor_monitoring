<template>
  <div class="vld-parent">
    <loading
      :active.sync="isLoading"
      :can-cancel="true"
      :is-full-page="fullPage"
      :loader="loader"
      :color="loaderColor"
    ></loading>
    <div class="card">
      <div class="card-header">
        <i class="fa fa-list"></i> Member list
        <div style="float: right" class="search-wrapper panel-heading col-sm-4">
          <input
            class="form-control"
            type="text"
            v-model="inputSearchMessage"
            placeholder="Search"
          />
        </div>
      </div>
      <div class="card-body" style="padding: 0px">
        <div class="table-responsive">
          <table class="table table-striped" style="padding: 0px">
            <thead>
              <tr>
                <th></th>
                <th>Name</th>
                <th>Number</th>
                <th>Email</th>
                <th>DoB</th>
                <th>Spi. DoB</th>
                <th>Gender</th>
                <th>Category</th>
                <th>Tribe</th>
                <th>Mentor</th>
                <th>Label</th>

                <th>Address</th>
                <th>Datetime</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(member, index) in searchResult" :key="member.id">
                <td @click="goToProfile(member.id)" style="cursor: pointer">
                  <!-- <router-link :to="{name: 'MemberProfile', params: {id: member.id}}"> -->
                  <img
                    v-if="member.photo"
                    :src="'uploads/' + member.photo"
                    :id="index"
                    style="border-radius: 50%"
                    height="40"
                    width="40"
                  />
                  <img
                    v-else
                    :src="'uploads/image_icon.png'"
                    style="border-radius: 50%"
                    height="40"
                    width="40"
                  />
                  <!-- </router-link> -->
                </td>
                <td @click="goToProfile(member.id)" style="cursor: pointer">
                  {{ member.full_name }}
                  <!-- {{ member.last_name == null ? " ":member.last_name+","}} {{ member.first_name == null ? " ":member.first_name}} {{ member.name_extension == null ? " ":member.name_extension}} {{ member.middle_name == null ? " ":member.middle_name}} -->
                </td>
                <td @click="goToProfile(member.id)" style="cursor: pointer">
                  {{
                    member.contact_number == null ? " " : member.contact_number
                  }}
                </td>
                <td
                  @click="goToProfile(member.id)"
                  class="truncate"
                  style="cursor: pointer; max-width: 100px"
                >
                  {{
                    member.email_address == null ? " " : member.email_address
                  }}
                </td>
                <td
                  @click="goToProfile(member.id)"
                  style="cursor: pointer; width: 120px"
                >
                  {{ member.dob == null ? " " : member.dob }}
                </td>
                <td
                  @click="goToProfile(member.id)"
                  style="cursor: pointer; width: 120px"
                >
                  {{
                    member.spiritual_dob == null ? " " : member.spiritual_dob
                  }}
                </td>

                <td
                  @click="goToProfile(member.id)"
                  style="cursor: pointer; width: 100px"
                >
                  {{ member.gender == null ? " " : member.gender }}
                </td>

                <td
                  @click="goToProfile(member.id)"
                  style="cursor: pointer; width: 100px"
                >
                  {{
                    member.category_name == null ? " " : member.category_name
                  }}
                </td>

                <td
                  @click="goToProfile(member.id)"
                  style="cursor: pointer; width: 100px"
                >
                  {{ member.tribe_name == null ? " " : member.tribe_name }}
                </td>

                <td
                  @click="goToProfile(member.id)"
                  style="cursor: pointer; width: 100px"
                >
                  {{ member.mentor_name == null ? " " : member.mentor_name }}
                </td>
                <td
                  @click="goToProfile(member.id)"
                  style="cursor: pointer; width: 100px"
                >
                  {{ member.label == null ? " " : member.label }}
                </td>

                <td
                  @click="showMoreLess(member.id)"
                  class="truncate"
                  :id="'truncate_' + member.id"
                  style="cursor: pointer; max-width: 100px"
                >
                  {{ member.address1 == null ? " " : member.address1 + "," }}
                  {{ member.address2 == null ? " " : member.address2 + "," }}
                  {{ member.barangay == null ? " " : member.barangay + "," }}
                  {{
                    member.municipality == null
                      ? " "
                      : member.municipality + ","
                  }}
                  {{ member.province == null ? " " : member.province + "," }}
                  {{ member.zipcode == null ? " " : member.zipcode }}
                </td>
                <td
                  @click="goToProfile(member.id)"
                  style="cursor: pointer; width: 100px"
                >
                  {{ member.created_at }}
                </td>
                <td>
                  <button
                    class="btn btn-sm btn-danger"
                    v-on:click="deleteEntry(member.id)"
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
  </div>
</template>
<script>
export default {
  data() {
    return {
      inputSearchMessage: "",
      members: [],
      fullPage: false,
      isLoading: true,
      loader: "dots",
      loaderColor: "rgb(11, 49, 200)",
    };
  },
  created: function () {
    var app = this;
    this.$http({
      url: "auth/user",
      method: "GET",
    }).then(
      (res) => {
        // app.UserInfo = res.data.data;
        // app.UserName = res.data.data.email;
        var tribe_id = res.data.data.tribe;
        // console.log();
        app.getMemberList(tribe_id);
      },
      () => {
        this.has_error = true;
      }
    );
  },
  computed: {
    searchResult: function () {
      var self = this;
      return this.members.filter((item) => {
        return (
          (
            item.full_name +
            " " +
            item.address1 +
            " " +
            item.address2 +
            " " +
            item.dob +
            " " +
            item.gender +
            " " +
            item.category_name +
            " " +
            item.tribe_name +
            " " +
            item.mentor_name +
            " " +
            item.label +
            " " +
            item.email_address +
            " " +
            item.contact_number +
            " " +
            item.barangay +
            " " +
            item.municipality +
            " " +
            item.province +
            " " +
            item.zipcode +
            " " +
            item.created_at
          )
            .toLowerCase()
            .indexOf(this.inputSearchMessage.toLowerCase()) > -1
        );
      });
    },
  },
  methods: {
    goToProfile(id) {
      this.$router.push({ name: "UserMemberProfile", params: { id: id } });
    },
    showMoreLess(index) {
      var element = document.getElementById("truncate_" + index);
      element.classList.toggle("truncate");
    },
    getMemberList(tribe_id) {
      var app = this;
      app.isLoading = true;
      axios
        .get("/auth/" + tribe_id + "/member/list")
        .then(function (resp) {
          app.members = resp.data.records;

          $.each(app.members, function (key, value) {
            if (value.category) {
              var category_name = [];
              var category_split = value.category.split(",");

              $.each(category_split, function (key1, value1) {
                $.each(resp.data.categories, function (key2, value2) {
                  if (value1 == value2.value) {
                    category_name.push(value2.text);
                  }
                });
              });
              app.members[key].category_name = category_name.join(",");
            }
          });

          app.isLoading = false;
        })
        .catch(function (resp) {
          console.log(resp);
          alert("Could not load members");
          app.isLoading = false;
        });
    },
    deleteEntry(id) {
      if (confirm("Do you really want to delete it?")) {
        var app = this;
        axios
          .delete("/auth/member/delete/" + id)
          .then(function (resp) {
            var index = app.members
              .map((x) => {
                return x.id;
              })
              .indexOf(id);
            app.members.splice(index, 1);
          })
          .catch(function (resp) {
            alert("Could not delete Member");
          });
      }
    },
  },
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
  height: 70vh;
  overflow-x: auto;
}
.table {
  margin: 0px;
  padding: 0px;
  text-align: center;
  min-width: 1400px;
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