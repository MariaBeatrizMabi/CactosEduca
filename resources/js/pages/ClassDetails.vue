<script setup>
import MenuComponent from "../components/menu.vue";
import UserWelcomeComponent from "../components/userWelcome.vue";
import TitleComponent from "../components/title.vue";
import TableComponent from '../components/tableComponent.vue';
import {computed, onMounted, ref, toRaw} from "vue";
import { useRoute } from "vue-router";
import { api } from "../services/api"
import ModalComponent from "../components/modal.vue";
import ModalComponentDeleted from "../components/modalComponentShort.vue";
import Breadcrumb from '../components/Breadcrumb'
import { exportClassStudentsData, exportClassStudentsSampleData } from '../services/export';
import { importClassStudents } from '../services/import';
import LoadingComponent from "../components/loading.vue";
import CreateExamModal from "../components/createExamModal.vue";
import Checkbox from "../components/checkbox.vue";
import SelectComponent from "../components/SelectComponent.vue";
import axios from "axios";

const route = useRoute();

const showAddStudentModal = ref(false);
const showRemoveStudentModal = ref(false);

const studentIdToRemove = ref(null);

const school = ref({});
const availableStudents = ref([]);
const availableTeachers = ref([]);
const students = ref([]);
const isLoading = ref(false);
const openedCreateExamModal = ref(false);
const studentIdToCreateExam = ref(null);
const createExamData = ref({
    reading: '',
    writing: '',
    action: null
});

const hasErrors = ref({
    reading: false,
    writing: false
})
const literacyParameters = ref({});
const selectedLiteracyParameters = ref([]);
const selectedStudentToAdd = ref('');

const classData = ref({
    id: null,
    name: '',
    shift: '',
    teacher_id: null
});
const clearErrors = () => {
    hasErrors.value = {
        reading: false,
        writing: false
    }
}
const validateData = () => {
    clearErrors()

    if(!createExamData.value.reading || createExamData.value.reading === ''){
        hasErrors.value.reading = true
    }

    if(!createExamData.value.writing || createExamData.value.writing === ''){
        hasErrors.value.writing = true
    }
}

const formData = ref({
    name: '',
    shift: '',
    teacher_id: null
});

const showCloseClass = ref(false);

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
    try{
        const { data } = await api.get(`/api/management-schools/${school.value.id}/teachers`);
        return data;
    } catch (e) {
        return null
    }


}

async function fetchStudents() {
    const { data } = await api.get(`/api/classes/${route.params.class}/students`)
    return data.map(({ id, name }) => ({ id, name }));
}

async function fetchAvailableStudents() {
    try{
        const { data } = await api.get(`/api/management-schools/${school.value.id}/classes/${classData.value.id}/students`);
        return data
    } catch (e) {
        const { data } = await api.get(`/api/management-schools/${classData.value.school_id}/classes/${classData.value.id}/students`);
        return data
    }
}

const getliteracyParameters = async () => {
    const response = await api.get('/api/literacy_parameters')
    literacyParameters.value = response.data.data;
}

onMounted(async () => {
    school.value = await fetchSchool()
    classData.value = await fetchClassData()
    formData.value = classData.value
    availableTeachers.value = await fetchAvailableTeachers();
    availableStudents.value = await fetchAvailableStudents();
    students.value = await fetchStudents()
    await getliteracyParameters();
});

function redirectToStudentScreen(id) {
    window.location.href = `/student/${id}`
}

async function openAddStudentModal() {
    showAddStudentModal.value = true;
}

