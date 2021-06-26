<template>
  <div class="dropdown">
    <input
      ref="dropdowninput"
      v-model.trim="inputValue"
      class="form-control"
      type="text"
      placeholder="Find or Create tag"
      @click="inputValue=true"
    />
    <!-- <div class="dropdown-selected">
      <img :src="selectedItem.flag" class="dropdown-item-flag" />
      {{ selectedItem.name }}
    </div>-->
    <div v-show="inputValue" class="dropdown-list">
      <div
        @click="selectItem(item)"
        v-for="item in searchResult"
        :key="item.name"
        class="dropdown-item"
      >
        <i :style="'color:' + item.color" class="fa fa-circle" aria-hidden="true"></i>
        {{ item.name }}
      </div>
      <div @click="saveTag()" v-if="searchResult.length == 0 && inputValue" class="dropdown-item">
        <!-- <img :src="item.color" class="dropdown-item-flag" /> -->
        <i style="color:#1877f2;" class="fa fa-plus-circle"></i>
        {{ inputValue }}
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      selectedItem: {},
      inputValue: "",
      itemList: [],
      apiLoaded: false,
      apiUrl: "/auth/customer/tag/list/" + this.$auth.user().store_name,
    };
  },
  computed: {
    searchResult: function () {
      var self = this;
      return this.itemList.filter((item) => {
        return (
          item.name.toLowerCase().indexOf(this.inputValue.toLowerCase()) > -1
        );
      });
    },
  },
  mounted() {
    let app = this;
    if (app.$store.state.customer_tags) {
      if (app.$store.state.customer_tags.length > 0) {
        app.itemList = app.$store.state.customer_tags;
        // JSON.parse(
        //   JSON.stringify()
        // )
      } else {
        app.loadCustomerTags();
      }
    } else {
      app.loadCustomerTags();
    }
  },
  methods: {
    saveTag() {
      event.preventDefault();
      var app = this;
      app.isLoading = true;
      var customer = app.customer;
      var formData = new FormData();

      formData.append("token", app.strRandom(30));
      formData.append("user_id", this.$auth.user().id);
      formData.append("store_name", this.$auth.user().store_name);
      formData.append("name", app.inputValue);
      formData.append("color", "blue");
      axios
        .post("/auth/customer/tag/store", formData)
        .then(function (resp) {
          app.$store.commit(
            "pushCustomerTag",
            JSON.parse(JSON.stringify(resp.data.customer_tag))
          );
          //   app.itemList.push(resp.data.customer_tag)
          app.selectedItem = resp.data.customer_tag;
          app.selectItem(JSON.parse(JSON.stringify(resp.data.customer_tag)));
          app.success("Tag Added");
        })
        .catch(function (resp) {
          console.log(resp);
          app.isLoading = false;
          alert("Could not create tag");
        });
    },
    success: function (text) {
      this.$alertify.success(text);
    },
    resetSelection() {
      this.selectedItem = {};
      this.$nextTick(() => this.$refs.dropdowninput.focus());
      this.$emit("on-item-reset");
    },
    selectItem(theItem) {
      this.selectedItem = theItem;
      this.inputValue = "";
      this.$emit("on-item-selected", theItem);
    },
    itemVisible(item) {
      let currentName = item.name.toLowerCase();
      let currentInput = this.inputValue.toLowerCase();
      return currentName.includes(currentInput);
    },
    loadCustomerTags() {
      var app = this;
      axios
        .get(app.apiUrl)
        .then(function (resp) {
          app.itemList = resp.data.records;
          app.$store.commit("changeCustomerTags", app.itemList);
          //   app.$store.commit(
          //     "changeCustomerTags",
          //     JSON.parse(JSON.stringify(app.itemList))
          //   )
        })
        .catch(function (resp) {
          console.log(resp);
          alert("Could not customer tags");
          app.isLoading = false;
        });
    },
    strRandom(len) {
      let text = "";
      let chars =
        "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
      for (let i = 0; i < len; i++) {
        text += chars.charAt(Math.floor(Math.random() * chars.length));
      }

      return text;
    },
  },
};
</script>

<style>
.dropdown {
  position: relative;
  width: 100%;
  max-width: 400px;
  margin: 0 auto;
}

.dropdown-input,
.dropdown-selected {
  width: 100%;
  padding: 10px 16px;
  border: 1px solid transparent;
  background: #edf2f7;
  line-height: 1.5em;
  outline: none;
  border-radius: 8px;
}
.dropdown-input:focus,
.dropdown-selected:hover {
  background: #fff;
  border-color: #e2e8f0;
}
.dropdown-input::placeholder {
  opacity: 0.7;
}
.dropdown-selected {
  font-weight: bold;
  cursor: pointer;
}

.dropdown-list {
  position: absolute;
  width: 100%;
  max-height: 500px;
  margin-top: 4px;
  overflow-y: auto;
  background: #ffffff;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
    0 4px 6px -2px rgba(0, 0, 0, 0.05);
  border-radius: 8px;
  z-index: 999;
}

.dropdown-item {
  display: flex;
  width: 100%;
  padding: 11px 16px;
  cursor: pointer;
}

.dropdown-item:hover {
  background: #edf2f7;
}

.dropdown-item-flag {
  max-width: 24px;
  max-height: 18px;
  margin: auto 12px auto 0px;
}
</style>
