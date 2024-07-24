<script setup>
import MenuComponent from "../components/menu.vue";
import UserWelcomeComponent from "../components/userWelcome.vue";
import TitleComponent from "../components/title.vue";
import TableComponent from '../components/tableComponent.vue';
import {onMounted, ref} from "vue";
import { useRoute } from "vue-router";
import {api} from "../api.js"
import ModalComponent from "../components/modal.vue";
import Input from "../components/input.vue";
import ModalComponentDeleted from "../components/modalComponentShort.vue";

const route = useRoute();

const showAddStudentModal = ref(false);
const showRemoveStudentModal = ref(false);

const studentIdToRemove = ref(null);

const school = ref({});
const availableStudents = ref([]);
const availableTeachers = ref([]);
const students = ref([]);

const selectedStudentToAdd = ref(null);

const classData = ref({
    id: null,
    name: '',
    teacher_id: null
});

const formData = ref({
    name: '',
    teacher_id: null
});

async function fetchSchool() {
    const { data: loginUserData } = await api.get('/loginUser');

    if (loginUserData.type === 'teacher') {
        const { data: teacher } = await api.get(`/api/users/${loginUserData.id}/teacher`);
        const { data } = await api.get(`/api/management-schools/${teacher.teacher.school_id}`);

        return data
    } else if (loginUserData.type === 'school') {
        const { data } = await api.get(`/api/users/${loginUserData.id}/management-schools`);
        return data.management_school
    }
}

async function fetchClassData() {
    const { data } = await api.get(`/api/classes/${route.params.class}`)
    return data
}

async function fetchAvailableTeachers() {
    const { data } = await api.get(`/api/management-schools/${school.value.id}/teachers`);

    return data;
}

async function fetchStudents() {
    const { data } = await api.get(`/api/classes/${route.params.class}/students`)
    return data.map(({ id, name, age }) => ({ id, name, age }));
}

async function fetchAvailableStudents() {
    const { data } = await api.get(`/api/management-schools/${school.value.id}/students`)
    return data
}

onMounted(async () => {
    school.value = await fetchSchool()
    availableStudents.value = await fetchAvailableStudents()
    classData.value = await fetchClassData()
    formData.value = classData.value
    availableTeachers.value = await fetchAvailableTeachers();
    students.value = await fetchStudents()
});

function redirectToStudentScreen(id) {
    window.location.href = `/student/${id}`
}

function openAddStudentModal() {
    showAddStudentModal.value = true;
}

async function submitAddStudent() {
    await api.post(`/api/classes/${classData.value.id}/students/${selectedStudentToAdd.value}`);
    students.value = await fetchStudents();
    showAddStudentModal.value = false;
}

function openRemoveStudentModal(id) {
    studentIdToRemove.value = id;
    showRemoveStudentModal.value = true;
}

function closeRemoveStudentModal() {
    studentIdToRemove.value = null;
    showRemoveStudentModal.value = false;
}

async function submitRemoveStudent() {
    await api.delete(`/api/classes/${classData.value.id}/students/${studentIdToRemove.value}`);
    students.value = await fetchStudents();
    closeRemoveStudentModal();
}
</script>

