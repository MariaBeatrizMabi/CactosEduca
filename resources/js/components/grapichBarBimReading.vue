<script setup>
import { onMounted, ref } from 'vue';
import Chart from 'chart.js/auto';
import axios from "axios";

const chartRef = ref(null);
const readingStatuses = ref([]);

const translationMap = {
  null: 'não informado',
  'not_reader': 'Não leitor',
  'syllable_reader': 'Leitor de sílabas',
  'word_reader': 'Leitor de palavras',
  'sentence_reader': 'Leitor de frases',
  'no_fluent_text_reader': 'Leitor de texto sem fluência',
  'fluent_text_reader': 'Leitor de texto com fluência'
};

const fetchSchools = async () => {
  try {
    const response = await axios.get('/ManagementSchool/all');
    console.log(response);

    const statusCount = {
      not_reader: 0,
      syllable_reader: 0,
      word_reader: 0,
      sentence_reader: 0,
      no_fluent_text_reader: 0,
      fluent_text_reader: 0,
    };

    response.data.forEach(city => {
      city.schools.forEach(school => {
        school.exams.forEach(exam => {
          if (statusCount[exam.reading] !== undefined) {
            statusCount[exam.reading]++;
          }
        });
      });
    });

    readingStatuses.value = Object.entries(statusCount);

    const ctx = chartRef.value?.getContext('2d');
    if (!ctx) {
      console.error('Contexto do canvas não encontrado.');
      return;
    }

    const data = {
      labels: readingStatuses.value.map(status => translationMap[status[0]]), 
      datasets: [{
        label: 'Quantidade de alunos',
        backgroundColor: ["#0D5413", "#76AA3B", "#FFCB00", "#FF5C00", "#008BD0", "#FF0000", "#9747FF"],
        borderWidth: 0,
        data: readingStatuses.value.map(status => status[1]),
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
                return readingStatuses.value.map(status => ({
                  text: translationMap[status[0]],
                  fillStyle: chart.data.datasets[0].backgroundColor[readingStatuses.value.indexOf(status)],
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
              text: 'Quantidade de alunos'
            }
          },
          x: {
            ticks: {
              display: false
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