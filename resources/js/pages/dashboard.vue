<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import menuCompoent from '/resources/js/components/menu.vue';
import TitleComponent from '/resources/js/components/title.vue';
import CardComponent from '/resources/js/components/card.vue';
import CardgrapichComponent from '/resources/js/components/cardGraphic.vue';
import UserWelcomeComponet from '/resources/js/components/userWelcome.vue';
import Breadcrumb from "../components/Breadcrumb";

const userType = ref({
    id: null,
    type: '',
});

const filteredTeachers = ref([]);
const TeacherAllCount = ref([]);
const TeacherCount = ref([]);
const SchoolCount = ref([]);
const ClassDataCount = ref([]);
const ClassAllDataCount = ref([]);
const StudentAllCount = ref([]);
const StudentCount = ref([]);
const teachers = ref([]);
const teachersAll = ref([]);
const school = ref([]);
const classData = ref([]);
const classAllData = ref([])
const student = ref([]);
const studentAll = ref([]);
const studentTeachers = ref([]);
const filteredcountStudentTeachers = ref([]);

const getUserType = async () => {
    try {
        const response = await axios.get('/loginUser');
        userType.value.type = response.data.type;
        userType.value.id = response.data.id;
    } catch (error) {
        console.error("ERROR", error);
    }
}

const fetchAllTeachers = async () => {
    try {
        const response = await axios.get('/Teachers');
        teachersAll.value = response.data;
    } catch (error) {
        console.error("ERROR", error);
    }
}

const fetchTeachers = async () => {
    try {
        const response = await axios.get('/TeachersSchool');
        teachers.value = response.data;
    } catch (error) {
        console.error("ERROR", error);
    }
}

const fetchSchool = async () => {
    try {
        const response = await axios.get('/ManagementSchool/all');
        school.value = response.data;
    } catch (error) {
        console.error("ERROR", error);
    }
}

const fetchClassAllData = async () => {
    try {
        const response = await axios.get('/ClassAllSchool');
        classAllData.value = response.data;
    } catch (error) {
        console.error("ERROR", error);
    }
}

const fetchClassData = async () => {
    try {
        const response = await axios.get('/ClassSchool');
        classData.value = response.data;
    } catch (error) {
        console.error("ERROR", error);
    }
}

const fetchAllStudent = async () => {
    try {
        const response = await axios.get('/StudentsAllData');
        studentAll.value = response.data;
    } catch (error) {
        console.error("ERROR", error);
    }
}

const fetchStudent = async () => {
    try {
        const response = await axios.get('/StudentsData');
        student.value = response.data;
    } catch (error) {
        console.error("ERROR", error);
    }
}

const fetchTeacherStudent = async () => {
    try {
        const response = await axios.get('/StudentsChartData');
        studentTeachers.value = response.data;
    } catch (error) {
        console.error("ERROR", error);
    }
}

const countTeachersBySchoolId = () => {
    return teachers.value.length;
}

const countAllTeachers = () => {
    return teachersAll.value.length;
}

const countTeachers = () => {
    return teachers.value.length;
}

const countAllSchool = () => {
    return school.value.length;
}

const countAllClassData = () => {
    return classAllData.value.length;
}

const countClassData = () => {
    return classData.value.length;
}

const countStudent = () => {
    return student.value.length;
}

const countStudentTeachers = () => {
    return studentTeachers.value.length;
}

const countAllStudent= () => {
    return studentAll.value.length;
}

onMounted(async () => {
    await getUserType();
    await fetchAllTeachers()
    await fetchTeachers();
    await fetchSchool();
    await fetchClassData();
    await fetchStudent();
    await fetchAllStudent();
    await fetchClassAllData();
    await fetchTeacherStudent();
    SchoolCount.value = countAllSchool();
    TeacherAllCount.value = countAllTeachers();
    TeacherCount.value = countTeachers();
    ClassDataCount.value = countClassData()
    ClassAllDataCount.value = countAllClassData()
    StudentCount.value = countStudent()
    StudentAllCount.value = countAllStudent()
    filteredTeachers.value = countTeachersBySchoolId();
    filteredcountStudentTeachers.value = countStudentTeachers();
});
</script>

<template>
    <div class="dashboard">
        <menuCompoent />
        <div class="dashboard-content">
        <UserWelcomeComponet class="welcome-component"></UserWelcomeComponet>

            <Breadcrumb.Root>
                <Breadcrumb.Content>
                    <Breadcrumb.Item>
                        <svg style="margin-right: 4px;" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 0 576 512"><path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>
                        <a href="/dashboard">
                            Início
                        </a>
                    </Breadcrumb.Item>
                </Breadcrumb.Content>
            </Breadcrumb.Root>

            <TitleComponent title="Análise Geral"/>
            <div class="Cards-container" v-if="userType.type === 'admin'">
                <CardComponent imageCard="school.gif" titleCard="Escolas Cadastradas" :valueCard="SchoolCount" :ref="CardValue"></CardComponent>
                <CardComponent imageCard="groupSchool.gif" titleCard="Turmas Cadastradas" :valueCard="ClassAllDataCount"></CardComponent>
                <CardComponent imageCard="student.gif" titleCard="Professores Cadastradas" :valueCard="TeacherAllCount"></CardComponent>
                <CardComponent imageCard="teacher.gif" titleCard="Alunos Cadastradas" :valueCard="StudentAllCount"></CardComponent>
            </div>

            <div class="Cards-container" v-else-if="userType.type === 'school'">
                <CardComponent imageCard="groupSchool.gif" titleCard="Turmas Cadastradas" :valueCard="ClassDataCount" :ref="CardValue"></CardComponent>
                <CardComponent imageCard="teacher.gif" titleCard="Professores Cadastrados"  :valueCard="filteredTeachers"></CardComponent>
                <CardComponent imageCard="student.gif" titleCard="Alunos Cadastradas" :valueCard="StudentCount"></CardComponent>
            </div>

            <div class="Cards-container" v-else-if="userType.type === 'teacher'">
                <CardComponent imageCard="teacher.gif" titleCard="Minhas Turmas" :valueCard="ClassDataCount"></CardComponent>
                <CardComponent imageCard="student.gif" titleCard="Meus Alunos" :valueCard="filteredcountStudentTeachers"></CardComponent>
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
                <TitleComponent title="Alunos por turma"/>
                <CardgrapichComponent titleGrapichCard="Alunos por turma" ></CardgrapichComponent>
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
        margin: 5rem 0 0 0;
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
