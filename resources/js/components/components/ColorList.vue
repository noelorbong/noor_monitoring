<template>
  <div class="dropdown">
    <button
      ref="dropdowninput"
      class="form-control"
    ></button>
    <div v-show="inputValue" class="dropdown-list">
      <div
        @click="selectItem(item)"
        v-for="item in searchResult"
        :key="item.name"
        class="dropdown-item"
      >
        <i
          :style="'color:' + item.color"
          class="fa fa-circle"
          aria-hidden="true"
        ></i>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios"
export default {
  data() {
    return {
      selectedItem: {},
      inputValue: "",
      itemList: [],
      apiLoaded: false,
      colors: [
        "#0024ff",
        "#fe0000",
        "#ff9c00",
        "#f5ff00",
        "#3cf10e",
        "#3cf10e",
        "#01ffe5",
        "#c500ff",
        "#ff009c",
      ],
    }
  },
  computed: {
  },
  mounted() {
  },
  methods: {
 
    resetSelection() {
      this.selectedItem = {}
      this.$nextTick(() => this.$refs.dropdowninput.focus())
      this.$emit("on-item-reset")
    },
    selectItem(theItem) {
      this.selectedItem = theItem
      this.inputValue = ""
      this.$emit("on-item-selected", theItem)
    },
    itemVisible(item) {
      let currentName = item.name.toLowerCase()
      let currentInput = this.inputValue.toLowerCase()
      return currentName.includes(currentInput)
    },
  },
}
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
