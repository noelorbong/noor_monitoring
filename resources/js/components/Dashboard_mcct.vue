<template>
  <div class="row">
    <div class="col-sm-12 col-xl-4">
      <div class="card">
        <div class="card-header">
          <i class="nav-icon fa fa-list-alt"></i> Report
        </div>
        <div class="card-body">
          <div class="title">Total Report</div>
          <p class="counter">{{totalReport}}</p>
          <div class="title">Responded Report</div>
          <p class="counter">{{respondedReport}}</p>
          <div class="title">Unresponded Report</div>
          <p class="counter">{{unrespondedReport}}</p>
          <div style="text-align:center">{{currentDate}}</div>
        </div>
      </div>
    </div>
    <div v-for="(report,index) in reports" :key="report.id" class="col-sm-12 col-xl-4">
      <div class="card">
        <div class="card-header" style="padding-top: 8px;
    padding-bottom: 8px;">
          <router-link :to="{name: 'userProfile', params: {email: report.email}}">
            <img :src="'uploads/'+report.photo" style="border-radius: 50%;" height="40" width="40" />
          </router-link>
          <a
            style="float:right"
            v-if="report.responded == 0"
            href="#"
            class="btn btn-sm btn-warning"
            v-on:click="responded(report.id,1, index)"
          >Unresponded</a>
          <a
            style="float:right"
            v-else
            href="#"
            class="btn btn-sm btn-success"
            v-on:click="responded(report.id,0, index)"
          >Responded</a>
        </div>

        <div class="card-body">
          <div>
            <span style="font-weight: bold;">User Name:</span>
            {{ report.name}}
            <br />
            <span style="font-weight: bold;">Number:</span>
            {{report.number}}
            <br />
            <span style="font-weight: bold;">Email:</span>
            {{report.email}}
            <br />
            <span style="font-weight: bold;">Request Type:</span>
            {{report.title}}
            <br />
            <span style="font-weight: bold;">Quantity:</span>
            {{ report.quantity}}
            <br />
            <span style="font-weight: bold;">Latitude:</span>
            {{ report.latitude}}
            <br />
            <span style="font-weight: bold;">Longitude:</span>
            {{ report.longitude}}
            <br />
            <span style="font-weight: bold;">Created Date:</span>
            {{ report.created_at}}
            <br />
          </div>
          <p
            :id="'truncate_'+ report.id"
            @click="showMoreLess('truncate_'+report.id)"
            class="truncate"
            style="font-weight: initial; cursor:pointer; text-align: justify;"
          >
            <span style="color: black;">
              <span style="font-weight: bold;">Note:</span>
              {{ report.note }}
            </span>
          </p>
        </div>
      </div>
      <!-- end card -->
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      reportcount: 0,
      reports: [],
      totalReport: 0,
      respondedReport: 0,
      unrespondedReport: 0,
      currentDate: this.getCurrentDate()
    };
  },
  created: function() {
    var app = this;
    this.getRecords();
    setInterval(
      function() {
        app.getRecords();
      }.bind(app),
      10000
    );
  },
  computed: {},
  methods: {
    getRecords() {
      var app = this;
      axios
        .get("/report/list/")
        .then(function(resp) {
          app.reports = resp.data.records;
          app.reportcount = app.reports.length;
          app.totalReport = resp.data.total_report;
          app.respondedReport = resp.data.responded_report;
          app.unrespondedReport = resp.data.unresponded_report;
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not load users");
        });
    },
    showMoreLess(id_name) {
      // console.log(id_name);
      var elements = document.getElementById(id_name);
      elements.classList.toggle("truncate");
    },
    responded(id, status, index) {
      var consceice =
        status == 1
          ? "Do you really want to Respond it?"
          : "Do you really want to Unrespond it?";
      if (confirm(consceice)) {
        var app = this;
        axios
          .put("/report/responded/" + id + "/" + status)
          .then(function(resp) {
            app.reports[index].responded = status;
            app.reports.splice(index, 1);
          })
          .catch(function(resp) {
            alert("Could not Respond");
          });
      }
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
      console.log(today);
      return today;
      // this.startdate = today;
      // this.enddate = today;
      // this.getRecords();
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
.counter {
  font-weight: bold;
  font-size: 1.2em;
  text-align: center;
  margin: 0px;
}
.title {
  font-size: 1.5em;
  text-align: center;
  font-weight: bolder;
}
</style>