async function submitAddStudent() {
    await api.post(`/api/classes/${classData.value.id}/students/${selectedStudentToAdd.value}`);
    students.value = await fetchStudents();
    showAddStudentModal.value = false;
    selectedStudentToAdd.value = '';
    isLoading.value = true;
    setTimeout(() => {
        isLoading.value = false;
    }, 800);
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
    isLoading.value = true;
    setTimeout(() => {
        isLoading.value = false;
        window.location.reload();
    }, 800);
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

async function submitCloseClass() {
    await api.post(`/api/classes/${route.params.class}/close`);
    window.location.href = '/Management';
}

async function handleImportData() {
    await importClassStudents(toRaw(availableStudents.value), classData.value.id);
    students.value = await fetchStudents();
    availableStudents.value = await fetchAvailableStudents();
}

const openCreateExamModal = (studentId) => {
    createExamData.value = {
        reading: '',
        writing: '',
        action: null
    };

    clearErrors();
    studentIdToCreateExam.value = studentId
    openedCreateExamModal.value = true
}

const updateLiteracyValue = (literacyParameterValueId) => {
    const position = selectedLiteracyParameters.value.indexOf(literacyParameterValueId);
    if (position !== -1){
        selectedLiteracyParameters.value.splice(position, 1)
    } else {
        selectedLiteracyParameters.value.push(literacyParameterValueId)
    }
}

const literacyParameterTranslator = (parameter) => {
    const parameters = {
        write_name: 'Escreve o nome',
        recognize_write_alphabet: 'Reconhece e escreve o alfabeto',
        recognize_write_vocal_encounters: 'Reconhece e escreve encontros vocálicos',
        recognize_write_syllable_family: 'Reconhece e escreve familias silábicas',
        recognize_write_number: 'Reconhece e escreve numeros',
    }

    return parameters[parameter];
}

async function getStudentExams(studentId) {
    const { data } = await api.get(`/api/students/${studentId}/classes/${classData.value.id}/exams`);
    return data?.reverse();
}

const submitExamCreated = async () => {
    validateData();

    if(!(hasErrors.value.reading || hasErrors.value.writing)){
        const studentExams = await getStudentExams(studentIdToCreateExam.value);
        isLoading.value = true

        try {

            const createdPoll = await axios.post(`/PollCreate`, {
                name: studentExams.length + 1 + '° Período de sondagem',
                class_id: classData.value.id,
                poll_number: studentExams.length + 1,
                school_id: school.value.id,
                year: classData.value.id
            });

            await api.post(`/api/exams`, {
                ...createExamData.value,
                student_id: studentIdToCreateExam.value,
                class_id: classData.value.id,
                poll_id: createdPoll.data.id,
                literacy_parameters_values: selectedLiteracyParameters.value
            });

            createExamData.value = {
                reading: '',
                writing: '',
                action: null
            }

        } catch (error) {
            console.error("Erro ao criar a pesquisa:", error);
        }
        openedCreateExamModal.value = false;
        isLoading.value = false;
    }

}



</script>

<template>
<LoadingComponent :isLoading="isLoading" />
<div class="content-wrapper">
    <MenuComponent />
    <UserWelcomeComponent />

    <CreateExamModal
         v-if='openedCreateExamModal'
         Titlevalue="Cadastro de Sondagens"
         @close-modal="openedCreateExamModal = false"
    >

        <div class="modal-body-size">
            <h2>Detalhes da sondagem</h2>
            <a href="/documentos/instrucoes.pdf" target="_blank">Mais informações sobre ações de intervenção - Escrita</a><br>
            <a href="/documentos/instrucoesLeitura.pdf" target="_blank">Mais informações sobre ações de intervenção - Leitura</a>

            <div class="modal-content-div mobile">
                <div class="modal-content-div-error-div">
                    <SelectComponent
                        labelTitle="Nível de leitura"
                        placeholderValue="Nível de leitura"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                        typeValue="select"
                        :value="createExamData.reading"
                        valueField="id"
                        RightAction="display: none;"
                        @input="createExamData.reading = $event.target.value"
                    >
                        <option value="not_reader">Não leitor</option>
                        <option value="syllable_reader">Leitor de sílabas</option>
                        <option value="word_reader">Leitor de palavras</option>
                        <option value="sentence_reader">Leitor de frases</option>
                        <option value="no_fluent_text_reader">Leitor de texto sem fluência</option>
                        <option value="fluent_text_reader">Leitor de texto com fluência</option>
                        <option value="missed">Faltou</option>
                        <option value="transferred">Transferido</option>
                    </SelectComponent>
                    <div class="alert alert-danger" role="alert" v-if="hasErrors.reading">
                        <span class="error-span font-medium">O campo nível de leitura é obrigatório</span>
                    </div>
                </div>

                <div class="modal-content-div-error-div mobile">
                    <SelectComponent
                        labelTitle="Nível de escrita"
                        placeholderValue="Nível de escrita"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                        typeValue="select"
                        :value="createExamData.writing"
                        valueField="id"
                        RightAction="display: none;"
                        @input="createExamData.writing = $event.target.value"
                    >
                        <option value="pre_syllabic">Pré silábico</option>
                        <option value="syllabic">Silábico</option>
                        <option value="alphabetical_syllabic">Silábico alfabético</option>
                        <option value="alphabetical">Alfabético</option>
                        <option value="missed">Faltou</option>
                        <option value="transferred">Transferido</option>
                    </SelectComponent>
                    <div class="alert alert-danger" role="alert" v-if="hasErrors.writing">
                        <span class="error-span font-medium">O campo nível de leitura é obrigatório</span>
                    </div>
                </div>

                <span class="textarea-wrapper mobile">
                    <h3>Ações de Intervenção</h3>
                    <textarea
                        class="w-full"
                        :value="createExamData.action"
                        @input="createExamData.action = $event.target.value"
                        rows="12"
                    ></textarea>
                </span>

                <div v-if="classData.type === 'preschool'" class="mobile" v-for="(literacyParameter, index) in literacyParameters" :key="index">
                    <h3>{{literacyParameterTranslator(literacyParameter.literacy_parameter)}}</h3>
                    <div v-for="(value, index) in literacyParameter.values" :key="index">
                        <Checkbox
                            :isChecked="selectedLiteracyParameters.includes(value.id)"
                            :label="value.name_to_show"
                            @change="() => updateLiteracyValue(value.id)"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-end">
            <a class="close-modal btn" @click="openedCreateExamModal = false">
                <svg
                    width="20"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                >
                    <path
                        fill="red"
                        d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"
                    ></path>
                </svg>
                Cancelar
            </a>
            <a class="school-add btn" @click="submitExamCreated">
                <svg
                    width="20"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                >
                    <path
                        fill="var(--secondary-color)"
                        d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"
                    />
                </svg>
                Adicionar sondagem
            </a>
        </div>
    </CreateExamModal>

    <ModalComponent v-if="showAddStudentModal" Titlevalue="Adicionar aluno">
        <div class="modal-body-size">
            <div class="modal-content-div">
                <div class="input-component">
                    <label>Aluno</label>
                    <div class="input-formating">
                        <svg width="9" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                            <path d="M337.8 5.4C327-1.8 313-1.8 302.2 5.4L166.3 96H48C21.5 96 0 117.5 0 144V464c0 26.5 21.5 48 48 48H256V416c0-35.3 28.7-64 64-64s64 28.7 64 64v96H592c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48H473.7L337.8 5.4zM96 192h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V208zM96 320h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V336c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V336zM232 176a88 88 0 1 1 176 0 88 88 0 1 1 -176 0zm88-48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H336V144c0-8.8-7.2-16-16-16z"/>
                        </svg>
                        <hr>
                        <select v-model="selectedStudentToAdd">
                            <option value="" disabled selected>Selecione um aluno</option>
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

    <ModalComponentDeleted v-if="showCloseClass" Titlevalue="Fechar turma">
        <h1 class="deleted-title">
            Você realmente deseja fechar essa turma? Esta é uma ação permanente que não poderá ser desfeita.
        </h1>
        <div class="modal-end-deleted">
            <a class="close-modal-deleted" @click="showCloseClass = false">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="var(--black-color)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path>
                </svg>
                Cancelar
            </a>
            <a ref="link" @click="submitCloseClass">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="red" d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                </svg>
                Continuar
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
                    <a :href="`/classesFilter/${classData.id}`">{{ classData.name }}</a>
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
            :TableHeader="['Nome']"
            :TableContent="students"
            :TableActions="true"
            :TableActionVisibility="true"
            :TableActionUpdate="false"
            :TableAddButton="true"
            :ButtonTitle="'Adicionar Aluno'"
            :OpenAddModal="openAddStudentModal"
            :InsertExam="true"
            @viewDetails="redirectToStudentScreen"
            @createExam="openCreateExamModal"
            @deletedAction="openRemoveStudentModal"
            @exportData="exportClassStudentsData(students.map(({ name }) => ({
                studentName: name,
                className: classData.name
            })))"
            @exportSampleData="exportClassStudentsSampleData"
            @importData="handleImportData"
        />

        <button class="close-class" @click="showCloseClass = true">
            Fechar turma
        </button>
    </div>
