<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from 'vue-router'; // Importa useRoute para acessar parâmetros da URL
import axios from "axios";
import MenuComponent from '../components/menu.vue';
import TitleComponent from '/resources/js/components/title.vue';
import UserWelcomeComponent from '/resources/js/components/userWelcome.vue';
import ChartBarBimReading from '/resources/js/components/chartBarBimReading.vue';
import ChartBarBimWriting from '/resources/js/components/chartBarBimWriting.vue';

const route = useRoute();
const schoolId = route.params.schoolId; 
const formDataStudentPreview = ref([]);

async function getTableStudentData() {
    try {
        const response = await axios.get(`/ManagementSchool/${schoolId}`);
        formDataStudentPreview.value = response.data.map((take) => ({
            id: take.id,
            name: take.name,
            enrollment: take.enrollment,
        }));
    } catch (error) {
        console.error(error);
    }
}

onMounted(async () => {
    await getTableStudentData();
});
</script>

<template>
    <div class="dashboard">
        <MenuComponent />    
        <div class="dashboard-content">
            <TitleComponent title="Análise Geral das escolas"/>
            <div class="tableContent">
                <div class="table-container" style="overflow-x: auto;">
                    <div class="titleTable">
                        <h1>Escolas cadastradas - turma 01 | Ensino fundamental</h1>
                    </div>
                    <table>
                        <tr>
                            <th>Nome</th>
                            <th>Matrícula</th>
                            <th class="registration">Data de matrícula</th>
                            <th>Última Nota - Leitura</th>
                            <th>Última Nota - Escrita</th>
                            <th class="stage">Etapa</th>
                        </tr>
                        <tr v-for="student in formDataStudentPreview" :key="student.id">
                            <td>{{ student.name }}</td>
                            <td>{{ student.enrollment }}</td>
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