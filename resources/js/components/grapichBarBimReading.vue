<script>
import * as am5 from '@amcharts/amcharts5';
import * as am5xy from '@amcharts/amcharts5/xy';
import am5themes_Animated from '@amcharts/amcharts5/themes/Animated';

export default {
  name: 'Chart',
  mounted() {

let root = am5.Root.new("chartdiv");

root.setThemes([
  am5themes_Animated.new(root)
]);

// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
let chart = root.container.children.push(am5xy.XYChart.new(root, {
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
// Add cursor
// https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
let cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
cursor.lineY.set("visible", true);

// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
let xRenderer = am5xy.AxisRendererX.new(root, { 
  minGridDistance: 30, 
  minorGridEnabled: true
});

xRenderer.labels.template.setAll({
  rotation: 0,
  centerY: am5.p50,
  centerX: am5.p50,
  paddingRight: 0
});

xRenderer.grid.template.setAll({
  location: 1
})

let xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
  maxDeviation: 0.3,
  categoryField: "nameValue",
  renderer: xRenderer,
  tooltip: am5.Tooltip.new(root, {})
}));

let yRenderer = am5xy.AxisRendererY.new(root, {
  strokeOpacity: 0.1
})

let yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  maxDeviation: 0.3,
  renderer: yRenderer
}));

// Create series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
let series = chart.series.push(am5xy.ColumnSeries.new(root, {
  name: "Series 1",
  xAxis: xAxis,
  yAxis: yAxis,
  valueYField: "value",
  sequencedInterpolation: true,
  categoryXField: "nameValue",
  tooltip: am5.Tooltip.new(root, {
    labelText: "{nameValue}"
  })
}));

series.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });
series.columns.template.adapters.add("fill", function (fill, target) {
  let columnIndex = series.columns.indexOf(target);
  let colors = ["#0D5413", "#76AA3B", "#FFCB00", "#0D5413", "#008BD0", "#FF0000", "#9747FF"];
  return am5.color(colors[columnIndex % colors.length]);
});

series.columns.template.adapters.add("stroke", function (stroke, target) {
  return chart.get("colors").getIndex(series.columns.indexOf(target));
});

// Set data
let data = [{
  nameValue: "1° Bimestre",
  value: 5,
  value2: 10,
  value3: 1,
  value4: 4
}, {
  nameValue: "2° Bimestre",
  value: 3,
  value2: 2,
  value3: 6,
  value4: 10
}, {
  nameValue: "3° Bimestre",
  value: 4,
  value2: 4,
  value3: 6,
  value4: 8
}, {
  nameValue: "4° Bimestre",
  value: 3,
  value2: 10,
  value3: 9,
  value4: 10
}];

xAxis.data.setAll(data);

series.data.setAll(data);
series.appear(1000);

chart.appear(1000, 100);

},
}
</script>

<template>
  <div class="hello" id="chartdiv" ref="chartdiv">
  </div>
</template>


<style scoped>
.hello {
  width: 100%;
  height: 250px;
}
</style>