<script setup>
import { onMounted, ref } from 'vue';
import Chart from 'chart.js/auto';
import axios from "axios";

const chartRef = ref(null);
const readingStatuses = ref([]);
const selectedFilter = JSON.parse(localStorage.getItem('selectedFilter'));
import { api } from "../../services/api"

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

        var totalExamsQuantity = 0;

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
                        totalExamsQuantity += school.exams.length;
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
                        totalExamsQuantity += school.exams.length;
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
                    totalExamsQuantity += school.exams.length;
                    school.exams.forEach(exam => {
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
                        totalExamsQuantity += student.exams.length;
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

        readingStatuses.value.map(readingStatus => {
            readingStatus[2] = (readingStatus[1] * 100 / totalExamsQuantity).toFixed(2)
        })

        const ctx = chartRef.value?.getContext('2d');
        Chart.defaults.color = '#000000'
        Chart.defaults.font.weight = 'bold';
        if (!ctx) {
            console.error('Contexto do canvas não encontrado.');
            return;
        }

        const data = {
            labels: readingStatuses.value.map(status => translationMap[status[0]] + ' - ' + status[2] + '%'), // Labels traduzidas
            datasets: [{
                label: 'Quantidade de alunos',
                backgroundColor: ["#FF0000", "#FFCB00", "#7B0000", "#9747FF", "#ADD8E6", "#0D5413"],
                borderWidth: 0,
                data: readingStatuses.value.map(status => status[1]),
            }]
        };

        new Chart(ctx, {
            type: 'pie',
            data: data,
            options: {
                responsive: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
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
    <div class="cards">
        <div class="card-grapich">
            <div class="card-grapich-content">
                <div class="card-title">
                    <h1>Análise média dividida por tipo - Leitura</h1>
                </div>
            </div>
            <div class="grapich">
                <div>
                    <canvas class="canvar-style" id="myChart" ref="chartRef"></canvas>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

.canvar-style {
    margin-left: 70px;
    width: 550px;
    height: 550px !important;
}
.cards{

    display: flex;
    width: 100%;
    justify-content: center;

    & .card-grapich {
        margin: 1.5rem 0 !important;
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
            display: flex !important;
            justify-content: center !important;
            height: 400px !important;
            flex-direction: column;
            padding: 1rem;
            border-radius: 0 0 1rem 1rem;
            background-color: white;
        }
    }
}
</style>
