<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import menuCompoent from '/resources/js/components/menu.vue';
import TitleComponent from '/resources/js/components/title.vue';
import CardComponent from '/resources/js/components/card.vue';
import CardgrapichComponent from '/resources/js/components/cardGraphic.vue';
import UserWelcomeComponet from '/resources/js/components/userWelcome.vue';

const userType = ref({
    id: null,
    type: '',
});

const filteredTeachers = ref([]);
const TeacherCount = ref([]);
const SchoolCount = ref([]);
const teachers = ref([]);
const school = ref([]);

const getUserType = async () => {
    try {
        const response = await axios.get('/loginUser');
        userType.value.type = response.data.type;
        userType.value.id = response.data.id;
    } catch (error) {
        console.error("ERROR", error);
    }
}

const fetchTeachers = async () => {
    try {
        const response = await axios.get('/Teachers');
        teachers.value = response.data;
    } catch (error) {
        console.error("ERROR", error);
    }
}

const fetchSchool = async () => {
    try {
        const response = await axios.get('/ManagementSchool');
        school.value = response.data;
    } catch (error) {
        console.error("ERROR", error);
    }
}

const countTeachersBySchoolId = () => {
    return teachers.value.filter(teacher => teacher.school_id === userType.value.id);
}

const countAllTeachers = () => {
    return teachers.value.length;
}

const countAllSchool = () => {
    return school.value.length;
}

onMounted(async () => {
    await getUserType();
    await fetchTeachers();
    await fetchSchool()
    SchoolCount.value = countAllSchool();
    TeacherCount.value = countAllTeachers();
    filteredTeachers.value = countTeachersBySchoolId();
});

</script>


<template>
    <div class="dashboard">
        <menuCompoent />
        <div class="dashboard-content">
        <UserWelcomeComponet class="welcome-component"></UserWelcomeComponet>
            <TitleComponent title="Análise Geral"/>
            
            <div class="Cards-container" v-if="userType.type === 'admin'">
                <CardComponent imageCard="school.gif" titleCard="Escolas Cadastradas" :valueCard="SchoolCount" :ref="CardValue"></CardComponent>
                <CardComponent imageCard="groupSchool.gif" titleCard="Turmas Cadastradas" valueCard="253"></CardComponent>
                <CardComponent imageCard="student.gif" titleCard="Professores Cadastradas" :valueCard="TeacherCount"></CardComponent>
                <CardComponent imageCard="teacher.gif" titleCard="Alunos Cadastradas" valueCard="1.021"></CardComponent>
            </div>

            <div class="Cards-container" v-else-if="userType.type === 'school'">
                <CardComponent imageCard="groupSchool.gif" titleCard="Turmas Cadastradas" valueCard="120" :ref="CardValue"></CardComponent>
                <CardComponent imageCard="teacher.gif" titleCard="Professores Cadastrados"  :valueCard="filteredTeachers.length"></CardComponent>
                <CardComponent imageCard="student.gif" titleCard="Alunos Cadastradas" valueCard="1.021"></CardComponent>
            </div>

            <div class="Cards-container" v-else-if="userType.type === 'teacher'">
                <CardComponent imageCard="groupSchool.gif" titleCard="Turmas Cadastradas" valueCard="120" :ref="CardValue"></CardComponent>
                <CardComponent imageCard="teacher.gif" titleCard="Minhas Turmas" valueCard="253"></CardComponent>
                <CardComponent imageCard="student.gif" titleCard="Meus Alunos" valueCard="1.021"></CardComponent>
            </div>

            <div class="Cards-container" v-else>
                <CardComponent imageCard="teacher.gif" titleCard="Usuário não encontrado" valueCard="0"></CardComponent>
            </div>

            <div class="fullWidth" v-if="userType.type === 'admin'">
                <TitleComponent title="Escolas Cadastradas"/>
                <CardgrapichComponent titleGrapichCard="Cadastrados Por Múnicipio" ></CardgrapichComponent>
            </div>

            <div class="fullWidth" v-if="userType.type === 'school'">
                <TitleComponent title="Escolas Cadastradas"/>
                <CardgrapichComponent titleGrapichCard="Alunos por turmas" ></CardgrapichComponent>
            </div>

            <div class="fullWidth" v-if="userType.type === 'teacher'">
                <!-- <TitleComponent title="Escolas Cadastradas"/> -->
                <!-- <CardgrapichComponent titleGrapichCard="Alunos por turma" ></CardgrapichComponent> -->
            </div>
            
        </div>
    </div>
</template>

<style>
    @import '../../../public/css/main.css';
</style>

<style scoped>
.fullWidth {
    width: 100%;
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
}

.dashboard {
    height: 100vh;
    display: flex;

    & .dashboard-content{
        display: flex;
        align-items: center;
        margin: 7rem 0 0 0;
        width: 100%;
        flex-direction: column;

        & .Cards-container {
            width: 90%;
            display: flex;
            margin: 3rem 0;
            justify-content: space-evenly;
        }
    }

    @media (max-width: 1600px) {
        & .dashboard-content{
            & .Cards-container {
                width: 80vw;
            }
        }
    }

    @media (max-width: 1300px) {
        & .dashboard-content{
            & .Cards-container {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 3rem;
                width: 74vw;
            }
        }

            .cards{
                flex-direction: column;
                align-items: center;
                gap: 0;
            }
    }

        @media (max-width: 600px) {
            & .dashboard-content{
            & .Cards-container {
                display: grid;
                grid-template-columns: 1fr;
            }
        }
    }
}

</style>