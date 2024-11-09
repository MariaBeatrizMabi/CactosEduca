<script setup>
import { onMounted, ref } from 'vue';
import Chart from 'chart.js/auto';
import axios from 'axios';

const chartRef = ref(null);
const selectedFilter = JSON.parse(localStorage.getItem('selectedFilter'));
import { api } from "../../services/api"

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
                    backgroundColor: ["#FF0000"],
                    borderWidth: 0,
                    data: totalValuesPerLabel.pre_syllabic
                },
                {
                    label: 'Silábico',
                    backgroundColor: ["#FFCB00"],
                    borderWidth: 0,
                    data: totalValuesPerLabel.syllabic
                },
                {
                    label: 'Silábico Alfabético',
                    backgroundColor: ["#76AA3B"],
                    borderWidth: 0,
                    data: totalValuesPerLabel.alphabetical_syllabic
                },
                {
                    label: 'Alfabético',
                    backgroundColor: ["#0D5413"],
                    borderWidth: 0,
                    data: totalValuesPerLabel.alphabetical
                },
            ]
        };

        Chart.defaults.color = '#000000'
        Chart.defaults.font.weight = 'normal';

        new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: false,
                devicePixelRatio: 1,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantidade de escolas'
                        },
                        ticks: {
                            stepSize: 1
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
    <div class="cards">
        <div class="card-grapich">
            <div class="card-grapich-content">
                <div class="card-title">
                    <h1>Analise dividida por sondagem - Escrita</h1>
                </div>
            </div>
            <div class="grapich">
                <div>
                    <canvas class="test" id="myChart" ref="chartRef"></canvas>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

.test {
    width: 650px;
    height: 350px !important;
}

.cards{
    display: flex;
    width: 100%;
    justify-content: center;

    & .card-grapich {
        margin: 2rem 0 !important;
        width: 95%;

        display: flex;
        flex-direction: column;

        border-radius: 1rem;
        border: 3px solid var(--secondary-color);

        background-color: var(--secondary-color);

        & .card-grapich-content {
            & .card-title {
                & h1 {
                    margin: 0.5rem;
                    text-align: center;

                    color: white;

                    font-weight: 400;
                    font-size: 20px;
                }
            }
        }
        & .grapich {
            width: 100%;
            display: flex;
            justify-content: center;
            flex-direction: column;
            padding: 1rem;
            border-radius: 0 0 1rem 1rem;
            background-color: white;
        }
    }
}
</style>
