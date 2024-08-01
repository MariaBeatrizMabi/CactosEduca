<template>
  <div id="chartdivpie" class="chart-container"></div>
</template>

<script>
import axios from 'axios';
import * as am5 from '@amcharts/amcharts5';
import am5themes_Animated from '@amcharts/amcharts5/themes/Animated';
import * as am5percent from '@amcharts/amcharts5/percent';

export default {
  name: 'Chart',
  mounted() {
    this.createChart();
    this.fetchData();
  },
  methods: {
    createChart() {
      this.root = am5.Root.new("chartdivpie");

      this.root.setThemes([am5themes_Animated.new(this.root)]);

      this.chart = this.root.container.children.push(
        am5percent.PieChart.new(this.root, {
          endAngle: 270,
        })
      );

      this.series = this.chart.series.push(
        am5percent.PieSeries.new(this.root, {
          valueField: "value",
          categoryField: "nameValue",
          endAngle: 270,
          fillField: "sliceColor",
        })
      );

      this.series.slices.template.setAll({
        fillOpacity: 1,
        stroke: am5.color(0xffffff),
        strokeWidth: 1,
      });

      let colorSet = am5.ColorSet.new(this.root, {
        colors: [
          am5.color("#0D5413"),
          am5.color("#76AA3B"),
          am5.color("#FFCB00"),
          am5.color("#FF5C00"),
          am5.color("#008BD0"),
          am5.color("#FF0000"),
          am5.color("#9747FF")
        ]
      });

      this.series.set('colors', colorSet);

      this.series.labels.template.setAll({
        fill: am5.color(0x253138)
      });

      this.series.appear(1000, 100);
    },
    fetchData() {
      axios.get('/ManagementSchool')
        .then(response => {
          const data = response.data.map(item => ({
            nameValue: item.city,
            value: item.schools.length,
            sliceColor: this.assignColor(item.schools.length)
          }));

          this.series.data.setAll(data);
        })
        .catch(error => {
          console.error("Error fetching data: ", error);
        });
    },
    assignColor(value) {
      const colorMap = {
        1: "#0D5413",
        2: "#76AA3B",
        3: "#FFCB00",
        4: "#FF5C00",
        5: "#008BD0",
        6: "#FF0000",
        7: "#9747FF"
      };
      return colorMap[value] || "#000000"; 
    }
  },
  beforeDestroy() {
    if (this.root) {
      this.root.dispose();
    }
  }
};
</script>

<style scoped>
.chart-container {
  width: 100%;
  height: 250px;
}
</style>
