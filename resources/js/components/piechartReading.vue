<script setup>
import { onMounted, ref } from 'vue';
import Chart from 'chart.js/auto';
import axios from "axios";

const chartRef = ref(null);
const readingStatuses = ref([]);
const selectedFilter = JSON.parse(localStorage.getItem('selectedFilter'));
import { api } from "../services/api"

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
                        school.exams.forEach(exam => {
                            if (statusCount[exam.reading] !== undefined) {
                                statusCount[exam.reading]++;
                            }
                        });
                    });
                });
            } else if (selectedFilter.filterType === 'All Schools in City') {
                response = await axios.get(`/ManagementSchool/${selectedFilter.city.schools[0].city_id}/all`);

                const schools = response.data[0];

                schools.forEach(school => {
                    if (school.exams) {
                        school.exams.forEach(exam => {
                            if (statusCount[exam.reading] !== undefined) {
                                statusCount[exam.reading]++;
                            }
                        });
                    }
                });

            } else if (selectedFilter.filterType === 'Specific School') {

                response = await axios.get(`/schoolDetails/json/${selectedFilter.city}/${selectedFilter.school}/${selectedFilter.schoolId}`);

                const school = response.data;

                if (school.exams) {
                    school.exams.forEach(exam => {
                        ('city', school.exams);
                        if (statusCount[exam.reading] !== undefined) {
                            statusCount[exam.reading]++;
                        }
                    });
                }
            } else if (selectedFilter.filterType === 'Specific School Class') {
                response = await api.get(`/api/classes/${selectedFilter.classId}/exams`);
                const school = response.data;

                if (school.students) {
                    school.students.forEach(student => {
                        student.exams.forEach(exam => {
                            if (statusCount[exam.reading] !== undefined) {
                                statusCount[exam.reading]++;
                            }
                        });
                    });
                }
            }
        }

        readingStatuses.value = Object.entries(statusCount);

        const ctx = chartRef.value?.getContext('2d');
        if (!ctx) {
            console.error('Contexto do canvas não encontrado.');
            return;
        }

        const data = {
            labels: readingStatuses.value.map(status => translationMap[status[0]]), // Labels traduzidas
            datasets: [{
                label: 'Quantidade de alunos',
                backgroundColor: ["#FF0000", "#FFCB00", "#FF5C00", "#9747FF", "#ADD8E6", "#0D5413"],
                data: readingStatuses.value.map(status => status[1]),
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
