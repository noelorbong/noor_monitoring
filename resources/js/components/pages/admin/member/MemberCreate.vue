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
        <i class="nav-icon fa fa-plus"></i> Member Add
      </div>
      <div class="card-body" style="padding:30px; margin:0;">
        <form autocomplete="off" v-on:submit="saveForm()">
          <div class="profile">
            <label for="file-input">
              <img class="profile_image" v-if="url" :src="url" alt="Image Icon" />
            </label>
            <input
              style="display: none;"
              @change="onFileChange($event)"
              id="file-input"
              type="file"
            />
          </div>
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
          </div>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="date">Date of Birth</label>
              <input
                type="date"
                class="form-control"
                v-model="member.dob"
                placeholder="Date of Birth"
              />
            </div>
            <div class="form-group col-md-4">
              <label for="date">Spiritual Birthday</label>
              <input
                type="date"
                class="form-control"
                v-model="member.spiritual_dob"
                placeholder="Date of Birth"
              />
            </div>
            <div class="form-group col-md-4">
              <label for="Gender">Gender</label>
              <select class="form-control" v-model="member.gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="grade_level">Email Address</label>
              <input
                type="email"
                class="form-control"
                v-model="member.email_address"
                placeholder="e.g., juandelacruz@gmail.com"
              />
            </div>
            <div class="form-group col-md-6">
              <label for="section">Contact Number</label>
              <input
                type="text"
                class="form-control"
                v-model="member.contact_number"
                placeholder="e.g. 092734569473"
              />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="category">Category</label>
              <multi-select
                :options="options"
                :selected-options="items"
                placeholder="select categories"
                @select="onSelect"
              ></multi-select>
              <!-- <select class="form-control" v-model="member.category">
                <option
                  v-for="category in categories"
                  :key="category.id"
                  v-bind:value="category.id"
                >{{ category.category_name }}</option>
              </select>-->
            </div>
            <div class="form-group col-md-3">
              <label for="section">Tribe</label>
              <select class="form-control" v-model="member.tribe">
                <option
                  v-for="tribe in tribes"
                  :key="tribe.id"
                  v-bind:value="tribe.id"
                >{{ tribe.tribe_name }}</option>
              </select>
            </div>
            <div class="form-group col-md-3">
              <label for="section">Mentor</label>
              <input type="hidden" v-model="member.mentor" />
              <input
                type="text"
                class="form-control"
                v-on:keyup="showMembers"
                v-model="member.mentor_name"
                placeholder="E.g. Jim Enrico Balboa"
              />
              <!-- <select class="form-control" v-model="member.mentor">
                <option value="0">None</option>
                <option
                  v-for="mentor in mentors"
                  :key="mentor.id"
                  v-bind:value="mentor.id"
                >{{ mentor.first_name }}</option>
              </select>-->
            </div>
            <div class="form-group col-md-3">
              <label for="section">Label</label>
              <select class="form-control" v-model="member.label">
                <option
                  v-for="label in labels"
                  :key="label.value"
                  v-bind:value="label.value"
                >{{ label.text }}</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="grade_level">Address 1</label>
              <input
                type="text"
                class="form-control"
                v-model="member.address1"
                placeholder="e.g., 123 Sampaguita St."
              />
            </div>
            <div class="form-group col-md-6">
              <label for="section">Address 2</label>
              <input
                type="text"
                class="form-control"
                v-model="member.address2"
                placeholder="e.g., B6 L11 Camilla Subd. "
              />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="grade_level">Barangay</label>
              <input
                type="text"
                class="form-control"
                v-model="member.barangay"
                placeholder="e.g., Dolores"
              />
            </div>
            <div class="form-group col-md-3">
              <label for="section">Municipality/City</label>
              <input
                type="text"
                class="form-control"
                v-model="member.municipality"
                placeholder="e.g., San Fernando "
              />
            </div>
            <div class="form-group col-md-3">
              <label for="section">Province/State/Country</label>
              <input
                type="text"
                class="form-control"
                v-model="member.province"
                placeholder="e.g., Pampanga "
              />
            </div>
            <div class="form-group col-md-2">
              <label for="section">Zip Code</label>
              <input
                type="text"
                class="form-control"
                v-model="member.zipcode"
                placeholder="e.g., 2000 "
              />
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Save</button>
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
                  <td @click="selectMentor(member)" style="cursor:pointer">
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
                  <td @click="selectMentor(member)" style="cursor:pointer">{{member.full_name}}</td>
                  <td
                    @click="selectMentor(member)"
                    style="cursor:pointer"
                  >{{ member.contact_number == null ? " ":member.contact_number }}</td>
                  <td
                    @click="selectMentor(member)"
                    style="cursor:pointer"
                  >{{ member.category_name == null ? " ":member.category_name }}</td>
                  <td
                    @click="selectMentor(member)"
                    style="cursor:pointer"
                  >{{ member.tribe_name == null ? " ":member.tribe_name }}</td>
                  <td
                    @click="selectMentor(member)"
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
import _ from "lodash";
import { MultiSelect } from "vue-search-select";
export default {
  mounted() {
    let app = this;
    axios
      .get("/auth/options")
      .then(function(resp) {
        app.tribes = resp.data.tribes;
        app.categories = resp.data.categories;
        app.options = resp.data.categories;
      })
      .catch(function() {
        alert("Something Went Wrong");
      });

    app.memberList();
    app.getCurrentDate();
  },
  data: function() {
    return {
      url: "/uploads/image_icon.png",
      inputSearchMessage: "",
      members: [],
      availability: [],
      member: {
        photo: null,
        last_name: "",
        first_name: "",
        name_extension: "",
        middle_name: "",
        email_address: "",
        contact_number: "",
        dob: "1994-04-16",
        spiritual_dob: "",
        gender: "Male",
        category: 1,
        tribe: 1,
        mentor: 0,
        mentor_name: "",
        label: 0,
        address1: "",
        address2: "",
        barangay: "",
        municipality: "",
        province: "",
        zipcode: ""
      },
      categories: [],
      tribes: [],
      mentors: [],
      labels: [
        { text: "None", value: 0 },
        { text: "1", value: 1 },
        { text: "12", value: 12 },
        { text: "144", value: 144 },
        { text: "1728", value: 1728 },
        { text: "20,736", value: 20736 },
        { text: "248,832", value: 248832 }
      ],
      fullPage: false,
      isLoading: false,
      loader: "dots",
      loaderColor: "rgb(11, 49, 200)",
      options: [],
      searchText: "", // If value is falsy, reset searchText & searchItem
      items: [{ text: "None", value: 1 }],
      lastSelectItem: {}
    };
  },
  components: {
    MultiSelect
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
    success: function() {
      this.$alertify.success("success");
    },
    getCurrentDate() {
      var today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth() + 1;
      var yyyy = today.getFullYear();

      if (dd < 10) {
        dd = "0" + dd;
      }

      if (mm < 10) {
        mm = "0" + mm;
      }
      today = yyyy + "-" + mm + "-" + dd;
      // console.log(today);
      this.member.spiritual_dob = today;
    },
    saveForm() {
      event.preventDefault();
      var app = this;
      var category_list = [];
      $.each(app.items, function(key, value) {
        category_list.push(value.value);
      });
      var categories = category_list.join(",");
      // console.log(categories);
      //return;
      app.isLoading = true;
      var member = app.member;
      var formData = new FormData();

      formData.append("photo", app.member.photo);
      formData.append("last_name", app.member.last_name);
      formData.append("first_name", app.member.first_name);
      formData.append("name_extension", app.member.name_extension);
      formData.append("middle_name", app.member.middle_name);
      formData.append("email_address", app.member.email_address);
      formData.append("contact_number", app.member.contact_number);
      formData.append("dob", app.member.dob);
      formData.append("spiritual_dob", app.member.spiritual_dob);

      formData.append("gender", app.member.gender);
      formData.append("category", categories);
      formData.append("tribe", app.member.tribe);
      formData.append("mentor", app.member.mentor);
      formData.append("label", app.member.label);

      formData.append("address1", app.member.address1);
      formData.append("address2", app.member.address2);
      formData.append("barangay", app.member.barangay);
      formData.append("municipality", app.member.municipality);
      formData.append("province", app.member.province);
      formData.append("zipcode", app.member.zipcode);
      axios
        .post("/auth/member/store", formData)
        .then(function(resp) {
          // console.log(resp);

          app.isLoading = false;
          app.resetForm();
          app.success();
        })
        .catch(function(resp) {
          console.log(resp);
          app.isLoading = false;
          alert("Could not create member");
        });
    },
    resetForm() {
      var app = this;
      app.url = "/uploads/image_icon.png";
      app.member.photo = null;
      app.member.last_name = "";
      app.member.first_name = "";
      app.member.name_extension = "";
      app.member.middle_name = "";
      app.member.email_address = "";
      app.member.contact_number = "";
      app.member.dob = "1994-04-16";
      app.member.gender = "Male";
      app.member.category = 1;
      app.member.tribe = 1;
      app.member.mentor = 0;
      app.member.mentor_name = "";
      app.member.label = 0;
      app.member.address1 = "";
      app.member.address2 = "";
      app.member.barangay = "";
      app.member.municipality = "";
      app.member.province = "";
      app.member.zipcode = "";
      app.items = [{ text: "None", value: 1 }];
    },

    onFileChange(e) {
      var app = this;
      app.member.photo = e.target.files[0];
      // console.log(app.member.photo);
      app.url = URL.createObjectURL(app.member.photo);
    },

    selectMentor(member) {
      var app = this;

      app.member.mentor = member.id;
      app.member.mentor_name = member.full_name;
      app.hide();
    },
    showMembers() {
      var app = this;

      app.inputSearchMessage = app.member.mentor_name;
      this.$modal.show("members-modal");
      //this.$refs.search.focus();
    },
    hide() {
      this.$modal.hide("members-modal");
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
    onSelect(items, lastSelectItem) {
      this.items = items;
      this.lastSelectItem = lastSelectItem;
    },
    // deselect option
    reset() {
      this.items = []; // reset
    },
    // select option from parent component
    selectFromParentComponent() {
      this.items = _.unionWith(this.items, [this.options[0]], _.isEqual);
    }
  }
};
</script>
<style>
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
.table-striped thead th,
.table-striped thead td {
  text-align: center;
}

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