<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import MenuComponent from '../components/menu.vue';
import TitleComponent from '/resources/js/components/title.vue';
import UserWelcomeComponent from '/resources/js/components/userWelcome.vue';
import ChartBarBimReading from '/resources/js/components/chartBarBimReading.vue';
import ChartBarBimWriting from '/resources/js/components/chartBarBimWriting.vue';

const route = useRoute();
const formDataStudentPreview = ref([]);
const schoolsWithAverages = ref([]);
const selectedFilter = JSON.parse(localStorage.getItem('selectedFilter'));

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

const fetchAllSchools = async () => {
  try {
    const response = await axios.get('/ManagementSchool/all');
    formDataStudentPreview.value = Array.isArray(response.data) ? response.data : [];
    calculateAverages();
  } catch (error) {
    console.error('Error fetching all schools:', error);
    formDataStudentPreview.value = [];
  }
};

const fetchSchoolsByCity = async (city) => {
  try {
    const response = await axios.get(`/ManagementSchool/${city.schools[0].city_id}/all`);

    if (response.data && response.data.length > 0) {
      const data = response.data[0];

      formDataStudentPreview.value = data || [];

    } else {
      formDataStudentPreview.value = [];
    }

    calculateAveragesAllCities();
  } catch (error) {
    console.error(`Erro ao buscar escolas na cidade ${city.schools[0].city_id}:`, error);
    formDataStudentPreview.value = [];
  }
};

const fetchSpecificSchoolInCity = async (city, schoolNames, schoolId) => {
  if (!schoolId) {
    console.error('schoolId is undefined!');
    return;
  }

  try {
    const response = await axios.get(`/schoolDetails/json/${city}/${schoolNames}/${schoolId}`);
    if (Array.isArray(response.data)) {
      formDataStudentPreview.value = response.data; 
    } else {
      formDataStudentPreview.value = [response.data];
    }
    calculateAveragesCityAndSchool();
  } catch (error) {
    console.error(`Error fetching specific school ${schoolNames} in city ${city}:`, error);
    formDataStudentPreview.value = [];
  }
};


const translateGrade = (grade) => {
  return gradeTranslations[grade] || grade;
};

const translateReadingGradeBack = (average) => {
  if (average <= 1) return 'not_reader';
  if (average <= 2) return 'syllable_reader';
  if (average <= 3) return 'word_reader';
  if (average <= 4) return 'sentence_reader';
  if (average <= 5) return 'no_fluent_text_reader';
  return 'fluent_text_reader';
};

const translateWritingGradeBack = (average) => {
  if (average <= 1) return 'not_reader';
  if (average <= 2) return 'syllable_reader';
  if (average <= 3) return 'word_reader';
  if (average <= 4) return 'sentence_reader';
  if (average <= 5) return 'no_fluent_text_reader';
  return 'fluent_text_reader';
};

const gradeTranslations = {
  'not_reader': 'Não Leitor',
  'syllable_reader': 'Leitor Silábico',
  'word_reader': 'Leitor de Palavras',
  'sentence_reader': 'Leitor de Frases',
  'no_fluent_text_reader': 'Leitor de Texto sem Fluência',
  'fluent_text_reader': 'Leitor de Texto Fluente',
  'pre_syllabic': 'Pré-Silábico',
  'syllabic': 'Silábico',
  'alphabetical_syllabic': 'Alfabetização Silábica',
  'alphabetical': 'Alfabetização'
};

const calculateAveragesAllCities = () => {
  let previewValue = formDataStudentPreview.value;

  if (previewValue && previewValue._value && Array.isArray(previewValue._value)) {
    previewValue = previewValue._value;
  }

  if (!Array.isArray(previewValue)) {
    console.error('formDataStudentPreview is not an array:', formDataStudentPreview.value);
    return;
  }

  schoolsWithAverages.value = previewValue.map(school => {
    let totalReading = 0;
    let totalWriting = 0;
    let examCount = 0;

    if (Array.isArray(school.exams)) {
      school.exams.forEach(exam => {
        totalReading += getGradeValue(exam.reading);
        totalWriting += getGradeValue(exam.writing);
        examCount++;
      });
    }

    const averageReading = examCount ? totalReading / examCount : null;
    const averageWriting = examCount ? totalWriting / examCount : null;

    return {
      ...school,
      averageReading: averageReading ? translateReadingGradeBack(averageReading) : null,
      averageWriting: averageWriting ? translateWritingGradeBack(averageWriting) : null
    };
  });
};

