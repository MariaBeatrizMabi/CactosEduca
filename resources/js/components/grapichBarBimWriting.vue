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

      const polls = [];
      const graphicLabels = [];
      const totalValuesPerLabel = {
          pre_syllabic: [],
          syllabic: [],
          alphabetical_syllabic: [],
          alphabetical: [],
      };

    if (selectedFilter && selectedFilter.filterType) {
      if (selectedFilter.filterType === 'All Cities') {
        response = await axios.get('/ManagementSchool/all');

        response.data.forEach(city => {

          city.schools.forEach(school => {
            if (school.exams) {
              school.exams.forEach(exam => {

                  let existentPoll = false
                  polls.map(poll => {
                      if (poll.label === `${exam.poll_number + 'º Sondagem'}`) {
                          existentPoll = true
                      }
                  })

                  if (!existentPoll) {
                      polls.push({
                          label: `${exam.poll_number + 'º Sondagem'}`,
                          polls_values: {
                              pre_syllabic: 0,
                              syllabic: 0,
                              alphabetical_syllabic: 0,
                              alphabetical: 0,
                          }
                      })
                  }

                  polls.map(poll => {
                      if (poll.label === `${exam.poll_number + 'º Sondagem'}`) {

                          if (exam.writing === 'pre_syllabic') {
                              poll.polls_values.pre_syllabic += 1;
                          } else if (exam.writing === 'syllabic') {
                              poll.polls_values.syllabic += 1;
                          } else if (exam.writing === 'alphabetical_syllabic') {
                              poll.polls_values.alphabetical_syllabic += 1;
                          } else if (exam.writing === 'alphabetical') {
                              poll.polls_values.alphabetical += 1;
                          }
                      }
                  })
              });
            }
          });
        });
      }
      else if (selectedFilter.filterType === 'All Schools in City') {
        response = await axios.get(`/ManagementSchool/${selectedFilter.city.schools[0].city_id}/all`);

        const schools = response.data[0];
        schools.forEach(school => {
          if (school.exams) {
            school.exams.forEach(exam => {
                let existentPoll = false
                polls.map(poll => {
                    if (poll.label === `${exam.poll_number + 'º Sondagem'}`) {
                        existentPoll = true
                    }
                })

                if (!existentPoll) {
                    polls.push({
                        label: `${exam.poll_number + 'º Sondagem'}`,
                        polls_values: {
                            pre_syllabic: 0,
                            syllabic: 0,
                            alphabetical_syllabic: 0,
                            alphabetical: 0,
                        }
                    })
                }

                polls.map(poll => {
                    if (poll.label === `${exam.poll_number + 'º Sondagem'}`) {

                        if (exam.writing === 'pre_syllabic') {
                            poll.polls_values.pre_syllabic += 1;
                        } else if (exam.writing === 'syllabic') {
                            poll.polls_values.syllabic += 1;
                        } else if (exam.writing === 'alphabetical_syllabic') {
                            poll.polls_values.alphabetical_syllabic += 1;
                        } else if (exam.writing === 'alphabetical') {
                            poll.polls_values.alphabetical += 1;
                        }
                    }
                })
            });
          }
        });

      }
      else if (selectedFilter.filterType === 'Specific School') {

        response = await axios.get(`/schoolDetails/json/${selectedFilter.city}/${selectedFilter.school}/${selectedFilter.schoolId}`);

        const school = response.data;

        if (school.exams) {
          school.exams.forEach(exam => {
              let existentPoll = false
              polls.map(poll => {
                  if (poll.label === `${exam.poll_number + 'º Sondagem'}`) {
                      existentPoll = true
                  }
              })

              if (!existentPoll) {
                  polls.push({
                      label: `${exam.poll_number + 'º Sondagem'}`,
                      polls_values: {
                          pre_syllabic: 0,
                          syllabic: 0,
                          alphabetical_syllabic: 0,
                          alphabetical: 0,
                      }
                  })
              }

              polls.map(poll => {
                  if (poll.label === `${exam.poll_number + 'º Sondagem'}`) {

                      if (exam.writing === 'pre_syllabic') {
                          poll.polls_values.pre_syllabic += 1;
                      } else if (exam.writing === 'syllabic') {
                          poll.polls_values.syllabic += 1;
                      } else if (exam.writing === 'alphabetical_syllabic') {
                          poll.polls_values.alphabetical_syllabic += 1;
                      } else if (exam.writing === 'alphabetical') {
                          poll.polls_values.alphabetical += 1;
                      }
                  }
              })
          });
        }

      }
      else if (selectedFilter.filterType === 'Specific School Class') {
        response = await api.get(`/api/classes/${selectedFilter.classId}/exams`);
        const school = response.data;

        school.students.forEach(student => {
          student.exams.forEach(exam => {
              let existentPoll = false
              polls.map(poll => {
                  if (poll.label === `${exam.poll_number + 'º Sondagem'}`) {
                      existentPoll = true
                  }
              })

              if (!existentPoll) {
                  polls.push({
                      label: `${exam.poll_number + 'º Sondagem'}`,
                      polls_values: {
                          pre_syllabic: 0,
                          syllabic: 0,
                          alphabetical_syllabic: 0,
                          alphabetical: 0,
                      }
                  })
              }

              polls.map(poll => {
                  if (poll.label === `${exam.poll_number + 'º Sondagem'}`) {

                      if (exam.writing === 'pre_syllabic') {
                          poll.polls_values.pre_syllabic += 1;
                      } else if (exam.writing === 'syllabic') {
                          poll.polls_values.syllabic += 1;
                      } else if (exam.writing === 'alphabetical_syllabic') {
                          poll.polls_values.alphabetical_syllabic += 1;
                      } else if (exam.writing === 'alphabetical') {
                          poll.polls_values.alphabetical += 1;
                      }
                  }
              })
          });
        });
      }
    }

      polls.map(poll => {
          graphicLabels.push(poll.label)
          totalValuesPerLabel.pre_syllabic.push(poll.polls_values.pre_syllabic);
          totalValuesPerLabel.syllabic.push(poll.polls_values.syllabic);
          totalValuesPerLabel.alphabetical_syllabic.push(poll.polls_values.alphabetical_syllabic);
          totalValuesPerLabel.alphabetical.push(poll.polls_values.alphabetical);
      });

    const ctx = chartRef.value?.getContext('2d');
    if (!ctx) {
      console.error('Contexto do canvas não encontrado.');
      return;
    }

    const data = {
      labels: graphicLabels,
      datasets: [
          {
              label: 'Pré-silabico',
              backgroundColor: ["#9747FF"],
              borderWidth: 0,
              data: totalValuesPerLabel.pre_syllabic
          },
          {
              label: 'Silábico',
              backgroundColor: ["#FF0000"],
              borderWidth: 0,
              data: totalValuesPerLabel.syllabic
          },
          {
              label: 'Silábico Alfabético',
              backgroundColor: ["#FF5C00"],
              borderWidth: 0,
              data: totalValuesPerLabel.alphabetical_syllabic
          },
          {
              label: 'Alfabético',
              backgroundColor: ["#FFCB00"],
              borderWidth: 0,
              data: totalValuesPerLabel.alphabetical
          },
      ]
    };

      let colorsAndNames = [
          {
              name: 'Pré-silabico',
              color :"#9747FF"
          },
          {
              name: 'Silábico',
              color :"#FF0000"
          },
          {
              name: 'Silábico Alfabético',
              color :"#FF5C00"
          },
          {
              name: 'Alfabético',
              color :"#FFCB00"
          }
      ]

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
                return colorsAndNames.map(configData => ({
                  text: configData.name,
                  fillStyle: configData.color,
                }));
              }
            }
          },
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
