<template>
  <div class="form-group col-12">
    <div>
      <label for="name">Collections</label>
      <span
        @click="$modal.show('collections-modal')"
        style="cursor:pointer; float:right; color:#1877f2;"
      >Manage Collections</span>
    </div>
    <div class="tag-input" style="display:inline-block; margin-bottom:5px;">
      <div v-for="collection in collections_push" :key="collection.name" class="tag-output">
        <i :style="'color:' + collection.color" class="fa fa-circle"></i>
        {{ collection.name }}
        <span
          style="cursor: pointer;"
          @click="removeCollection(collection)"
        >x</span>
      </div>
    </div>
    <div>
      <input
        class="dropdown-toggle form-control"
        data-toggle="dropdown"
        aria-expanded="false"
        style="width:100%; padding: 5px;"
        type="text"
        v-model="search_input_collection"
        placeholder="Select or Create Collection."
      />
      <div class="dropdown-menu" style="width: 92%; max-height: 30vh; overflow-y: auto;">
        <a
          v-for="(collection, index) in search_collections"
          :key="index"
          @click="addCollection(collection)"
          class="dropdown-item"
          style="width:100%; padding: 5px;"
        >
          <i :style="'color:' + collection.color" class="fa fa-circle"></i>
          {{ collection.name }}
        </a>

        <a
          v-if="
            search_collections.length == 0 &&
              search_input_collection.length != 0
          "
          class="dropdown-item"
          style="width:100%; padding: 5px !important;"
          href="#"
          @click="
            product_collection = {
              name: search_input_collection,
              color: '#0024ff',
            }
            saveProductCollection()
          "
        >
          <span style="margin: 3px; color:blue;" class="fa fa-plus"></span>
          {{ search_input_collection }}
        </a>
      </div>
    </div>

    <modal name="collections-modal" height="auto" :width="310" :scrollable="true">
      <div v-if="!booleans.edit_collection" style="padding:20px 20px 20px 20px">
        <h4>
          Collections
          <span
            style="float:right; font-size:11px; cursor:pointer"
            v-if="!booleans.show_manage_input_collection"
            @click="booleans.show_manage_input_collection = true"
            class="add-tag"
          >
            <i class="fa fa-plus-circle"></i> Add Collection
          </span>
          <span
            style="float:right; font-size:11px; cursor:pointer"
            v-else
            @click="booleans.show_manage_input_collection = false"
            class="add-tag"
          >
            <i class="fa fa-minus-circle"></i> Add Collection
          </span>
        </h4>
        <div v-if="!booleans.show_manage_input_collection">
          <input
            style="margin: 3px 0px 3px 0px; width:100%"
            class="form-control"
            type="text"
            v-model="search_input_collection"
            placeholder="Search"
          />
          <div class="customer-tag-table">
            <table style="width:100%; padding:10px;">
              <tbody>
                <tr v-for="collection in search_collections" :key="collection.id">
                  <td @click="addCollection(collection)">
                    <i :style="'color:' + collection.color" class="fa fa-circle"></i>
                  </td>
                  <td
                    @click="addCollection(collection)"
                    style="text-align:left;"
                  >{{ collection.name }}</td>
                  <td style="text-align:right">
                    <i
                      style="color:blue; cursor:pointer"
                      @click="
                        product_collection = JSON.parse(
                          JSON.stringify(collection)
                        )
                        product_collection_bkp = JSON.parse(
                          JSON.stringify(collection)
                        )
                        booleans.edit_collection = true
                      "
                      class="fa fa-pencil"
                    ></i>
                    <i
                      style="color:red; cursor:pointer"
                      @click="deleteCollection(collection.id, collection)"
                      class="fa fa-trash"
                    ></i>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div v-else>
          <form>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label for="Gender">Color</label>
                <div class="btn-group">
                  <button
                    class="btn btn-sm dropdown-toggle"
                    type="button"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i :style="'color:' + product_collection.color" class="fa fa-circle"></i>
                  </button>
                  <div style="width: 130px;" class="dropdown-menu">
                    <a
                      style="padding:2px !important; width:fit-content;"
                      v-for="(color, index) in collection_colors"
                      :key="index"
                      @click="
                        product_collection.color = JSON.parse(
                          JSON.stringify(color)
                        )
                      "
                    >
                      <i :style="'color:' + color" class="fa fa-circle"></i>
                    </a>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-9">
                <label for="date">
                  Collection Name
                  <span style="color:red; font-size:9px;">
                    {{
                    collectionExist == true ? " * Collection Name Exist" : ""
                    }}
                  </span>
                </label>
                <input
                  type="text"
                  class="form-control"
                  v-model="product_collection.name"
                  placeholder="e.g Pinatubo Farm"
                  required
                />
              </div>
              <div class="form-group col-12">
                <a
                  style="float:right"
                  @click="saveProductCollection()"
                  class="btn btn-sm btn-success"
                >
                  <i class="fa fa-plus"></i> Add
                </a>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div v-else style="padding:20px 20px 20px 20px">
        <h4>
          Edit Collection
          <span
            style="float:right; font-size:11px; cursor:pointer"
            @click="
              product_collection = {}
              booleans.edit_collection = false
            "
            class="add-tag"
          >
            <i class="fa fa-pencil"></i> Back
          </span>
        </h4>
        <div>
          <form>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label for="Gender">Color</label>
                <div class="btn-group">
                  <button
                    class="btn btn-sm dropdown-toggle"
                    type="button"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i :style="'color:' + product_collection.color" class="fa fa-circle"></i>
                  </button>
                  <div style="width: 130px;" class="dropdown-menu">
                    <a
                      style="padding:2px !important; width:fit-content;"
                      v-for="(color, index) in collection_colors"
                      :key="index"
                      @click="
                        product_collection.color = JSON.parse(
                          JSON.stringify(color)
                        )
                      "
                    >
                      <i :style="'color:' + color" class="fa fa-circle"></i>
                    </a>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-9">
                <label for="date">
                  Collection Name
                  <span style="color:red; font-size:9px;">
                    {{
                    collectionExist == true &&
                    product_collection_bkp.name != product_collection.name
                    ? " * Collection Name Exist"
                    : ""
                    }}
                  </span>
                </label>
                <input
                  type="text"
                  class="form-control"
                  v-model="product_collection.name"
                  placeholder="e.g. Known You"
                  required
                />
              </div>
              <div class="form-group col-12">
                <a style="float:right" @click="updateCollection()" class="btn btn-sm btn-success">
                  <i class="fa fa-pencil"></i> Update
                </a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </modal>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: ["collection_ids", "isReset"],
  data() {
    return {
      // Product Collection Start
      booleans: {
        show_input_collection: false,
        show_manage_input_collection: false,
        edit_collection: false,
      },
      product_collection: { color: "#0024ff", name: "" },
      product_collection_bkp: {},
      collection_colors: [
        "#0024ff",
        "#fe0000",
        "#ff9c00",
        "#ababab",
        "#3cf10e",
        "#01ffe5",
        "#c500ff",
        "#ff009c",
      ],
      collections: [],
      collections_push: [],

      search_input_collection: "",
      selected_collection: { color: "#1877f2" },
      emit_collections: "",
      // Product collection End
    };
  },
  computed: {
    //collection start
    search_collections: function () {
      var app = this;
      return app.collections.filter((item) => {
        return (
          item.name
            .toLowerCase()
            .indexOf(app.search_input_collection.toLowerCase()) > -1
        );
      });
    },
    collectionExist: function () {
      let app = this;
      var local_product_collections = app.collections.filter(
        (i) => i.name.toLowerCase() == app.product_collection.name.toLowerCase()
      );
      if (local_product_collections.length >= 1) {
        return true;
      } else {
        return false;
      }
    },
    //collections end
  },
  watch: {
    // isReset: {
    // the callback will be called immediately after the start of the observation
    //    immediate: true,
    //   handler(val, oldVal) {
    //     console.log("Oldval: " + oldVal);
    //     this.isReset = false;
    //     // do your stuff
    //   },
    // },
    // whenever question changes, this function will run
    isReset: function (newQuestion, oldQuestion) {
      // console.log(newQuestion);
      // console.log(oldQuestion);
      if (newQuestion) {
        this.collections_push = [];
        // this.isReset = false;
      }
    },
  },
  mounted() {
    let app = this;
    if (app.$store.state.product_collections) {
      if (app.$store.state.product_collections.length > 0) {
        app.collections = JSON.parse(
          JSON.stringify(app.$store.state.product_collections)
        );

        if (app.collection_ids) {
          $.each(app.collection_ids.split(","), function (key, value) {
            var index2 = app.collections
              .map((x) => {
                return x.id;
              })
              .indexOf(parseInt(value));

            if (app.collections[index2]) {
              app.collections_push.push(app.collections[index2]);
            }
          });
        }
      } else {
        app.loadProductCollections();
      }
    } else {
      app.loadProductCollections();
    }
  },
  methods: {
    success: function (text) {
      this.$alertify.success(text);
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
    //collection functions start
    addCollection(collection) {
      let app = this;
      var collection_length = app.collections_push.filter(
        (i) => i.id == collection.id
      ).length;

      if (collection_length <= 0) {
        app.collections_push.push(collection);
        app.emitData();
      }
    },
    deleteCollection(id, collection) {
      if (confirm("Do you really want to delete it?")) {
        var app = this;
        app.isLoading = true;
        axios
          .delete(
            "/auth/product/collection/delete/" +
              id +
              "/" +
              app.$auth.user().store_name +
              "/" +
              app.$auth.user().id +
              "/" +
              collection.token
          )
          .then(function (resp) {
            app.isLoading = false;
            var index = app.collections
              .map((x) => {
                return x.id;
              })
              .indexOf(id);
            app.collections.splice(index, 1);

            var index2 = app.collections_push
              .map((x) => {
                return x.id;
              })
              .indexOf(collection.id);

            if (app.collections_push[index2]) {
              app.collections_push.splice(index2, 1);

              app.emitData();
            }

            app.$store.commit(
              "cutProductCollection",
              JSON.parse(JSON.stringify(collection))
            );

            app.success("Successful Delete..");
          })
          .catch(function (resp) {
            app.isLoading = false;
            console.log(resp);
            alert("Could not delete Collection");
          });
      }
    },
    emitData() {
      let app = this;
      var local_data = [];

      $.each(app.collections_push, function (key, value) {
        local_data.push(value.id);
      });

      app.$emit("on-item-selected", local_data.join());
    },
    loadProductCollections() {
      var app = this;
      app.isLoading = true;
      axios
        .get("/auth/product/collection/list/" + this.$auth.user().store_name)
        .then(function (resp) {
          app.collections = resp.data.records;
          app.isLoading = false;
          app.$store.commit(
            "changeProductCollections",
            JSON.parse(JSON.stringify(app.collections))
          );

          if (app.collection_ids) {
            $.each(app.collection_ids.split(","), function (key, value) {
              var index2 = app.collections
                .map((x) => {
                  return x.id;
                })
                .indexOf(parseInt(value));

              if (app.collections[index2]) {
                app.collections_push.push(app.collections[index2]);
              }
            });
          }
        })
        .catch(function (resp) {
          console.log(resp);
          alert("Could not load product collections");
          app.isLoading = false;
        });
    },
    removeCollection(collection) {
      let app = this;
      var index = app.collections_push
        .map((x) => {
          return x.id;
        })
        .indexOf(collection.id);
      app.collections_push.splice(index, 1);

      app.emitData();
    },
    saveProductCollection() {
      var app = this;
      if (app.collectionExist) {
        return false;
      }
      event.preventDefault();

      app.isLoading = true;
      var formData = new FormData();

      formData.append("token", app.strRandom(30));
      formData.append("user_id", this.$auth.user().id);
      formData.append("store_name", this.$auth.user().store_name);
      formData.append("name", app.product_collection.name);
      formData.append("color", app.product_collection.color);
      axios
        .post("/auth/product/collection/store", formData)
        .then(function (resp) {
          app.$store.commit(
            "pushProductCollection",
            JSON.parse(JSON.stringify(resp.data.product_collection))
          );
          app.collections.push(resp.data.product_collection);
          app.addCollection(resp.data.product_collection);

          app.product_collection = { color: "#0024ff", name: "" };
          app.booleans.show_manage_input_collection = false;
          app.isLoading = false;
          app.success("Collection Added");
        })
        .catch(function (resp) {
          console.log(resp);
          app.isLoading = false;
          alert("Could not create collection");
        });
    },
    updateCollection() {
      let app = this;

      if (
        app.collectionExist &&
        app.product_collection.name != app.product_collection_bkp.name
      ) {
        return false;
      }
      app.isLoading = true;
      var formData = new FormData();

      formData.append("id", app.product_collection.id);
      formData.append("token", app.product_collection.token);
      formData.append("user_id", this.$auth.user().id);
      formData.append("store_name", this.$auth.user().store_name);
      formData.append("name", app.product_collection.name);
      formData.append("color", app.product_collection.color);
      axios
        .post("/auth/product/collection/update", formData)
        .then(function (resp) {
          app.$store.commit(
            "replaceProductCollection",
            JSON.parse(JSON.stringify(resp.data.product_collection))
          );

          var index = app.collections
            .map((x) => {
              return x.id;
            })
            .indexOf(app.product_collection.id);
          app.collections.splice(index, 1, resp.data.product_collection);

          var index2 = app.collections_push
            .map((x) => {
              return x.id;
            })
            .indexOf(app.product_collection.id);

          if (app.collections_push[index2]) {
            app.collections_push.splice(
              index2,
              1,
              resp.data.product_collection
            );
            app.emitData();
          }

          app.product_collection = { color: "#0024ff", name: "" };
          app.booleans.edit_collection = false;

          app.isLoading = false;
          app.success("Collection Updated");
        })
        .catch(function (resp) {
          console.log(resp);
          app.isLoading = false;
          alert("Could not update collection");
        });
    },
    //collection functions end
  },
};
</script>

<style>
.customer-tag-table {
  max-height: 40vh;
  overflow-y: auto;
}

.tag-output {
  border: 1px solid #eee;
  box-sizing: border-box;
  padding: 0 10px;
  border-radius: 5px;
  background: #e6e6e6;
  width: fit-content;
  float: left;
}

.add-tag {
  align-items: center;
  background-color: #e6f2ff;
  border: none;
  border-radius: 4px;
  color: #1877f2;
  font-weight: normal;
  justify-content: center;
  padding: 6px;
  width: fit-content;
}

.form-group .form-control {
  border-radius: 0px !important;
}

.form-control:focus,
select.custom-select:focus {
  border-color: #0ad251 !important;
  box-shadow: 0 1px 0 0 #0ad251 !important;
}

select option:hover,
select option:focus {
  background: #0ad251 !important;
  border-color: #0ad251 !important;
  box-shadow: 0 1px 0 0 #0ad251 !important;
}
</style>
