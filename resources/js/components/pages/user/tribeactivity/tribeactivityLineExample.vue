<script>
import { Line, Bar } from "vue-chartjs";
import { CustomTooltips } from "@coreui/coreui-plugin-chartjs-custom-tooltips";
import { hexToRgba } from "@coreui/coreui/dist/js/coreui-utilities";
export default {
  components: {
    hexToRgba,
    CustomTooltips
  },
  data() {
    return {
      g_labels: [],
      g_values: []
    };
  },
  extends: Bar,
  mounted() {},
  created: function() {
    var app = this;
    let id = app.$route.params.id;
    axios
      .get("/auth/tribeactivity/list/" + id)
      .then(function(resp) {
        let labels = [];
        let values = [];
        $.each(resp.data.records, function(key, value) {
          labels.push(value.date + " " + value.time);
          values.push(value.tribe_attendee);
        });
        app.g_labels = labels.reverse();
        app.g_values = values.reverse();
        app.loadGraph();
      })
      .catch(function(resp) {
        console.log(resp);
        // alert("Could not load Activity");
      });
  },
  methods: {
    loadGraph() {
      this.renderChart(
        {
          labels: this.g_labels,
          datasets: [
            {
              label: "Attendees",
              borderColor: "rgba(2, 203, 255, 1)",
              backgroundColor: "rgba(2, 203, 255, 0.2)",
              data: this.g_values,
              borderWidth: 1
            }
          ]
        },
        {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            yAxes: [
              {
                ticks: {
                  beginAtZero: true
                }
              }
            ],
            xAxes: [
              {
                ticks: {
                  callback: function(value) {
                    return value; //truncate
                  }
                }
              }
            ]
          },
          tooltips: {
            enabled: true,
            mode: "label",
            callbacks: {
              title: function(tooltipItems, data) {
                var idx = tooltipItems[0].index;
                return "Date: " + data.labels[idx]; //do something with title
              },
              label: function(tooltipItem, data) {
                var label = data.datasets[tooltipItem.datasetIndex].label || "";

                if (label) {
                  label += ": ";
                }
                label += Math.round(tooltipItem.yLabel * 100) / 100;
                return label;
              }
            }
          }
        }
      );
    }
  }
};
</script>