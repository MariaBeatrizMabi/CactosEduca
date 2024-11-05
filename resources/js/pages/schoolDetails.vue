<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import MenuComponent from '../components/menu.vue';
import TitleComponent from '/resources/js/components/title.vue';
import UserWelcomeComponent from '/resources/js/components/userWelcome.vue';
import ChartBarBimReading from '/resources/js/components/chartBarBimReading.vue';
import ChartBarBimWriting from '/resources/js/components/chartBarBimWriting.vue';
import { api } from "../services/api"
import html2pdf from 'html2pdf.js';
import Button from "../components/button.vue";
import ExportBarGraphics from "../components/Export/ExportReadingBarGraphics.vue";
import ExportWritingBarGraphic from "../components/Export/ExportWritingBarGraphic.vue";
import ExportReadingBarGraphics from "../components/Export/ExportReadingBarGraphics.vue";
import ExportReadingPizzaGraphic from "../components/Export/ExportReadingPieGraphic.vue";
import ExportWritingPieChart from "../components/Export/ExportWritingPieChart.vue";

const route = useRoute();
const formDataStudentPreview = ref([]);
const schoolsWithAverages = ref([]);
const selectedFilter = JSON.parse(localStorage.getItem('selectedFilter'));
const studentData = ref([]);
const isImpress = ref(false);

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

const fetchSpecificSchoolInCityData = async (city, schoolNames, schoolId) => {
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
    calculateAveragesCityAndSchoolEspecify();
  } catch (error) {
    console.error(`Error fetching specific school ${schoolNames} in city ${city}:`, error);
    formDataStudentPreview.value = [];
  }
};

const fetchSpecificClassInSchool = async (classId) => {
  if (!classId) {
    console.error('classId is undefined!');
    return;
  }

  try {
      const response = await api.get(`/api/classes/${classId.classId}/exams`);
    if (Array.isArray(response.data)) {
      formDataStudentPreview.value = response.data;
    } else {
      formDataStudentPreview.value = [response.data];
    }
      formDataStudentPreview.value.map(school => {

          if (Array.isArray(school.students)) {
              school.students.forEach(student => {
                  if (Array.isArray(student.exams) && student.exams.length > 0) {
                      student.exams.forEach(exam => {
                          let hasStudant = false;
                          studentData.value.forEach(studant => {
                             if(studant.name === student.student_name){

                                 studant.writing = exam.writing != 'transferred' && exam.writing != 'transferred'
                                     ? exam.writing
                                     : studant.writing;

                                 studant.reading = exam.reading != 'transferred' && exam.reading != 'transferred'
                                     ? exam.reading
                                     : studant.reading;

                                 hasStudant = true
                             }
                          });

                          if (!hasStudant){
                              studentData.value.push({
                                  name: student.student_name,
                                  reading: exam.reading != 'transferred' && exam.reading != 'transferred'
                                      ? exam.reading
                                      : "Sem sondagens",
                                  writing: exam.writing != 'transferred' && exam.writing != 'transferred'
                                      ? exam.writing
                                      : "Sem sondagens"
                              });
                          }
                      });
                  } else {
                      studentData.value.push({
                          name: student.student_name,
                          reading: "Sem sondagens",
                          writing: "Sem sondagens"
                      });
                      console.error('student.exams is not an array:', student);
                  }
              });
          } else {
              console.error('school.students is not an array:', school.students);
              return {
                  name: school.class,
                  averageReading: null,
                  averageWriting: null
              };
          }
      });


  } catch (error) {
    console.error('Error fetching exams for classId:', classId, error);
  }
};

