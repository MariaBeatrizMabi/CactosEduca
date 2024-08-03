<template>
    <div class="dashboard">
        <MenuComponent />    
        <div class="dashboard-content">
            <TitleComponent title="Análise Geral das escolas"/>
            <div class="tableContent">
                <div class="table-container" style="overflow-x: auto;">
                    <div class="titleTable">
                        <h1>Média Geral</h1>
                    </div>
                    <table>
                        <tr>
                            <th>Escolas</th>
                            <th>Média Geral de leitura</th>
                            <th style="text-align: left;">Média Geral de escrita</th>
                        </tr>
                        <tr v-for="school in schoolsWithAverages" :key="school.id">
                            <td>{{ school.name }}</td>
                            <td>{{ translateGrade(school.averageReading) }}</td>
                            <td>{{ translateGrade(school.averageWriting) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <UserWelcomeComponent class="welcome-component"></UserWelcomeComponent>
            <TitleComponent title="Análise geral média turmas - Leitura"/>
            <ChartBarBimReading titleGrapichCard="Nível turmas das escolas - Leitura" />
            <TitleComponent title="Análise geral média turmas - Escrita"/>
            <ChartBarBimWriting titleGrapichCard="Nível geral das turmas - Escrita" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from 'vue-router';
import axios from "axios";
import MenuComponent from '../components/menu.vue';
import TitleComponent from '/resources/js/components/title.vue';
import UserWelcomeComponent from '/resources/js/components/userWelcome.vue';
import ChartBarBimReading from '/resources/js/components/chartBarBimReading.vue';
import ChartBarBimWriting from '/resources/js/components/chartBarBimWriting.vue';

const route = useRoute();
const schoolId = route.params.schoolId; 
const formDataStudentPreview = ref([]);
const schoolsWithAverages = ref([]);

const gradeTranslations = {
    'not_reader': 'Não leitor',
    'syllable_reader': 'Leitor de silabas',
    'word_reader': 'Leitor de palavras',
    'sentence_reader': 'Leitor de frases',
    'no_fluent_text_reader': 'Leitor de texto sem fluência',
    'fluent_text_reader': 'Leitor de texto com fluência',
    'pre_syllabic': 'Pré silábico',
    'syllabic': 'Silábico',
    'alphabetical_syllabic': 'Silábico alfabético',
    'alphabetical': 'Alfabético'
};

const fetchSchools = async () => {
    try {
        const response = await axios.get(`/ManagementSchool/${schoolId}`);
        formDataStudentPreview.value = response.data ? [response.data] : [];
        calculateAverages();
    } catch (error) {
        console.error('Error fetching schools:', error);
        formDataStudentPreview.value = [];
    }
};

const calculateAverages = () => {
    console.log(formDataStudentPreview.value, 'formDataStudentPreview at start of calculateAverages');
    if (!Array.isArray(formDataStudentPreview.value)) {
        console.error('formDataStudentPreview is not an array:', formDataStudentPreview.value);
        return;
    }
    schoolsWithAverages.value = formDataStudentPreview.value.map(school => {
        let readingCounts = { 'not_reader': 0, 'syllable_reader': 0, 'word_reader': 0, 'sentence_reader': 0, 'no_fluent_text_reader': 0, 'fluent_text_reader': 0 };
        let writingCounts = { 'pre_syllabic': 0, 'syllabic': 0, 'alphabetical_syllabic': 0, 'alphabetical': 0 };
        let examCount = 0;

        school.classes.forEach(classData => {
            classData.students.forEach(student => {
                student.exams.forEach(exam => {
                    readingCounts[exam.reading] = (readingCounts[exam.reading] || 0) + 1;
                    writingCounts[exam.writing] = (writingCounts[exam.writing] || 0) + 1;
                    examCount++;
                });
            });
        });

        const mostFrequentReading = getMostFrequentGrade(readingCounts);
        const mostFrequentWriting = getMostFrequentGrade(writingCounts);

        return {
            ...school,
            averageReading: mostFrequentReading,
            averageWriting: mostFrequentWriting
        };
    });
};

const getMostFrequentGrade = (counts) => {
    return Object.keys(counts).reduce((a, b) => counts[a] > counts[b] ? a : b);
};

const translateGrade = (grade) => {
    return gradeTranslations[grade] || grade;
};

onMounted(() => {
    fetchSchools();
});
</script>

<style scoped>
.dashboard {
    display: flex;

    & .dashboard-content{
        display: flex;
        align-items: center;
        margin: 7rem 0;
        width: 100%;
        flex-direction: column;
        
    }

    & .table-container {
        padding: 2rem 0 3rem 0;
    }
}

.searcheble {
        display: flex;
        align-items: center;
        gap: 5%;
        width: 85%;
        margin: 2rem 0 0 0;
        
        & .seacheble-camp{
            padding: 0.7rem 1rem;
            width: 80%;
            border: 2px solid var(--black-color);
            border-radius: 4rem;
            outline: 0;
            font-size: 14px;
        }

        & .send-searche {
            width: 15%;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            border: 2px solid var(--black-color);
            text-align: center;
            border-radius: 4rem;
            background-color: var(--white-color);
            color: var(--black-color);
            padding: 0.6rem;
        }
    }

@media (max-width: 1270px){
    .cards{
        flex-direction: column;
        align-items: center;
        gap: 0;
    }
}

@media (max-width: 900px) {
    .titleTable{
        font-size: 9px;
    }

    .stage, .registration {
        display: none;
    }
}


@media (max-width: 550px) {
        .searcheble {
            display: flex;
            flex-direction: column !important;
            margin: 2rem 0;
        & .seacheble-camp{
            width: 100% !important;
        }

        & .send-searche {
            width: 100%;
        }
    }
}
</style>