<template>
<div class="content-wrapper">
    <MenuComponent />
    <UserWelcomeComponent />

    <ModalComponent v-if="showAddStudentModal" Titlevalue="Adicionar aluno">
        <div class="modal-body-size">
            <div class="modal-content-details">
                <div class="input-component">
                    <label>Aluno</label>
                    <div class="input-formating">
                        <svg width="9" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                            <path d="M337.8 5.4C327-1.8 313-1.8 302.2 5.4L166.3 96H48C21.5 96 0 117.5 0 144V464c0 26.5 21.5 48 48 48H256V416c0-35.3 28.7-64 64-64s64 28.7 64 64v96H592c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48H473.7L337.8 5.4zM96 192h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V208zM96 320h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V336c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V336zM232 176a88 88 0 1 1 176 0 88 88 0 1 1 -176 0zm88-48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H336V144c0-8.8-7.2-16-16-16z"/>
                        </svg>
                        <hr>
                        <select v-model="selectedStudentToAdd">
                            <option v-bind:value="null" disabled selected>Selecione um aluno</option>
                            <option v-for="row in availableStudents" v-bind:value="row.id">{{ row.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-end">
            <a style="margin-right: 5rem;" class="close-modal" @click="showAddStudentModal = false">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="red" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path>
                </svg>
                Fechar
            </a>
            <a class="school-add" @click="submitAddStudent">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="var(--secondary-color)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>
                </svg>
                Adicionar aluno
            </a>
        </div>
    </ModalComponent>

    <ModalComponentDeleted v-if="showRemoveStudentModal" Titlevalue="Apagar professor">
        <h1 class="deleted-title">
            Você realmente deseja remover o aluno(a) <strong>{{ students.find(({ id }) => id === studentIdToRemove)?.name }}</strong> dessa turma? Esta é uma ação permanente que não poderá ser desfeita
        </h1>
        <div class="modal-end-deleted">
            <a class="close-modal-deleted" @click="closeRemoveStudentModal">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="var(--black-color)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path>
                </svg>
                Cancelar
            </a>
            <a ref="link" @click="submitRemoveStudent">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="red" d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                </svg>
                Remover aluno
            </a>
        </div>
    </ModalComponentDeleted>

    <div class="content-container">
        <TitleComponent title="DADOS DA TURMA" />
        <div class="class-form">
            <label>
                Nome da turma
                <input
                    id="name"
                    name="name"
                    placeholder="Nome da turma"
                    type="text"
                    class="input"
                    :value="formData.name"
                    @input="formData = { ...formData, name: $event.target.value }"
                />
            </label>

            <label>
                Professor responsável
                <select
                    id="class"
                    name="class"
                    placeholder="Professor responsável"
                    type="text"
                    class="input"
                    :value="formData.teacher_id"
                    @input="formData = { ...formData, teacher_id: $event.target.value }"
                >
                    <option v-for="row in availableTeachers" :value="row.id">{{ row.name }}</option>
                </select>
            </label>
        </div>

        <TableComponent
            TitleValue="ALUNOS DA TURMA"
            :TableHeader="['Nome', 'Idade']"
            :TableContent="students"
            :TableActions="true"
            :TableActionVisibility="true"
            :TableActionUpdate="false"
            :TableAddButton="true"
            :ButtonTitle="'Adicionar Aluno'"
            :OpenAddModal="openAddStudentModal"
            @viewDetails="redirectToStudentScreen"
            @deletedAction="openRemoveStudentModal"
        />
    </div>
</div>
</template>

<style scoped>
.content-wrapper {
    display: flex;
    width: 100vw;
    height: 100vh;
}

.content-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 8rem 0;
    width: 100%;
    gap: 2rem;
}

.class-form {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-auto-rows: 1fr;
    grid-gap: 3rem;
    width: 84%;
}

.input {
    width: 100%;
    border: 0 solid var(--secondary-color);
    border-bottom-width: 1px;
    background-color: transparent;
    outline: 0;
    font-size: 13pt;
    color: var(--primary-color);
    font-weight: bold;
}

.input-component {
    display: flex;
    flex-direction: column;

    & label {
        font-size: 14px;
        margin-bottom: 3px;
        font-weight: 600;
        color: var(--black-color);
    }

    & select {
        width: 100%;
        padding: 1rem;
        border-radius: 3rem;
        border: 0;
        border-left: 0;
        background-color: var(--grap-color);
        outline: 0;
        font-size: 13pt;
    }

    & .input-formating {
        display: flex;
        background-color: var(--grap-color);
        border: 2px solid var(--primary-color);
        border-radius:  2rem;

        & svg {
            background-color: var(--grap-color);
            border: 0;
            border-radius: 5rem;
            margin-left: 0.5rem;
            width: 3.5rem;
            padding: 0 1rem;
            fill: var(--primary-color);
        }

        & hr {
            margin: 1rem 0;
        }
    }
}

@media (max-width: 700px) {
    .input-component {
        & input {
            padding: 0.8rem 1rem 0.8rem 1rem;
        }
    }
}
</style>
