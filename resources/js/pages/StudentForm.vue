<script setup>
import MenuComponent from "../components/menu.vue";
import UserWelcomeComponent from "../components/userWelcome.vue";
import TitleComponent from "../components/title.vue";
import { computed, onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { api } from '../services/api';
import Breadcrumb from '../components/Breadcrumb'
import Modal from "../components/modal.vue";
import Checkbox from "../components/checkbox.vue";
import SelectComponent from "../components/SelectComponent.vue";
import { translate } from '../utils/translate';
import { Dropdown } from "../components/Dropdown";
import { exportExamsData, exportExamsSampleData } from "../services/export";
import { importExams } from "../services/import";
import axios from "axios";
import Table from "../components/table.vue";
import CreateExamModal from "../components/createExamModal.vue";

const route = useRoute();
const schoolId = ref();
const studentId = route.params.student;
const studentInterventions = ref();
const literacyParameters = ref();
const selectedLiteracyParameters = ref([]);
const examLiteracyParameters = ref([])
const hasErrors = ref({
    reading: false,
    writing: false
});

const studentData = ref({
    name: '',
    age: null,
    group_id: null,
    enrollment_date: '',
    needAction: false,
    actionStatus: '',
    comments: '',
    people_with_disabilities: ''
});

const formData = ref({
    name: '',
    age: null,
    group_id: null,
    enrollment_date: '',
    needAction: false,
    actionStatus: '',
    people_with_disabilities: ''
});

const classData = ref({
    id: null,
})

const studentExams = ref([]);
const availableClassSchool = ref([]);

const createExamData = ref({
    reading: '',
    writing: '',
    action: null
});

const updateExamData = ref({
    reading: '',
    writing: '',
    action: null
})

const userID = ref("");
const userType = ref("");

async function getUserType() {
    const { data: loginUserData } = await axios.get("/loginUser");
    userType.value = loginUserData.type;
    userID.value = loginUserData.id;
}

const showExamCreateModal = ref(false);
const showExamViewModal = ref(false);
const showExamUpdateModal = ref(false);

const activeExamId = ref(null);
const activeExamData = computed(() => studentExams.value.find(({ id }) => id === activeExamId.value));

const hasChangesToUpdate = computed(() =>
    Object.entries(studentData.value)
        .filter(([key]) => key != 'comments')
        .map(([key, value]) => value == formData.value[key])
        .some((value) => !value)
)

const getliteracyParameters = async () => {
    const response = await api.get('/api/literacy-parameters')
    literacyParameters.value = response.data.data;
}

async function updateStudent() {
    const peopleWithDisabilities = JSON.parse(formData.value?.people_with_disabilities);

    await api.put(`/api/students/${route.params.student}`, {
        ...formData.value,
        people_with_disabilities: peopleWithDisabilities
    });
    studentData.value = { ...formData.value };
}

const pollData = ref({
    name: '',
    school_id: null,
    class_id: null,
    active: true,
    average: null,
    year: null,
});

const validateData = () => {
    clearErrors()

    if(!createExamData.value.reading || createExamData.value.reading === ''){
        hasErrors.value.reading = true
    }

    if(!createExamData.value.writing || createExamData.value.writing === ''){
        hasErrors.value.writing = true
    }
}

async function submitPollCreated(name) {
    validateData()

    if(!(hasErrors.value.writing || hasErrors.value.reading )){
        try {
            const response = await axios.get(`/Teachers`);
            const teacher = response.data.find(teacher => teacher.user_id === userID.value);

            if (teacher) {
                const schoolId = teacher.school_id;
                let createdPoll = null;

                if (userType === 'teacher') {
                    createdPoll = await axios.post(`/PollCreate`, {
                        name: studentExams.value.length + 1 + '° Período de sondagem',
                        class_id: classData.value.id,
                        poll_number: studentExams.value.length + 1,
                        school_id: schoolId,
                        year: classData.value.id
                    });
                } else {
                    createdPoll = await axios.post(`/PollCreate`, {
                        name: studentExams.value.length + 1 + '° Período de sondagem',
                        class_id: classData.value.id,
                        school_id: schoolId,
                        poll_number: studentExams.value.length + 1,
                        year: classData.value.id
                    });
                }

                submitExamCreate(createdPoll.data.id);
                await getUserType();
                await getStudent();
                showExamCreateModal.value = false;

            } else {
                console.error("Professor não encontrado ou não possui um user_id correspondente.");
            }
        } catch (error) {
            console.error("Erro ao criar a pesquisa:", error);
        }
    }


}

async function submitExamCreate(createdPollId) {
    await api.post(`/api/exams`, {
        ...createExamData.value,
        student_id: route.params.student,
        class_id: classData.value.id,
        poll_id: createdPollId,
        literacy_parameters_values: selectedLiteracyParameters.value
    });

    studentExams.value = await getStudentExams()
    showExamCreateModal.value = false

    createExamData.value = {
        reading: '',
        writing: '',
        action: null
    }

    await getStudent();


}

const clearErrors = () => {
    hasErrors.value = {
        reading: false,
        writing: false
    }
}

const openExamCreateModal = () => {
    createExamData.value = {
        reading: '',
        writing: '',
        action: null
    }

    clearErrors();

    selectedLiteracyParameters.value = [];
    showExamCreateModal.value = true;
}

async function submitExamUpdate() {
    updateExamData.value.literacy_parameters_values = selectedLiteracyParameters.value
    await api.put(`/api/exams/${activeExamId.value}`, updateExamData.value);

    selectedLiteracyParameters.value = [];
    studentExams.value = await getStudentExams()
    showExamUpdateModal.value = false
}

async function submitExamDelete(id) {
    await api.delete(`/api/exams/${id}`);
    studentExams.value = await getStudentExams()
}

async function getActiveStudentClass() {
    const { data } = await api.get(`/api/students/${route.params.student}/class`);
    return data;
}

async function getStudentExams() {
    const { data } = await api.get(`/api/students/${route.params.student}/classes/${classData.value.id}/exams`);
    return data?.reverse();
}

function resetForm() {
    formData.value = studentData.value
}

const getStudent = async () => {
    const { data } = await api.get(`/api/students/${route.params.student}`);

    studentData.value = {
        ...data,
        needAction: false,
        actionStatus: '',
    };

    studentInterventions.value = (await api.get(`/api/student/all-interventions/${studentData.value.id}`)).data;

    formData.value = studentData.value

    const { data: classSchoolData } = await api.get('/ClassSchool');
    availableClassSchool.value = classSchoolData

    classData.value = await getActiveStudentClass()

    if (classData.value?.id) {
        studentExams.value = await getStudentExams()
    }
}

onMounted(async () => {
    await getUserType();
    await getStudent();
    await getliteracyParameters();
});

function openShowExamModal(id) {
    showExamViewModal.value = true;
    activeExamId.value = id;
    verifyIfLiteracyParameterIsChecked(activeExamData.value.literacy_parameter_values)
}

function openExamUpdateModal(id) {
    showExamUpdateModal.value = true;
    activeExamId.value = id;

    updateExamData.value = {...activeExamData.value};
    verifyIfLiteracyParameterIsChecked(updateExamData.value.literacy_parameter_values)
}

async function handleImportExams() {
    await importExams(route.params.student, classData.value.id);
    studentExams.value = await getStudentExams();
}

const showInterventionModal = ref(false);
const interventions = ref([]);
const selectedInterventions = ref([]);
const pollIdD = ref(null);
const selectedInterventionsPoll = ref({});
const writingType = ref('');

const saveSelectedInterventions = (pollId) => {
    localStorage.setItem(`selectedInterventions_${pollId}`, JSON.stringify(selectedInterventionsPoll.value[pollId]));
};

const loadSelectedInterventions = (pollId) => {
    const storedInterventions = localStorage.getItem(`selectedInterventions_${pollId}`);
    if (storedInterventions) {
        selectedInterventionsPoll.value[pollId] = JSON.parse(storedInterventions);
        selectedInterventions.value = [...selectedInterventionsPoll.value[pollId]];
    }
};
const openInterventionModal = async (writing, pollId) => {

    pollIdD.value = null;
    interventions.value = [];
    showInterventionModal.value = false;
    pollIdD.value = pollId;
    writingType.value = writing;

    try {
        const response = await api.get(`/api/interventions/${writing}/${studentId}/${pollId}`);

        interventions.value = response.data.interventions;

        if (!selectedInterventionsPoll.value[pollId]) {
            selectedInterventionsPoll.value[pollId] = [];
        }

        selectedInterventions.value = selectedInterventionsPoll.value[pollId];
        loadSelectedInterventions(pollId);
        showInterventionModal.value = true;
    } catch (error) {
        console.error("Erro na intervenção:", error);
    }
};

const getExamIdForStudent = async (pollIdD) => {
    try {
        const response = await api.get(`/api/interventions/exam/${studentId}/${pollIdD}`);
        return response.data.exam_id;
    } catch (error) {
        console.error("Erro ao obter o ID do exame:", error);
        return null;
    }

};

const updateLiteracyValue = (literacyParameterValueId) => {
    const position = selectedLiteracyParameters.value.indexOf(literacyParameterValueId);
    if (position !== -1){
        selectedLiteracyParameters.value.splice(position, 1)
    } else {
        selectedLiteracyParameters.value.push(literacyParameterValueId)
    }
}

const updateIntervention = (interventionId) => {
    const pollInterventions = selectedInterventionsPoll.value[pollIdD.value];
    const index = pollInterventions.indexOf(interventionId);

    if (index === -1) {
        pollInterventions.push(interventionId);
    } else {
        pollInterventions.splice(index, 1);
    }

    selectedInterventions.value = [...pollInterventions];
    saveSelectedInterventions(pollIdD.value);
};

const submitIntervention = async () => {
    try {
        await api.post('/api/students/interventions', {
            selectedInterventions: selectedInterventionsPoll.value[pollIdD.value],
            exam_id: pollIdD.value,
        });

        await getStudent();
        showInterventionModal.value = false;
    } catch (error) {
        console.error(error);
    }
};

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

const verifyIfLiteracyParameterIsChecked = (examParameters) => {
    examLiteracyParameters.value = [];

    examParameters.forEach((parameter) => {
        examLiteracyParameters.value.push(parameter.id)
    })

    selectedLiteracyParameters.value = examLiteracyParameters.value
}

const showTooltip = (elementId) => {
    const element = document.getElementById(elementId)
    element.classList.add('d-flex')
    element.classList.remove('d-none')
}

const hideTooltip = (elementId) => {
    const element = document.getElementById(elementId)
    element.classList.remove('d-flex')
    element.classList.add('d-none')
}
</script>

<template>
    <div class="school-register">
        <MenuComponent />
        <UserWelcomeComponent />

        <div class="register-content">
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
                    <Breadcrumb.Item>Alunos</Breadcrumb.Item>
                    <Breadcrumb.Item>
                        <a :href="`/student/${route.params.student}`">{{ studentData.name }}</a>
                    </Breadcrumb.Item>
                </Breadcrumb.Content>
            </Breadcrumb.Root>

            <TitleComponent title="DADOS REFERENTES AO ALUNO" />
            <div class="student-form-wrapper">
                <form class="student-form">
                    <div>
                        <label>
                            Nome do aluno
                            <input
                                id="name"
                                name="name"
                                placeholder="Nome do aluno"
                                type="text"
                                class="input"
                                :value="formData.name"
                                @input="formData = { ...formData, name: $event.target.value }"
                            />
                        </label>

                        <label>
                            Sexo
                            <select
                                id="class"
                                name="class"
                                class="input"
                                :value="formData.gender"
                                @input="formData = { ...formData, gender: $event.target.value }"
                            >
                                <option value="" disabled>Selecione uma opção</option>
                                <option value="male">Masculino</option>
                                <option value="female">Feminino</option>
                            </select>
                        </label>

                        <label>
                            Data de nascimento
                            <input
                                id="class"
                                name="class"
                                placeholder="Data de nascimento"
                                type="date"
                                class="input"
                                :value="formData.date_of_birth"
                                @input="formData = { ...formData, date_of_birth: $event.target.value }"
                            />
                        </label>

                        <label>
                            Matrícula
                            <input
                                id="name"
                                name="name"
                                placeholder="Matrícula"
                                type="text"
                                class="input"
                                :value="formData.enrollment"
                                @input="formData = { ...formData, enrollment: $event.target.value }"
                            />
                        </label>

                        <label>
                            Matriculado em
                            <input
                                id="class"
                                name="class"
                                placeholder="Matriculado em"
                                type="date"
                                class="input"
                                :value="formData.enrollment_date"
                                @input="formData = { ...formData, enrollment_date: $event.target.value }"
                            />
                        </label>

                        <label>
                            PCD
                            <select
                                id="class"
                                name="class"
                                class="input"
                                :value="formData.people_with_disabilities"
                                @input="formData = { ...formData, people_with_disabilities: $event.target.value }"
                            >
                                <option value="" disabled>Selecione uma opção</option>
                                <option :value="true">Sim</option>
                                <option :value="false">Não</option>
                            </select>
                        </label>
                    </div>

                    <div class="student-image-wrapper">
                        <img
                            class="student-image"
                            src="https://placehold.co/300x300"
                            alt="Nome do aluno"
                        />
                    </div>
                </form>

                <div v-if="hasChangesToUpdate" class="student-form-actions-container">
                    <button class="student-form-action-button" @click="updateStudent">Salvar</button>
                    <button class="student-form-action-button" @click="resetForm">Cancelar</button>
                </div>
            </div>

            <template v-if="classData?.id">
                <TitleComponent title="SONDAGENS" />

                <div class="tests-content">
                    <div
                        v-if="studentExams?.length > 0"
                        v-for="(row, index) in studentExams"
                        class="test-table-container"
                        :key="row.id"
                    >
                        <div class="import-actions" v-if="studentExams?.length > index + 1 || studentExams?.length === 0">
                            <button
                                class="create-test"
                                @click="openExamCreateModal"
                            >
                                Adicionar sondagem
                            </button>

                            <Dropdown.Root>
                                <Dropdown.Trigger>
                                    <svg width="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                        <path fill="var(--secondary-color)" d="M8 256a56 56 0 1 1 112 0A56 56 0 1 1 8 256zm160 0a56 56 0 1 1 112 0 56 56 0 1 1 -112 0zm216-56a56 56 0 1 1 0 112 56 56 0 1 1 0-112z"/>
                                    </svg>
                                </Dropdown.Trigger>
                                <Dropdown.Portal>
                                    <Dropdown.Content>
                                        <Dropdown.Item @click="handleImportExams">
                                            <svg width="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                                <path fill="var(--secondary-color)" d="M48 448L48 64c0-8.8 7.2-16 16-16l160 0 0 80c0 17.7 14.3 32 32 32l80 0 0 288c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16zM64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-293.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0L64 0zm90.9 233.3c-8.1-10.5-23.2-12.3-33.7-4.2s-12.3 23.2-4.2 33.7L161.6 320l-44.5 57.3c-8.1 10.5-6.3 25.5 4.2 33.7s25.5 6.3 33.7-4.2L192 359.1l37.1 47.6c8.1 10.5 23.2 12.3 33.7 4.2s12.3-23.2 4.2-33.7L222.4 320l44.5-57.3c8.1-10.5 6.3-25.5-4.2-33.7s-25.5-6.3-33.7 4.2L192 280.9l-37.1-47.6z"/>
                                            </svg>
                                            Importar dados
                                        </Dropdown.Item>
                                        <Dropdown.Item @click="exportExamsSampleData">
                                            <svg width="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                                <path fill="var(--secondary-color)" d="M48 448L48 64c0-8.8 7.2-16 16-16l160 0 0 80c0 17.7 14.3 32 32 32l80 0 0 288c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16zM64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-293.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0L64 0zm90.9 233.3c-8.1-10.5-23.2-12.3-33.7-4.2s-12.3 23.2-4.2 33.7L161.6 320l-44.5 57.3c-8.1 10.5-6.3 25.5 4.2 33.7s25.5 6.3 33.7-4.2L192 359.1l37.1 47.6c8.1 10.5 23.2 12.3 33.7 4.2s12.3-23.2 4.2-33.7L222.4 320l44.5-57.3c8.1-10.5 6.3-25.5-4.2-33.7s-25.5-6.3-33.7 4.2L192 280.9l-37.1-47.6z"/>
                                            </svg>
                                            Exportar planilha modelo
                                        </Dropdown.Item>
                                        <Dropdown.Item @click="exportExamsData(studentExams)">
                                            <svg width="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                                <path fill="var(--secondary-color)" d="M48 448L48 64c0-8.8 7.2-16 16-16l160 0 0 80c0 17.7 14.3 32 32 32l80 0 0 288c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16zM64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-293.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0L64 0zm90.9 233.3c-8.1-10.5-23.2-12.3-33.7-4.2s-12.3 23.2-4.2 33.7L161.6 320l-44.5 57.3c-8.1 10.5-6.3 25.5 4.2 33.7s25.5 6.3 33.7-4.2L192 359.1l37.1 47.6c8.1 10.5 23.2 12.3 33.7 4.2s12.3-23.2 4.2-33.7L222.4 320l44.5-57.3c8.1-10.5 6.3-25.5-4.2-33.7s-25.5-6.3-33.7 4.2L192 280.9l-37.1-47.6z"/>
                                            </svg>
                                            Exportar dados
                                        </Dropdown.Item>
                                    </Dropdown.Content>
                                </Dropdown.Portal>
                            </Dropdown.Root>
                        </div>
                        <h2>{{ studentExams.length - index }}° Período de sondagem</h2>
                        <table class="test-table">
                            <tr>
                                <th>Leitura</th>
                                <th>Escrita</th>
                                <th>Ações</th>
                            </tr>
                            <tr>
                                <td>{{ translate(row.reading) }}</td>
                                <td>{{ translate(row.writing) }}</td>
                                <td>
                                    <div class="actions">
                                        <div
                                            @mouseover="showTooltip(`${row.id}-show-exam`)"
                                            @mouseleave="hideTooltip(`${row.id}-show-exam`)"
                                            class="show"
                                            @click="() => openShowExamModal(row.id)"
                                        >
                                            <svg width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                <path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/>
                                            </svg>
                                            <div class="tooltip d-none" :id="`${row.id}-show-exam`">
                                                Visualizar detalhes da sondagem
                                            </div>
                                        </div>
                                        <div
                                            @mouseover="showTooltip(`${row.id}-update-exam`)"
                                            @mouseleave="hideTooltip(`${row.id}-update-exam`)"
                                            class="edit"
                                            @click="() => openExamUpdateModal(row.id)"
                                        >
                                            <svg width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/>
                                            </svg>
                                            <div class="tooltip d-none" :id="`${row.id}-update-exam`">
                                                Atualizar sondagem
                                            </div>
                                        </div>
                                        <div
                                            @mouseover="showTooltip(`${row.id}-create-intervention`)"
                                            @mouseleave="hideTooltip(`${row.id}-create-intervention`)"
                                            class="intervention"
                                            @click="() => openInterventionModal(row.writing, row.id)"
                                        >
                                            <svg width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M160 96a96 96 0 1 1 192 0A96 96 0 1 1 160 96zm80 152l0 264-48.4-24.2c-20.9-10.4-43.5-17-66.8-19.3l-96-9.6C12.5 457.2 0 443.5 0 427L0 224c0-17.7 14.3-32 32-32l30.3 0c63.6 0 125.6 19.6 177.7 56zm32 264l0-264c52.1-36.4 114.1-56 177.7-56l30.3 0c17.7 0 32 14.3 32 32l0 203c0 16.4-12.5 30.2-28.8 31.8l-96 9.6c-23.2 2.3-45.9 8.9-66.8 19.3L272 512z"/></svg>
                                            <div class="tooltip d-none" :id="`${row.id}-create-intervention`">
                                                Intervenções sugeridas
                                            </div>
                                        </div>
                                        <div
                                            @mouseover="showTooltip(`${row.id}-delete-exam`)"
                                            @mouseleave="hideTooltip(`${row.id}-delete-exam`)"
                                            class="deleted"
                                            @click="() => submitExamDelete(row.id)"
                                        >
                                            <svg width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path>
                                            </svg>
                                            <div class="tooltip-delete d-none" :id="`${row.id}-delete-exam`">
                                                Deletar sondagem
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <table class="test-table intervention-table">
                            <tr>
                                <th>Ações de intervenção aplicadas</th>
                            </tr>
                            <tr v-if="studentInterventions[studentExams.length - index] && studentInterventions[studentExams.length - index].length > 0" v-for="(intervention, interventionIndex) in studentInterventions[studentExams.length - index]">

                                <td class="intervention-table-middle" v-if="studentInterventions[studentExams.length - index].length > interventionIndex + 1">
                                    {{intervention.code}} - {{intervention.description}}
                                </td>

                                <td v-else class="intervention-table-end">
                                    {{intervention.code}} - {{intervention.description}}
                                </td>

                            </tr>
                            <tr v-else>
                                <td >Sem ações cadastradas</td>
                            </tr>
                        </table>

                        <span class="textarea-wrapper">
                            <h3>Ações de Intervenção</h3>
                            <textarea
                                disabled
                                :value="row.action"
                                rows="8"
                            ></textarea>
                        </span>
                    </div>
                    <h3 v-else>O aluno não possui nenhuma sondagem</h3>
                    <div class="import-actions">
                        <button
                            class="create-test"
                            @click="openExamCreateModal"
                        >
                            Adicionar sondagem
                        </button>

                        <Dropdown.Root>
                            <Dropdown.Trigger>
                                <svg width="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                    <path fill="var(--secondary-color)" d="M8 256a56 56 0 1 1 112 0A56 56 0 1 1 8 256zm160 0a56 56 0 1 1 112 0 56 56 0 1 1 -112 0zm216-56a56 56 0 1 1 0 112 56 56 0 1 1 0-112z"/>
                                </svg>
                            </Dropdown.Trigger>
                            <Dropdown.Portal>
                                <Dropdown.Content>
                                    <Dropdown.Item @click="handleImportExams">
                                        <svg width="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                            <path fill="var(--secondary-color)" d="M48 448L48 64c0-8.8 7.2-16 16-16l160 0 0 80c0 17.7 14.3 32 32 32l80 0 0 288c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16zM64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-293.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0L64 0zm90.9 233.3c-8.1-10.5-23.2-12.3-33.7-4.2s-12.3 23.2-4.2 33.7L161.6 320l-44.5 57.3c-8.1 10.5-6.3 25.5 4.2 33.7s25.5 6.3 33.7-4.2L192 359.1l37.1 47.6c8.1 10.5 23.2 12.3 33.7 4.2s12.3-23.2 4.2-33.7L222.4 320l44.5-57.3c8.1-10.5 6.3-25.5-4.2-33.7s-25.5-6.3-33.7 4.2L192 280.9l-37.1-47.6z"/>
                                        </svg>
                                        Importar dados
                                    </Dropdown.Item>
                                    <Dropdown.Item @click="exportExamsSampleData">
                                        <svg width="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                            <path fill="var(--secondary-color)" d="M48 448L48 64c0-8.8 7.2-16 16-16l160 0 0 80c0 17.7 14.3 32 32 32l80 0 0 288c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16zM64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-293.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0L64 0zm90.9 233.3c-8.1-10.5-23.2-12.3-33.7-4.2s-12.3 23.2-4.2 33.7L161.6 320l-44.5 57.3c-8.1 10.5-6.3 25.5 4.2 33.7s25.5 6.3 33.7-4.2L192 359.1l37.1 47.6c8.1 10.5 23.2 12.3 33.7 4.2s12.3-23.2 4.2-33.7L222.4 320l44.5-57.3c8.1-10.5 6.3-25.5-4.2-33.7s-25.5-6.3-33.7 4.2L192 280.9l-37.1-47.6z"/>
                                        </svg>
                                        Exportar planilha modelo
                                    </Dropdown.Item>
                                    <Dropdown.Item @click="exportExamsData(studentExams)">
                                        <svg width="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                            <path fill="var(--secondary-color)" d="M48 448L48 64c0-8.8 7.2-16 16-16l160 0 0 80c0 17.7 14.3 32 32 32l80 0 0 288c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16zM64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-293.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0L64 0zm90.9 233.3c-8.1-10.5-23.2-12.3-33.7-4.2s-12.3 23.2-4.2 33.7L161.6 320l-44.5 57.3c-8.1 10.5-6.3 25.5 4.2 33.7s25.5 6.3 33.7-4.2L192 359.1l37.1 47.6c8.1 10.5 23.2 12.3 33.7 4.2s12.3-23.2 4.2-33.7L222.4 320l44.5-57.3c8.1-10.5 6.3-25.5-4.2-33.7s-25.5-6.3-33.7 4.2L192 280.9l-37.1-47.6z"/>
                                        </svg>
                                        Exportar dados
                                    </Dropdown.Item>
                                </Dropdown.Content>
                            </Dropdown.Portal>
                        </Dropdown.Root>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <CreateExamModal
        v-if="showExamCreateModal"
        Titlevalue="Cadastro de Sondagens"
        @close-modal="showExamCreateModal = false"
    >
        <div class="modal-body-size">
            <h2>Detalhes da sondagem</h2>
            <a href="/documentos/instrucoes.pdf" target="_blank">Mais informações sobre ações de intervenção - Escrita</a><br>
            <a href="/documentos/instrucoesLeitura.pdf" target="_blank">Mais informações sobre ações de intervenção - Leitura</a>

            <div class="modal-content-div">
                <div class="modal-content-div-error-div mobile">
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
                        <span class="error-span font-medium">O campo nível de escrita é obrigatório</span>
                    </div>
                </div>

                <span class="textarea-wrapper mobile">
                    <h3>Ações de Intervenção</h3>
                    <textarea
                        :value="createExamData.action"
                        @input="createExamData.action = $event.target.value"
                        rows="12"
                    ></textarea>
                </span>

                <div v-if="classData.type === 'preschool'" v-for="(literacyParameter, index) in literacyParameters" :key="index" class="mobile">
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
        <div class="modal-end mobile">
            <a class="close-modal btn" @click="showExamCreateModal = false">
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
            <a class="school-add btn" @click="submitPollCreated">
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

    <CreateExamModal
        @close-modal="showExamViewModal = false"
        v-if="showExamViewModal"
        Titlevalue="Cadastro de Sondagens"
    >
        <div class="modal-body-size">
            <h2>Detalhes da sondagem</h2>
            <div class="modal-content-div">
                <SelectComponent
                    class="mobile"
                    disabled
                    labelTitle="Nível de leitura"
                    placeholderValue="Nível de leitura"
                    icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                    typeValue="select"
                    :value="activeExamData.reading"
                    valueField="id"
                    RightAction="display: none;"
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

                <SelectComponent
                    class="mobile"
                    disabled
                    labelTitle="Nível de escrita"
                    placeholderValue="Nível de escrita"
                    icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                    typeValue="select"
                    :value="activeExamData.writing"
                    valueField="id"
                    RightAction="display: none;"
                >
                    <option value="pre_syllabic">Pré silábico</option>
                    <option value="syllabic">Silábico</option>
                    <option value="alphabetical_syllabic">Silábico alfabético</option>
                    <option value="alphabetical">Alfabético</option>
                    <option value="missed">Faltou</option>
                    <option value="transferred">Transferido</option>
                </SelectComponent>

                <span class="textarea-wrapper mobile">
                    <h3>Ações de Intervenção</h3>
                    <textarea
                        disabled
                        :value="activeExamData.action"
                        rows="12"
                    ></textarea>
                </span>

                <div v-if="classData.type === 'preschool'" class="mobile" v-for="(literacyParameter, index) in literacyParameters" :key="index">
                    <h3>{{literacyParameterTranslator(literacyParameter.literacy_parameter)}}</h3>
                    <div v-for="(value, index) in literacyParameter.values" :key="index">
                        <Checkbox
                            :disabled="true"
                            :isChecked="examLiteracyParameters.indexOf(value.id) !== -1"
                            :label="value.name_to_show"
                            @change="() => updateLiteracyValue(value.id)"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-end">
            <a class="close-modal" @click="showExamViewModal = false">
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
                Fechar
            </a>
        </div>
    </CreateExamModal>

    <CreateExamModal
        v-if="showExamUpdateModal"
        Titlevalue="Cadastro de Sondagens"
        @close-modal="showExamUpdateModal=false"
    >
        <div class="modal-body-size">
            <h2>Detalhes da sondagem</h2>
            <div class="modal-content-div">
                <SelectComponent
                    class="mobile"
                    labelTitle="Nível de leitura"
                    placeholderValue="Nível de leitura"
                    icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                    typeValue="select"
                    :value="updateExamData.reading"
                    valueField="id"
                    RightAction="display: none;"
                    @input="updateExamData.reading = $event.target.value"
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

                <SelectComponent
                    class="mobile"
                    labelTitle="Nível de escrita"
                    placeholderValue="Nível de escrita"
                    icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                    typeValue="select"
                    :value="updateExamData.writing"
                    valueField="id"
                    RightAction="display: none;"
                    @input="updateExamData.writing = $event.target.value"
                >
                    <option value="pre_syllabic">Pré silábico</option>
                    <option value="syllabic">Silábico</option>
                    <option value="alphabetical_syllabic">Silábico alfabético</option>
                    <option value="alphabetical">Alfabético</option>
                    <option value="missed">Faltou</option>
                    <option value="transferred">Transferido</option>
                </SelectComponent>

                <span class="textarea-wrapper mobile">
                    <h3>Ações de Intervenção</h3>
                    <textarea
                        :value="updateExamData.action"
                        @input="updateExamData.action = $event.target.value"
                        rows="12"
                    ></textarea>
                </span>

                <div v-if="classData.type === 'preschool'" class="mobile" v-for="(literacyParameter, index) in literacyParameters" :key="index">
                    <h3>{{literacyParameterTranslator(literacyParameter.literacy_parameter)}}</h3>
                    <div v-for="(value, index) in literacyParameter.values" :key="index">
                        <Checkbox
                            :disabled="false"
                            :isChecked="examLiteracyParameters.indexOf(value.id) !== -1"
                            :label="value.name_to_show"
                            @change="() => updateLiteracyValue(value.id)"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-end">
            <a class="close-modal btn" @click="showExamUpdateModal = false">
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
            <a class="school-add btn" @click="submitExamUpdate">
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
    <Modal
        v-if="showInterventionModal"
        :Titlevalue="`Aluno ${{
            pre_syllabic: 'Pré-silábico',
            syllabic: 'Silábico',
            alphabetical_syllabic: 'Silábico Alfabético',
            alphabetical: 'Alfabético',
            missed:'Faltou',
            transferred: 'Transferido'
        }[writingType] || writingType}`"
        >
        <div class="modal-body-size">
            <h2 style="text-align: center;">Intervenções sugeridas ao professor(a)</h2>
                <div id="custom-modal-check" class="modal-content-div">
                    <div v-if="interventions.length">
                        <div v-for="intervention in interventions" :key="intervention.id">
                            <Checkbox
                                :isChecked="selectedInterventions.includes(intervention.id)"
                                :label="`${intervention.code}: ${intervention.description}`"
                                @change="() => updateIntervention(intervention.id)"
                            />
                        </div>
                    </div>
                </div>
        </div>
        <div class="modal-end">
            <a class="close-modal" @click="showInterventionModal = false">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="red" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"/>
                </svg>
                Cancelar
            </a>
            <a class="school-add" @click="submitIntervention">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="var(--secondary-color)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>
                </svg>
                Adicionar Intervenção
            </a>
        </div>
    </Modal>
</template>

<style scoped>

.btn {
    width: 15rem !important;
}

.mobile {
    margin-bottom: 0;
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

.error-span {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.modal-content-div {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    margin: 2rem 0;
    width: 100%;
}

@media (max-width: 950px) {
    .modal-content-div {
        display: block;
    }

    .mobile{
        margin-bottom: 20px;
    }

    .modal-end {
        justify-content: center;
        justify-items: center;
    }

    .school-add{
        margin-right: 32px;
        margin-left: 32px;
    }

    .close-modal{
        margin-right: 32px;
        margin-left: 32px;
    }


}

@media (max-width: 700px) {
    .school-add{
        margin-right: 32px;
        margin-left: 32px;
    }

    .close-modal{
        margin-right: 32px !important;
        margin-left: 32px !important;
    }


}

.school-register {
    display: flex;
    height: 100vh;
    width: 100vw;

    & .register-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 5rem 0;
        gap: 2rem;
    }
}

.student-form {
    display: grid;
    grid-template-columns: 1fr;
    grid-template-rows: 1fr;
    gap: 3rem;
    width: 100%;

    & > div {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
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

.student-image-wrapper {
    display: flex;
    width: 100%;
    align-items: flex-end;
}

.student-image {
    aspect-ratio: 4 / 5;
    object-fit: cover;
    border: 2px solid var(--primary-color);
    border-radius: 10px;
    width: 100%;
    max-width: 22rem;
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

.import-actions {
    display: flex;
    flex-direction: row;
    gap: 8px;
    position: absolute;
    right: 0;
}

@media (max-width: 750px) {
    .import-actions {
        display: flex;
        flex-direction: row;
        justify-content: right;
        position: relative;
    }
}

.create-test {
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
    font-weight: 700;
}

.create-test:hover {
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
}


.tests-content {
    display: flex;
    flex-direction: column;
    width: 84%;
    position: relative;
    padding-bottom: 5vh;
    gap: 2rem;



    & > .test-table-container {
        display: flex;
        flex-direction: column;
        width: 100%;
        gap: 1.8rem;

        & > h2 {
            position: relative;
            transform: translateX(20px);
        }

        & > h2::before {
            content: "";
            position: absolute;
            top: 50%;
            transform: translate(-20px, -50%);
            border-radius: 2px;
            width: 5px;
            height: 90%;
            background-color: var(--black-color);
        }

        & > .test-table::before {
            content: "";
            position: absolute;
            height: 100%;
            width: 10px;
            background-color: var(--secondary-color);
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            transform: translateX(-10px);
        }

        & > .test-table {
            transform: translateX(10px);
            position: relative;
            width: calc(100% - 10px);
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 1rem;

            th {
                font-size: 1.2rem;
                font-weight: 600;
                padding: 10px 1rem;
            }

            @media (max-width: 360px) {
                th {
                    padding: 10px 0 !important;
                }

                td {
                    padding: 10px 10px !important;
                }
            }

            td,
            th {
                background-color: rgba(118, 171, 59, 0.1);
            }

            tr th:last-child {
                border-top-right-radius: 1rem;
            }

            tr:nth-child(2) td:last-child {
                border-bottom-right-radius: 1rem;
            }
        }

        & > .intervention-table {
            tr .intervention-table-middle {
                border-bottom-right-radius: 0 !important;
            }

            tr .intervention-table-end{
                border-bottom-right-radius: 1rem !important;
            }
        }

    }
}

.support-material-content {
    display: flex;
    width: 84%;
    justify-content: flex-start;
    gap: 2rem;

    & > .material-card {
        display: flex;
        align-items: center;
        justify-content: center;
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        color: var(--secondary-color);
        aspect-ratio: 5 / 7;
        width: 10vw;
        border-radius: 0.5rem;
        background-color: #fff;
        border: 2px solid var(--secondary-color);
    }
}

.student-form-wrapper {
    display: flex;
    width: 84%;
    flex-direction: column;
    align-items: center;
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
#custom-modal-check {
    grid-template-columns: 1fr;
    max-height: 400px;
    overflow-x: hidden;
    padding: 20px;
}

.tooltip {
    position: absolute;
    margin-top: -50px;
    background-color: #333;
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1000;
    opacity: 0.9;
}

.tooltip-delete {
    position: absolute;
    margin-top: -120px;
    background-color: #333;
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1000;
    opacity: 0.9;
}

.d-none {
    display: none;
}

.d-flex{
    display: flex;
}

</style>