const translateGrade = (grade) => {
  return gradeTranslations[grade] || grade;
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
  'alphabetical_syllabic': 'Silabico Alfabetico',
  'alphabetical': 'Alfabetico',
  'missed': 'Faltou',
  'transfered': 'Transferido',
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
      let statusReadingCounter = {
          'not_reader': 0,
          'syllable_reader': 0,
          'word_reader': 0,
          'sentence_reader':0,
          'no_fluent_text_reader': 0,
          'fluent_text_reader': 0,
      }

      let statusWritingCounter = {
          'pre_syllabic': 0,
          'syllabic': 0,
          'alphabetical_syllabic': 0,
          'alphabetical': 0
      }

    if (Array.isArray(school.exams)) {
      school.exams.forEach(exam => {
          statusReadingCounter[exam.reading] += 1;
          statusWritingCounter[exam.writing] += 1;
      });
    }

      let maxReadingStatus = {
          name: null,
          value: -1,
      }

      const readingStatuses = Object.entries(statusReadingCounter);
      readingStatuses.forEach(status => {
          if(maxReadingStatus.value < status[1]){
              maxReadingStatus.name = status[0];
              maxReadingStatus.value = status[1]
          }
      })

      let maxWritingStatus = {
          name: null,
          value: -1,
      }
      const writingStatuses = Object.entries(statusWritingCounter);
      writingStatuses.forEach(status => {
          if(maxWritingStatus.value < status[1]) {
              maxWritingStatus.name = status[0];
              maxWritingStatus.value = status[1]
          }
      })

    return {
      ...school,
      averageReading: maxReadingStatus.name,
      averageWriting: maxWritingStatus.name
    };
  });
};

const calculateAveragesCityAndSchool = () => {
  if (!Array.isArray(formDataStudentPreview.value)) {
    console.error('formDataStudentPreview is not an array:', formDataStudentPreview.value);
    return;
  }

  schoolsWithAverages.value = formDataStudentPreview.value.map(school => {
      let statusReadingCounter = {
          'not_reader': 0,
          'syllable_reader': 0,
          'word_reader': 0,
          'sentence_reader':0,
          'no_fluent_text_reader': 0,
          'fluent_text_reader': 0,
      }

      let statusWritingCounter = {
          'pre_syllabic': 0,
          'syllabic': 0,
          'alphabetical_syllabic': 0,
          'alphabetical': 0
      }

    if (Array.isArray(school.students)) {
    school.students.forEach(student => {
        if (Array.isArray(student.exams)) {
          student.exams.forEach(exam => {
              statusReadingCounter[exam.reading] += 1;
              statusWritingCounter[exam.writing] += 1;
          });
        } else {
          console.error('student.exams is not an array:', student);
        }
      });
    } else {
      console.error('school.students is not an array:', school.students);
      return {
        name: school.class,
        averageReading: null,
        averageWriting: null
      };
    }

      let maxReadingStatus = {
          name: null,
          value: -1,
      }

      const readingStatuses = Object.entries(statusReadingCounter);
      readingStatuses.forEach(status => {
          if(maxReadingStatus.value < status[1]){
              maxReadingStatus.name = status[0];
              maxReadingStatus.value = status[1]
          }
      })

      let maxWritingStatus = {
          name: null,
          value: -1,
      }
      const writingStatuses = Object.entries(statusWritingCounter);
      writingStatuses.forEach(status => {
          if(maxWritingStatus.value < status[1]) {
              maxWritingStatus.name = status[0];
              maxWritingStatus.value = status[1]
          }
      })

      return school.students.map(student => {
      return {
        student_name: student.student_name,
        averageReading: maxReadingStatus.name,
        averageWriting: maxWritingStatus.name
      };
    });


  });
};

