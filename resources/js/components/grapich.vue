<script>
import axios from 'axios';
import * as am5 from '@amcharts/amcharts5';
import * as am5xy from '@amcharts/amcharts5/xy';
import am5themes_Animated from '@amcharts/amcharts5/themes/Animated';
import { ref, onMounted, defineProps } from 'vue';

export default {
  name: 'Chart',
  data() {
    return {
      chart: null,
      series: null,
      xAxis: null,
      data: [],
      userType: ''
    };
  },
  mounted() {
    this.getUserType();
  },
  methods: {
    createChart() {
      let root = am5.Root.new("chartdiv");
      root.setThemes([am5themes_Animated.new(root)]);

      this.chart = root.container.children.push(am5xy.XYChart.new(root, {
        panX: true,
        panY: true,
        wheelX: "panX",
        wheelY: "zoomX",
        pinchZoomX: true,
        paddingLeft:0,
        paddingRight:1,
        categoryXField: "nameValue",
        tooltip: am5.Tooltip.new(root, {
          labelText: "{nameValue}"
        })
      }));

      let cursor = this.chart.set("cursor", am5xy.XYCursor.new(root, {}));
      cursor.lineY.set("visible", false);

      let xRenderer = am5xy.AxisRendererX.new(root, {
        minGridDistance: 30,
      let xRenderer = am5xy.AxisRendererX.new(root, {
        minGridDistance: 30,
        minorGridEnabled: true
      });

      xRenderer.labels.template.setAll({
        rotation: -90,
        centerY: am5.p50,
        centerX: am5.p100,
        paddingRight: 15
      });

      xRenderer.grid.template.setAll({
        location: 1
      });

      this.xAxis = this.chart.xAxes.push(am5xy.CategoryAxis.new(root, {
        maxDeviation: 0.3,
        categoryField: "nameValue",
        renderer: xRenderer,
        tooltip: am5.Tooltip.new(root, {})
      }));

      let yRenderer = am5xy.AxisRendererY.new(root, {
        strokeOpacity: 0.1
      });

      let yAxis = this.chart.yAxes.push(am5xy.ValueAxis.new(root, {
        maxDeviation: 0.3,
        renderer: yRenderer
      }));

      this.series = this.chart.series.push(am5xy.ColumnSeries.new(root, {
        name: "Series 1",
        xAxis: this.xAxis,
        yAxis: yAxis,
        valueYField: "value",
        sequencedInterpolation: true,
        categoryXField: "nameValue",
        tooltip: am5.Tooltip.new(root, {
          labelText: "{nameValue}"
        })
      }));

      this.series.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });
      this.series.columns.template.adapters.add("fill", (fill, target) => {
        let columnIndex = this.series.columns.indexOf(target);
        let colors = ["#0D5413", "#76AA3B", "#FFCB00", "#FF5C00", "#008BD0", "#FF0000", "#9747FF"];
        if (columnIndex >= 0 && columnIndex < colors.length) {
          return am5.color(colors[columnIndex % colors.length]);
        }
        return am5.color("#76AA3B");
      });

      this.series.columns.template.adapters.add("stroke", (stroke, target) => {
        let columnIndex = this.series.columns.indexOf(target);
        if (this.chart && this.chart.get("colors") && columnIndex !== -1) {
          return this.chart.get("colors").getIndex(columnIndex);
        }
        return am5.color("#000000");
      });
    },
    fetchData() {
      let url;
      if (this.userType === 'admin') {
        url = '/ManagementSchool';

        axios.get(url)
        return am5.color("#000000");
      });
    },
    fetchData() {
      let url;
      if (this.userType === 'admin') {
        url = '/ManagementSchool';

        axios.get(url)
        .then(response => {
          this.data = response.data.map(cityData => ({
            nameValue: cityData.city,
            value: cityData.schools.length
          }));
          this.updateChart();
        })
        .catch(error => {
          console.error("Error fetching data: ", error);
        });

      } else if (this.userType === 'school') {
        url = '/ClassSchool';

        axios.get(url)
        .then(response => {
          this.data = response.data.map(classData => ({
            nameValue: classData.name,
            value: classData.students_in_class.length
          }));
          this.updateChart();
        })
        .catch(error => {
          console.error("Error fetching data: ", error);
        });

      } else if (this.userType === 'teacher') {
        url = '/ClassSchool';
      } else {
        url = '/ClassSchool';
      }


    },
    getUserType() {
      axios.get('/loginUser')
        .then(response => {
          this.userType = response.data.type;
          this.createChart();
          this.fetchData();
        })
        .catch(error => {
          console.log("ERROR", error);
        });
    },
    updateChart() {
      if (this.xAxis && this.series) {
        this.xAxis.data.setAll(this.data);
        this.series.data.setAll(this.data);
        this.series.appear(1000);
        this.chart.appear(1000, 100);
      } else {
        console.error("xAxis or series is not defined");
      }
    }
  }
}
</script>

<template>
  <div class="hello" id="chartdiv">
  </div>
</template>

<style scoped>
.hello {
  width: 100%;
  height: 250px;
}
</style>
