<script setup>
import {onMounted, ref} from 'vue';
import Chart from 'chart.js/auto';
import axios from "axios";
import {api} from "../services/api"

const chartRef = ref(null);
const readingStatuses = ref([]);
const selectedFilter = JSON.parse(localStorage.getItem('selectedFilter'));

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
        let response;
        const polls = [];
        const graphicLabels = [];
        const totalValuesPerLabel = {
            not_reader: [],
            syllable_reader: [],
            word_reader: [],
            sentence_reader: [],
            no_fluent_text_reader: [],
            fluent_text_reader: [],
        };

        const statusCount = {
            not_reader: 0,
            syllable_reader: 0,
            word_reader: 0,
            sentence_reader: 0,
            no_fluent_text_reader: 0,
            fluent_text_reader: 0,
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
                                            not_reader: 0,
                                            syllable_reader: 0,
                                            word_reader: 0,
                                            sentence_reader: 0,
                                            no_fluent_text_reader: 0,
                                            fluent_text_reader: 0,
                                        }
                                    })
                                }

                                polls.map(poll => {
                                    if (poll.label === `${exam.poll_number + 'º Sondagem'}`) {
                                        if (exam.reading === 'not_reader') {
                                            poll.polls_values.not_reader += 1;
                                        } else if (exam.reading === 'syllable_reader') {
                                            poll.polls_values.syllable_reader += 1;
                                        } else if (exam.reading === 'word_reader') {
                                            poll.polls_values.word_reader += 1;
                                        } else if (exam.reading === 'sentence_reader') {
                                            poll.polls_values.sentence_reader += 1;
                                        } else if (exam.reading === 'no_fluent_text_reader') {
                                            poll.polls_values.no_fluent_text_reader += 1;
                                        } else if (exam.reading === 'fluent_text_reader') {
                                            poll.polls_values.fluent_text_reader += 1;
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
                                        not_reader: 0,
                                        syllable_reader: 0,
                                        word_reader: 0,
                                        sentence_reader: 0,
                                        no_fluent_text_reader: 0,
                                        fluent_text_reader: 0,
                                    }
                                })
                            }

                            polls.map(poll => {
                                if (poll.label === `${exam.poll_number + 'º Sondagem'}`) {
                                    if (exam.reading === 'not_reader') {
                                        poll.polls_values.not_reader += 1;
                                    } else if (exam.reading === 'syllable_reader') {
                                        poll.polls_values.syllable_reader += 1;
                                    } else if (exam.reading === 'word_reader') {
                                        poll.polls_values.word_reader += 1;
                                    } else if (exam.reading === 'sentence_reader') {
                                        poll.polls_values.sentence_reader += 1;
                                    } else if (exam.reading === 'no_fluent_text_reader') {
                                        poll.polls_values.no_fluent_text_reader += 1;
                                    } else if (exam.reading === 'fluent_text_reader') {
                                        poll.polls_values.fluent_text_reader += 1;
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
                console.log('aqui: c')
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
                                    not_reader: 0,
                                    syllable_reader: 0,
                                    word_reader: 0,
                                    sentence_reader: 0,
                                    no_fluent_text_reader: 0,
                                    fluent_text_reader: 0,
                                }
                            })
                        }

                        polls.map(poll => {
                            if (poll.label === `${exam.poll_number + 'º Sondagem'}`) {
                                if (exam.reading === 'not_reader') {
                                    poll.polls_values.not_reader += 1;
                                } else if (exam.reading === 'syllable_reader') {
                                    poll.polls_values.syllable_reader += 1;
                                } else if (exam.reading === 'word_reader') {
                                    poll.polls_values.word_reader += 1;
                                } else if (exam.reading === 'sentence_reader') {
                                    poll.polls_values.sentence_reader += 1;
                                } else if (exam.reading === 'no_fluent_text_reader') {
                                    poll.polls_values.no_fluent_text_reader += 1;
                                } else if (exam.reading === 'fluent_text_reader') {
                                    poll.polls_values.fluent_text_reader += 1;
                                }
                            }
                        })
                    });
                }

            }
            else if (selectedFilter.filterType === 'Specific School Class') {
                response = await api.get(`/api/classes/${selectedFilter.classId}/exams`);

                const school = response.data;
                console.log('aqui: d')
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
                                    not_reader: 0,
                                    syllable_reader: 0,
                                    word_reader: 0,
                                    sentence_reader: 0,
                                    no_fluent_text_reader: 0,
                                    fluent_text_reader: 0,
                                }
                            })
                        }

                        polls.map(poll => {
                            if (poll.label === `${exam.poll_number + 'º Sondagem'}`) {
                                if (exam.reading === 'not_reader') {
                                    poll.polls_values.not_reader += 1;
                                } else if (exam.reading === 'syllable_reader') {
                                    poll.polls_values.syllable_reader += 1;
                                } else if (exam.reading === 'word_reader') {
                                    poll.polls_values.word_reader += 1;
                                } else if (exam.reading === 'sentence_reader') {
                                    poll.polls_values.sentence_reader += 1;
                                } else if (exam.reading === 'no_fluent_text_reader') {
                                    poll.polls_values.no_fluent_text_reader += 1;
                                } else if (exam.reading === 'fluent_text_reader') {
                                    poll.polls_values.fluent_text_reader += 1;
                                }
                            }
                        })
                    });
                });
            }

        }

        if (polls.length > 0) {
            polls.map(poll => {

                graphicLabels.push(poll.label)
                totalValuesPerLabel.not_reader.push(poll.polls_values.not_reader);
                totalValuesPerLabel.syllable_reader.push(poll.polls_values.syllable_reader);
                totalValuesPerLabel.word_reader.push(poll.polls_values.word_reader);
                totalValuesPerLabel.sentence_reader.push(poll.polls_values.sentence_reader);
                totalValuesPerLabel.no_fluent_text_reader.push(poll.polls_values.no_fluent_text_reader);
                totalValuesPerLabel.fluent_text_reader.push(poll.polls_values.fluent_text_reader);
            });

            console.log(totalValuesPerLabel)
        } else {

        }


        const ctx = chartRef.value?.getContext('2d');
        if (!ctx) {
            console.error('Contexto do canvas não encontrado.');
            return;
        }

        const data = {
            labels: graphicLabels,
            datasets: [
                {
                    label: 'Não leitor',
                    backgroundColor: ["#9747FF"],
                    borderWidth: 0,
                    data: totalValuesPerLabel.not_reader
                },
                {
                    label: 'Leitor de silabas',
                    backgroundColor: ["#FF0000"],
                    borderWidth: 0,
                    data: totalValuesPerLabel.syllable_reader
                },
                {
                    label: 'Leitor de Palavras',
                    backgroundColor: ["#FF5C00"],
                    borderWidth: 0,
                    data: totalValuesPerLabel.word_reader
                },
                {
                    label: 'Leitor de frases',
                    backgroundColor: ["#FFCB00"],
                    borderWidth: 0,
                    data: totalValuesPerLabel.sentence_reader
                },
                {
                    label: 'Leitor de Texto com fluencia',
                    backgroundColor: ["#0AA7AA"],
                    borderWidth: 0,
                    data: totalValuesPerLabel.fluent_text_reader
                },
                {
                    label: 'Leitor de texto sem fluencia',
                    backgroundColor: ["#0D5413"],
                    borderWidth: 0,
                    data: totalValuesPerLabel.no_fluent_text_reader
                },
            ]
        };

        let colorsAndNames = [
            {
                name: 'Não leitor',
                color :"#9747FF"
            },
            {
                name: 'Leitor de silabas',
                color :"#FF0000"
            },
            {
                name: 'Leitor de palavras',
                color :"#FF5C00"
            },
            {
                name: 'Leitor de frases',
                color :"#FFCB00"
            },
            {
                name: 'Leitor de Texto com fluencia',
                color :"#76AA3B"
            },
            {
                name: 'Leitor de texto sem fluencia',
                color :"#0AA7AA"
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
                                    text: configData['name'],
                                    fillStyle: configData['color'],
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
                            text: 'Quantidade de alunos'
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
        <canvas id="myChart" ref="chartRef"></canvas>
    </div>
</template>
