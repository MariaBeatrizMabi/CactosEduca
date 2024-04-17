<script>
import * as am5 from '@amcharts/amcharts5';
import * as am5xy from '@amcharts/amcharts5/xy';
import am5themes_Animated from '@amcharts/amcharts5/themes/Animated';



export default {
  name: 'Chart',
  mounted() {
    function createChart(div, chartData) {

/* Chart code */
// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
// let root = am5.Root.new("chartdiv");
  var root = am5.Root.new(div);
// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
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

let series2 = chart.series.push(am5xy.ColumnSeries.new(root, {
      name: "Series 2",
      xAxis: xAxis,
      yAxis: yAxis,
      valueYField: "value2",
      sequencedInterpolation: true,
      categoryXField: "nameValue",
      tooltip: am5.Tooltip.new(root, {
        labelText: "{categoryX}: {value2}"
      })
}));

let series3 = chart.series.push(am5xy.ColumnSeries.new(root, {
      name: "Series 3",
      xAxis: xAxis,
      yAxis: yAxis,
      valueYField: "value3",
      sequencedInterpolation: true,
      categoryXField: "nameValue",
      tooltip: am5.Tooltip.new(root, {
        labelText: "{categoryX}: {value2}"
      })
}));

let series4 = chart.series.push(am5xy.ColumnSeries.new(root, {
      name: "Series 4",
      xAxis: xAxis,
      yAxis: yAxis,
      valueYField: "value4",
      sequencedInterpolation: true,
      categoryXField: "nameValue",
      tooltip: am5.Tooltip.new(root, {
        labelText: "{categoryX}: {value2}"
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

series2.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });
series2.columns.template.adapters.add("fill", function (fill, target) {
  let columnIndex = series2.columns.indexOf(target);
  let colors = ["#FFCB00", "#9747FF", "#0D5413", "#008BD0", "#FF5C00", "#FF0000", "#76AA3B"];
  return am5.color(colors[columnIndex % colors.length]);
});


series2.columns.template.adapters.add("stroke", function (stroke, target) {
  return chart.get("colors").getIndex(series2.columns.indexOf(target));
});

series3.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });
series3.columns.template.adapters.add("fill", function (fill, target) {
  let columnIndex = series3.columns.indexOf(target);
  let colors = ["#FF0000", "#008BD0", "#76AA3B", "#FF5C00", "#FFCB00", "#0D5413", "#9747FF"];
  return am5.color(colors[columnIndex % colors.length]);
});


series3.columns.template.adapters.add("stroke", function (stroke, target) {
  return chart.get("colors").getIndex(series3.columns.indexOf(target));
});

series4.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });
series4.columns.template.adapters.add("fill", function (fill, target) {
  let columnIndex = series4.columns.indexOf(target);
  let colors = ["#9747FF", "#FF5C00", "#FFCB00", "#FF0000", "#76AA3B", "#008BD0", "#0D5413"];
  return am5.color(colors[columnIndex % colors.length]);
});


series4.columns.template.adapters.add("stroke", function (stroke, target) {
  return chart.get("colors").getIndex(series4.columns.indexOf(target));
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

series2.data.setAll(data);
series2.appear(1000);

series3.data.setAll(data);
series3.appear(1000);

series4.data.setAll(data);
series4.appear(1000);

chart.appear(1000, 100);

}
}
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