</div>
</template>

<style scoped>

.btn {
    width: 15rem !important;
}

.mobile {
    margin-bottom: 0;
}

@media (max-width: 700px) {
    .school-add{
        margin-right: 32px !important;
        margin-left: 32px !important;
    }

    .close-modal{
        margin-right: 32px !important;
        margin-left: 32px !important;
    }


}

@media (max-width: 950px) {
    .modal-content-div {
        display: block !important;
    }

    .mobile{
        margin-bottom: 20px;
    }

    .school-add{
        margin-right: 32px !important;
        margin-left: 32px !important;
    }

    .close-modal{
        margin-right: 32px !important;
        margin-left: 32px !important;
    }
}

.alert {
    position: relative;
    padding: 5px 20px;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: 4px;
    font-size: 16px;
    line-height: 1.5;
    display: flex;
    align-items: center;
}

.modal-content-div-error-div {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 1rem;
    width: 100%;
}

.modal-content-div {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    margin: 2rem 0;
    width: 100%;
}

.error-span {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.close-class {
    all: unset;
    cursor: pointer;
    width: 84%;
    padding: 1rem;
    background-color: #F00;
    text-align: center;
    border-radius: 0.8rem;
    font-weight: bold;
    color: #fff;
}

.content-wrapper {
    overflow-x: hidden;
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
    overflow-x: hidden;
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

.textarea-wrapper {
    grid-area: 2 / 1 / 3 / 3;
    display: flex;
    flex-direction: column;
    border: 2px solid var(--primary-color);
    border-radius: 1rem;
    align-items: center;
    cursor: text;
    background-color: white;
    padding: 5px 0;

    & > h3 {
        padding: 10px 0;
    }
}

textarea {
    width: 100%;
    background-color: transparent;
    padding: 1rem;
    outline: none;
    border: none;
    resize: none;
    color: var(--primary-color);
    font-weight: 500;
    font-size: 1.1rem;
}

@media (max-width: 750px) {
    .import-actions {
        display: flex;
        flex-direction: row;
        justify-content: right;
        position: relative;
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
    color: var(--secondary-color);
    padding: 0.6rem 1.6rem;
    background-color: #fff;
    right: 0;
    font-weight: 700;
}

@media screen and (min-width: 1200px) {
    .student-form {
        grid-template-columns: 2fr 1fr;
    }
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}
</style>