const calculateAveragesCityAndSchoolEspecify = () => {
  if (!Array.isArray(formDataStudentPreview.value)) {
    console.error('formDataStudentPreview is not an array:', formDataStudentPreview.value);
    return;
  }

  schoolsWithAverages.value = formDataStudentPreview.value.map(school => {
      let statusReadingCounter = {
          'not_reader': 0,
          'syllable_reader': 0,
          'word_reader': 0,
          'sentence_reader':0,
          'no_fluent_text_reader': 0,
          'fluent_text_reader': 0,
      }

      let statusWritingCounter = {
          'pre_syllabic': 0,
          'syllabic': 0,
          'alphabetical_syllabic': 0,
          'alphabetical': 0
      }

    if (Array.isArray(school.exams)) {
      school.exams.forEach(exam => {
          statusReadingCounter[exam.reading] += 1;
          statusWritingCounter[exam.writing] += 1;
      });
    } else {
      console.error('school.exams is not an array:', school);
      return {
        name: school.name,
        averageReading: null,
        averageWriting: null
      };
    }

      let maxReadingStatus = {
          name: null,
          value: -1,
      }

      const readingStatuses = Object.entries(statusReadingCounter);
      readingStatuses.forEach(status => {
          if(maxReadingStatus.value < status[1]){
              maxReadingStatus.name = status[0];
              maxReadingStatus.value = status[1]
          }
      })

      let maxWritingStatus = {
          name: null,
          value: -1,
      }
      const writingStatuses = Object.entries(statusWritingCounter);
      writingStatuses.forEach(status => {
          if(maxWritingStatus.value < status[1]) {
              maxWritingStatus.name = status[0];
              maxWritingStatus.value = status[1]
          }
      })

    return {
      name: school.name,
      averageReading: maxReadingStatus.name,
      averageWriting: maxWritingStatus.name
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

      let statusReadingCounter = {
          'not_reader': 0,
          'syllable_reader': 0,
          'word_reader': 0,
          'sentence_reader':0,
          'no_fluent_text_reader': 0,
          'fluent_text_reader': 0,
      }

      let statusWritingCounter = {
          'pre_syllabic': 0,
          'syllabic': 0,
          'alphabetical_syllabic': 0,
          'alphabetical': 0
      }

      school.exams.forEach(exam => {
        statusReadingCounter[exam.reading] += 1;
        statusWritingCounter[exam.writing] += 1;
      });

      let maxReadingStatus = {
          name: null,
          value: -1,
      }

      const readingStatuses = Object.entries(statusReadingCounter);
      readingStatuses.forEach(status => {
          if(maxReadingStatus.value < status[1]){
              maxReadingStatus.name = status[0];
              maxReadingStatus.value = status[1]
          }
      })

      let maxWritingStatus = {
          name: null,
          value: -1,
      }
      const writingStatuses = Object.entries(statusWritingCounter);
      writingStatuses.forEach(status => {
          if(maxWritingStatus.value < status[1]) {
              maxWritingStatus.name = status[0];
              maxWritingStatus.value = status[1]
          }
      })

      return {
        ...school,
        averageReading: maxReadingStatus.name,
        averageWriting: maxWritingStatus.name
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

    } else if (selectedFilter.filterType === 'Specific School') {
        fetchSpecificSchoolInCityData(selectedFilter.city, selectedFilter.school, selectedFilter.schoolId);

    } else if (selectedFilter.filterType === 'Specific School in City') {
        fetchSpecificSchoolInCity(selectedFilter.city, selectedFilter.schoolNames, selectedFilter.id);

    } else if (selectedFilter.filterType === 'Specific School Class') {
        fetchSpecificClassInSchool(selectedFilter);
    }
  } else {
    console.warn('selectedFilter is undefined or missing filterType');
  }
});

const exportToPDF = () => {

    isImpress.value = true;

    // Elemento raiz
    const element = document.getElementById('dashboard');

    // Removidos da página
    const pizzaElement = document.getElementById('pizza-graphic');
    pizzaElement.classList.add('none-on-impress')

    const pizzaElementTitle = document.getElementById('pizza-graphic-title');
    pizzaElementTitle.classList.add('none-on-impress')

    const userModal = document.getElementById('user-modal');
    userModal.classList.add('none-on-impress')

    const exportBtn = document.getElementById('export-btn');
    exportBtn.classList.add('none-on-impress')

    // Formatando modal de média
    const averageModalTitle = document.getElementById('average-modal-title');
    averageModalTitle.classList.add('average-modal')

    const averageModalContent = document.getElementById('average-modal-content');
    averageModalContent.classList.add('average-modal')


    const barChartTitle = document.getElementById('bar-chart-title');
    barChartTitle.classList.add('average-modal')

    const pieChartTitle = document.getElementById('pie-chart-title');
    pieChartTitle.classList.add('average-modal')

    setTimeout(html2pdf()
        .from(element)
        .set({
            margin: [5, 5, 5, 5],
            filename: 'exported-file.pdf',
            html2canvas: {
                scale: 3, // Aumenta a escala para melhorar a qualidade
                useCORS: true, // Para garantir que imagens externas sejam carregadas corretamente
                scrollX: 0,
                scrollY: 0,
            },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        })
        .save()
, 3000);

};

</script>

<template>
  <div class="dashboard">
    <MenuComponent />
    <div id="dashboard" class="dashboard-content">
        <TitleComponent id='bar-chart-title' title="Análise geral dividida por sondagem" />
        <ExportReadingBarGraphics id="bar-chart-component"></ExportReadingBarGraphics>
        <ExportWritingBarGraphic></ExportWritingBarGraphic>
        <TitleComponent id='pie-chart-title' title="Análise geral média" />
        <ExportReadingPizzaGraphic></ExportReadingPizzaGraphic>
        <ExportWritingPieChart></ExportWritingPieChart>
      <TitleComponent id='average-modal-title' v-if="selectedFilter.filterType !== 'Specific School Class' && !isImpress" title="Análise Geral das escolas" />
      <TitleComponent id='average-modal-title' v-if="selectedFilter.filterType === 'Specific School Class' && !isImpress" title="Análise Geral das turmas" />
      <div id='average-modal-content' class="tableContent">
        <div class="table-container" style="overflow-x: auto;">
          <div class="titleTable">
            <h1>Média Geral</h1>
          </div>

          <table>
            <tr>
              <th v-if="selectedFilter.filterType !== 'Specific School Class'">Escolas</th>
              <th v-else>Alunos</th>
              <th>Média Geral de Leitura</th>
              <th style="text-align: left;">Média Geral de Escrita</th>
            </tr>

            <template v-if="selectedFilter.filterType === 'Specific School'">
              <tr v-for="school in schoolsWithAverages" :key="school.id">
                <td>{{ school.name }}</td>
                <td>{{ translateGrade(school.averageReading) }}</td>
                <td>{{ translateGrade(school.averageWriting) }}</td>
              </tr>
            </template>

            <template v-else-if="selectedFilter.filterType !== 'Specific School Class' && selectedFilter.filterType !== 'Specific School'">
              <tr v-for="school in schoolsWithAverages" :key="school.id">
                <td>{{ school.name }}</td>
                <td>{{ translateGrade(school.averageReading) }}</td>
                <td>{{ translateGrade(school.averageWriting) }}</td>
              </tr>
            </template>

            <template v-else>
                <tr v-for="student in studentData" :key="student.name">
                  <td>{{ student.name }}</td>
                  <td>{{ translateGrade(student.reading) }}</td>
                  <td>{{ translateGrade(student.writing) }}</td>
                </tr>
            </template>
          </table>

        </div>
      </div>
      <UserWelcomeComponent v-if='!isImpress' id='user-modal' class="welcome-component"></UserWelcomeComponent>
      <TitleComponent v-if='!isImpress' id='bar-chart-title' title="Análise geral dividida por sondagem" />
      <ChartBarBimReading v-if='!isImpress' id='bar-chart-content' titleGrapichCard="Nível turmas das escolas - Leitura" />
      <TitleComponent v-if='!isImpress' id='pizza-graphic-title' title="Análise geral média " />
      <ChartBarBimWriting v-if='!isImpress' id='pizza-graphic' titleGrapichCard="Nível geral das turmas - Escrita" />
       <button v-if='!isImpress' id="export-btn" @click="exportToPDF">teste</button>

    </div>
  </div>
</template>

<style scoped>

.average-modal{
    display: flex;
    justify-content: center;
    width: 95% !important;
    margin: 15px !important;
}

.bar-chart-content{
    display: flex;
    width: 100%;
    justify-content: center;
    gap: 3rem;
}

.none-on-impress{
    display: none;
}

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
