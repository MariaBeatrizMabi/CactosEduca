<script setup>
import { onMounted, ref } from 'vue';
import Chart from 'chart.js/auto';
import axios from 'axios';

const chartRef = ref(null);
const writingStatuses = ref([]);
const selectedFilter = JSON.parse(localStorage.getItem('selectedFilter'));
import { api } from "../../services/api"

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
                        totalExamsQuantity += school.exams.length;
                        school.exams.forEach(exam => {
                            if (statusCount[exam.writing] !== undefined) {
                                statusCount[exam.writing]++;
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
                            if (statusCount[exam.writing] !== undefined) {
                                statusCount[exam.writing]++;
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
                        if (statusCount[exam.writing] !== undefined) {
                            statusCount[exam.writing]++;
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
        Chart.defaults.color = '#000000'
        Chart.defaults.font.weight = 'bold';
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
                responsive: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'right'
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
                    <h1>Análise média dividida por tipo - Escrita</h1>
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
    width: 500px !important;
    height: 500px !important;
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
            display: flex;
            justify-content: center;
            flex-direction: column;
            padding: 1rem;
            height: 400px !important;
            border-radius: 0 0 1rem 1rem;
            background-color: white;
        }
    }
}
</style>
