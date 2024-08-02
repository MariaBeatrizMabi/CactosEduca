<script setup>
import MenuComponent from "../components/menu.vue";
import UserWelcomeComponent from "../components/userWelcome.vue";
import TitleComponent from "../components/title.vue";
import TableComponent from '../components/tableComponent.vue';
import {computed, onMounted, ref} from "vue";
import { useRoute } from "vue-router";
import {api} from "../api.js"
import ModalComponent from "../components/modal.vue";
import Input from "../components/input.vue";
import ModalComponentDeleted from "../components/modalComponentShort.vue";
import Breadcrumb from '../components/Breadcrumb'

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
    shift: '',
    teacher_id: null
});

const formData = ref({
    name: '',
    shift: '',
    teacher_id: null
});

const hasChangesToUpdate = computed(() =>
    Object.entries(classData.value)
        .map(([key, value]) => value === formData.value[key])
        .some((value) => !value)
)

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
    const { data } = await api.get(`/api/management-schools/${school.value.id}/classes/${classData.value.id}/students`)
    return data
}

onMounted(async () => {
    school.value = await fetchSchool()
    classData.value = await fetchClassData()
    formData.value = classData.value
    availableTeachers.value = await fetchAvailableTeachers();
    students.value = await fetchStudents()
});

function redirectToStudentScreen(id) {
    window.location.href = `/student/${id}`
}