const calculateAveragesCityAndSchool = () => {
  if (!Array.isArray(formDataStudentPreview.value)) {
    console.error('formDataStudentPreview is not an array:', formDataStudentPreview.value);
    return;
  }

  schoolsWithAverages.value = formDataStudentPreview.value.map(school => {

    let totalReading = 0;
    let totalWriting = 0;
    let examCount = 0;

    if (Array.isArray(school.exams)) {
      school.exams.forEach(exam => {
        totalReading += getGradeValue(exam.reading);
        totalWriting += getGradeValue(exam.writing);
        examCount++;
      });
    } else {
      console.error('school.exams is not an array:', school.exams);
    }

    const averageReading = examCount ? totalReading / examCount : null;
    const averageWriting = examCount ? totalWriting / examCount : null;

    return {
      ...school,
      averageReading: averageReading ? translateReadingGradeBack(averageReading) : null,
      averageWriting: averageWriting ? translateWritingGradeBack(averageWriting) : null
    };
  });
};


const calculateAverages = () => {
  if (!Array.isArray(formDataStudentPreview.value)) {
    console.error('formDataStudentPreview is not an array:', formDataStudentPreview.value);
    return;
  }
  schoolsWithAverages.value = formDataStudentPreview.value.flatMap(cityData => {
    if (!Array.isArray(cityData.schools)) {
      console.error('cityData.schools is not an array:', cityData.schools);
      return [];
    }

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
        averageReading: averageReading ? translateReadingGradeBack(averageReading) : null,
        averageWriting: averageWriting ? translateWritingGradeBack(averageWriting) : null
      };
    });
  });
};

watch(selectedFilter, async (newFilter) => {
  const city = selectedFilter.city;
  const school = selectedFilter.school;
  const schoolId = selectedFilter.schoolId;

  if (selectedFilter.filterType === 'All Cities') {
    await fetchAllSchools();
  } else if (selectedFilter.filterType === 'All Schools in City') {
    await fetchSchoolsByCity(city);
  } else if (selectedFilter.filterType === 'Specific School') {
    await fetchSpecificSchoolInCity(city, school, schoolId);
  }
}, { immediate: true });

onMounted(() => {
  if (selectedFilter && selectedFilter.filterType) {
    if (selectedFilter.filterType === 'All Cities') {
      fetchAllSchools();
    } else if (selectedFilter.filterType === 'All Schools in City') {
      fetchSchoolsByCity(selectedFilter.city);
    } else if (selectedFilter.filterType === 'Specific School in City') {
      fetchSpecificSchoolInCity(selectedFilter.city, selectedFilter.schoolNames, selectedFilter.id);
    }
  } else {
    console.warn('selectedFilter is undefined or missing filterType');
  }
});
</script>

<template>
  <div class="dashboard">
    <MenuComponent />
    <div class="dashboard-content">
      <TitleComponent title="Análise Geral das escolas" />
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
      <TitleComponent title="Análise geral média turmas - Leitura" />
      <ChartBarBimReading titleGrapichCard="Nível turmas das escolas - Leitura" />
      <TitleComponent title="Análise geral média turmas - Escrita" />
      <ChartBarBimWriting titleGrapichCard="Nível geral das turmas - Escrita" />
    </div>
  </div>
</template>

<style scoped>
.dashboard {
  display: flex;

  & .dashboard-content {
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

  & .seacheble-camp {
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

@media (max-width: 1270px) {
  .cards {
    flex-direction: column;
    align-items: center;
    gap: 0;
  }
}

@media (max-width: 900px) {
  .titleTable {
    font-size: 9px;
  }

  .stage,
  .registration {
    display: none;
  }
}


@media (max-width: 550px) {
  .searcheble {
    display: flex;
    flex-direction: column !important;
    margin: 2rem 0;

    & .seacheble-camp {
      width: 100% !important;
    }

    & .send-searche {
      width: 100%;
    }
  }
}
</style>