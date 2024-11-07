<script setup>
import { onMounted, ref } from 'vue';
import Chart from 'chart.js/auto';
import axios from 'axios';

const chartRef = ref(null);
const writingStatuses = ref([]);
const selectedFilter = JSON.parse(localStorage.getItem('selectedFilter'));
import { api } from "../services/api"

const translationMap = {
  null: 'não informado',
  'pre_syllabic': 'Pré-silábico',
  'syllabic': 'Silábico',
  'alphabetical_syllabic': 'Silábico alfabético',
  'alphabetical': 'Alfabético'
};

const fetchSchools = async () => {
  try {
    let response;

    var totalExamsQuantity = 0;

    const statusCount = {
      pre_syllabic: 0,
      syllabic: 0,
      alphabetical_syllabic: 0,
      alphabetical: 0,
    };

    if (selectedFilter && selectedFilter.filterType) {
      if (selectedFilter.filterType === 'All Cities') {
        response = await axios.get('/ManagementSchool/all');
        response.data.forEach(city => {

          city.schools.forEach(school => {
            school.exams.forEach(exam => {
              totalExamsQuantity += school.exams.length;
              if (statusCount[exam.writing] !== undefined) {
                statusCount[exam.writing]++;
              }
            });
          });
        });
      }
      else if (selectedFilter.filterType === 'All Schools in City') {
        response = await axios.get(`/ManagementSchool/${selectedFilter.city.schools[0].city_id}/all`);

        const schools = response.data[0];

        schools.forEach(school => {
          totalExamsQuantity += school.exams.length;
          if (school.exams) {
            school.exams.forEach(exam => {
              if (statusCount[exam.writing] !== undefined) {
                statusCount[exam.writing]++;
              }
            });
          }
        });

      }
      else if (selectedFilter.filterType === 'Specific School') {

        response = await axios.get(`/schoolDetails/json/${selectedFilter.city}/${selectedFilter.school}/${selectedFilter.schoolId}`);

        const school = response.data;

        if (school.exams) {
          totalExamsQuantity += school.exams.length;
          school.exams.forEach(exam => {
            if (statusCount[exam.writing] !== undefined) {
              statusCount[exam.writing]++;
            }
          });
        }

      }
      else if (selectedFilter.filterType === 'Specific School Class') {
        response = await api.get(`/api/classes/${selectedFilter.classId}/exams`);
        const school = response.data;

        if (school.students) {
          school.students.forEach(student => {
            totalExamsQuantity += student.exams.length;
            student.exams.forEach(exam => {
              if (statusCount[exam.writing] !== undefined) {
                statusCount[exam.writing]++;
              }
            });
          });
        }
      }
    }

    writingStatuses.value = Object.entries(statusCount);

    writingStatuses.value.map(writingStatus => {
        writingStatus[2] = (writingStatus[1] * 100 / totalExamsQuantity).toFixed(2)
    })

    const ctx = chartRef.value?.getContext('2d');
    if (!ctx) {
      console.error('Contexto do canvas não encontrado.');
      return;
    }

    const data = {
      labels: writingStatuses.value.map(status => translationMap[status[0]] + ' - ' + status[2] + '%'),
      datasets: [{
        label: 'Quantidade de escolas',
        backgroundColor: ["#FF0000", "#FFCB00", "#76AA3B", "#0D5413"],
        borderWidth: 0,
        data: writingStatuses.value.map(status => status[1]),
      }]
    };

    new Chart(ctx, {
      type: 'pie',
      data: data,
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: true,
            position: 'bottom'
          },
          tooltip: {
            callbacks: {
              label: function (tooltipItem) {
                const label = tooltipItem.label;
                return `${label}: ${tooltipItem.raw}`;
              }
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
