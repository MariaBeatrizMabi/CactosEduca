<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Chart from 'chart.js/auto';

const chartRef = ref(null);
const chartInstance = ref(null);
const chartData = ref([]);
const userType = ref('');

const fetchData = async () => {
  let url;
  try {
    if (userType.value === 'admin') {
      url = '/ManagementSchool/all';
      const response = await axios.get(url);
      chartData.value = response.data.map(cityData => ({
        nameValue: cityData.city,
        value: cityData.schools.length
      }));
    } else if (userType.value === 'school' || userType.value === 'teacher') {
      url = '/ClassSchool';
      const response = await axios.get(url);
      chartData.value = response.data.map(classData => ({
        nameValue: classData.name,
        value: classData.students_in_class.length
      }));
    }
    updateChart();
  } catch (error) {
    console.error("Error fetching data: ", error);
  }
};

const createChart = () => {
  const ctx = chartRef.value?.getContext('2d');
  if (!ctx) {
    console.error('Canvas context not found.');
    return;
  }

  if (chartInstance.value) {
    chartInstance.value.destroy();
  }

  chartInstance.value = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: chartData.value.map(item => item.nameValue),
      datasets: [{
        label: 'Quantidade de Escolas',
        data: chartData.value.map(item => item.value),
        backgroundColor: ["#0D5413", "#76AA3B", "#FFCB00", "#FF5C00", "#008BD0", "#FF0000", "#9747FF"],
        borderWidth: 0,
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false, 
        },
        tooltip: {
          callbacks: {
            label: function (tooltipItem) {
              return `${tooltipItem.label}: ${tooltipItem.raw}`;
            }
          }
        }
      },
      scales: {
        x: {
          beginAtZero: true,
          title: {
            display: false,
          },
          ticks: {
            maxRotation: 90,
            minRotation: 45
          }
        },
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Quantidade'
          }
        }
      }
    }
  });
};

const getUserType = async () => {
  try {
    const response = await axios.get('/loginUser');
    userType.value = response.data.type;
    await fetchData();  
    createChart();
  } catch (error) {
    console.error("Error fetching user type: ", error);
  }
};

const updateChart = () => {
  if (chartInstance.value) {
    chartInstance.value.data.labels = chartData.value.map(item => item.nameValue);
    chartInstance.value.data.datasets[0].data = chartData.value.map(item => item.value);
    chartInstance.value.update();
  } else {
    console.error("Chart instance is not defined");
  }
};

onMounted(() => {
  getUserType();
});
</script>

<template>
  <div class="chart-container">
    <canvas ref="chartRef"></canvas>
  </div>
</template>

<style scoped>
.chart-container {
  width: 100%;
  height: 250px;
}
</style>