async function openAddStudentModal() {
    availableStudents.value = await fetchAvailableStudents();
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

async function updateClassData() {
    await api.put(`/ClassSchoolUpdate/${classData.value.id}`, formData.value);
    classData.value = {
        ...classData.value,
        ...formData.value
    }
}

function resetForm() {
    formData.value = classData.value
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
        <Breadcrumb.Root style="width: 84%; margin-bottom: -1rem;">
            <Breadcrumb.Content>
                <Breadcrumb.Item>
                    <svg
                        style="margin-right: 4px"
                        height="16"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 640 512"
                    >
                        <path
                            d="M144 160A80 80 0 1 0 144 0a80 80 0 1 0 0 160zm368 0A80 80 0 1 0 512 0a80 80 0 1 0 0 160zM0 298.7C0 310.4 9.6 320 21.3 320H234.7c.2 0 .4 0 .7 0c-26.6-23.5-43.3-57.8-43.3-96c0-7.6 .7-15 1.9-22.3c-13.6-6.3-28.7-9.7-44.6-9.7H106.7C47.8 192 0 239.8 0 298.7zM320 320c24 0 45.9-8.8 62.7-23.3c2.5-3.7 5.2-7.3 8-10.7c2.7-3.3 5.7-6.1 9-8.3C410 262.3 416 243.9 416 224c0-53-43-96-96-96s-96 43-96 96s43 96 96 96zm65.4 60.2c-10.3-5.9-18.1-16.2-20.8-28.2H261.3C187.7 352 128 411.7 128 485.3c0 14.7 11.9 26.7 26.7 26.7H455.2c-2.1-5.2-3.2-10.9-3.2-16.4v-3c-1.3-.7-2.7-1.5-4-2.3l-2.6 1.5c-16.8 9.7-40.5 8-54.7-9.7c-4.5-5.6-8.6-11.5-12.4-17.6l-.1-.2-.1-.2-2.4-4.1-.1-.2-.1-.2c-3.4-6.2-6.4-12.6-9-19.3c-8.2-21.2 2.2-42.6 19-52.3l2.7-1.5c0-.8 0-1.5 0-2.3s0-1.5 0-2.3l-2.7-1.5zM533.3 192H490.7c-15.9 0-31 3.5-44.6 9.7c1.3 7.2 1.9 14.7 1.9 22.3c0 17.4-3.5 33.9-9.7 49c2.5 .9 4.9 2 7.1 3.3l2.6 1.5c1.3-.8 2.6-1.6 4-2.3v-3c0-19.4 13.3-39.1 35.8-42.6c7.9-1.2 16-1.9 24.2-1.9s16.3 .6 24.2 1.9c22.5 3.5 35.8 23.2 35.8 42.6v3c1.3 .7 2.7 1.5 4 2.3l2.6-1.5c16.8-9.7 40.5-8 54.7 9.7c2.3 2.8 4.5 5.8 6.6 8.7c-2.1-57.1-49-102.7-106.6-102.7zm91.3 163.9c6.3-3.6 9.5-11.1 6.8-18c-2.1-5.5-4.6-10.8-7.4-15.9l-2.3-4c-3.1-5.1-6.5-9.9-10.2-14.5c-4.6-5.7-12.7-6.7-19-3l-2.9 1.7c-9.2 5.3-20.4 4-29.6-1.3s-16.1-14.5-16.1-25.1v-3.4c0-7.3-4.9-13.8-12.1-14.9c-6.5-1-13.1-1.5-19.9-1.5s-13.4 .5-19.9 1.5c-7.2 1.1-12.1 7.6-12.1 14.9v3.4c0 10.6-6.9 19.8-16.1 25.1s-20.4 6.6-29.6 1.3l-2.9-1.7c-6.3-3.6-14.4-2.6-19 3c-3.7 4.6-7.1 9.5-10.2 14.6l-2.3 3.9c-2.8 5.1-5.3 10.4-7.4 15.9c-2.6 6.8 .5 14.3 6.8 17.9l2.9 1.7c9.2 5.3 13.7 15.8 13.7 26.4s-4.5 21.1-13.7 26.4l-3 1.7c-6.3 3.6-9.5 11.1-6.8 17.9c2.1 5.5 4.6 10.7 7.4 15.8l2.4 4.1c3 5.1 6.4 9.9 10.1 14.5c4.6 5.7 12.7 6.7 19 3l2.9-1.7c9.2-5.3 20.4-4 29.6 1.3s16.1 14.5 16.1 25.1v3.4c0 7.3 4.9 13.8 12.1 14.9c6.5 1 13.1 1.5 19.9 1.5s13.4-.5 19.9-1.5c7.2-1.1 12.1-7.6 12.1-14.9v-3.4c0-10.6 6.9-19.8 16.1-25.1s20.4-6.6 29.6-1.3l2.9 1.7c6.3 3.6 14.4 2.6 19-3c3.7-4.6 7.1-9.4 10.1-14.5l2.4-4.2c2.8-5.1 5.3-10.3 7.4-15.8c2.6-6.8-.5-14.3-6.8-17.9l-3-1.7c-9.2-5.3-13.7-15.8-13.7-26.4s4.5-21.1 13.7-26.4l3-1.7zM472 384a40 40 0 1 1 80 0 40 40 0 1 1 -80 0z"
                        />
                    </svg>
                    <a href="/Management">Gerenciamento</a>
                </Breadcrumb.Item>
                <Breadcrumb.Item>Turmas</Breadcrumb.Item>
                <Breadcrumb.Item>
                    <a :href="`/class/${classData.id}`">{{ classData.name }}</a>
                </Breadcrumb.Item>
            </Breadcrumb.Content>
        </Breadcrumb.Root>

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

            <label>
                Turno
                <select
                    id="class"
                    name="class"
                    class="input"
                    :value="formData.shift"
                    @input="formData = { ...formData, shift: $event.target.value }"
                >
                    <option value="" disabled>Selecione uma opção</option>
                    <option value="morning">Matutino</option>
                    <option value="afternoon">Vespertino</option>
                    <option value="night">Noturno</option>
                </select>
            </label>
        </div>

        <div v-if="hasChangesToUpdate" class="student-form-actions-container">
            <button class="student-form-action-button" @click="updateClassData">Salvar</button>
            <button class="student-form-action-button" @click="resetForm">Cancelar</button>
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
    margin: 5rem 0;
    width: 100%;
    gap: 2rem;
}

.class-form {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-auto-rows: 1fr;
    grid-column-gap: 3rem;
    grid-row-gap: 1rem;
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
        padding: 0 1rem 0 0;

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

.student-form-actions-container {
    display: flex;
    gap: 1rem;
}

.student-form-action-button {
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    font-size: 15px;
    border: 2px solid var(--secondary-color);
    text-align: center;
    border-radius: 4rem;
    background-color: transparent;
    color: var(--secondary-color);
    padding: 0.6rem 1.6rem;
    background-color: #fff;
    right: 0;
    font-weight: 700;
}
</style>
