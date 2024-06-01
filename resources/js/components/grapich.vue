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

let cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
cursor.lineY.set("visible", false);

// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
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
  let colors = ["#0D5413", "#76AA3B", "#FFCB00", "#FF5C00", "#008BD0", "#FF0000", "#9747FF"];
  return am5.color(colors[columnIndex % colors.length]);
});

series.columns.template.adapters.add("stroke", function (stroke, target) {
  return chart.get("colors").getIndex(series.columns.indexOf(target));
});

// Set data
let data = [{
  nameValue: "Fortaleza",
  value: 5
}, {
  nameValue: "Aquiraz",
  value: 3
}, {
  nameValue: "Aracati",
  value: 4
}, {
  nameValue: "Pacajus",
  value: 3
}, {
  nameValue: "Crato",
  value: 7
}, {
  nameValue: "Quixadá",
  value: 8
}, {
  nameValue: "Itaitinga",
  value: 2
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