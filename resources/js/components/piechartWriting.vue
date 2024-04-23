<script>
import * as am5 from '@amcharts/amcharts5';
import * as am5xy from '@amcharts/amcharts5/xy';
import am5themes_Animated from '@amcharts/amcharts5/themes/Animated';
import * as am5percent from '@amcharts/amcharts5/percent'; // Importe o módulo de gráficos percentuais

export default {
  name: 'Chart',
  mounted() {
let root = am5.Root.new("pieWriting");

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);

// Create chart
// https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/

let chart = root.container.children.push(
  am5percent.PieChart.new(root, {
    endAngle: 270,
    
  })
);

// Create series
// https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
let series = chart.series.push(
  am5percent.PieSeries.new(root, {
    valueField: "value",
    categoryField: "nameValue",
    endAngle: 270,
    fillField: "sliceColor"
  })
);

series.states.create("hidden", {
  endAngle: -90
});

// Set data
// https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
series.data.setAll([{
  nameValue: "Fortaleza",
  value: 5,      
  sliceColor: "#0D5413"
}, {
  nameValue: "Aquiraz",
  value: 3,
  sliceColor: "#76AA3B"
}, {
  nameValue: "Aracati",
  value: 4,
  sliceColor: "#FFCB00"
}, {
  nameValue: "Pacajus",
  value: 3,
  sliceColor: "#FF5C00"
}, {
  nameValue: "Crato",
  value: 7,
  sliceColor: "#008BD0"
}, {
  nameValue: "Quixadá",
  value: 8,
  sliceColor: "#FF0000"
}, {
  nameValue: "Itaitinga",
  value: 2,
  sliceColor: "#9747FF"
}]);



series.slices.template.setAll({
  fillOpacity: 1,
  stroke: am5.color(0xffffff),
  strokeWidth: 1,
});


let assignedColors = (numberOfColors) => {
        let colors = [];
        for (let i = 0; i < numberOfColors; i++) {
          colors.push(am5.color(this.membersPerCountryData[i].countryColor));
        }
        return colors;
      };

      var colorSet = am5.ColorSet.new(root, {
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

series.set('colors', colorSet);

series.labels.template.setAll({
    fill: am5.color(0x253138)
})
 
series.appear(1000, 100);
},
};
</script>

<template>
  <div class="hello" id="pieWriting" ref="pieWriting">
  </div>
</template>


<style scoped>
.hello {
  width: 100%;
  height: 250px;
}
</style>