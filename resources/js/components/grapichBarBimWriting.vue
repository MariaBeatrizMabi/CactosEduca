<script setup>
import { onMounted, ref } from 'vue';
import Chart from 'chart.js/auto';
import axios from 'axios';

const chartRef = ref(null);
const writingStatuses = ref([]);

const translationMap = {
  null: 'não informado',
  'pre_syllabic': 'Pré-silábico',
  'syllabic': 'Silábico',
  'alphabetical_syllabic': 'Silábico alfabético',
  'alphabetical': 'Alfabético'
};

const fetchSchools = async () => {
  try {
    const response = await axios.get('/ManagementSchool/all');

    const statusCount = {
      pre_syllabic: 0,
      syllabic: 0,
      alphabetical_syllabic: 0,
      alphabetical: 0,
    };

    response.data.forEach(city => {
      city.schools.forEach(school => {
        school.exams.forEach(exam => {
          if (statusCount[exam.writing] !== undefined) {
            statusCount[exam.writing]++;
          }
        });
      });
    });

    writingStatuses.value = Object.entries(statusCount);

    const ctx = chartRef.value?.getContext('2d');
    if (!ctx) {
      console.error('Contexto do canvas não encontrado.');
      return;
    }

    const data = {
      labels: writingStatuses.value.map(status => translationMap[status[0]]), 
      datasets: [{
        label: 'Quantidade de escolas',
        backgroundColor: ["#0D5413", "#76AA3B", "#FFCB00", "#FF5C00", "#008BD0", "#FF0000", "#9747FF"],
        borderWidth: 0,
        data: writingStatuses.value.map(status => status[1]),
      }]
    };

    new Chart(ctx, {
      type: 'bar',
      data: data,
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: true,
            position: 'bottom',
            labels: {
              generateLabels: function (chart) {
                return writingStatuses.value.map(status => ({
                  text: translationMap[status[0]],
                  fillStyle: chart.data.datasets[0].backgroundColor[writingStatuses.value.indexOf(status)],
                }));
              }
            }
          },
          tooltip: {
            callbacks: {
              label: function (tooltipItem) {
                const label = tooltipItem.label;
                return `${label}: ${tooltipItem.raw}`;
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Quantidade de escolas'
            }
          },
          x: {
            ticks: {
              display: true
            }
          }
        }
      }
    });

  } catch (error) {
    console.error('Error fetching schools:', error);
  }
};

onMounted(() => {
  fetchSchools();
});
</script>

<template>
  <div>
    <canvas ref="chartRef" id="myChart"></canvas>
  </div>
</template>
