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
                <th>Média Geral de Leitura</th>
                <th style="text-align: left;">Média Geral de Escrita</th>
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
    'syllable_reader': 'Leitor de sílabas',
    'word_reader': 'Leitor de palavras',
    'sentence_reader': 'Leitor de frases',
    'no_fluent_text_reader': 'Leitor de texto sem fluência',
    'fluent_text_reader': 'Leitor de texto com fluência',
    'pre_syllabic': 'Pré-silábico',
    'syllabic': 'Silábico',
    'alphabetical_syllabic': 'Silábico alfabético',
    'alphabetical': 'Alfabético'
  };
  
  const fetchSchools = async () => {
    try {
      const response = await axios.get('/ManagementSchool/examsAll');
      console.log(response);
      formDataStudentPreview.value = response.data ? response.data : [];
      calculateAverages();
    } catch (error) {
      console.error('Error fetching schools:', error);
      formDataStudentPreview.value = [];
    }
  };
  
  const calculateAverages = () => {
    if (!Array.isArray(formDataStudentPreview.value)) {
      console.error('formDataStudentPreview is not an array:', formDataStudentPreview.value);
      return;
    }
    schoolsWithAverages.value = formDataStudentPreview.value.flatMap(cityData => {
      return cityData.schools.map(school => {
        let totalReading = 0;
        let totalWriting = 0;
        let examCount = 0;
  
        school.exams.forEach(exam => {
          totalReading += getGradeValue(exam.reading);
          totalWriting += getGradeValue(exam.writing);
          examCount++;
        });
  
        const averageReading = examCount ? totalReading / examCount : null;
        const averageWriting = examCount ? totalWriting / examCount : null;
  
        return {
          ...school,
          averageReading: averageReading ? translateGradeBack(averageReading) : null,
          averageWriting: averageWriting ? translateGradeBack(averageWriting) : null
        };
      });
    });
  };
  
  const getGradeValue = (grade) => {
    const grades = {
      'not_reader': 1,
      'syllable_reader': 2,
      'word_reader': 3,
      'sentence_reader': 4,
      'no_fluent_text_reader': 5,
      'fluent_text_reader': 6,
      'pre_syllabic': 1,
      'syllabic': 2,
      'alphabetical_syllabic': 3,
      'alphabetical': 4
    };
    return grades[grade] || 0;
  };
  
  const translateGrade = (grade) => {
    return gradeTranslations[grade] || grade;
  };
  
  const translateGradeBack = (average) => {
    if (average <= 1) return 'not_reader';
    if (average <= 2) return 'syllable_reader';
    if (average <= 3) return 'word_reader';
    if (average <= 4) return 'sentence_reader';
    if (average <= 5) return 'no_fluent_text_reader';
    return 'fluent_text_reader';
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